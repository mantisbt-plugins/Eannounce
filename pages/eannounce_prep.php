<?PHP
auth_reauthenticate();
access_ensure_global_level( plugin_config_get( 'eannounce_sendmail_threshold' ) );
layout_page_header( plugin_lang_get( 'sendpage_title' ) );

layout_page_begin( 'manage_overview_page.php' );
print_manage_menu('eannounce_prep.php');
$g_send	= plugin_page('eannounce_send.php');
$user_id = auth_get_current_user_id();
?>
<br/>

<div class="space-10"></div>
	<form id="manage-tags-create-form" method="post" action="<?php echo $g_send; ?>">
	<div class="widget-box widget-color-blue2">
		<div class="widget-header widget-header-small">
			<h4 class="widget-title lighter">
				<i class="ace-icon fa fa-envelope"></i>
				<?php echo plugin_lang_get('sendpage_title') ?>
			</h4>
		</div>
		<div class="widget-body">
			<a name="tagcreate"></a>
			<div class="widget-main no-padding">
		<div class="form-container">
		<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
		<fieldset>
			<?php echo form_security_field( 'plugin_eannouncesendpage_title' ); ?>
			<tr>
    			<td class="category">
					<span class="required">* </span>
                    <?php echo plugin_lang_get( 'project' ) ?>
                </td>
                <td class="left">
        			<select name="project">
                    <?php 
                    	// THIS GENERATES THE USERGROUP LIST
                        $t_projects = project_get_all_rows();
                    	foreach ( $t_projects as $t_project ) {
                    	   if(access_get_project_level($t_project['id'], $user_id)){
                    	       echo '<option value="' . $t_project['id'] . '"';
                    		   echo '>' . $t_project['name'] . '</option>';
                    	   }
                    	}
                    ?>;
                    </select>
                </td>
			</tr>
			<tr>
    			<td class="category">
					<span class="required">* </span>
                    <?php echo plugin_lang_get( 'usergroup' ) ?>
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
                    		echo '>' . $t_access_level . '</option>';
                    		}
                    ?>;
                    </select>
                </td>
			</tr>
			<tr>	
				<td class="category">
					<span class="required">* </span>
                	<?php echo plugin_lang_get( 'emailsubject' ) ?>
                </td>
                <td class="left">
                	<textarea name="emailsubject" cols="60" rows="1"></textarea>
                </td>
            </tr>
			<tr>
				<td class="category">
					<span class="required">* </span>
                    <?php echo plugin_lang_get( 'emailbody' ) ?>
                    </td>
                    <td class="left">
                    <textarea name="emailbody" cols="60" rows="10"></textarea>
                </td>
			</tr>
		</fieldset>
		</table>
		</div>
		</div>
		</div>
			<div class="widget-toolbox padding-8 clearfix">
				<span class="required pull-right"> * <?php echo lang_get( 'required' ); ?></span>
				<input type="submit" name="config_set" class="btn btn-primary btn-sm btn-white btn-round"
					   value="<?php echo plugin_lang_get('sendmailbutton') ?>"/>
			</div>
		</div>
	</div>
    </form>
<?php
layout_page_end();
