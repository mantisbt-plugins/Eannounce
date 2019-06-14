<?PHP
auth_reauthenticate();
access_ensure_global_level( plugin_config_get( 'eannounce_sendmail_threshold' ) );
html_page_top1( 'Eannounce plugin' );
html_page_top2();
print_manage_menu();
$g_send	= plugin_page('Eannounce_send.php.php');
?>
<br/>

<form action="<?PHP echo $g_send; ?> " method="post">
<table align="center" class="width50" cellspacing="1">

<tr>
<td class="form-title" colspan="3">
<?php echo lang_get( 'plugin_eannouncesendpage_title' ) ?>
</td>
</tr>

<!-- DISPLAY THE SELECTOR -->
<tr <?php echo helper_alternate_class() ?>>
<td class="category">
<?php echo lang_get( 'plugin_eannounce_usergroup' ) ?>
</td>
<td class="left">
<select name="to[]" multiple="multiple" size="6">

<?php 
	// THIS GENERATES THE USERGROUP LIST
	$t_access_levels_enum_string = config_get( 'access_levels_enum_string' );
	$t_enum_values = MantisEnum::getValues( $t_access_levels_enum_string );
	foreach ( $t_enum_values as $t_enum_value ) 
		{
		$t_access_level = get_enum_element( 'access_levels', $t_enum_value );
		echo '<option value="' . $t_enum_value . '"';
		check_selected( $p_val, $t_enum_value );
		echo '>' . $t_access_level . '</option>';
		}
?>;

</select>
</td>
</tr>

<!-- ITT TESZEM KI A SUBJECT MEZOT -->
<tr <?php echo helper_alternate_class() ?>>
<td class="category">
<?php echo lang_get( 'plugin_eannounce_emailsubject' ) ?>
</td>
<td class="left">
<textarea name="emailsubject" cols="60" rows="1"></textarea>
</select>
</td>
</tr>

<!-- ITT TESZEM KI A BODY MEZOT -->
<tr <?php echo helper_alternate_class() ?>>
<td class="category">
<?php echo lang_get( 'plugin_eannounce_emailbody' ) ?>
</td>
<td class="left">
<textarea name="emailbody" cols="60" rows="10"></textarea>
</select>
</td>
</tr>


<!-- ITT TESZEM KI A GOMBOT -->
<tr>
<td class="center" colspan="3">
<input type="submit" class="button" value="<?php echo lang_get( 'plugin_eannounce_sendmailbutton' ) ?>" />
</td>
</tr>

</table>


<form>
<?php
html_page_bottom1( __FILE__ );