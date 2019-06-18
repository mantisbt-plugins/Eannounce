<?php
require_once( 'core.php' );
require_once(__DIR__.'/../api/utils/utils.php');
$f_to = explode( ";", gpc_get_string('emailto'));

$success = send_mail($f_to, gpc_get_string( 'emailsubject' ), gpc_get_string( 'emailbody' ));

if( $success ){
    $log_message = sprintf( plugin_lang_get( 'log_success' ), user_get_name(auth_get_current_user_id()), gpc_get_string( 'emailto' ), gpc_get_string( 'emailsubject' ));
} else {
    $log_message = sprintf( plugin_lang_get( 'log_error' ), user_get_name(auth_get_current_user_id()), gpc_get_string( 'emailto' ), gpc_get_string( 'emailsubject' ));
}

eannounce_log($log_message);

form_security_purge( 'eannounce_config_form' );

layout_page_header( null, plugin_page( 'eannounce_prep',TRUE ));

layout_page_begin();

html_operation_successful( plugin_page( 'eannounce_prep',TRUE ));

layout_page_end();