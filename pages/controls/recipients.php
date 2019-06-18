<?php 

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


$sql = "SELECT email FROM $user_table WHERE enabled=1 AND id IN 
        (SELECT user_id from $user_project_table WHERE project_id=$t_project AND access_level=$t_profile)";

$profile_user_mail = db_query($sql);
$t_rows = array();

while($t_row = db_fetch_array( $profile_user_mail )) {
    array_push( $t_rows, $t_row['email'] );
}

if( $t_checked ) {
    $merge = array_merge( $t_recipients, $t_rows );
    $result = array_unique( $merge );
} else{
    $result = array_diff( $t_recipients, $t_rows );
}
    
echo implode( ";", $result );


