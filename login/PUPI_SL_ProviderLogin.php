<?php

use Hybridauth\Hybridauth;
use Hybridauth\Storage\Session;
use PUPI_SL\Config;
use PUPI_SL\Models\ProviderModel;
use PUPI_SL\Templates\ProvidersTemplate;

class PUPI_SL_ProviderLogin
{
    private array $enabledProviders;

    public static function getModuleInstance()
    {
        return qa_load_module('login', 'PUPI_SL Provider Login');
    }

    public function load_module($directory, $urlToRoot)
    {
        $this->enabledProviders = [];

        $providerModel = new ProviderModel();

        foreach (ProviderModel::PROVIDERS as $providerId => $provider) {
            $settingId = $providerModel->getSettingIdForProvider('enabled', $providerId);
            $isEnabled = pupi_sl()->util()->getSetting($settingId, false, false);

            if ($isEnabled) {
                $this->enabledProviders[] = $provider;
            }
        }
    }

    public function match_source($source)
    {
        $allSources = array_column(ProviderModel::PROVIDERS, 'id', 'source');
        if (!isset($allSources[$source])) {
            return false;
        }

        $providerId = $allSources[$source];

        return $this->isProviderEnabled($providerId);
    }

    function check_login()
    {
        $providerId = $this->getProviderFromUrl();

        if (!$this->isProviderEnabled($providerId)) {
            return;
        }

        $storage = new Session();
        $redirectTo = null;

        try {
            $config = $this->getProvidersConfig([$providerId]);

            $hybridauth = new Hybridauth($config);

            if (qa_get('action') === 'login') {
                $storage->set('provider', $providerId);

                $to = qa_get('to');
                $storage->set('to', empty($to) ? null : $to);
            }

            $storedProvider = $storage->get('provider');
            if ($storedProvider === $providerId) {
                $hybridauth->authenticate($providerId);
                $storage->set('provider', null);

                $redirectTo = $storage->get('to');
                $storage->set('to', null);

                // Retrieve the provider record
                $adapter = $hybridauth->getAdapter($providerId);
                $user = $adapter->getUserProfile();

                if (!empty($user)) {
                    $source = ProviderModel::PROVIDERS[$providerId]['source'];

                    qa_log_in_external_user($source, $user->identifier, [
                        'email' => $user->email ?? '',
                        'handle' => implode('_', mb_split(' ', ($user->displayName ?? ''))),
                        'confirmed' => true,
                        'avatar' => empty($user->photoURL) ? null : qa_retrieve_url($user->photoURL),
                    ]);
                }
            }
        } catch (Exception $e) {
            error_log(Config::PLUGIN_ID . ': ' . $e->getMessage());

            if (isset($adapter)) {
                $adapter->disconnect();
            }

            $storage->set('provider', null);
            $storage->set('to', null);
        }

        if (!empty($redirectTo)) {
            qa_redirect_raw(qa_path_to_root() . $redirectTo); // path already provided as URL fragment
        }

        qa_redirect('');
    }

    public function executeLogout()
    {
        $config = $this->getProvidersConfig(array_keys(ProviderModel::PROVIDERS));

        try {
            $hybridauth = new Hybridauth($config);

            $adapters = $hybridauth->getConnectedAdapters();

            foreach ($adapters as $adapter) {
                $adapter->disconnect();
            }
        } catch (Exception $e) {
        } finally {
            if (qa_is_logged_in()) {
                qa_set_logged_in_user(null);
            }

            qa_redirect('');
        }
    }

    public function login_html($tourl, $context)
    {
        if ($context === 'menu') {
            return;
        }

        echo (new ProvidersTemplate())->getLoginButtons($this->enabledProviders, qa_get('to'));
    }

    public function logout_html($tourl)
    {
        echo (new ProvidersTemplate())->getLogoutButton();
    }

    /**
     * @param $providerId
     *
     * @return bool
     */
    private function isProviderEnabled($providerId)
    {
        $enabledProviderIds = array_column(ProviderModel::PROVIDERS, 'id');

        return in_array($providerId, $enabledProviderIds);
    }

    private function getProvidersConfig($providerIds)
    {
        $providerModel = new ProviderModel();
        $providersConfig = [];
        foreach ($providerIds as $providerId) {
            if (!$this->isProviderEnabled($providerId)) {
                continue;
            }
            $providersConfig[$providerId] = [
                'enabled' => true,
                'keys' => [
                    'id' => pupi_sl()->util()->getSetting($providerModel->getSettingIdForProvider('app_key', $providerId)),
                    'secret' => pupi_sl()->util()->getSetting($providerModel->getSettingIdForProvider('app_secret', $providerId)),
                ],
                'callback' => qa_path_absolute('', ['provider' => $providerId]),
            ];
        }

        return [
            'providers' => $providersConfig,
            'debug_mode' => false,
            'debug_file' => '',
        ];
    }

    /**
     * @return string
     */
    private function getProviderFromUrl()
    {
        $provider = (string)qa_get('provider');

        return strtolower($provider);
    }
}
