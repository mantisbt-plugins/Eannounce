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

layout_page_header( null, plugin_page( 'config', true ) );

layout_page_begin();

html_operation_successful( plugin_page( 'config', true ) );

layout_page_end();
