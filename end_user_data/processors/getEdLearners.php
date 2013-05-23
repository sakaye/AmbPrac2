<?php
session_start();

define('DB_CONN','dbConnect.php');
require DB_CONN;
require '../EndUserVO.php';
include('../endUserController.php');
include('../cbt_classes/EndUserEducationVO.php');

if(isset($_SESSION['cbtAdminID'])){
	$edID = $_POST['edID'];
	
	function grabEndUsers($learnerID){
		require DB_CONN;
		$endUserObj = new EndUserVO();
		$endUserObj = getUserNFO($learnerID);
		return $endUserObj;
	}
	
	$learnerQ = 'SELECT * FROM end_user_education WHERE eue_ed_link = ' . $_POST['edID'];
	$learnerQResult = $db->query($learnerQ);
	
	$learnerArry = array();
	while($row = $learnerQResult->fetch_object("EndUserEducationVO")){
		array_push($learnerArry,$row);
	}
	
	for($i = 0; $i<count($learnerArry);$i++){
		$learnerArry[$i]->endUserVO = grabEndUsers($learnerArry[$i]->eue_user_link);
	}
	
	echo json_encode($learnerArry);
	
		
}

?>