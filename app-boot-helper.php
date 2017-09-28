<?php
// define constants for locale 
if (!defined('LOCALE_DIR')) {
    define('LOCALE_DIR', './locale');
}

// default locale
$locale = 'en_US';

@ini_set('session.cookie_httponly', true);
@ini_set('session.save_path', __DIR__.'/storage/framework/native-sessions');
@ini_set('session.cookie_lifetime', 60 * 60 * 24 * 90); // 3 months
@ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 90); // 3 months

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('changeAppLocale')) {
    function changeAppLocale($localeId, $localeConfig = null)
    {
        if (!$localeConfig) {
            $localeConfig = config('locale');
        }

        // default locale
        $locale = env('LC_DEFAULT_LANG', 'en_US');

        // get available locale
        $availableLocale = $localeConfig['available'];

        // check if locale is available
        if ($localeId and array_key_exists($localeId, $availableLocale)) {
            $locale = $localeId;
           // set current locale in session
           $_SESSION['CURRENT_LOCALE'] = $locale;

        // check if curent locale is already set if yes use it
        } elseif (isset($_SESSION['CURRENT_LOCALE']) and $_SESSION['CURRENT_LOCALE']) {
            $locale = $_SESSION['CURRENT_LOCALE'];
        }

        // define constant for current locale
        if (!defined('CURRENT_LOCALE')) {
            define('CURRENT_LOCALE', $locale);
        }

        $domain = 'messages';
        putenv('LC_ALL='.$locale.'.utf8');
        T_setlocale(LC_ALL, $locale.'.utf8');
        T_bindtextdomain($domain, LOCALE_DIR);
        T_bind_textdomain_codeset($domain, 'UTF-8');
        T_textdomain($domain);
    }
}