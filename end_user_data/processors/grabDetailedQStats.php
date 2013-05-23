<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../cbt_classes/QScoreStatVO.php');
include('../cbt_classes/QuestionObjVO.php');
include('../cbt_classes/QScoreDataVO.php');
include('../cbt_classes/ScoresVO.php');

//===================== admin check? what if used on amprac site, make stats avail to all?? ==
//$_POST['edID'] = 13;
// ==================================================

function getScoreDetails($qID){
	global $qScoreArry;
	$tempArry = array();
	
	for($i=0; $i<count($qScoreArry);$i++){
		if($qScoreArry[$i]->qss_q_link == $qID){
			$tempScoresVO = new ScoresVO();
			$tempScoresVO->testType = $qScoreArry[$i]->qss_pre_post;
			$tempScoresVO->confCount = $qScoreArry[$i]->qss_conf_count;
			$tempScoresVO->doNotKnowCount = $qScoreArry[$i]->qss_do_not_know_count;
			$tempScoresVO->totalCorrect = $qScoreArry[$i]->qss_total_correct;
			$tempScoresVO->totalIncorrect = $qScoreArry[$i]->qss_total_incorrect;
			$tempScoresVO->uncertainCount = $qScoreArry[$i]->qss_uncertain_count;
			array_push($tempArry,$tempScoresVO);
		}
	}
	return $tempArry;
}

$quesQ = 'SELECT * FROM test_questions WHERE tq_ed_link = ' . $_POST['edID'] . ' ORDER BY tq_id';
$quesQResult = $db->query($quesQ);


$qScoreStatsQ = 'SELECT * FROM q_score_stats WHERE qss_test_link=' . $_POST['edID'];
$qScoreStatsQResult = $db->query($qScoreStatsQ);
$qScoreArry = array();
while($row = $qScoreStatsQResult->fetch_object("QScoreStatVO")){
	array_push($qScoreArry,$row);
}

$qDataArry = array();

while($row = $quesQResult->fetch_object("QScoreDataVO")){
	$row->scoresVOArry = getScoreDetails($row->tq_id);
	array_push($qDataArry,$row);
}

/*
$qScoreStatVOArry = array();
while($row = $qScoreStatsQResult->fetch_object("QScoreStatVO")){
	array_push($qScoreStatVOArry,$row);
}

for($i=0;$i<count($qScoreStatVOArry);$i++){
	$qScoreStatVOArry[$i]->questionTxt = getQTxt($qScoreStatVOArry[$i]->qss_q_link);
}

echo json_encode($qScoreStatVOArry);
*/
echo json_encode($qDataArry);
?>