<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;

// =================== CHECK SESSION ID FOR USER ID ===========
$edID = $_SESSION['admin_EdID'];
// ============================================================

if(isset($_SESSION['cbtAdminID'])){
	$updateQ = 'UPDATE education_table SET et_title="' . $_POST['edTitle'] . '", et_topic_area_link=' . $_POST['taID'] . ', et_description="' . $_POST['edDescript'] . '" WHERE et_id=' . $edID;
	$updateQResult = $db->query($updateQ);
	echo json_encode($edID);
}
?>