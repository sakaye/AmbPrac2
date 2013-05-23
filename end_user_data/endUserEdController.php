<?php session_start();
define('DB_CONN_MCM','dbConnect.php');
require DB_CONN_MCM;

$edID = $_SESSION['edID'];
$userID = $_SESSION['end_user_id'];
$prePost = $_SESSION['pre_post'];
/*
$edID=1;
$userID='b327190';
$prePost='post';
*/
function safeString($string){
	return str_replace("'","\'",$string);
}
if(isset($_POST['endUserScore'])){
	class EndUserFinalScore{
	
		public $userPreScore='';
		public $userPostScore='';
		public $userSAScore='';
		public $userPreTestDate='';
		public $userPostTestDate='';
		public $userSATestDate='';
		public $testType='';
		public function __construct(){
		
		}
	}

	$edQ = 'SELECT *, DATE_FORMAT(eue_pre_test_date, "%a, %b %d, %Y - %r") AS dateFrmPre, DATE_FORMAT(eue_post_test_date, "%a, %b %d, %Y - %r") AS dateFrmPost, DATE_FORMAT(eue_sa_test_date, "%a, %b %d, %Y - %r") AS dateFrmSA FROM end_user_education WHERE eue_user_link = "' . $userID . '" AND eue_ed_link = ' . $edID . ' ORDER BY eue_id DESC LIMIT 1';
	$edQResult = $db->query($edQ);
	$userScore = new EndUserFinalScore();
	$userScoreArry = array();
	while($row = $edQResult->fetch_assoc()){
		$userScore->userPreScore = $row['eue_pre_score'];
		$userScore->userPostScore = $row['eue_post_score'];
		$userScore->userSAScore = $row['eue_sa_score'];
		$userScore->userPreTestDate = $row['dateFrmPre'];
		$userScore->userPostTestDate = $row['dateFrmPost'];
		$userScore->userSATestDate = $row['dateFrmSA'];
		$userScore->testType = $prePost;
		array_push($userScoreArry,$userScore);
	}

	echo json_encode($userScoreArry);
}
// ==========================================================
// ============= grab scores if the request is for a pre_post test and not a stand-alone test
if($prePost != 'sa'){
	if(isset($_POST['endUserDetails'])){
		class EndUserTestDetails{
			public $qID='';
			public $aID='';
			public $answer='';
			public $question='';
			public $ansCorr='';
			public $prePost='';
			public $ansGroup='';
			public $score='';
		}
		
		$endUserQ = 'SELECT * FROM education_score_details, test_questions, test_answers 
		WHERE esd_user_id="' . $userID . '" AND esd_ed_id = ' . $edID . ' 
		AND education_score_details.esd_ans_id = test_answers.ta_id 
		AND education_score_details.esd_question_id = test_questions.tq_id
		AND education_score_details.esd_pre_post = "post" 
		ORDER BY test_questions.tq_id ASC';
		$endUserQResult = $db->query($endUserQ);
		$tempArry = array();
		while($row = $endUserQResult->fetch_assoc()){
			$tempTestDetailObj = new EndUserTestDetails();
			$tempTestDetailObj->aID = $row['ta_id'];
			$tempTestDetailObj->ansCorr = $row['ta_correct'];
			if($row['esd_ans_group']=='setB'){
				$tempTestDetailObj->answer = safeString($row['esd_setb_answers']);
			} else if($row['esd_ans_group']=='setC'){
				$tempTestDetailObj->answer = 'I do not know the answer';
			} else {
				$tempTestDetailObj->answer = safeString($row['ta_answer']);
			}
			$tempTestDetailObj->qID = $row['tq_id'];
			$tempTestDetailObj->prePost = $row['esd_pre_post'];
			$tempTestDetailObj->ansGroup = $row['esd_ans_group'];
			$tempTestDetailObj->question = safeString($row['tq_question']);
			$tempTestDetailObj->score = $row['esd_score'];
			array_push($tempArry,$tempTestDetailObj);
		}
		echo json_encode($tempArry);
	}
} else {
	if(isset($_POST['endUserDetails'])){
		class EndUserTestDetails{
			public $qID='';
			public $aID='';
			public $answer='';
			public $question='';
			public $ansCorr='';
			public $prePost='';
			public $ansGroup='';
			public $score='';
		}
		
		$endUserQ = 'SELECT * FROM education_score_details, test_questions, test_answers 
		WHERE esd_user_id="' . $userID . '" AND esd_ed_id = ' . $edID . ' 
		AND education_score_details.esd_ans_id = test_answers.ta_id 
		AND education_score_details.esd_question_id = test_questions.tq_id
		AND education_score_details.esd_pre_post = "sa" 
		ORDER BY test_questions.tq_id ASC';
		$endUserQResult = $db->query($endUserQ);
		$tempArry = array();
		while($row = $endUserQResult->fetch_assoc()){
			$tempTestDetailObj = new EndUserTestDetails();
			$tempTestDetailObj->aID = $row['ta_id'];
			$tempTestDetailObj->ansCorr = $row['ta_correct'];
			if($row['esd_ans_group']=='setB'){
				$tempTestDetailObj->answer = safeString($row['esd_setb_answers']);
			} else if($row['esd_ans_group']=='setC'){
				$tempTestDetailObj->answer = 'I do not know the answer';
			} else {
				$tempTestDetailObj->answer = safeString($row['ta_answer']);
			}
			$tempTestDetailObj->qID = $row['tq_id'];
			$tempTestDetailObj->prePost = $row['esd_pre_post'];
			$tempTestDetailObj->ansGroup = $row['esd_ans_group'];
			$tempTestDetailObj->question = safeString($row['tq_question']);
			$tempTestDetailObj->score = $row['esd_score'];
			array_push($tempArry,$tempTestDetailObj);
		}
		echo json_encode($tempArry);
	}
}
?>