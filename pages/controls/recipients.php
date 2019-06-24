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

/**
 * Handles display of recipient addresses
 */

$t_recipients = isset( $_POST["addresses"]) ? $_POST["addresses"] : array();
$t_checked = gpc_get_bool( "checked" );
$t_profile = gpc_get_int( "selected_profile" );
$t_project = gpc_get_int( "project" );
access_ensure_global_level( plugin_config_get( "eannounce_sendmail_threshold" ));

$user_table = db_get_table( "user" );
$user_project_table = db_get_table( "project_user_list" );

// Get email address from enabled users with correct access level
$sql = "SELECT email FROM $user_table WHERE enabled=1 AND id IN 
        (SELECT user_id from $user_project_table WHERE project_id=$t_project AND access_level=$t_profile)";

$profile_user_mail = db_query($sql);
$t_rows = array();

while($t_row = db_fetch_array( $profile_user_mail )) {
    array_push( $t_rows, $t_row['email'] );
}

if( $t_checked ) {
    // Access level was added, merge array of addresses
    $merge = array_merge( $t_recipients, $t_rows );
    $result = array_unique( $merge );
} else{
    // Access level was removed, return diff of addresses
    $result = array_diff( $t_recipients, $t_rows );
}
    
echo implode( ";", $result );