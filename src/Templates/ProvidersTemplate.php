<?php

namespace PUPI_SL\Templates;

use PUPI_SL\Config;
use PUPI_SL\Models\ProviderModel;

class ProvidersTemplate
{
    public function getLoginButtons(array $providers, $to = null)
    {
        $html = '<div class="pupi_sl-login-buttons-container">';

        foreach ($providers as $provider) {
            $html .= $this->getLoginButtonsForProvider($provider, $to);
        }

        $html .= '</div>'; // pupi_sl-login-buttons-container

        return $html;
    }

    public function getProviderImage($provider)
    {
        $providerImage = '';
        if ($provider == 'facebook') {
            $providerImage = '<svg viewBox="0 0 25 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><g clip-path="url(#clip0_636_23113)"><path d="M10.42 23.88C4.71996 22.86 0.399963 17.94 0.399963 12C0.399963 5.4 5.79996 0 12.4 0C19 0 24.4 5.4 24.4 12C24.4 17.94 20.08 22.86 14.38 23.88L13.72 23.34H11.08L10.42 23.88Z" fill="#0866ff"></path><path d="M17.0799 15.36L17.62 12H14.4399V9.65999C14.4399 8.69999 14.7999 7.97999 16.24 7.97999H17.8V4.91999C16.96 4.79999 16 4.67999 15.16 4.67999C12.4 4.67999 10.4799 6.35999 10.4799 9.35999V12H7.47995V15.36H10.4799V23.82C11.1399 23.94 11.8 24 12.46 24C13.12 24 13.7799 23.94 14.4399 23.82V15.36H17.0799Z" fill="white"></path></g><defs><linearGradient id="paint0_linear_636_23113" x1="12.4006" y1="23.1654" x2="12.4006" y2="-0.00442066" gradientUnits="userSpaceOnUse"><stop stop-color="#0062E0"></stop><stop offset="1" stop-color="#19AFFF"></stop></linearGradient><clipPath id="clip0_636_23113"><rect width="24" height="24" fill="white" transform="translate(0.399963)"></rect></clipPath></defs></svg>';
        } else if ($provider == 'google') {
            $providerImage = '<svg viewBox="0 0 25 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><g clip-path="url(#clip0_636_23102)"><path d="M24.545 12.27C24.545 11.48 24.475 10.73 24.355 10H13.055V14.51H19.525C19.235 15.99 18.385 17.24 17.125 18.09V21.09H20.985C23.245 19 24.545 15.92 24.545 12.27Z" fill="#4285F4"></path><path d="M13.055 24C16.295 24 19.005 22.92 20.985 21.09L17.125 18.09C16.045 18.81 14.675 19.25 13.055 19.25C9.92497 19.25 7.27497 17.14 6.32497 14.29H2.34497V17.38C4.31497 21.3 8.36497 24 13.055 24Z" fill="#34A853"></path><path d="M6.32499 14.29C6.07499 13.57 5.94499 12.8 5.94499 12C5.94499 11.2 6.08499 10.43 6.32499 9.71V6.62H2.34499C1.52499 8.24 1.05499 10.06 1.05499 12C1.05499 13.94 1.52499 15.76 2.34499 17.38L6.32499 14.29Z" fill="#FBBC05"></path><path d="M13.055 4.75C14.825 4.75 16.405 5.36 17.655 6.55L21.075 3.13C19.005 1.19 16.295 0 13.055 0C8.36497 0 4.31497 2.7 2.34497 6.62L6.32497 9.71C7.27497 6.86 9.92497 4.75 13.055 4.75Z" fill="#EA4335"></path></g><defs><clipPath id="clip0_636_23102"><rect width="24" height="24" fill="white" transform="translate(0.799988)"></rect></clipPath></defs></svg>';
        } else if ($provider == 'twitter') {
            $providerImage = '<svg viewBox="-3 -3 28 28" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><g><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></g></svg>';
        } else if ($provider == 'linkedinopenid') {
            $providerImage = '<svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><g><g fill="none"><path d="M28.4863253 59.9692983c-6.6364044-.569063-11.5630204-2.3269561-16.3219736-5.8239327C4.44376366 48.4721168 3e-7 39.6467924 3e-7 29.9869344c0-14.8753747 10.506778-27.18854591 25.2744118-29.61975392 6.0281072-.9924119 12.7038532.04926445 18.2879399 2.85362966C57.1386273 10.0389054 63.3436516 25.7618627 58.2050229 40.3239688 54.677067 50.3216743 45.4153135 57.9417536 34.81395 59.5689067c-2.0856252.3201125-5.0651487.5086456-6.3276247.4003916z" fill="#0077B5"></path><g fill="#FFF"><path d="M17.88024691 22.0816337c2.14182716 0 3.87817284-1.58346229 3.87817284-3.53891365C21.75841975 16.58553851 20.02207407 15 17.88024691 15 15.73634568 15 14 16.58553851 14 18.54272005c0 1.95545136 1.73634568 3.53891365 3.88024691 3.53891365M14.88888889 44.8468474h6.95851852V24.77777778h-6.95851852zM31.6137778 33.6848316c0-2.3014877 1.0888889-4.552108 3.6925432-4.552108 2.6036543 0 3.2438518 2.2506203 3.2438518 4.4970883v10.960701h6.9274074V33.1816948c0-7.9263084-4.6853333-9.29280591-7.5676049-9.29280591-2.8798518 0-4.4682469.9740923-6.2961975 3.33440621v-2.70185178h-6.9471111V44.5905129h6.9471111V33.6848316z"></path></g></g></g></svg>';
        }
        return $providerImage;
    }

    public function getLoginButtonsForProvider($provider, $to = null)
    {
        $loginText = str_contains($_SERVER['REQUEST_URI'], 'register')
            ? pupi_sl()->util()->langHtml(Config::LANG_ID_REGISTER_WITH_PROVIDER, $provider['name'])
            : pupi_sl()->util()->langHtml(Config::LANG_ID_LOGIN_WITH_PROVIDER, $provider['name']);

        $params = array('action' => 'login', 'provider' => $provider['id']);
        if (!empty($to)) {
            $params['to'] = $to;
        }

        $url = qa_path('', $params, qa_path_to_root());

        $html = sprintf('<a class="pupi_sl-login-button" href="%s">', $url);

        $html .= '<div class="pupi_sl-login-button-image">';
        // $html .= sprintf('<img alt="%s" src="%s"/>', $provider['id'], pupi_sl()->util()->getPublicUrlToRoot() . 'provider-images/' . $provider['image']);
        $html .= $this->getProviderImage($provider['id']);
        $html .= '</div>'; // pupi_sl-login-button-image

        $html .= '<div class="pupi_sl-login-button-text">';
        $html .= $loginText;
        $html .= '</div>'; // pupi_sl-login-button-text

        $html .= '<div class="pupi_sl-login-button-spacer"></div>';

        $html .= '</a>'; // pupi_sl-provider-login-button

        return $html;
    }

    public function getLogoutButton()
    {
        return sprintf(
            '<a href="%s" class="qa-nav-user-link">%s</a>',
            qa_path_absolute(Config::URL_SESSION_PAGE, array('action' => 'logout')),
            qa_lang_html('main/nav_logout')
        );
    }
}
