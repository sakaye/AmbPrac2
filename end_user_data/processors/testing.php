<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../utilities/safe_strings.php');
// =================== CHECK SESSION ID FOR USER ID ===========

// ============================================================
// sessions below were originally created in processors/educationCreator.php
$edID = 47;
$topicAreaLink = 48;

require('../cbt_classes/QuestionObjVO.php');
require('../cbt_classes/AnsObjVO.php');
require('../cbt_classes/QAObjectVO.php');


function getRelatedAnswers($theQID){
	require DB_CONN;
	$tempAnsArry = array();
	$grabAllRelatedAnswers = 'SELECT * FROM test_answers WHERE ta_q_link = ' . $theQID . ' ORDER BY ta_id';
	$grabAllRelatedAnswersResult = $db->query($grabAllRelatedAnswers);	
	while($row = $grabAllRelatedAnswersResult->fetch_object("AnsObjVO")){
		array_push($tempAnsArry,$row);	
	}
	return $tempAnsArry;
}

$grabAllTQ = 'SELECT * FROM test_questions WHERE tq_ed_link = ' . $edID . ' ORDER BY tq_id';
$grabAllTQResult = $db->query($grabAllTQ);

$tempQAObjArry = array();
while($row = $grabAllTQResult->fetch_object("QuestionObjVO")){
	$tempQAObj = new QAObjectVO();
	$tempQAObj->qObj = $row;
	$tempQAObj->ansObjArry = getRelatedAnswers($row->tq_id);
	array_push($tempQAObjArry,$tempQAObj);
}
?>