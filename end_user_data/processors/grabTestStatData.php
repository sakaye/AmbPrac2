<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../cbt_classes/TestStatObj.php');

if(isset($_SESSION['cbtAdminID'])){
	
function getNumberOfQuestions($edID){
	require DB_CONN;
	$numQ = 'SELECT tq_id FROM test_questions WHERE tq_ed_link = ' . $edID;
	$numQResult = $db->query($numQ);
	$totalQs = $numQResult->num_rows;
	return $totalQs;
}
function getTopicArea($areaID){
	require DB_CONN;
	$areaQ = 'SELECT tta_topic FROM test_topic_areas WHERE tta_id = '. $areaID . ' LIMIT 1';
	$areaQResult = $db->query($areaQ);
	$row = $areaQResult->fetch_assoc();
	$areaName = $row['tta_topic'];
	return $areaName;
		
}
function getTestCompletionCount($edID){
	require DB_CONN;
	$tempArry = array();
	$countQ = 'SELECT cbtc_comp_count, DATE_FORMAT(cbtc_last_comp,"%a, %b, %D %Y, %r") AS dateFrm FROM cbt_completions WHERE cbtc_ed_link = ' . $edID . ' LIMIT 1';
	$countQResult = $db->query($countQ);
	if($countQResult->num_rows>0){
		$row = $countQResult->fetch_assoc();
		array_push($tempArry,$row['cbtc_comp_count']);
		array_push($tempArry,$row['dateFrm']);
	} else {
		$tempArry[0]=0;
		$tempArry[1]=0;
	}
	return $tempArry;
}
if(isset($_SESSION['cbtAdminID'])){
	
	$grabAllTestData = 'SELECT * FROM education_table WHERE et_test_author_link ="' . $_SESSION['cbtAdminID'] . '" ORDER BY et_title';
	$grabAllTestDataResult = $db->query($grabAllTestData);
	$tempTestArry = array();
	
	while($row = $grabAllTestDataResult->fetch_assoc()){
		$tempTestStatObj = new TestStatObj();
		$tempTestStatObj->testID = $row['et_id'];
		$tempTestStatObj->testAdmin = $row['et_test_author_link'];
		$tempTestStatObj->testDescription = $row['et_description'];
		$tempTestStatObj->testTitle = $row['et_title'];
		$tempTestStatObj->liveOnWeb = $row['et_live_on_web'];
		$tempTestStatObj->topicArea = getTopicArea($row['et_topic_area_link']);
		$tempTestStatObj->numQuestions = getNumberOfQuestions($row['et_id']);
		$tempCompArry = array();
		$tempCompArry = getTestCompletionCount($row['et_id']);
		$tempTestStatObj->testCompletions = $tempCompArry[0];
		$tempTestStatObj->lastCompletion = $tempCompArry[1];
		
		array_push($tempTestArry,$tempTestStatObj);
	}
	
	echo json_encode($tempTestArry);
	
}

}
?>