<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

layout_page_header_begin( plugin_lang_get( 'config' ) );
html_css_cdn_link(plugin_file('css/eannounce.css'));
layout_page_header_end();

layout_page_begin( 'manage_overview_page.php' );
print_manage_menu('manage_plugin_page.php');
?>

<div class="space-10"></div>
	<form method="post" name="eannounce-config-form" action="<?php echo plugin_page( 'config_edit' ) ?>">
	<div class="widget-box widget-color-blue2">
		<div class="widget-header widget-header-small">
			<h4 class="widget-title lighter">
				<i class="ace-icon fa fa-wrench"></i>
				<?php echo plugin_lang_get( 'title' ) . ': ' . plugin_lang_get( 'config' );?>
			</h4>
		</div>
		<div class="widget-body">
			<a name="tagcreate"></a>
			<div class="widget-main no-padding">
		<div class="form-container">
		<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
		<fieldset>
			<?php echo form_security_field( 'plugin_Eannounce_configuration' ); ?>
			<tr>
    			<td class="category">
                	<?php echo plugin_lang_get( 'sendmail_threshold' ) ?>
                </td>
                <td class="left">
                <select name="eannounce_sendmail_threshold">
                	<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'sendmail_threshold' ) )?>
                </select>
			</tr>
			<tr>
    			<td class="category">
                	<?php echo plugin_lang_get( 'access_levels' ) ?>  
                </td>
                <td class="left">
                	<?php 
                	$t_access_levels_config = plugin_config_get( 'access_levels' );
                	$t_access_levels_enum_string = config_get( 'access_levels_enum_string' );
                	$t_enum_values = MantisEnum::getValues( $t_access_levels_enum_string );
                	foreach ( $t_enum_values as $t_enum_value ) {
                	    $t_access_level = get_enum_element( 'access_levels', $t_enum_value );
                		echo '<div><input type="checkbox" name="eannounce_access_levels[]" value="' . $t_enum_value . '"';
                		check_checked($t_access_levels_config, $t_enum_value, false);
                		echo '/><label>' . $t_access_level . '</label></div>';
                	}
                	?>
			</tr>
		</fieldset>
		</table>
		</div>
		</div>
		</div>
			<div class="widget-toolbox padding-8 clearfix">
				<input type="submit" class="btn btn-primary btn-sm btn-white btn-round"
					   value="<?php echo lang_get( 'change_configuration' ) ?>"/>
			</div>
		</div>
	</div>
	</form>
<?php
layout_page_end();