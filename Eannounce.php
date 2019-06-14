<?php

class EannouncePlugin extends MantisPlugin {
 


	function register() {
		$this->name        = 'Eannounce';
		$this->description = 'Sending email notifications to user groups';
		$this->version     = '0.99a';
		$this->requires    = array('MantisCore'       => '2.0.0',);
		$this->author      = 'Istvan Baktai';
		$this->contact     = 'istvan.baktai_at_gmail.com';
		$this->url         = 'N/A';
		$this->page			= 'config';
	}
 
	function config() 
		{
		return array
			(
			'eannounce_sendmail_threshold'	=> REPORTER,
			);
		}

	function init() 
		{
		plugin_event_hook ('EVENT_MENU_MANAGE','eannounce_form');
		}

	function eannounce_form() 
		{
		 return array('<a href="'. plugin_page( 'eannounce_prep.php' ) . '">' . lang_get( 'plugin_eannouncesendpage_title' ) . '</a>' );
		}

		
	

}
