<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;

$edID = $_SESSION['edID'];
$userID = $_SESSION['end_user_id'];
$prePost = $_SESSION['pre_post'];
if($prePost=='pre'){
	$prePostForQ = ' eue_pre_score !=5000';
} else if($prePost=='post') {
	$prePostForQ = ' eue_post_score !=5000';
} else if($prePost=='sa') {
	$prePostForQ = ' eue_sa_score !=5000';
}
$edQ = 'SELECT * FROM end_user_education WHERE eue_user_link ="' . $userID . '" AND eue_ed_link = ' . $edID . ' AND' . $prePostForQ;
$edQResult = $db->query($edQ);
$theResult ='';
if($edQResult->num_rows>0){
	$row = $edQResult->fetch_assoc();
	$_SESSION['userEdID'] = $row['eue_id'];
	if($prePost == 'pre'){
		$userTestScore = $row['eue_pre_score'];
	} else if($prePost == 'post'){
		$userTestScore = $row['eue_post_score'];
	} else {
		$userTestScore = $row['eue_sa_score'];	
	}
	$theResult = 'true';	
} else {
	$theResult = 'false';
	$userTestScore = 5000;
}

$preTestCheck = 'okToProceed';
$preTestCheckQ = 'SELECT * FROM end_user_education WHERE eue_user_link="' . $userID . '" AND eue_ed_link = ' . $edID . ' LIMIT 1';
$preTestCheckQResult = $db->query($preTestCheckQ);
$rowPTCheck = $preTestCheckQResult->fetch_assoc();
if($prePost=="post"){
	if(($preTestCheckQResult->num_rows > 0) && ($rowPTCheck['eue_pre_score']==5000)){
		$preTestCheck = 'sendToPreTest';	
	} else if($preTestCheckQResult->num_rows==0) {
		$preTestCheck = 'sendToPreTest';
	}
} else {
	$preTestCheck = 'okForStandAlone';
}

$getEdTitleQ = 'SELECT * FROM education_table WHERE et_id = ' . $edID;
$getEdTitleQResult = $db->query($getEdTitleQ);
$row = $getEdTitleQResult->fetch_assoc();
$_SESSION['edTitle'] = $row['et_title'];
$tempArry = array();
$tempArry[0] = $theResult;
$tempArry[1] = $row['et_title'];
$tempArry[2] = $userTestScore;
$tempArry[3] = $prePost;
$tempArry[4] = $preTestCheck;
echo json_encode($tempArry);
?>