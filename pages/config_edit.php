<?php
// authenticate
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
// Read results
$f_eannounce_sendmail_threshold = gpc_get_int( 'eannounce_sendmail_threshold', ADMINISTRATOR );

// update results
plugin_config_set( 'eannounce_sendmail_threshold', $f_eannounce_sendmail_threshold );
// redirect
print_successful_redirect( plugin_page( 'config',TRUE ) );