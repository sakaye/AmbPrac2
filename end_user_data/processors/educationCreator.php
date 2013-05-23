<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;

// =================== CHECK SESSION ID FOR USER ID ===========
$adminID = $_SESSION['cbtAdminID'];
// ============================================================
require('../cbt_classes/EducationVO.php');
$tempEdID=0;

if(isset($_POST['addNewEd'])){
	
	$edTitle = $_POST['educationTitle'];
	$areaLink = $_POST['areaLink'];
	$testDescription = $_POST['testDescription'];
		
	$addEdQ = 'INSERT INTO education_table (et_title,et_topic_area_link,et_description,et_test_author_link) VALUES ("' . $edTitle . '",' . $areaLink . ',"' . $testDescription . '","' . $adminID . '")';
	$addEdQResult = $db->query($addEdQ);
	

}
if(isset($_SESSION['admin_EdID'])){
	$getNewEd = 'SELECT * FROM education_table WHERE et_id = '. $_SESSION['admin_EdID'];
} else {
	$getNewEd = 'SELECT * FROM education_table ORDER BY et_id DESC LIMIT 1';
}

$getNewEdResult = $db->query($getNewEd);
$row = $getNewEdResult->fetch_object("EducationVO");
$theTopicArea = $row->et_topic_area_link;
		
$getTopicArea = 'SELECT * FROM test_topic_areas WHERE tta_id = '. $theTopicArea . ' LIMIT 1';
$getTopicAreaResult = $db->query($getTopicArea);
$topicArea = $getTopicAreaResult->fetch_assoc();
	
$_SESSION['admin_EdID'] = $row->et_id;
$_SESSION['admin_topicAreaLink'] = $row->et_topic_area_link;
	
$row->topicArea = $topicArea['tta_topic'];

echo json_encode($row);

?>