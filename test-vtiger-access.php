<?php
if ($_REQUEST['check'] = "checkVtigerWebservice") {
	global $plugin_dir_vts_tiger;
	chdir($plugin_dir_vts_tiger);
	include_once $plugin_dir_vts_tiger . "vtwsclib/Vtiger/WSClient.php";
	$url = $_REQUEST['url'];
	$username = $_REQUEST['Vts_host_username'];
	$accessKey = $_REQUEST['VTS_host_access_key'];
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
