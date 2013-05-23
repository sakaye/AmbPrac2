<?php session_start();
define('DB_CONN','dbConnect.php'); 
require DB_CONN;

$edID = $_SESSION['edID'];
$userID = $_SESSION['end_user_id'];
$prePost = $_SESSION['pre_post'];
$userEDID = $_SESSION['userEdID'];
$prePostScoreField ='';

if($prePost=='pre'){
	$delPreConfQ = 'DELETE FROM end_user_pretest_conf WHERE eupc_ed_link=' . $edID . ' AND eupc_end_user_link="' . $userID . '"';
	$delPreConfQResult = $db->query($delPreConfQ);
	$prePostScoreField = 'eue_pre_score';
} else {
	$prePostScoreField = 'eue_post_score';	
}
if($prePost=='sa'){
	$prePostScoreField = 'eue_sa_score';	
}
 
$delDetailsQ = 'DELETE FROM education_score_details WHERE esd_user_id="' . $userID . '" AND esd_ed_id=' . $edID . ' AND esd_pre_post="' . $prePost . '"';
$delDetailsQResult = $db->query($delDetailsQ);

// set pre OR post score back to default 5000 ------------
$resetDScoreQ = 'UPDATE end_user_education SET ' . $prePostScoreField . '=5000 WHERE eue_id=' . $userEDID;
$resetDScoreQResult = $db->query($resetDScoreQ);

$result="complete";
echo json_encode($result);
?>