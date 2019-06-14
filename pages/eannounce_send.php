<?php
require_once( 'core.php' );
$f_to = gpc_get_int_array('to');

$usertabla = db_get_table( 'mantis_user_table' );

foreach ( $f_to as $t_userlevel ){
	if ($userlevel != -1) 		{
		$query = "SELECT id FROM $usertabla WHERE access_level = '$t_userlevel'";
		$t_result = db_query_bound($query);
		foreach ($t_result as $t_userid) {	
			foreach ($t_userid as $t_useradat) {
				if(ON == config_get( 'enable_email_notification' )) 	{
					email_store( user_get_email($t_useradat),gpc_get_string('emailsubject'),gpc_get_string('emailbody'));
				}
			}
		}
	}
}

if(OFF == config_get( 'email_send_using_cronjob')) 	{
	email_send_all();
}
print_successful_redirect( plugin_page( 'eannounce_prep',TRUE ) );