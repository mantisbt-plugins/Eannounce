<?php

/**
 * Main class of Eannounce Mantis plugin
 * @author c2pil
 *
 */
class EannouncePlugin extends MantisPlugin {

    /**
     * Called to register the plugin in Mantis
     */
    public function register() {
        $this->name = 'Eannounce';
        $this->description = plugin_lang_get( 'description' );
        $this->version = '2.21';
        $this->requires = array (
                'MantisCore' => '2.21.0'
        );
        // Plugin stored in an issue, put on GitHub
        $this->author = array (
                'Istvan Baktai',
                'c2pil'
        );
        $this->contact = 'c2pil@gmail.com';
        $this->page = 'config';
    }

    /**
     * Returns the configuration for the plugin if nothing is set yet
     *
     * @return array
     */
    public function config() {
        $t_access_levels_enum_string = config_get ( 'access_levels_enum_string' );
        $t_enum_values = MantisEnum::getValues ( $t_access_levels_enum_string );
        return array (
                'sendmail_threshold' => MANAGER,
                'access_levels' => $t_enum_values,
                'date_format' => 'Y-m-d H:i:s'
        );
    }

    /**
     * Hook to add tab on Manage menu
     */
    public function init() {
        plugin_event_hook ( 'EVENT_MENU_MANAGE', 'eannounce_form' );
    }

    /**
     * Adds tab to send group mails if user has sufficient rights
     *
     * @return string[]
     */
    public function eannounce_form() {
        if (access_get_global_level ( auth_get_current_user_id () )
                >= plugin_config_get ( 'eannounce_sendmail_threshold' )) {
            return array (
                    '<a href="' . plugin_page ( 'eannounce_prep.php' ) . '">'
                    . plugin_lang_get ( 'sendpage_title' ) . '</a>'
            );
        }
    }
}
