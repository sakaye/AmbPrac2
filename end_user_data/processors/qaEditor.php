<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../utilities/safe_strings.php');
// =================== CHECK SESSION ID FOR USER ID ===========

// ============================================================
// sessions below were originally created in processors/educationCreator.php
//$edID = $_SESSION['admin_EdID'];

$qExp = makeSafe($_POST['qExpl']);

$qID = $_POST['qID'];
$editedQText = $_POST['editedQText'];
$ansTxtArry = array();
$rbAnsArry = array();
$aIDArry = array();

$ansTxtArry = explode(',',$_POST['ansTxtArry']);
$rbAnsArry = explode(',',$_POST['rbAnsArry']);
$aIDArry = explode(',',$_POST['aIDArry']);

$updateQ = 'UPDATE test_questions SET tq_question="' . makeSafe($editedQText) . '", tq_answer="' . makeSafe($qExp) . '" WHERE tq_id=' . $qID;
$updateQResult = $db->query($updateQ);


for($i=0; $i<count($aIDArry);$i++){
	$updateAQ = 'UPDATE test_answers SET ta_answer="' . makeSafe($ansTxtArry[$i]) . '", ta_correct="' . $rbAnsArry[$i] . '" WHERE ta_id=' . $aIDArry[$i];
	$udpateAQResult = $db->query($updateAQ);	
}

echo json_encode($qID);

?>