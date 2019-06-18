<?php
// authenticate
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
// Read results
$f_eannounce_sendmail_threshold = gpc_get_int( 'eannounce_sendmail_threshold', REPORTER );
$f_eannounce_access_levels = gpc_get( 'eannounce_access_levels' );

// update results
plugin_config_set( 'sendmail_threshold', $f_eannounce_sendmail_threshold );
plugin_config_set( 'access_levels' , $f_eannounce_access_levels );

// redirect
form_security_purge( 'eannounce_config_form' );

layout_page_header( null, plugin_page( 'config',TRUE ) );

layout_page_begin();

html_operation_successful( plugin_page( 'config',TRUE ) );

layout_page_end();
