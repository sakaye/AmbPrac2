<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
require('../cbt_classes/TopicAreaVO.php');

if(isset($_POST['getTopics'])){
	$getTopicAreasQ = 'SELECT * FROM test_topic_areas ORDER BY tta_topic';
	$getTopicAreasQResult = $db->query($getTopicAreasQ);
	$tempArry = array();
	
	while($row = $getTopicAreasQResult->fetch_object("TopicAreaVO")){
		array_push($tempArry,$row);
	}

	echo json_encode($tempArry);
}

if(isset($_POST['addTA'])){
	include('../utilities/safe_strings.php');
	$newTopic = $_POST['newTopic'];
	$newTopic = makeSafe($newTopic);
	
	$insertTopicQ = 'INSERT INTO test_topic_areas (tta_topic) VALUES ("' . $newTopic . '")';
	$insertTopicQResult = $db->query($insertTopicQ);
	
	$getLastAddedTopic = 'SELECT * FROM test_topic_areas ORDER BY tta_id DESC LIMIT 1';
	$getLastAddedTopicResult = $db->query($getLastAddedTopic);
	$row = $getLastAddedTopicResult->fetch_assoc();
	$theID = $row['tta_id'];
	
	echo json_encode($theID);
}
?>