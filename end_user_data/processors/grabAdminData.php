<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../cbt_classes/EndUserVO.php');
if(isset($_SESSION['cbtAdminID'])){
	$cbtAdmin = $_SESSION['cbtAdminID'];
	$grabUserInfoQ = 'SELECT * FROM end_users WHERE nuid="' . $cbtAdmin . '" LIMIT 1';
	$grabUserInfoQResult = $db->query($grabUserInfoQ);
	$userObj = $grabUserInfoQResult->fetch_object("EndUserVO");
	
	echo json_encode($userObj);
}

?>