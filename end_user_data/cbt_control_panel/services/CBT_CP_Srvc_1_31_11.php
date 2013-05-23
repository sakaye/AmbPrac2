<?php
	/* ================= UNCOMMENT FOR TESTING ===================
	define('CBT_CONN','../../../../Conn/conn_end_user_cbt.php');
	require('../classes/EducatorVO.php');
	require('../classes/EducationTableVO.php');
	require('../classes/TopicAreaVO.php');
	require('../classes/EducationSourceVO.php');
	*/
class CBT_CP_Srvc{

	
	public $educationSourceVO;
	public $topicAreaArry;
	public $numCompletionsArry;
	
	public function __construct(){
		$this->educationSourceVO = new EducationSourceVO();
		$this->topicAreaArry = array();
		$this->numCompletionsArry = array();
	}
	
	public function loadEducatorInfo($adminID){
		$this->grabTopicAreas();
		$this->grabNumCompletions();
		require CBT_CONN;
		
		$edAdminQ = 'SELECT * FROM end_users WHERE nuid = "' . $adminID . '" AND ' .
				'eu_access_level="tEditor" LIMIT 1';
		$edAdminQResult = $db->query($edAdminQ);
		$edVO = $edAdminQResult->fetch_object("EducatorVO");
		
		$this->educationSourceVO->edAuthor = $edVO;
		$this->educationSourceVO->edTableVOArry = $this->getAllEducation($edVO);
		
		return $this->educationSourceVO;
	}
	
	private function getAllEducation($edVO){
		require CBT_CONN;
		$educationTableQ ='SELECT * FROM education_table';
		$educationTableQResult = $db->query($educationTableQ);
		$tempArry = array();
		
		while($row = $educationTableQResult->fetch_object("EducationTableVO")){
			array_push($tempArry,$row);
		}
		$edObjArry = array();

		for($i=0;$i<count($tempArry);$i++){
			if($tempArry[$i]->et_test_author_link == $edVO->nuid){
				$tempArry[$i]->et_topic_area_string = $this->grabThisTopicArea($tempArry[$i]->et_topic_area_link);
				$tempCBTCompVO = new CBTCompletionsVO();
				$tempCBTCompVO = $this->getTestNumCompletions($tempArry[$i]->et_id);
				$tempArry[$i]->numCompletions = $tempCBTCompVO->cbtc_comp_count;
				$tempArry[$i]->lastCompletion = $tempCBTCompVO->lastCompFrm;
				$tempArry[$i]->numQs = $this->grabNumQuestions($tempArry[$i]->et_id);
				array_push($edObjArry,$tempArry[$i]);
			}
			
		}
		return $edObjArry;
	}
	private function getTestNumCompletions($edID){
		$theCount = new CBTCompletionsVO();
		for($i=0;$i<count($this->numCompletionsArry);$i++){
			if($this->numCompletionsArry[$i]->cbtc_ed_link == $edID){
				$theCount->cbtc_comp_count = $this->numCompletionsArry[$i]->cbtc_comp_count;
				$theCount->lastCompFrm = $this->numCompletionsArry[$i]->lastCompFrm;
			}
		}
		return $theCount;
	}
	private function grabTopicAreas(){
		require CBT_CONN;
		$taQ = 'SELECT * FROM test_topic_areas';
		$taQResult = $db->query($taQ);
		while($row = $taQResult->fetch_object("TopicAreaVO")){
			array_push($this->topicAreaArry,$row);
		}
		
	}
	public function grabThisTopicArea($taID){
		$tempTopicArea='';
		for($i=0;$i<count($this->topicAreaArry);$i++){
			if($taID == $this->topicAreaArry[$i]->tta_id){
				$tempTopicArea = $this->topicAreaArry[$i]->tta_topic;
			}
		}
		return $tempTopicArea;
	}
	public function grabNumCompletions(){
		require CBT_CONN;
		$numCompQ = 'SELECT *, DATE_FORMAT(cbtc_last_comp,"%a, %b, %D %Y, %r") as lastCompFrm FROM cbt_completions';
		$numCompQResult = $db->query($numCompQ);
		while($row = $numCompQResult->fetch_object("CBTCompletionsVO")){
			array_push($this->numCompletionsArry,$row);
		}
	}
	private function grabNumQuestions($edID){
		require CBT_CONN;
		$totalQs=0;
		$numQQ = 'SELECT tq_id FROM test_questions WHERE tq_ed_link = '. $edID;
		$numQQResult = $db->query($numQQ);
		$totalQs = $numQQResult->num_rows;
		return $totalQs;
	}
}
//$tempObj = new CBT_CP_Srvc();
//print_r($tempObj->loadEducatorInfo("b327190"));
?>