<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
require('QStatsVO.php');
$edID = $_SESSION['edID'];
$qIDArry = array();
$qIDArry = explode(',',$_POST['qIDArry']);
$prePost = $_SESSION['pre_post'];

$qStatsQ = 'SELECT * FROM q_score_stats WHERE qss_pre_post = "' . $prePost . '" AND qss_test_link = ' . $edID;
$qStatsQResult = $db->query($qStatsQ);
$tempArry = array();
while($row = $qStatsQResult->fetch_object("QStatsVO")){
	array_push($tempArry,$row);
}

echo json_encode($tempArry);
?>