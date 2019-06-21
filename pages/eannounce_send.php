<?php
require_once( 'core.php' );
require_once(__DIR__.'/../api/utils/utils.php');
// Get request params
$bcc = gpc_get_string('emailbcc');
$f_subject = gpc_get_string( 'emailsubject' );
$f_body = gpc_get_string( 'emailbody' );
$f_bcc = explode( ";", $bcc);

// Get result of sending
$success = send_mail($f_bcc, $f_subject, $f_body);

// Prepare log event
if( $success ){
    $log_message = sprintf( plugin_lang_get( 'log_success' ),
                    user_get_name(auth_get_current_user_id()), $bcc, $f_subject);
} else {
    $log_message = sprintf( plugin_lang_get( 'log_error' ),
                    user_get_name(auth_get_current_user_id()), $bcc, $f_subject);
}
// Log whether if succeeded or not
eannounce_log($log_message);
form_security_purge( 'eannounce_config_form' );
layout_page_header( null, plugin_page( 'eannounce_prep', true ));
layout_page_begin();
html_operation_successful( plugin_page( 'eannounce_prep', true ));
layout_page_end();