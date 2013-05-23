<?php
define('DB_CONN_MCM','dbConnect.php');

class AP_Education_Service{
	
	public function __construct(){
		
		
	}
	
/*
public function edLogin($un,$wp){
		require DB_CONN_MCM;
		$loginQ = 'SELECT * FROM lo_gin_mcm WHERE li_un = "' . $un . '" AND li_wordpass="' . $wp . '"';
		$loginQResult = $db->query($loginQ);
		$loginResponse = 'false';
		if($loginQResult->num_rows>0){
			setcookie("ed_user","allGood",time()+1800);
			$loginResponse = 'true';
		}
		return $loginResponse;
		

	}
*/
	public function getAnswers($qID){
		require DB_CONN_MCM;
		$ansQ = 'SELECT * FROM test_answers WHERE ta_q_link = ' . $qID . ' ORDER BY ta_id ASC';
		$ansQResult = $db->query($ansQ);
		$tempArry = array();
		while($row = $ansQResult->fetch_assoc()){
			array_push($tempArry,$row['ta_answer'] . '---' . $row['ta_correct'] . '---' . $row['ta_id'] . '---' . $row['ta_rank']);
		}
		return $tempArry;
	}
	public function getQAndAllAnswers($edID){
		require DB_CONN_MCM;
		require "QAObject.php";
		
		$tempArry = array();
		$questionQ = 'SELECT * FROM test_questions,qa_lookup WHERE qal_ed_link = ' . $edID . ' AND tq_id = qal_q_link ORDER BY qal_rank, tq_id ASC';
		$questionQResult = $db->query($questionQ);
		while($row = $questionQResult->fetch_assoc()){
			$qaObj = new QAObject();
			$qaObj->setQuestion($row['tq_question'],$row['tq_answer'],$row['tq_id'],$row['tq_pts_poss']);
			$ansArry = array();
			$ansArry = $this->getAnswers($row['tq_id']);
			for($i = 0; $i<count($ansArry); $i++){
				$ansObjs = array();
				$ansObjs = explode("---",$ansArry[$i]);
				$qaObj->addAns($ansObjs[0],$ansObjs[1],$ansObjs[2],$ansObjs[3]);
			}
			array_push($tempArry,$qaObj);
		}
		return $tempArry;
	}
	
}
/*
$tempArry = array();
$tempService = new AP_Education_Service();
$tempArry = $tempService->getQAndAllAnswers(1);
print_r($tempArry);
echo '<br />';
echo $tempArry[4]->question;
echo '<br />';
$ansArry = array();
$correctArry = array();
$ansArry = $tempArry[4]->ansArry;
$correctArry = $tempArry[4]->correctArry;

for($i=0; $i<count($ansArry);$i++){
	echo $ansArry[$i] . ' - ' . $correctArry[$i] . '<br />';
}
*/
?>
