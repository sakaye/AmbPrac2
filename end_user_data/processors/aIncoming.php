<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../utilities/safe_strings.php');

$cbtAdminID = makeSafe($_POST['nRelay']);
$cbtAdminID = strtolower($cbtAdminID);

$adminCheckQ = 'SELECT * FROM end_users WHERE nuid = "' . $cbtAdminID . '" AND eu_access_level="tEditor" LIMIT 1';
$adminCheckQResult = $db->query($adminCheckQ);

$theResponse='';
if($adminCheckQResult->num_rows>0){
	$_SESSION['cbtAdminID'] = $cbtAdminID;
	$theResponse='userChecksOut';
} else {
	$theResponse="userFail";
}

echo json_encode($theResponse);