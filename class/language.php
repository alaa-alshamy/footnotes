<?php
/**
 * 
 * @filesource
 * @author Stefan Herndler
 * @since 1.5.0 14.09.14 17:47
 * 
 * Edited:
 * 
 * @since 2.0.0  PHP-related bug fix thanks to @matkus2 code contribution   2020-10-26T1609+0100
 * @link https://wordpress.org/support/topic/error-missing-parameter-if-using-php-7-1-or-later/
 * @link https://www.php.net/manual/en/migration71.incompatible.php
 * 
 * @since 2.1.6  conform to WordPress plugin language file name scheme, thanks to @nikelaos bug report   2020-12-08T1931+0100
 * @link https://wordpress.org/support/topic/more-feature-ideas/
 * 
 * Last modified:  2021-01-10T1755+0100
 */

/**
 *
 * @author Stefan Herndler
 * @since 1.5.0
 */
class MCI_Footnotes_Language {

    /**
     * Register WordPress Hook.
     *
     * @author Stefan Herndler
     * @since 1.5.0
     */
    public static function registerHooks() {
        add_action('plugins_loaded', array("MCI_Footnotes_Language", "loadTextDomain"));
    }

    /**
     * Loads the text domain for current WordPress language if exists. Otherwise fallback "en_GB" will be loaded.
     *
     * @author Stefan Herndler
     * @since 1.5.0
     * 
     * @since 2.0.0  PHP 7.1 related bug fix thanks to @matkus2 code contribution
     * @link https://wordpress.org/support/topic/error-missing-parameter-if-using-php-7-1-or-later/
     * @link https://www.php.net/manual/en/migration71.incompatible.php
     */
    public static function loadTextDomain() {
        // language file with localization exists
        if (self::load(apply_filters('plugin_locale', get_locale(), ''))) {
            // added 3rd (empty) parameter to prevent "Fatal error: Uncaught ArgumentCountError: Too few arguments […]"
            // 2020-10-26T1609+0100
            return;
        }
        // fall back to British English:
        self::load("en_GB");
    }

    /**
     * Loads a specific text domain.
     *
     * @author Stefan Herndler
     * @since 1.5.1
     * @param string $p_str_LanguageCode Language Code to load a specific text domain.
     * @return bool
     * 
     * Edited:
     * @since 2.1.6  conform to WordPress plugin language file name scheme, thanks to @nikelaos bug report
     * @link https://wordpress.org/support/topic/more-feature-ideas/
     * That is done by using load_plugin_textdomain()
     * @see wp-includes/l10n.php:857
     * “The .mo file should be named based on the text domain with a dash, and then the locale exactly.”
     */
    private static function load($p_str_LanguageCode) {
        return load_plugin_textdomain(
            MCI_Footnotes_Config::C_STR_PLUGIN_NAME,
            // This argument only fills the gap left by a deprecated argument (since WP2.7):
            false, 
            // The plugin basedir is provided; trailing slash would be clipped:
            MCI_Footnotes_Config::C_STR_PLUGIN_NAME . '/languages' 
        );
    }
}
