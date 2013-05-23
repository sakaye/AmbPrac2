<?php session_start();

	/* ================= UNCOMMENT FOR TESTING ===================
	define('CBT_CONN','../../../../Conn/conn_end_user_cbt.php');
	require('../classes/EducatorVO.php');
	require('../classes/EducationTableVO.php');
	require('../classes/TopicAreaVO.php');
	require('../classes/EducationSourceVO.php');
	*/
class CBT_CP_Srvc{
	private $adminID;
	public $educationSourceVO;
	public $topicAreaArry;
	public $numCompletionsArry;
	public $questionTxtArry;
	public $qStatsArry;
	public $ansArry;
	
	public function __construct(){
		$this->adminID = $_SESSION['cbtAdminID'];
		$this->educationSourceVO = new EducationSourceVO();
		$this->topicAreaArry = array();
		$this->numCompletionsArry = array();
		$this->questionTxtArry = array();
		$this->qStatsArry = array();
		$this->ansArry = array();
	}
	public function retreiveAdmin(){
		
		$theResponse='fail';
		if(isset($_SESSION['cbtAdminID'])){
			$theResponse = $_SESSION['cbtAdminID'];
		} else {
			$theResponse='fail';
		}
		return $theResponse;
		
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
	public function addNewTest($et_title,$et_topic_area_link,$et_description,$nuid){
		require CBT_CONN;
		$newTestQ = 'INSERT INTO education_table (et_title,et_topic_area_link,et_description,et_test_author_link) ' .
				'VALUES ("'.$et_title.'",'.$et_topic_area_link.',"'.$et_description.'","'.$nuid.'")';
		$newTestQResult = $db->query($newTestQ);
		
		$this->grabTopicAreas();
		$newestTestQ = 'SELECT * FROM education_table WHERE et_test_author_link="'. $nuid . '" ORDER BY et_id DESC LIMIT 1';
		$newestTestQResult = $db->query($newestTestQ);
		
		$row = $newestTestQResult->fetch_object("EducationTableVO");
		
		for($i=0;$i<count($this->topicAreaArry); $i++){
			if($row->et_topic_area_link==$this->topicAreaArry[$i]->tta_id){
				$row->et_topic_area_string = $this->topicAreaArry[$i]->tta_topic;
			}
		}
		
		return $row;
	}
	public function grabTestForEdit($edID){
		require CBT_CONN;
		$this->grabTopicAreas();
		
		$newestTestQ = 'SELECT * FROM education_table WHERE et_id=' . $edID . ' LIMIT 1';
		$newestTestQResult = $db->query($newestTestQ);
		
		$row = $newestTestQResult->fetch_object("EducationTableVO");
		
		for($i=0;$i<count($this->topicAreaArry); $i++){
			if($row->et_topic_area_link==$this->topicAreaArry[$i]->tta_id){
				$row->et_topic_area_string = $this->topicAreaArry[$i]->tta_topic;
			}
		}
		return $row;
		
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
		$taQ = 'SELECT * FROM test_topic_areas ORDER BY tta_topic';
		$taQResult = $db->query($taQ);
		while($row = $taQResult->fetch_object("TopicAreaVO")){
			array_push($this->topicAreaArry,$row);
		}
	}
	public function grabTopicAreasForTestCreator(){
		$this->grabTopicAreas();
		$tempTAObj = new TopicAreaVO();
		$tempTAObj->tta_id=0;
		$tempTAObj->tta_topic='Add New Topic Area';
		array_push($this->topicAreaArry,$tempTAObj);
		return $this->topicAreaArry;
	}
	public function grabTopicAreasForLibrary(){
		$this->grabTopicAreas();
		$tempTAObj = new TopicAreaVO();
		$tempTAObj->tta_id=0;
		$tempTAObj->tta_topic='View All Questions';
		array_push($this->topicAreaArry,$tempTAObj);
		return $this->topicAreaArry;
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
	public function addNewTA($newTA){
		require CBT_CONN;
		$newTAQ = 'INSERT INTO test_topic_areas (tta_topic) VALUES ("' . $newTA . '")';
		$newTAQResult = $db->query($newTAQ);
		
		$newTAIDQ = 'SELECT tta_id FROM test_topic_areas ORDER BY tta_id DESC LIMIT 1';
		$newTAIDQResult = $db->query($newTAIDQ);
		$tempTAID = $newTAIDQResult->fetch_assoc();
		
		$this->grabTopicAreas();
		$tempTAObj = new TopicAreaVO();
		$tempTAObj->tta_id=0;
		$tempTAObj->tta_topic='Add New Topic Area';
		array_push($this->topicAreaArry,$tempTAObj);
		
		$tempArry = array();
		$tempArry[0] = $this->topicAreaArry;
		$tempArry[1] = $tempTAID['tta_id'];
		//return $this->topicAreaArry;
		return $tempArry;
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
		$numQQ = 'SELECT tq_id FROM test_questions,qa_lookup WHERE qal_ed_link = '. $edID .' AND ' .
				'qal_q_link = tq_id';
		$numQQResult = $db->query($numQQ);
		$totalQs = $numQQResult->num_rows;
		return $totalQs;
	}
	public function getQuestions($edID){
		require CBT_CONN;
		$this->getQStats($edID);
		$questionQ = 'SELECT * FROM test_questions,qa_lookup WHERE qal_ed_link = ' . $edID .
				' AND test_questions.tq_id = qal_q_link ORDER BY tq_id ASC';
		$questionQResult = $db->query($questionQ);
		while($row = $questionQResult->fetch_object("TestQuestionsVO")){
			array_push($this->questionTxtArry,$row);
		}
		
		for($i=0;$i<count($this->questionTxtArry);$i++){
			for($j=0; $j<count($this->qStatsArry);$j++){
				if($this->qStatsArry[$j]->qss_q_link == $this->questionTxtArry[$i]->tq_id){
					$tempStatObj = new QScoreStatsVO();
					$tempStatObj->qCount = count($this->questionTxtArry);
					$tempStatObj->question = $this->questionTxtArry[$i]->tq_question;
					$tempStatObj->qss_conf_count = $this->qStatsArry[$j]->qss_conf_count;
					$tempStatObj->qss_do_not_know_count = $this->qStatsArry[$j]->qss_do_not_know_count;
					$tempStatObj->qss_id = $this->qStatsArry[$j]->qss_id;
					$tempStatObj->qss_pre_post = $this->qStatsArry[$j]->qss_pre_post;
					$tempStatObj->qss_q_link = $this->qStatsArry[$j]->qss_q_link;
					$tempStatObj->qss_q_test= $this->qStatsArry[$j]->qss_test_link;
					$tempStatObj->qss_total_correct = $this->qStatsArry[$j]->qss_total_correct;
					$tempStatObj->qss_total_incorrect = $this->qStatsArry[$j]->qss_total_incorrect;
					$tempStatObj->qss_uncertain_count = $this->qStatsArry[$j]->qss_uncertain_count;
					array_push($this->questionTxtArry[$i]->qStatsArry,$tempStatObj);
				}
			}
		}
		return $this->questionTxtArry;
	}
	public function getQStats($edID){
		require CBT_CONN;
		$qStatsQ = 'SELECT * FROM q_score_stats WHERE qss_test_link = ' . $edID;
		$qStatsQResult = $db->query($qStatsQ);
		while($row = $qStatsQResult->fetch_object("QScoreStatsVO")){
			array_push($this->qStatsArry,$row);
		}
	}
	public function getAvgScores($edID){
		require CBT_CONN;
		$avgQ = 'SELECT * FROM end_user_education WHERE eue_ed_link = '. $edID;
		$avgQResult = $db->query($avgQ);
		$preAvgArry = array();
		$postAvgArry = array();
		$saAvgArry = array();
		while($row = $avgQResult->fetch_assoc()){
			if($row['eue_pre_score']!=5000){
				array_push($preAvgArry,$row['eue_pre_score']);
			}
			if($row['eue_post_score']!=5000){
				array_push($postAvgArry,$row['eue_post_score']);
			}
			if($row['eue_sa_score']!=5000){
				array_push($saAvgArry,$row['eue_sa_score']);
			}
		}
		$preAvg = $this->avgThis($preAvgArry);
		$postAvg = $this->avgThis($postAvgArry);
		$saAvg = $this->avgThis($saAvgArry);
		
		$tempArry = array();
		$tempArry[0] = round($saAvg);
		$tempArry[1] = round($preAvg);
		$tempArry[2] = round($postAvg);
		
		//$totalAvg = ($preAvg + $postAvg + $saAvg) / 3;
		//$totalAvg = round($totalAvg);
		//return $totalAvg;
		return $tempArry;
	}
	
	private function avgThis($theArry){
		$theAvg=0;
		if(count($theArry)!=0){
			for($i=0;$i<count($theArry);$i++){
				$theAvg+=$theArry[$i];
			}
			$theAvg = $theAvg/count($theArry);
		}
		return $theAvg;
	}
	public function getEdEndUsers($edID){
		require CBT_CONN;
		$learnerQ = 'SELECT * FROM end_users,end_user_education ' .
				'WHERE eue_ed_link = ' . $edID . ' ' .
						'AND end_user_education.eue_user_link = end_users.nuid';
		$learnerQResult = $db->query($learnerQ);
		$learnerArry = $this->grabLearnerFields($learnerQResult);
		
		return $learnerArry;
	}
	private function grabLearnerFields($learnerResult){
		require CBT_CONN;
		
		
		$tempAreaArry = array();
		$areaQ = 'SELECT * FROM end_user_area';
		$areaQResult = $db->query($areaQ);
		while($row = $areaQResult->fetch_assoc()){
			$tempAreaObj='';
			$tempAreaObj->areaID = $row['eua_id'];
			$tempAreaObj->areaTitle = $row['eua_title'];
			array_push($tempAreaArry,$tempAreaObj);
		}
		
		$tempTitleArry = array();
		$titleQ = 'SELECT * FROM end_user_titles';
		$titleQResult = $db->query($titleQ);
		while($row = $titleQResult->fetch_assoc()){
			$tempTitleObj='';
			$tempTitleObj->titleID = $row['eut_id'];
			$tempTitleObj->title = $row['eut_title'];
			array_push($tempTitleArry,$tempTitleObj);
		}
		
		$tempMOBArry = array();
		$mobQ = 'SELECT * FROM end_user_mob';
		$mobQResult = $db->query($mobQ);
		while($row = $mobQResult->fetch_assoc()){
			$tempMOBObj='';
			$tempMOBObj->mobID = $row['eum_id'];
			$tempMOBObj->mobTitle = $row['eum_title'];
			$tempMOBObj->areaLink = $row['eum_area_link'];
			array_push($tempMOBArry,$tempMOBObj);
		}
		
		$tempDeptArry = array();
		$deptQ = 'SELECT * FROM end_user_depts';
		$deptQResult = $db->query($deptQ);
		while($row = $deptQResult->fetch_assoc()){
			$tempDeptObj='';
			$tempDeptObj->deptID = $row['eud_id'];
			$tempDeptObj->deptTitle = $row['eud_title'];
			array_push($tempDeptArry,$tempDeptObj);
		}
		
		$learnerArry = array();
		
		while($row = $learnerResult->fetch_assoc()){
			$tempLearnerVO = new LearnerVO();
			$tempLearnerVO->nuid = $row['nuid'];
			$tempLearnerVO->fName = $row['f_name'];
			$tempLearnerVO->lName = $row['l_name'];
			$tempLearnerVO->mI = $row['m_init'];
			$tempLearnerVO->kpEmp = $row['eu_kp_emp'];
			$tempLearnerVO->edID = $row['eue_ed_link'];
			if($row['eue_pre_score']==5000){
				$tempLearnerVO->preScore = 'not taken';
			} else {
				$tempLearnerVO->preScore = $row['eue_pre_score'];
			}
			if($row['eue_post_score']==5000){
				$tempLearnerVO->postScore = 'not taken';
			} else {
				$tempLearnerVO->postScore = $row['eue_post_score'];
			}
			if($row['eue_sa_score']==5000){
				$tempLearnerVO->saScore = 'not taken';
			} else {
				$tempLearnerVO->saScore = $row['eue_sa_score'];
			}
			
			
			
			for($i=0;$i<count($tempAreaArry);$i++){
				if($row['area_link']==$tempAreaArry[$i]->areaID){
					$tempLearnerVO->area = $tempAreaArry[$i]->areaTitle;
				}
			}
			
			for($j=0;$j<count($tempTitleArry);$j++){
				if($row['title_link']==1000){
					$titleOtherQ = 'SELECT ot_title FROM other_titles WHERE ot_user_link="' . $row['nuid'] . '"';
					$titleOtherQResult = $db->query($titleOtherQ);
					$tempOtherTitle = $titleOtherQResult->fetch_assoc();
					$tempLearnerVO->title = $tempOtherTitle;
				} else {
					if($row['title_link']==$tempTitleArry[$j]->titleID){
						$tempLearnerVO->title = $tempTitleArry[$j]->title;
					}
				}
			}
			
			if($row['eu_kp_emp']!='false'){
				for($k=0;$k<count($tempMOBArry);$k++){
					if($row['mob_link']==$tempMOBArry[$k]->mobID){
						$tempLearnerVO->MOB = $tempMOBArry[$k]->mobTitle;
					}
				}
			} else {
				$tempLearnerVO->MOB = 'non-kp';
			}
			
			for($l=0;$l<count($tempDeptArry);$l++){
				if($row['dept_link']==2000){
					$otherDeptQ = 'SELECT euod_title FROM end_user_other_depts WHERE euod_user_id="' . $row['nuid'] .'"';
					$otherDeptQResult = $db->query($otherDeptQ);
					$tempOtherDept = $otherDeptQResult->fetch_assoc();
					$tempLearnerVO->dept = $tempOtherDept['euod_title'];	
				} else {
					if($row['dept_link']==$tempDeptArry[$l]->deptID){
						$tempLearnerVO->dept = $tempDeptArry[$l]->deptTitle;
					}
				}
			}
			
			array_push($learnerArry,$tempLearnerVO);
		}
		
		return $learnerArry;
	}
	public function deleteCBTTest($edID){
		require CBT_CONN;
		$delTestQ = 'DELETE FROM education_table WHERE et_id =' . $edID . ' LIMIT 1';
		$delTestQResult = $db->query($delTestQ);
		
	}
	public function detachCBTTest($edID,$nuid){
		require CBT_CONN;
		$updateTestQ = 'UPDATE education_table SET et_test_author_link=0 WHERE et_id='.$edID.' AND et_test_author_link="'.$nuid.'" LIMIT 1';
		$updateTestQResult = $db->query($updateTestQ);
		return 'complete';
	}
	public function editCBTest($testID,$testTitle,$testDescription,$topicAreaID){
		require CBT_CONN;
		$editQ = 'UPDATE education_table SET et_title="' . $testTitle . '",et_description="' . $testDescription . '",' .
				'et_topic_area_link='. $topicAreaID .' WHERE et_id ='. $testID . ' LIMIT 1';
		$editQResult = $db->query($editQ);
		
		$tempEdObj = new EducationTableVO();
		$tempEdObj = $this->grabThisTestsInfo($testID,$topicAreaID);
		
		return $tempEdObj;
	}
	public function grabThisTestsInfo($edID,$areaID){
		require CBT_CONN;
		$this->grabTopicAreas();
		$edQ = 'SELECT * FROM education_table WHERE et_id = '. $edID . ' LIMIT 1';
		$edQResult = $db->query($edQ);
		$row = $edQResult->fetch_object("EducationTableVO");
		$row->et_topic_area_string = $this->grabThisTopicArea($areaID);
		return $row;
	}
	public function addQuestion($edID,$topicID,$question,$ansExp){
		require CBT_CONN;
		$questionQ = 'INSERT INTO test_questions (tq_ed_link,tq_answer,tq_topic_area_link,tq_question,tq_pts_poss,tq_og_author)' .
				' VALUES ('. $edID . ',"' . $ansExp . '",'. $topicID .',"'. $question .'",10,"'.$this->adminID.'")';
		$questionQResult = $db->query($questionQ);
		
		$lastQuesQ = 'SELECT * FROM test_questions ORDER BY tq_id DESC LIMIT 1';
		$lastQResult = $db->query($lastQuesQ);
		$row = $lastQResult->fetch_object("TestQuestionVO");
		return $row;
		
	}
	private function insertIntoQALookup($qID,$edID){
		require CBT_CONN;
		$lookupQ = 'INSERT INTO qa_lookup (qal_ed_link,qal_q_link) VALUES ('.$edID.','.$qID.')';
		$lookupQResult = $db->query($lookupQ);
		return 'complete';
	}
	public function addNewTestQuestion($ansObjs){
		require CBT_CONN;
		$ansObjArry = $ansObjs;
		
		$questionObj = $this->addQuestion($ansObjArry['testID'],$ansObjArry['topicAreaLink'],$ansObjArry['question'],$ansObjArry['ansExp']);
		
		$qID = $questionObj->tq_id;
		
		$this->insertIntoQALookup($qID,$ansObjArry['testID']);
		
		$j=0;
		for($i=0;$i<count($ansObjs['ansArry']);$i++){
			$insertAQ = 'INSERT INTO test_answers (ta_q_link,ta_answer,ta_correct) ' .
				'VALUES ('. $qID .',"'. $ansObjs['ansArry'][$j]->answer .'","' . $ansObjs['ansArry'][$j]->correct . '")';
			$insertAResult = $db->query($insertAQ);
			
			$j+=1;
		}
		
		$tempArry = array();
		$tempArry = $this->getAllTestQAndAs($ansObjArry['testID']);
		return $tempArry;
	}
	public function loadAllQAndAs($edID){
		$tempArry = array();
		$tempArry = $this->getAllTestQAndAs($edID);
		return $tempArry;
	}
	private function getAllAnswers(){
		require CBT_CONN;
		$ansQ = 'SELECT * FROM test_answers ORDER BY ta_id';
		$ansQResult = $db->query($ansQ);
		while($row = $ansQResult->fetch_object("AnsObjVO")){
			array_push($this->ansArry,$row);
		}
		
	}
	private function getTheseAnswers($qID){
		$tempArry = array();
		for($i=0; $i<count($this->ansArry); $i++){
			if($this->ansArry[$i]->ta_q_link == $qID){
				array_push($tempArry,$this->ansArry[$i]);
			}
		}
		return $tempArry;
	}
	public function getAllTestQAndAs($edID){
		require CBT_CONN;
		$this->getAllAnswers();
		if($edID!=0){
			$quesQ = 'SELECT * FROM test_questions,qa_lookup WHERE qal_ed_link = ' . $edID . ' AND qal_q_link=test_questions.tq_id ORDER BY tq_id';
		} else {
			$quesQ = 'SELECT * FROM test_questions ORDER BY tq_topic_area_link';
		}
		$quesQResult = $db->query($quesQ);
		$tempQAObjArry = array();
		while($row = $quesQResult->fetch_object("TestQuestionVO")){
			$tempQAObjVO = new QAObjVO();
			$tempQAObjVO->testQuestionVO = $row;
			$tempQAObjVO->ansObjArry = $this->getTheseAnswers($row->tq_id);
			array_push($tempQAObjArry,$tempQAObjVO);
		}
		return $tempQAObjArry;
	}
	public function editQAObj($qaObj,$task,$edID){
		require CBT_CONN;
		$ansObjArry = array();
		$ansObjArry = $qaObj->ansArry;
		
		if($task=='edit'){
		$qEditQ = 'UPDATE test_questions SET tq_question="'. $qaObj->qData->question . '", tq_answer="'.$qaObj->qData->ansExp.'" ' .
				'WHERE tq_id=' . $qaObj->qData->qID . ' LIMIT 1';
		$qEditQResult = $db->query($qEditQ);
		
		for($i=0;$i<count($ansObjArry);$i++){
			$ansUpdateQ = 'UPDATE test_answers SET ta_answer="' . $ansObjArry[$i]->answer . '", ta_correct="'. $ansObjArry[$i]->correct .'" ' .
					'WHERE ta_id='. $ansObjArry[$i]->aID . ' LIMIT 1';
			$ansUpdateQResult = $db->query($ansUpdateQ);
		}
		} else {
			$removeQQ = 'UPDATE qa_lookup SET qal_ed_link=0 WHERE qal_ed_link='.$edID.' AND qal_q_link='. $qaObj->qData->qID . ' LIMIT 1';
			$removeQQResult = $db->query($removeQQ);
			
			/*
			$delQ = 'DELETE FROM test_questions WHERE tq_id=' . $qaObj->qData->qID . ' LIMIT 1';
			$delQResult = $db->query($delQ);
			
			for($i=0;$i<count($ansObjArry);$i++){
			$ansUpdateQ = 'DELETE FROM test_answers WHERE ta_id='. $ansObjArry[$i]->aID . ' LIMIT 1';
			$ansUpdateQResult = $db->query($ansUpdateQ);
			}
			*/
		
		}
		
		$tempQAObjArry = array();
		$tempQAObjArry = $this->getAllTestQAndAs($qaObj->edID);
		return $tempQAObjArry;
	}
	public function setTestOffline($edID){
		require CBT_CONN;
		$testQ = 'UPDATE education_table SET et_live_on_web="false" WHERE et_id=' . $edID;
		$testQResult = $db->query($testQ);
		return 'complete';
	}
	public function setTestOnline($edID){
		require CBT_CONN;
		$testQ = 'UPDATE education_table SET et_live_on_web="true" WHERE et_id=' . $edID;
		$testQResult = $db->query($testQ);
		return 'complete';
	}
	public function addTestInstructions($edInst){
		require CBT_CONN;
		$tempInsArry = $this->grabTestInstructions($edInst->edID);
		if(count($tempInsArry)==0){
		$addInsQ = 'INSERT INTO test_instructions (ti_pre_instructions,ti_post_instructions,ti_sa_instructions,ti_test_link) ' .
				'VALUES ("'.$edInst->preInst.'","'.$edInst->postInst.'","'.$edInst->saInst.'",'.$edInst->edID.')';
		$addInsQResult = $db->query($addInsQ);
		} else {
			
			$updateInstQ = 'UPDATE test_instructions SET ti_pre_instructions="'.$edInst->preInst.'", ' .
					'ti_post_instructions="'.$edInst->postInst.'", ' .
							'ti_sa_instructions="'.$edInst->saInst.'" WHERE ti_id='.$tempInsArry[0]->ti_id;
			$updateInstQResult = $db->query($updateInstQ);
			
		}
		
		$tempArry = array();
		$tempArry = $this->grabTestInstructions($edInst->edID);
		
		return $tempArry;
	}
	public function grabTestInstructions($edID){
		require CBT_CONN;
		$tInstQ = 'SELECT * FROM test_instructions WHERE ti_test_link='. $edID . ' LIMIT 1';
		$tInstQResult = $db->query($tInstQ);
		$tempArry = array();
		while($row = $tInstQResult->fetch_object("TestInstructionsVO")){
			array_push($tempArry,$row);
		}
		return $tempArry;
	}
	public function grabAdminMessages(){
		require CBT_CONN;
		$messagesQ = 'SELECT *, DATE_FORMAT(cam_post_date,"%a, %b %D %r") as camFrmDate FROM cbt_admin_messages ORDER BY cam_id ASC';
		$messagesQResult = $db->query($messagesQ);
		$tempArry = array();
		while($row = $messagesQResult->fetch_object("AdminMessagesVO")){
			array_push($tempArry,$row);
		}
		return $tempArry;
		
	}
	public function addToMessageBoard($theMessageObj){
		require CBT_CONN;
		$messageQ = 'INSERT INTO cbt_admin_messages (cam_user_name,cam_message,cam_user_link) ' .
				'VALUES ("'.$theMessageObj->userName.'","'.$theMessageObj->message.'","'.$theMessageObj->nuid.'")';
		$theMessageQResult = $db->query($messageQ);
		
		$tempArry = $this->grabAdminMessages();
		return $tempArry;

	}
	public function addQandAToTest($qArry){
		require CBT_CONN;
		for($i=0; $i<count($qArry);$i++){
			if($qArry[$i]->addToTest=='true'){
				$qID = $qArry[$i]->qID;
				$edID = $qArry[$i]->edID;
				$insertLookupQ = 'INSERT INTO qa_lookup (qal_ed_link,qal_q_link) VALUES ('.$edID.','.$qID.')';
				$insertLookupQResult = $db->query($insertLookupQ);
			}
		}
		return 'complete';
	}
}
//$tempObj = new CBT_CP_Srvc();
//print_r($tempObj->loadEducatorInfo("b327190"));
?>