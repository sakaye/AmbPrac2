<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../utilities/safe_strings.php');
// =================== CHECK SESSION ID FOR USER ID ===========

// ============================================================
// sessions below were originally created in processors/educationCreator.php
$edID = $_SESSION['admin_EdID'];


require('../cbt_classes/QuestionObjVO.php');
require('../cbt_classes/AnsObjVO.php');
require('../cbt_classes/QAObjectVO.php');

if(isset($_POST['insertNewQ'])){
	$topicAreaLink = $_SESSION['admin_topicAreaLink'];
	$ansArry = array();
	$ansCorrectArry = array();
	$ansArry = explode(',',$_POST['ansArry']);
	$ansCorrectArry = explode(',',$_POST['answerCorrectArry']);
	$question = $_POST['question'];
	$question = makeSafe($question);
	$qExplanation = $_POST['qExplanation'];
	$qExplanation = makeSafe($qExplanation);


	$insertQData = 'INSERT INTO test_questions (tq_ed_link,tq_question,tq_pts_poss,tq_answer,tq_topic_area_link) ' .
					'VALUES (' . $edID. ',"' . $question . '",10,"' .$qExplanation . '",' . $topicAreaLink . ')';
					
	$insertQDataResult = $db->query($insertQData);
	
	$grabNewQuestionID = 'SELECT * FROM test_questions ORDER BY tq_id DESC LIMIT 1';
	$grabNewQuestionIDResult = $db->query($grabNewQuestionID);
	$tempQObj = $grabNewQuestionIDResult->fetch_object("QuestionObjVO");
	$qID = $tempQObj->tq_id;
	 // ================================== CREATE THE QAOBJECT TO SEND BACK FOR DISPLAY ===============
	$tempQAObjVO = new QAObjectVO();
	$tempQAObjVO->qObj = $tempQObj;
	// ================================================================================================
	for($i=0;$i<count($ansArry);$i++){
		$tempAnswer = makeSafe($ansArry[$i]);
		if($tempAnswer!=''){
			$tempCorrect = $ansCorrectArry[$i];
			$insertNewA = 'INSERT INTO test_answers (ta_q_link,ta_answer,ta_correct) VALUES (' . $qID . ',"' . $tempAnswer . '","' . $tempCorrect . '")';
			$insertNewAResult = $db->query($insertNewA);
		}
	}
}


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

$grabAllTQ = 'SELECT * FROM test_questions WHERE tq_ed_link = ' . $edID . ' ORDER BY tq_id DESC';
$grabAllTQResult = $db->query($grabAllTQ);

$tempQAObjArry = array();
while($row = $grabAllTQResult->fetch_object("QuestionObjVO")){
	$tempQAObj = new QAObjectVO();
	$tempQAObj->qObj = $row;
	$tempQAObj->ansObjArry = getRelatedAnswers($row->tq_id);
	array_push($tempQAObjArry,$tempQAObj);
}

echo json_encode($tempQAObjArry);
?>