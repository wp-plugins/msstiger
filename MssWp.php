<?php

class MSSWPVT {

	/*
	 * Function to initialize this plugin
	 */
	public static function init() {
		add_action('admin_enqueue_scripts', 'msst_LoadMssTigerScript');
		add_action('admin_menu', 'msst_Msstigermenu');
		add_action('user_register', 'msst_mss_tiger_capture_registering_users');
		add_action('after_plugin_row_MSs-tiger/mss_tiger.php', array('MSSWPVT', 'plugin_row'));
		add_action('plugin_action_links_MSs-tiger/mss_tiger.php', array('MSSWPVT', 'plugin_settings_link'), 10, 2);
	}

	/*
	 * Function to get the settings link
	 * @$links string URL for the link
	 * @$file string filename for the link
	 * @return string html links
	 */
	public static function plugin_settings_link($links, $file) {

		array_unshift($links, '<a href="' . admin_url("admin.php") . '?page=Mss_tiger">' . __('Settings', 'Mss_tiger') . '</a>');

		return $links;
	}

	/*
	 * Function to get the plugin row
	 * @$plugin_name as string
	 */
	public static function plugin_row($plugin_name) {
		echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message"> Now get 25% discount for purchasing pro version using the coupon "<b>OFF25WPTIGER</b>" <a href="http://mastersoftwaretechnologies.com//wp-vtiger-pro.html">Purchase pro version now!</a></div></td>';
	}
}
