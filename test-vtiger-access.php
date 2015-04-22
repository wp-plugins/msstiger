<?php
if ($_REQUEST['check'] = "checkVtigerWebservice") {
	global $plugin_dir_mss_tiger;
	chdir($plugin_dir_mss_tiger);
	include_once $plugin_dir_mss_tiger . "vtwsclib/Vtiger/WSClient.php";
	$url = $_REQUEST['url'];
	$username = $_REQUEST['Mss_host_username'];
	$accessKey = $_REQUEST['MSS_host_access_key'];
	$client = new Vtiger_WSClient($url);
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	$login = $client->doLogin($username, $accessKey);
	if ($login) {
		echo "success";
	} else {

		echo "failure";
	}
}
?>
