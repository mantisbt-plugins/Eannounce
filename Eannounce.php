<?php

class EannouncePlugin extends MantisPlugin {
 


	function register() {
		$this->name        = 'Eannounce';
		$this->description = 'Sending email notifications to user groups';
		$this->version     = '2.21';
		$this->requires    = array('MantisCore'       => '2.0.0',);
		$this->author      = array('Istvan Baktai','c2pil');
		$this->contact     = 'istvan.baktai_at_gmail.com';
		$this->url         = 'N/A';
		$this->page			= 'config';
	}
 
	function config() {
	   $t_access_levels_enum_string = config_get( 'access_levels_enum_string' );
	   $t_enum_values = MantisEnum::getValues( $t_access_levels_enum_string );
	   return array
    		(
    		'sendmail_threshold'	=> MANAGER,
    		'access_levels' => $t_enum_values
    		);
	}

	function init() {
		plugin_event_hook ('EVENT_MENU_MANAGE','eannounce_form');
	}

	function eannounce_form() {
	    if(access_get_global_level( auth_get_current_user_id()) >=  plugin_config_get( 'eannounce_sendmail_threshold' ) ) {	       
		  return array('<a href="'. plugin_page( 'eannounce_prep.php' ) . '">' . plugin_lang_get( 'sendpage_title' ) . '</a>' );
	    }
	}

}
