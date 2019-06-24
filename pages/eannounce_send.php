<?php
/*
 * This file is part of the Eannounce plugin for MantisBT.
 *
 * Eannounce is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 
 * The Eannounce plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 
 * You should have received a copy of the GNU General Public License
 * along with the Eannounce plugin.  If not, see <https://www.gnu.org/licenses/>.
 */

require_once( 'core.php' );
require_once(__DIR__.'/../api/utils/utils.php');
// Get request params
$bcc = gpc_get_string('emailbcc');
$cc = gpc_get_string( 'emailcc' );
$f_subject = gpc_get_string( 'emailsubject' );
$f_body = gpc_get_string( 'emailbody' );
$f_bcc = explode( ";", $bcc );
$f_cc = explode( ";", $cc );

// Get result of sending
$success = send_mail($f_bcc, $f_subject, $f_body, $f_cc);

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