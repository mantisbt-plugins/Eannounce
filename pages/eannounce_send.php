<?php
require_once( 'core.php' );
require_once(__DIR__.'/../api/utils/utils.php');
$f_to = explode( ";", gpc_get_string('emailto'));
$f_cc = gpc_get_string('emailcc');

$f_project = gpc_get_int('project');

$usertable = db_get_table( 'user' );
$project_user_table = db_get_table('project_user_list');

send_mail($f_to, gpc_get_string('emailsubject'), gpc_get_string('emailbody'), $f_cc);

if(OFF == config_get( 'email_send_using_cronjob')) 	{
	email_send_all();
}

form_security_purge( 'eannounce_config_form' );

layout_page_header( null, plugin_page( 'eannounce_prep',TRUE ));

layout_page_begin();

html_operation_successful( plugin_page( 'eannounce_prep',TRUE ));

layout_page_end();