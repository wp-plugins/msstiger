<?php
class MSsWPAdminPages {

	function wptiger_rightContent() {
		global $plugin_url_mss_tiger;
		$rightContent = '<div class="tiger-plugindetail-box" id="wptiger-pluginDetails"><h3>Plugin Details</h3>
			<div class="wptiger-box-inside wptiger-plugin-details">
			<table>	<tbody>
			<tr><td><b>Plugin Name</b></td><td>WP Tiger</td></tr>
			<tr><td><b>Version</b></td><td>3.0.3 <a style="text-decoration:none" href="http://mastersoftwaretechnologies.com//free-wordpress-vtiger-webforms-module.html" target="_blank">( Update Now )</a></td></tr>
			</tbody></table>
			<div class="company-detials" id="company-detials">
			<div class="position" id="position"><a target="_blank" href="http://mastersoftwaretechnologies.com//"><img src=" "></a></div>
			</div>
			</div><!-- end inside div -->
			</div>';
		return $rightContent;
	}

	function topContent() {
		$header_content = '<div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; margin-top:15px;padding: 5px;text-align:center"> Please check out <a href="http://mastersoftwaretechnologies.com//blog/category/free-wordpress-plugins" target="_blank">www.mastersoftwaretechnologies.com</a> for the latest news and details of other great plugins and tools. </div><br/>';
		return $header_content;
	}

	function mss_tiger_listShortcodes() {
		$config = get_option('Mss_vtpl_settings');
		if (!empty($config['hostname']) && !empty($config['dbuser'])) {
			?>
		<div class="list-codes">

	<h2>List of Shortcodes</h2>
	<table cellspacing="0" cellpadding="0">
		<tbody><tr>

		</tr>

		<tr class="smack_alt">
			<th style="width: 5%;" class="list-view-th">#</th>
			<th style="width: 24%;" class="list-view-th">Shortcodes</th>
			<th style="width: 12%;" class="list-view-th">VT Module</th>
			<th style="width: 10%;" class="list-view-th">Assigned To</th>
			<th style="width: 10%;" class="list-view-th">IsWidget</th>
			<th style="width: 18%;" class="list-view-th">Action</th>
		</tr>
		<?php
global $wpdb;
			$myrows = $wpdb->get_results("SELECT * FROM create_shortcode");
			$i = 1;
			foreach ($myrows as $list) {

				$resut = json_decode("$list->data", true);

				?>
		<tr class="smack_highlight"><td style="text-align:center;"><?php echo $i;?></td>
		<td style="text-align:center;">[Mss-tiger-New-form name="<?php echo $list->shortcode;?>"]</td>
		<td style="text-align:center;"><?php echo $resut['lead'];?></td>
		<td style="text-align:center;"><?php echo $resut['assigned'];?></td>
		<td style="text-align:center;"><?php if ($resut['iswidget'] == on) {echo "YES";} else {echo "No";}?></td>
		<td style="text-align:center;">
		<select onchange="confirmDelete(&quot;<?php echo $list->shortcode;?>&quot;,&quot;<?php echo $resut['lead'];?>&quot;,&quot;<?php echo admin_url();?>&quot;)" name="<?php echo $list->shortcode;?>" id="<?php echo $list->shortcode;?>">
			<option value="Select Action">Select Action</option>
			<option value="edit">Edit</option>
			<option value="delete">Delete</option>
		</select></td></tr>
		<?php $i++;}?>
				</tbody></table><input type="hidden" name="ShortCodeaction" id="ShortCodeaction">		</div>

	<?php
} else {
			echo "<div style='margin-top:20px;font-weight:bold;'>
					Please enter a valid database <a href=" . admin_url() . "admin.php?page=Mss_tiger&action=plugin_settings>settings</a>
					</div>";
		}
	}

	function capture_wp_users() {
		global $plugin_url_mss_tiger;
		$imagepath = "{$plugin_url_mss_tiger}/images/";
		if (isset($_POST['submitbtn'])) {

			if (($_POST['msst_user_capture']) == 'on') {
				$config = get_option('Mss_vtpl_settings');
				if (!empty($config['hostname']) && !empty($config['dbuser'])) {
					$vtdb = new wpdb($config['dbuser'], $config['dbpass'], $config['dbname'], $config['hostname']);

					$allowedFields2 = $vtdb->get_results("SELECT firstname,email FROM vtiger_contactdetails");

					foreach ($allowedFields2 as $user) {
						if ($_POST['mmack_capture_duplicates'] == 'skip') {
							$user_id = username_exists($user->firstname);

							if (!$user_id and email_exists($user->email) == false) {
								echo "ok";
								$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
								$user_id = wp_create_user($user->firstname, $random_password, $user->email);
							} else {
								echo "no";
								$random_password = __('User already exists.  Password inherited.');
							}
						}
						if ($_POST['mmack_capture_duplicates'] == 'update') {
							$user_id = username_exists($user->firstname);

							$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
							$user_id = wp_create_user($user->firstname, $random_password, $user->email);
							echo $random_password;
						}
					}
				} else {
					$vtdb = new wpdb($config['dbuser'], $config['dbpass'], $config['dbname'], $config['hostname']);

					$allowedFields2 = $vtdb->get_results("SELECT * FROM vtiger_contactdetails");
				}
			}

		}

		?>
		<div class="upgradetopro" id="upgradetopro" style="display:none;">This feature is only available in Pro Version,
			Please <a href="http://mastersoftwaretechnologies.com//wp-vtiger-pro.html">UPGRADE TO PRO</a></div>
		<div class="syncro-codes">
			<div>

				<form id="msst-vtiger-user-capture-settings-form" action="<?php echo $_SERVER['REQUEST_URI'];?>"
					  method="post">

					<input type="hidden" name="msst-vtiger-user-capture-settings-form"
						   value="msst-vtiger-user-capture-settings-form"/>


					<h2>Capture WordPress users</h2>
					<table class="wor-table">
						<!-- <tr>
							<td>
								<div id="capuserid"></div>
							</td>
						</tr> -->
						<tr>
							<td>
								<label>
									<div style='float:left;padding-right: 5px;'>Sync New Registration to VT Contacts
									</div>
									<div style='float:right;'>:</div>
								</label>
							</td>
							<td>
								<input type='checkbox' class='msst-vtiger-settings-user-capture'
									   name='msst_user_capture' id='msst_user_capture'
									<?php
if ($config['msst_user_capture'] == 'on') {
			echo "checked";
		}
		?>
									>
							</td>
						</tr>
						<tr>
		<td>
			<label><div style="float:left;padding-right: 5px;">Duplicate handling</div><div style="float:right;">:</div> </label>
		</td>
		<td>
			<input type="radio" checked="" value="skip" id="mmack_capture_duplicates" name="mmack_capture_duplicates" class="smack-vtiger-settings-capture-duplicates"> Skip
		</td>
		<td>
			<input type="radio" value="update" id="mmack_update_records" name="mmack_capture_duplicates" class="smack-vtiger-settings-capture-duplicates"> Update
		</td>
		<td>
			<input type="radio" value="none" id="mmack_update_records" name="mmack_capture_duplicates" class="smack-vtiger-settings-capture-duplicates"> None
		</td>

	</tr>

					</table>



					<table>
						<tr>
							<td>
								<input type="hidden" name="posted" value="<?php echo 'posted';?>">

								<p class="submit">
									<input type="submit" value="<?php _e('Save Settings');?>" name="submitbtn" class="bottom-btn"
										  />
								</p>
							</td>
							<td>
								<input type="button" style="float:left;" value="<?php _e('Sync Now');?>"
									   class="bottom-btn submit-add-to-menu" onclick="captureAlreadyRegisteredUsers('<?php echo admin_Url();?>');"/>
								<img style="display:none; float:left; padding-top:5px; padding-left:5px;"
									 id="loading-image" src="<?php echo $imagepath . 'loading-indicator.gif';?>"/>
							</td>

						</tr>

					</table>
				</form>
			</div>

		</div>
	<?php
}

	function plugin_settings() {

		$fieldNames = array('hostname' => __('Database Host'), 'dbuser' => __('Database User'), 'dbpass' => __('Database Password'), 'dbname' => __('Database Name'), 'url' => __('URL'), 'Mss_host_username' => __("MSst Host Username"), 'MSS_host_access_key' => __("MSst Host Access Key"), 'wp_tiger_msst_user_capture' => __('Capture User'));
		foreach ($_POST as $key => $value) {
			$_POST[$key] = sanitize_text_field($value);
			if (sizeof($_POST) && isset($_POST["msst_vtlc_hidden"])) {
				$config = get_option("Mss_vtpl_settings");
				foreach ($fieldNames as $field => $value) {
					if (($field != "dbpass") && ($field != "MSS_host_access_key")) {
						$config[$field] = $_POST[$field];
					} else {
						if (($_POST['dbpass'] != '') && ($field == "dbpass")) {
							$config[$field] = $_POST[$field];
						} elseif (($_POST['MSS_host_access_key'] != '') && ($field == "MSS_host_access_key")) {
							$config[$field] = $_POST[$field];
						}
					}
				}

				$dbvalues = new wpdb($config['dbuser'], $config['dbpass'], $config['dbname'], $config['hostname']);
				$versions = $dbvalues->get_results("SELECT current_version FROM vtiger_version order by id desc limit 1");
				foreach ($versions as $tmp) {
					$version = $tmp->current_version;
				}
				$config['version'] = $version;
				update_option('Mss_vtpl_settings', $config);
				$wp_tiger_contact_form_attempts = get_option('mss_tiger-contact-form-attempts');
				$wp_tiger_contact_widget_form_attempts = get_option('mss-tiger-contact-widget-form-attempts');
				$successfulAtemptsOption['total'] = 0;
				$successfulAtemptsOption['success'] = 0;

				if (!is_array($wp_tiger_contact_form_attempts)) {
					update_option('mss_tiger-contact-form-attempts', $successfulAtemptsOption);
				}
				if (!is_array($wp_tiger_contact_widget_form_attempts)) {
					update_option('mss-tiger-contact-widget-form-attempts', $successfulAtemptsOption);
				}
			}
		}
		$siteurl = site_url();
		$config = get_option('Mss_vtpl_settings');
		$config_field = get_option("Mss_vtpl_field_settings");

		$content = '<div class="vtiger-database">
				<div>';

		if (!isset($config_field['fieldlist'])) {
			$content .= '<form class="left-side-content" id="msst_vtlc_form"
							method="post">';
		} else {
			$content .= '<form class="left-side-content" id="msst_vtlc_form"
							action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
		}
		if (isset($_POST['Submit']) && $_POST['Submit'] == 'Save Settings') {
			?>
			<script>
				saveSettings();
			</script>
		<?php
}
		//form to test the database connections
		$content .= '<input type="hidden" name="page_options" value="Mss_vtpl_settings" />
						<input type="hidden" name="msst_vtlc_hidden" value="1" />

						<h2>VtigerCRM sync configuration</h2>

						<div class="crm-first-section">
						<div class="messageBox" id="message-box" style="display:none;" ><b>Settings Successfully Saved!</b></div>
						<h3>VTigerCRM database settings</h3>
						<div id="dbfields">
							<table>
								<tr>
									<td class="msst_settings_msst_settings_td_label"><label>Database
											Hostname</label></td>
									<td><input class="msst_settings_input_text" type="text"
										id="hostname" name="hostname"
										value="' . $config['hostname'] . '" /></td>
								</tr>
								<tr>
									<td class="msst_settings_td_label"><label>Database Username</label>
									</td>
									<td><input class="msst_settings_input_text" type="text" id="dbuser"
										name="dbuser" value="' . $config['dbuser'] . '" /></td>
								</tr>
								<tr>
									<td class="msst_settings_td_label"><label>Database Password</label>
									</td>
									<td><input class="msst_settings_input_text" type="password" id="dbpass" onblur="enableTestDatabaseCredentials();" name="dbpass" autocomplete="off" /><br /></td>
								</tr>
								<tr>
									<td class="msst_settings_td_label"><label>Database Name</label></td>
									<td><input class="msst_settings_input_text" type="text" id="dbname"
										name="dbname" value="' . $config['dbname'] . '" /></td>
								</tr>
							</table>
						</div>
						<table>
							<tr>
								<td class="msst_settings_td_label"><input type="button" id="Test-Database-Credentials"
									class="button" disabled=disabled value="Test database connection"
									onclick="testDatabaseCredentials(\'' . $siteurl . '\');" /></td>
								<td id="msst-database-test-results"><p id="database_process" style="display:none;">Processing</p></td>
							</tr>

						</table>
						</div>

						<!--first-part-end-here-->

						<div class="crm-first-section">
						<h3>VtigerCRM settings</h3>
						<div id=vtigersettings>
							<table>
								<tr>
									<td class="msst_settings_td_label"><label>Vtiger URL</label></td>
									<td><input class="msst_settings_input_text" type="text" id="url"
										name="url" value="' . $config['url'] . '" /></td>
								</tr>
								<tr>
									<td class="msst_settings_td_label"><label>Vtiger Username</label></td>
									<td><input class="msst_settings_input_text" type="text" id="Mss_host_username"
										name="Mss_host_username" value="' . $config['Mss_host_username'] . '" /></td>
								</tr>
								<tr>
									<td class="msst_settings_td_label"><label>Vtiger AccessKey</label></td>
									<td><input class="msst_settings_input_text" type="password" id="MSS_host_access_key" onblur="enableTestVtigerCredentials();" autocomplete="off"
										name="MSS_host_access_key" /></td>
								</tr>
							</table>
							<table>
								<tr>
									<td class="msst_settings_td_label"><input type="button"
										class="button" disabled=disabled value="Test Vtiger Credentials" id="Test-Vtiger-Credentials"
										onclick="testVtigerCredentials(\'' . $siteurl . '\');" /></td>
									<td id="msst-vtiger-test-results"> <p id="vtiger_process"style="display:none">Processing</p></td>
								</tr>

							</table>
							<table>
								<tr>
									<td class="msst_settings_td_label"><label>Application Key</label></td>
									<td><input class="msst_settings_input_text" type="text" id="appkey"
										name="appkey" value="" /></td>
								</tr>
							</table>
							</div>
							</div>
							<!--second section close here-->


							<div class="crm-first-section">
							<h3>Capturing WordPress users</h3>
							<table>
								<tr>
									<td><label>
											<div style="float: left">Do you need to capture the registering
												users</div>
											<div style="float: right; padding-left: 5px;">:</div>
									</label></td>
									<td><input type="checkbox"
										class="msst-vtiger-settings-user-capture" onclick="recature();"
										name="wp_tiger_msst_user_capture" id="wp_tiger_msst_user_capture"';

		if ($config['wp_tiger_msst_user_capture'] == 'on') {
			$content .= "checked";
		}
		$content .= '>
						</td></tr><tr>
						<td><div id="capture"></div></td>
						</tr>
						<!--<tr>
							<td>
								<div style="float: left">Sync WP members to VtigerCRM contacts</div>
								<div style="float: right; padding-left: 5px;">:</div>
							</td>
							<td><input type="button" value="Sync"
								class="bottom-btn submit-add-to-menu"
								onclick="captureAlreadyRegisteredUsersWpTiger();" />
								<div id="please-upgrade" style="position: absolute; z-index: 100;"></div>
							</td>
						</tr>-->

						</table>


						<input type="hidden" name="posted" value="Posted">
						<p class="submit">
							<input name="Submit" type="submit" value="Save Settings" class="bottom-btn last-btn" />
						</p>
						<div id="vt_fields_container"></div>

						</div>

						</form></div>

	</div>';
		echo $content;
	}

	function widget_fields() {
		global $plugin_url_mss_tiger;
		global $wpdb;

		$config = get_option('Mss_vtpl_settings');
		$edit_fild = $_REQUEST['EditShortCode'];
		$siteurl = site_url();

		foreach ($_REQUEST as $key => $value) {
			//empty the value if sanitize fails
			if (!preg_match('/^[A-Za-z0-9_-]*$/', $value)) {
				$_REQUEST[$key] = '';
			}
		}

		if (($_REQUEST['action'] = 'widget_fields') && (empty($_REQUEST['EditShortCode']))) {
			$config = get_option('Mss_vtpl_settings');
			$config_widget_field = get_option("Mss_vtlc_widget_field_settings");

		} else {
			$alloweded2 = $wpdb->get_row("SELECT * FROM `create_shortcode` WHERE `shortcode`='$edit_fild'");
			$new_data = json_decode("$alloweded2->select_field", true);
			$config_widget_field['widgetfieldlist'] = $new_data;

		}
		$topContent = $this->topContent();
		$imagepath = "{$plugin_url_mss_tiger}/images/";

		if (($_REQUEST['action'] = 'widget_fields') && (empty($_REQUEST['EditShortCode']))) {
			if (isset($_POST['widget_field_posted'])) {
				$config_widget_field['widgetfieldlist'] = array();
				if (isset($_POST['no_of_vt_fields'])) {
					$fieldArr = array();
					for ($i = 0; $i <= $_POST['no_of_vt_fields']; $i++) {
						if (isset($_POST["msst_vtlc_field$i"])) {
							array_push($fieldArr, $_POST["msst_vtlc_field_hidden$i"]);
						}
					}
					$config_widget_field['widgetfieldlist'] = $fieldArr;

				}
				update_option('Mss_vtlc_widget_field_settings', $config_widget_field);
			}

			if (isset($_POST['saveContactFields']) && $_POST['saveContactFields'] == 'Save Field Settings') {

				foreach ($_POST as $key => $value) {
					$_POST[$key] = sanitize_text_field($value);
				}
				$config_widget_field['widgetfieldlist'] = array();
				if (isset($_POST['no_of_vt_fields'])) {
					$fieldArr = array();
					for ($i = 0; $i <= $_POST['no_of_vt_fields']; $i++) {
						if (isset($_POST["msst_vtlc_field$i"])) {
							array_push($fieldArr, $_POST["msst_vtlc_field_hidden$i"]);
						}
					}

					$data_array = json_encode($config_widget_field['widgetfieldlist'] = $fieldArr);

				}

				$update = update_option('Mss_vtlc_widget_field_settings', $config_widget_field);
				$linkUrl = site_url() . '/wp-admin/admin.php?page=Mss_tiger&action=widget_fields';
				?>
				<script>
					 saveFormFields('<?php echo $data_array;?>', '<?php echo $linkUrl;?>');
				</script>

				<?php

			}
		}
		$widgetContent = '<div class="left-side-content">
					 <div class="upgradetopro" id="upgradetopro" style="display:none;">This feature is only available in Pro Version, Please <a href="http://mastersoftwaretechnologies.com//wp-vtiger-pro.html">UPGRADE TO PRO</a> </div>
					<div class="messageBox" id="message-box" style="display:none;" ><b>Successfully Saved!</b></div>
		<form id="wiget fiels" action="' . $_SERVER['REQUEST_URI'] . '" method="post" name="">';

		if (!empty($config['hostname']) && !empty($config['dbuser'])) {
			$vtdb = new wpdb($config['dbuser'], $config['dbpass'], $config['dbname'], $config['hostname']);
			$allowedFields = $vtdb->get_results("SELECT fieldid, fieldname, fieldlabel, typeofdata,block FROM vtiger_field WHERE tabid = 4  ORDER BY block, sequence");

			if (($_REQUEST['action'] = 'widget_fields') && (!empty($_REQUEST['EditShortCode']))) {
				echo '<h3>[Mss-tiger-New-form name="' . $edit_fild . '"]</h3>';
			}

			if (!is_array($config_widget_field['widgetfieldlist'])) {
				$config_widget_field['widgetfieldlist'] = array();
			}
		} elseif (!empty($_POST['hostname']) && !empty($_POST['dbuser'])) {
			$vtdb = new wpdb($_POST['dbuser'], $_POST['dbpass'], $_POST['dbname'], $_POST['hostname']);
			$allowedFields = $vtdb->get_results("SELECT fieldid, fieldname, fieldlabel, typeofdata FROM vtiger_field WHERE tabid = 7 AND tablename != 'vtiger_crmentity' AND uitype != 4 ORDER BY block, sequence");

			if (!is_array($config_widget_field['widgetfieldlist'])) {
				$config_widget_field['widgetfieldlist'] = array();
			}
		}

		if (!empty($allowedFields)) {
			global $wpdb;
			if (($_REQUEST['action'] = 'widget_fields') && (empty($_REQUEST['EditShortCode']))) {
				if (isset($_POST['create_shortcode']) && $_POST['create_shortcode'] == 'Generate Shortcode') {
					// echo $_POST['assigned'];
					foreach ($_POST as $key => $value) {
						$_POST[$key] = sanitize_text_field($value);
					}
					$datanew = json_encode($_POST);
					$shortcode = createRandomPassword();
					echo '<h3>[Mss-tiger-New-form name="' . $shortcode . '"]</h3>';

					$fieldArr = array();
					if (isset($_POST['widget_field_posted'])) {
						if (isset($_POST['no_of_vt_fields'])) {

							for ($i = 0; $i <= $_POST['no_of_vt_fields']; $i++) {
								if (isset($_POST["msst_vtlc_field$i"])) {
									array_push($fieldArr, $_POST["msst_vtlc_field_hidden$i"]);
								}
							}

						}
					}
					$addval = json_encode($fieldArr);

					if ($datanew) {
						$_POST['assigned'] = sanitize_text_field($_POST['assigned']);
						$wpdb->insert('create_shortcode', array('shortcode' => $shortcode, 'data' => $datanew, 'assign' => $_POST['assigned'], 'select_field' => $addval), array('%s', '%s', '%s'));
					}
				}

				if (isset($_POST['Submit']) && $_POST['Submit'] == 'Save Mandatory Fields') {
					die('save');
					?>
				<script>
					saveSettings();
				</script>
			<?php
}

			} else {

				if (isset($_POST['Submit']) && $_POST['Submit'] == 'Save Field Settings') {

					foreach ($_POST as $key => $value) {
						$_POST[$key] = sanitize_text_field($value);
					}
					$datanew = json_encode($_POST);
					if ($datanew) {

						$_POST['assigned'] = sanitize_text_field($_POST['assigned']);
						$wpdb->update('create_shortcode', array('data' => $datanew, 'assign' => $_POST['assigned']), array('shortcode' => $edit_fild), array('%s', '%s'), array('%s'));
					}
				}

			}

			/*	$wp_tiger_contact_form_attempts = get_option('mss-tiger-contact-widget-form-attempts');
			$total = $wp_tiger_contact_form_attempts['total'];
			$success = $wp_tiger_contact_form_attempts['success'];
			$failure = $total - $success;
			if (isset($total)) {
			$widgetContent .= '<b>Submissions :-</b> ( Success <span style="color:green; cursor:pointer;" onclick="alertMsgWpTiger(\' Successful captures from this form ' . $success . ' \')";> ' . $success . ' </span> / Failure <span style="color:red; cursor:pointer;" onclick="alertMsgWpTiger(\' Failed attempts from this form ' . $failure . ' \')";> ' . $failure . ' </span> </span> ) Total <span style="color:green; cursor:pointer;" onclick="alertMsgWpTiger(\' Total trials from this form ' . $total . ' \')"; > ' . $total . ' </span> <br/>';
			}*/

			$widgetContent .= '<div style="width:100%;float:left;"><h3 class="title">Contact Field settings</h3></div><div style="width:75%;float:right;">';

			$widgetContent .= '</div>
							<div style="margin-top:10px;">
						<br/><div style="padding:2px;"><input type="checkbox" id="skipduplicate" onclick="upgradetopro()" /> Skip Duplicates. Note: Email should be mandatory and enabled to make this work. </div>
						<div style="padding:2px;"><input type="checkbox" name="iswidget" id="generateshortcode"  /> Generate this Shortcode for widget form. </div>
						<br/><div style="padding:2px;">Assign Leads to User: <select id="assignto" class="assign-leads" name="assigned" >';
			$blogusers = get_users(array('fields' => array('display_name', 'user_login')));
			foreach ($blogusers as $user) {
				$widgetContent .= '<option value=' . esc_html($user->user_login) . '>' . esc_html($user->display_name) . '</option>';
			}
			$widgetContent .= '</select></div>
							</div><br/>
							<input type="hidden" name="lead" value="widget">

							<input type="button" class="bottom-btn submit-add-to-menu" name="sync_crm_fields" value="Fetch CRM Fields"
							 onclick="goToTop(); syncCrmFields(&quot;' . admin_Url() . '&quot;,&quot;widget&quot;,&quot;smack_wp_vtiger_contact_fields-tmp&quot;,&quot;onCreate&quot;);"/>
							<input type="submit" value="Save Field Settings" class="bottom-btn submit-add-to-menu"
								name="saveContactFields"/>
							<input type="Submit" class="bottom-btn submit-add-to-menu" name="make_mandatory"
								id="make_mandatory" value="Save Mandatory Fields"  />
							<input type="button" class="bottom-btn submit-add-to-menu" name="save_display_name"
								id="save_display_name" value="Save Labels" onclick="goToTop(); saveDisplayName(&quot;' . admin_Url() . '&quot;,&quot;saveDisplayName&quot;,&quot;save_display_name&quot;,&quot;widget&quot;,&quot;smack_wp_vtiger_lead_fields-tmp&quot;,&quot;onCreate&quot;);" />';
			if (($_REQUEST['action'] = 'widget_fields') && (empty($_REQUEST['EditShortCode']))) {
				$widgetContent .= '<input type="submit" class="button-create-shortcode" name="create_shortcode" id="create_shortcode" value="Generate Shortcode"  />';
			}
			$widgetContent .= '<img id="loading-image" style="display: none; float: right; padding-top: 5px; padding-left: 5px;" src="">
<!--<p>Please use the short code <b>[display_widget_area]</> in widgets</p>!--><br>
<br/>
						<div id="fieldtables">
							<table class="tableborder">
								<tr class="msst_alt">
									<th style="width: 20px;"><input type="checkbox" name="selectall" id="selectall"
										onclick="select_allfields(\'msst_vtlc_field_form\',\'widget\')" /></th>
									<th style="width: 140px;"><h5>Field Name</h5></th>
									<th style="width: 80px;"><h5>Show Field</h5></th>
									<th style="width: 100px;"><h5>Order</h5></th>
									<th style="width: 120px;"><h5>Mandatory Fields</h5></th>
									<th style="width: 200px;"><h5>Field Label Display</h5></th>
								</tr>

								<tbody>
								<tr valign="top">
									<td><input type="hidden" id="no_of_vt_fields" name="no_of_vt_fields" value="' . sizeof($allowedFields) . '">';

			$nooffields = count($allowedFields);
			$inc = 1;
			foreach ($allowedFields as $key => $field) {
				if ($inc % 2 == 1) {
					$widgetContent .= '<tr class="msst_highlight">';
				} else {
					$widgetContent .= '<tr class="msst_highlight msst_alt">';
				}
				$typeofdata = explode('~', $field->typeofdata);
				$widgetContent .= '<td class="msst-field-td-middleit">
									<input type="hidden"  value="' . $field->fieldlabel . '"id="field_label' . $key . '">
									<input type="hidden" value="' . $typeofdata[1] . '" id="field_type' . $key . '">
									<input type="hidden" id="mss_label' . $key . '" name="msst_vtlc_field_hidden' . $key . '"value="' . $field->fieldid . '" />';

				if ($typeofdata[1] == 'M') {
					$checked = 'checked="checked" disabled';
				} else {
					$checked = "";
				}

				if ($typeofdata[1] == 'M') {
					$widgetContent .= '<input type="hidden" value="' . $field->fieldname . '"id="msst_vtlc_field' . $key . '"
										name="msst_vtlc_field' . $key . '" />
									<input type="checkbox" value="' . $field->fieldname . '"' . $checked . '>';
				} else {
					$widgetContent .= '<input type="checkbox" value="' . $field->fieldname . '"id="msst_vtlc_field' . $key . '"
									name="msst_vtlc_field' . $key . '" ' . $checked . '>';
				}

				$widgetContent .= '</td>
								<td>' . $field->fieldlabel;
				if ($typeofdata[1] == 'M') {
					$widgetContent .= '<span style="color: #FF4B33">&nbsp;*</span>';
				}

				$widgetContent .= '</td>
								<td class="msst-field-td-middleit">';

				if (in_array($field->fieldid, $config_widget_field['widgetfieldlist'])) {
					if ($typeofdata[1] == 'M') {
						$widgetContent .= '<a id="publish3" class="smack_pointer" onclick="published(' . $key . ',&quot;1&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);" name="publish3"><img id="msst-field-td-middleit" src="' . $imagepath . 'tick_strict.png"  /></a>';
					} else {
						$widgetContent .= '<a id="publish3" class="smack_pointer" onclick="published(' . $key . ',&quot;0&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);" name="publish3"><img  id="msst-field-td-middleit" src="' . $imagepath . 'tick.png"  /></a>';
					}
				} else {
					$widgetContent .= '<a id="publish3" class="smack_pointer" onclick="published(' . $key . ',&quot;1&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);" name="publish3"><img  id="msst-field-td-middleit" src="' . $imagepath . 'publish_x.png"  /></a>';
				}

				$widgetContent .= '</td>	<td class="msst-field-td-middleit">';

				if ($inc == 1) {
					$widgetContent .= '<a class="msst_pointer" id="down' . $key . '" onclick="move(' . $key . ',&quot;down&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);">
									<img src="' . $imagepath . 'downarrow.png" /></a>';
				} elseif ($inc == $nooffields) {
					$widgetContent .= '<a class="msst_pointer" id="up' . $key . '" onclick="move(' . $key . ',&quot;up&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;)">
									<img src="' . $imagepath . 'uparrow.png" /></a>';
				} else {
					$widgetContent .= '<a class="msst_pointer" id="down' . $key . '" onclick="move(' . $key . ',&quot;down&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);">
									<img src="' . $imagepath . 'downarrow.png" /></a>
									<a class="msst_pointer" id="up' . $key . '" onclick="move(' . $key . ',&quot;up&quot;,&quot;' . admin_url() . '&quot;,&quot;widget&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;)">
									<img src="' . $imagepath . 'uparrow.png" /></a>';
				}

				$widgetContent .= '</td>	<td class="msst-field-td-middleit">
								<input type="checkbox" name="check' . $key . '" id="check' . $key . '"';

				if ($typeofdata[1] == 'M') {
					$widgetContent .= ' checked="checked" disabled ';
				}

				$widgetContent .= '/></td>	<td class="msst-field-td-middleit" id="field_label_display_td' . $key . '">
								<input type="text" id="field_label_display_textbox' . $key . '" class="readonly-text"
										value="' . $field->fieldlabel . '"  /></td>
								</tr>';
				$inc++;
			}

			$widgetContent .= '</td>
										</tr>
									</tbody>
								</table></div>
								<!--<p class="submit">Please use the short code <b> [display_widget_area]</b> in widgets
								</p>!-->
								<input type="hidden" name="widget_field_posted" value="Posted" />
								<input type="hidden" name="Shortcode" value="' . $edit_fild . '" id="shortcode"/>
                                                                <input type="button" class="bottom-btn submit-add-to-menu"
                                name="sync_crm_fields" value="Fetch CRM Fields"
                               onclick="goToTop(); syncCrmFields(&quot;' . admin_Url() . '&quot;,&quot;widget&quot;,&quot;smack_wp_vtiger_contact_fields-tmp&quot;,&quot;onCreate&quot;);"/>
                        <input type="submit" value="Save Field Settings22"
                                class="bottom-btn submit-add-to-menu" name="Submit" />
                        <input type="button" class="bottom-btn submit-add-to-menu"
                                name="make_mandatory" id="make_mandatory"
                                value="Save Mandatory Fields" onclick="upgradetopro()" /> <input
                                type="button" class="bottom-btn submit-add-to-menu"
                                name="save_display_name" id="save_display_name" value="Save Labels"
                                onclick="upgradetopro()" />

								</form>
							</div>
							<div class="right-side-content" >
							</div>';

			echo $widgetContent;
		} else {
			$widgetContent = "<div style='margin-top:20px;font-weight:bold;'>
					Please enter a valid database <a href=" . admin_url() . "admin.php?page=Mss_tiger&action=plugin_settings>settings</a>
					</div>";
			echo $widgetContent;
		}
	}

	/**
	 *
	 * Function to get vtiger fields from the database
	 */
	function vtiger_db_fields() {
		global $plugin_url_mss_tiger;
		global $wpdb;
		$config = get_option('Mss_vtpl_settings');
		if (isset($_POST['hostname'])) {
			$config['hostname'] = $_POST['hostname'];
			$config['dbuser'] = $_POST['dbuser'];
			$config['dbname'] = $_POST['dbname'];
			$config['dbpass'] = $_POST['dbpass'];
			//	$config ['appkey'] = $_POST ['appkey'];

			update_option('Mss_vtpl_settings', $config);
		} else {
			$edit_fild = $_REQUEST['EditShortCode'];
			if (($_REQUEST['action'] = 'vtiger_db_fields') && (empty($_REQUEST['EditShortCode']))) {
				$config = get_option('Mss_vtpl_settings');
				$config_field = get_option("Mss_vtpl_field_settings");
			} else {
				$alloweded2 = $wpdb->get_row("SELECT * FROM `create_shortcode` WHERE `shortcode`='$edit_fild'");
				$new_data = json_decode("$alloweded2->select_field", true);
				$config_field['fieldlist'] = $new_data;
			}
		}

		if (($_REQUEST['action'] = 'vtiger_db_fields') && (empty($_REQUEST['EditShortCode']))) {
			if (isset($_POST['field_posted'])) {
				$config_field['fieldlist'] = array();
				if (isset($_POST['no_of_vt_fields'])) {
					$fieldArr = array();
					for ($i = 0; $i <= $_POST['no_of_vt_fields']; $i++) {
						if (isset($_POST["msst_vtlc_field$i"])) {
							array_push($fieldArr, $_POST["msst_vtlc_field_hidden$i"]);
						}
					}
					$config_field['fieldlist'] = $fieldArr;

				}

				update_option('Mss_vtpl_field_settings', $config_field);
			}
		}
		$content = '';
		$content .= '<div class="left-side-content">
	<div class="upgradetopro" id="upgradetopro" style="display:none;">This feature is only available in Pro Version, Please <a href="http://mastersoftwaretechnologies.com//wp-vtiger-pro.html">UPGRADE TO PRO</a></div>
	<div class="messageBox" id="message-box" style="display:none;" ><b>Successfully Saved!</b></div>
		<form id="msst_vtlc_field_form"
			action="' . $_SERVER['REQUEST_URI'] . '" method="post">';

		if (!empty($config['hostname']) && !empty($config['dbuser'])) {
			$vtdb = new wpdb($config['dbuser'], $config['dbpass'], $config['dbname'], $config['hostname']);
			$allowedFields = $vtdb->get_results("SELECT fieldid, fieldname, fieldlabel, typeofdata,block FROM vtiger_field WHERE tabid = 7  ORDER BY block, sequence");

			if (!is_array($config_field['fieldlist'])) {
				$config_field['fieldlist'] = array();
			}

		} elseif (!empty($_POST['hostname']) && !empty($_POST['dbuser'])) {
			$vtdb = new wpdb($_POST['dbuser'], $_POST['dbpass'], $_POST['dbname'], $_POST['hostname']);
			$allowedFields = $vtdb->get_results("SELECT fieldid, fieldname, fieldlabel, typeofdata FROM vtiger_field WHERE tabid = 7 AND tablename != 'vtiger_crmentity' AND uitype != 4 ORDER BY block, sequence");

			if (!is_array($config_field['fieldlist'])) {
				$config_field['fieldlist'] = array();
			}
		}
		if (($_REQUEST['action'] = 'vtiger_db_fields') && (!empty($_REQUEST['EditShortCode']))) {
			echo '<h3>[Mss-tiger-New-form name="' . $edit_fild . '"]</h3>';
		}

		if (!empty($allowedFields)) {
			global $wpdb;
			if (($_REQUEST['action'] = 'vtiger_db_fields') && (empty($_REQUEST['EditShortCode']))) {
				if (isset($_POST['create_shortcode']) && $_POST['create_shortcode'] == 'Generate Shortcode') {

					echo $_POST['assigned'];
					foreach ($_POST as $key => $value) {
						$_POST[$key] = sanitize_text_field($value);
					}
					$datanew = json_encode($_POST);
					$shortcode = createRandomPassword();
					echo '<h3>[Mss-tiger-New-form name="' . $shortcode . '"]</h3>';
					if (isset($_POST['field_posted'])) {

						if (isset($_POST['no_of_vt_fields'])) {
							$fieldArr = array();
							for ($i = 0; $i <= $_POST['no_of_vt_fields']; $i++) {
								if (isset($_POST["msst_vtlc_field$i"])) {
									array_push($fieldArr, $_POST["msst_vtlc_field_hidden$i"]);
								}
							}

						}
					}
					$addval = json_encode($fieldArr);
					if ($datanew) {

						$_POST['assigned'] = sanitize_text_field($_POST['assigned']);
						$wpdb->insert('create_shortcode', array('shortcode' => $shortcode, 'data' => $datanew, 'assign' => $_POST['assigned'], 'select_field' => $addval), array('%s', '%s', '%s'));
					}
				}
				if (isset($_POST['Submit']) && $_POST['Submit'] == 'Save Field Settings') {

					foreach ($_POST as $key => $value) {
						$_POST[$key] = sanitize_text_field($value);
					}
					$config_widget_field['widgetfieldlist'] = array();
					if (isset($_POST['no_of_vt_fields'])) {
						$fieldArr = array();
						for ($i = 0; $i <= $_POST['no_of_vt_fields']; $i++) {
							if (isset($_POST["msst_vtlc_field$i"])) {
								array_push($fieldArr, $_POST["msst_vtlc_field_hidden$i"]);
							}
						}

						$data_array = json_encode($config_widget_field['widgetfieldlist'] = $fieldArr);

					}
					$update = update_option('Mss_vtlc_widget_field_settings', $config_widget_field);
					$linkUrl = site_url() . '/wp-admin/admin.php?page=Mss_tiger&action=vtiger_db_fields';
					?>
				<script>
					 saveFormFields('<?php echo $data_array;?>','<?php echo $linkUrl;?>');
				</script>
			<?php
}
			} else {
				if (isset($_POST['Submit']) && $_POST['Submit'] == 'Save Field Settings') {

					foreach ($_POST as $key => $value) {
						$_POST[$key] = sanitize_text_field($value);
					}
					$datanew = json_encode($_POST);
					if ($datanew) {

						$_POST['assigned'] = sanitize_text_field($_POST['assigned']);
						$wpdb->update('create_shortcode', array('data' => $datanew, 'assign' => $_POST['assigned']), array('shortcode' => $edit_fild), array('%s', '%s'), array('%s'));
					}
				}

			}

			/*	$wp_tiger_contact_form_attempts = get_option('mss_tiger-contact-form-attempts');
			$total = $wp_tiger_contact_form_attempts['total'];
			$success = $wp_tiger_contact_form_attempts['success'];
			$failure = $total - $success;

			if (isset($total)) {
			$content .= '<b>Submissions :- </b> ( Success <span style="color:green; cursor:pointer;" onclick="alertMsgWpTiger(\' Successful captures from this form ' . $success . ' \')" > ' . $success . ' </span> / Failures <span style="color:red; cursor:pointer;" onclick="alertMsgWpTiger(\' Failed attempts from this form ' . $failure . ' \')" > ' . $failure . ' </span> </span> ) Total <span style="color:green; cursor:pointer;"  onclick="alertMsgWpTiger(\' Total trials from this form ' . $total . ' \')" > ' . $total . ' </span> <br/>';
			//					$content .= '<span style="color:green; cursor:pointer;"> 1 </span> ( <span style="color:green; cursor:pointer;"> 1 </span> / <span style="color:red; cursor:pointer;"> 2 </span> )';
			}*/

			$content .= '<div style="width:100%;float:left;"><h3 class="title">Lad Field settings</h3></div><div style="width:75%;float:right;">';

			$content .= '</div><br/>
				<div style="margin-top:0px;">
				<br/><div style="padding:2px;"><input type="checkbox" id="skipduplicate" onclick="upgradetopro()" /> Skip Duplicates. Note: Email should be mandatory and enabled to make this work. </div>
				<div style="padding:2px;"><input type="checkbox" name="iswidget" id="generateshortcode"  /> Generate this Shortcode for widget form. </div>
				<br/><div style="padding:2px;">Assign Leads to User: <select id="assignto" name="assigned" class="assign-leads">';
			$blogusers = get_users(array('fields' => array('display_name', 'user_login')));
			foreach ($blogusers as $user) {
				$content .= '<option value=' . esc_html($user->user_login) . '>' . esc_html($user->display_name) . '</option>';
			}
			$content .= '</select></div>
				</div><br/>

			<input type="hidden" name="lead" value="lead" />
			<input type="button" class="bottom-btn submit-add-to-menu"
				name="sync_crm_fields" value="Fetch CRM Fields"
				onclick="goToTop(); syncCrmFields(&quot;' . admin_Url() . '&quot;,&quot;lead&quot;,&quot;smack_wp_vtiger_contact_fields-tmp&quot;,&quot;onCreate&quot;);" />
			<input type="submit" value="Save Field Settings"
				class="bottom-btn submit-add-to-menu" name="Submit" />
			<input type="button" class="bottom-btn submit-add-to-menu"
				name="make_mandatory" id="make_mandatory"
				value="Save Mandatory Fields" onclick="upgradetopro()" /> <input type="button" class="bottom-btn submit-add-to-menu" name="save_display_name"
								id="save_display_name" value="Save Labels" onclick="goToTop(); saveDisplayName(&quot;' . admin_Url() . '&quot;,&quot;saveDisplayName&quot;,&quot;save_display_name&quot;,&quot;lead&quot;,&quot;smack_wp_vtiger_lead_fields-tmp&quot;,&quot;onCreate&quot;);" />';
			if (($_REQUEST['action'] = 'vtiger_db_fields') && (empty($_REQUEST['EditShortCode']))) {
				$content .= '<input type="submit"
				class="button-create-shortcode" name="create_shortcode"
				id="create_shortcode" value="Generate Shortcode"
				 />';
			}
			$content .= '<img id="loading-image" style="display: none; float: right; padding-top: 5px; padding-left: 5px;" src="">
					<!--<p>Please use the short code <b>[display_contact_page]</b> in page or post</p>!--><br/>
		<div id="fieldtable">
			<table class="tableborder">
				<tr class="msst_alt">
					<th style="width: 20px;"><input type="checkbox" name="selectall"
						id="selectall"
						onclick="select_allfields(\'msst_vtlc_field_form\',\'lead\')" /></th>
					<th style="width: 140px;"><h5>Field Name</h5></th>
					<th style="width: 80px;"><h5>Show Field</h5></th>
					<th style="width: 100px;"><h5>Order</h5></th>
					<th style="width: 120px;"><h5>Mandatory Fields</h5></th>
					<th style="width: 200px;"><h5>Field Label Display</h5></th>
				</tr>
				<tbody>
					<tr valign="top">

						<td><input type="hidden" id="no_of_vt_fields"
							name="no_of_vt_fields" value="' . sizeof($allowedFields) . '">';

			$nooffields = count($allowedFields);
			$inc = 1;
			foreach ($allowedFields as $key => $field) {
				?>
				<?php if ($inc % 2 == 1) {

					$content .= '<tr class="msst_highlight">';
				} else {

					$content .= '<tr class="msst_highlight msst_alt">';
				}
				$typeofdata = explode('~', $field->typeofdata);

				$content .= '<td class="msst-field-td-middleit"><input type="hidden"
							value="' . $field->fieldlabel . '"
							id="field_label' . $key . '"> <input type="hidden"
							value="' . $typeofdata[1] . '"
							id="field_type' . $key . '"> <input type="hidden" id="mss_label' . $key . '"
							name="msst_vtlc_field_hidden' . $key . '"
							value="' . $field->fieldid . '" />';
				if ($typeofdata[1] == 'M') {
					$checked = 'checked="checked" disabled';
					$mandatory = 'checked="checked" disabled';
				} else {
					$checked = "";
				}
				if ($typeofdata[1] == 'M') {
					$content .= '<input type="hidden"
						value="' . $field->fieldname . '"
							id="msst_vtlc_field' . $key . '"
							name="msst_vtlc_field' . $key . '" /> <input
							type="checkbox" value="' . $field->fieldname . '"' . $checked . ' />';
				} else {
					$content .= '<input type="checkbox"
								value="' . $field->fieldname . '"
									id="msst_vtlc_field' . $key . '"
									name="msst_vtlc_field' . $key . '"' . $checked . '/>';
				}
				$content .= "</td>
						<td>$field->fieldlabel";
				if ($typeofdata[1] == 'M') {
					$content .= '<span style="color: #FF4B33">&nbsp;*</span>';
				}
				$content .= '</td>';
				$contentUrl = WP_CONTENT_URL;
				$imagepath = "{$plugin_url_mss_tiger}/images/";
				$content .= '<td class="msst-field-td-middleit">';
				if (in_array($field->fieldid, $config_field['fieldlist'])) {

					if ($typeofdata[1] == 'M') {
						$content .= '<img  id="msst-field-td-middleit"src="' . $imagepath . 'tick_strict.png"
							onclick="upgradetopro()" />';
					} else {
						$content .= '<a id="publish3" class="smack_pointer" onclick="published(' . $key . ',&quot;0&quot;,&quot;' . admin_url() . '&quot;,&quot;lead&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);" name="publish3"><img  id="msst-field-td-middleit"src="' . $imagepath . 'tick.png" /></a>';
					}
				} else {
					$content .= '<a id="publish3" class="smack_pointer" onclick="published(' . $key . ',&quot;1&quot;,&quot;' . admin_url() . '&quot;,&quot;lead&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);" name="publish3"><img  id="msst-field-td-middleit" src="' . $imagepath . 'publish_x.png" /></a>';
				}
				$content .= '</td>
					<td class="msst-field-td-middleit">';
				if ($inc == 1) {
					$content .= '<a class="msst_pointer" id="down' . $key . '" onclick="move(' . $key . ',&quot;down&quot;,&quot;' . admin_url() . '&quot;,&quot;lead&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);"><img
								src="' . $imagepath . 'downarrow.png" /></a>';
				} elseif ($inc == $nooffields) {
					$content .= '<a class="msst_pointer" id="up' . $key . '" onclick="move(' . $key . ',&quot;up&quot;,&quot;' . admin_url() . '&quot;,&quot;lead&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;)"><img
								src="' . $imagepath . 'uparrow.png" /></a>';
				} else {
					$content .= '<a class="msst_pointer" id="down' . $key . '" onclick="move(' . $key . ',&quot;down&quot;,&quot;' . admin_url() . '&quot;,&quot;lead&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;);"><img
								src="' . $imagepath . 'downarrow.png" /></a> <a
							class="msst_pointer" id="up' . $key . '" onclick="move(' . $key . ',&quot;up&quot;,&quot;' . admin_url() . '&quot;,&quot;lead&quot;,&quot;' . $field->fieldid . '&quot;,&quot;onCreate&quot;)"><img
								src="' . $imagepath . 'uparrow.png" /></a>';
				}
				$content .= '</td>
						<td class="msst-field-td-middleit"><input type="checkbox"
							name="check' . $key . '" id="check' . $key . '"';
				if ($typeofdata[1] == 'M') {
					$content .= 'checked="checked" disabled';
				}
				$content .= ' /></td>
						<td class="msst-field-td-middleit"
							id="field_label_display_td' . $key . '"><input type="text"
							id="field_label_display_textbox' . $key . '" class="readonly-text"
							value="' . $field->fieldlabel . '"  /></td>
					</tr>';
				$inc++;
			}
			$content .= '</td>
					</tr>
				</tbody>
			</table></div>
                  <input type="hidden" name="field_posted" value="posted" />
			<input type="hidden" name="Shortcode" value="' . $edit_fild . '" id="shortcode"/>
                        <!--<p> Please use the short code <b>[display_contact_page]</b> in page or post</p>!--> <br/>
                        <input type="button" class="bottom-btn submit-add-to-menu"
                                name="sync_crm_fields" value="Fetch CRM Fields"
                                onclick="upgradetopro()" />
                        <input type="submit" value="Save Field Settings"
                                class="bottom-btn submit-add-to-menu" name="Submit" />
                        <input type="button" class="bottom-btn submit-add-to-menu"
                                name="make_mandatory" id="make_mandatory"
                                value="Save Mandatory Fields" onclick="upgradetopro()" /> <input
                                type="button" class="bottom-btn submit-add-to-menu"
                                name="save_display_name" id="save_display_name" value="Save Labels"
                                onclick="upgradetopro()" />
		</form>
	</div>
	<div class="right-side-content" >
	</div>';
			echo $content;
		} else {
			$Content = "<div style='margin-top:20px;font-weight:bold;'>
					Please enter a valid database <a href=" . admin_url() . "admin.php?page=Mss_tiger&action=plugin_settings>settings</a>
					</div>";
			echo $Content;
		}
	}
	function deleteShortCodes() {
		global $wpdb;
		$req_del = $_GET['deleteShortCode'];
		if ($_REQUEST['action'] == 'deleteShortCodes') {
			$delte_reqst = $wpdb->query($wpdb->prepare("DELETE FROM create_shortcode WHERE shortcode = %s ", $req_del));
			if ($delte_reqst) {?>
	<script>window.location.href ="admin.php?page=Mss_tiger&action=mss_tiger_listShortcodes";</script>;
	<?php
}
		}
	}

}
function createRandomPassword() {

	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double) microtime() * 1000000);
	$i = 0;
	$pass = '';

	while ($i <= 5) {
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$pass = $pass . $tmp;
		$i++;
	}
	return $pass;

}
