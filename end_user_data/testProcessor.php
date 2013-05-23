<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;

$_SESSION['inTest'] = 'true';
$edID = $_SESSION['edID'];
$userID = $_SESSION['end_user_id'];
$prePost = $_SESSION['pre_post'];

$ansIDArry = array();
$ansIDArry = explode(',',$_POST['ansID']);
$ptsPossArry = array();
$ptsPossArry = explode(',',$_POST['ptsPoss']);
$qIDArry = array();
$qIDArry = explode(',',$_POST['qID']);
$aIDArry = array();
$aIDArry = explode(',',$_POST['ansID']);
$ansGroupArry = array();
$ansGroupArry = explode(',',$_POST['ansGroup']);
$corrArry = array();
$corrArry = explode(',',$_POST['ansCorrect']);//true or ''
$setBAnswersArry = array();
$setBAnswersArry = explode(',',$_POST['setBAns']);

$totalScore = 0;
$totalPtsPoss = 0;
$percentScore = 0;
$preTestConf = 0;

function sendTestCompletion($edID){
	require DB_CONN;
	$compCheckQ = 'SELECT * FROM cbt_completions WHERE cbtc_ed_link = ' . $edID . ' LIMIT 1';
	$compCheckQResult = $db->query($compCheckQ);
	if($compCheckQResult->num_rows>0){
		$row = $compCheckQResult->fetch_assoc();
		$tempCount = $row['cbtc_comp_count'];
		$tempCount = (int)$tempCount;
		$tempCount +=1;
		$cbtcID = $row['cbtc_id'];
		$cbtcID = (int)$cbtcID;
		$addToCountQ = 'UPDATE cbt_completions SET cbtc_comp_count=' . $tempCount . ' WHERE cbtc_id = ' . $cbtcID;
	} else {
		$addToCountQ = 'INSERT INTO cbt_completions (cbtc_ed_link,cbtc_comp_count) VALUES (' . $edID . ',1)';
	}
	$addToCountQResult = $db->query($addToCountQ);
}
function checkForExistingRecord(){
	require DB_CONN;
	global $userID;
	global $edID;
	$recordCheck = 'SELECT * FROM end_user_education WHERE eue_user_link = "' . $userID . '" AND eue_ed_link = ' . $edID . ' LIMIT 1';
	$recordCheckResult = $db->query($recordCheck);
	if($recordCheckResult->num_rows>0){
		$row = $recordCheckResult->fetch_assoc();
		$tempID = $row['eue_id'];
	} else {
		$tempID= 0;
	}
	return $tempID;
}
$doNotKnowCount=0;

$revisedScoreTotals=0;
$revisedTotalScoresTotal=0;
$totalPtsPossNew=0;

for($i = 0; $i<count($ansIDArry);$i++){
	$currQScore = 0;
	$revisedScoreTotals = 0;
	$totalPtsPossNew+=$ptsPossArry[$i];
	if($ansGroupArry[$i]=='setA'){
		if($corrArry[$i]=='true'){
			$totalScore += $ptsPossArry[$i];
			$currQScore = $ptsPossArry[$i];
			$revisedScoreTotals = 10;
		} else {
			$totalScore -= $ptsPossArry[$i];
			$currQScore = $ptsPossArry[$i]*-0.5; // ========= NEED TO FIGURE CORRECT PTS BASED ON MISSED QUESTION ==== OG (-1)
			$revisedScoreTotals = 3;
		}
	}
	if($ansGroupArry[$i]=='setB'){
		if($corrArry[$i]=='true'){
			$totalScore += $ptsPossArry[$i]/1.25;
			$currQScore = $ptsPossArry[$i]/1.25;
			$revisedScoreTotals = 5;
		} else {
			$totalScore -= $ptsPossArry[$i]/1.25;
			$currQScore = ($ptsPossArry[$i]/1.25)*-0.3125;
			$revisedScoreTotals = 0;
		}
	}
	if($ansGroupArry[$i]=='setC'){
		$doNotKnowCount+=1;
		if($corrArry[$i]=='true'){
			$totalScore += 0;
			$currQScore = 0;
			$revisedScoreTotals = 0;
		} else {
			$totalScore -= 0;
			$currQScore = 0;
			$revisedScoreTotals = 0;
		}
	}
	$totalPtsPoss += $ptsPossArry[$i];
	$doNotKnowCountTotal = ($doNotKnowCount*9);
	//$totalPtsPoss = $totalPtsPoss-$doNotKnow_CountTotal;
	// ORIGINAL WAS JUST $TOTAL PTS POSS, SCORING WAS TOO HARSH, NOW TRYING totalPtsPoss X 2
	//$totalPtsPoss = $totalPtsPoss*2;
	$revisedTotalScoresTotal = $totalPtsPoss;
	$revisedScoreTotalsFinal += $revisedScoreTotals;
	//==============================================================
	$percentScore = ($totalScore/$totalPtsPoss)*100;
	
	// ================ education_score_details query =======
	$esdQ = 'INSERT INTO education_score_details (esd_user_id, esd_ed_id, esd_question_id, esd_ans_id, esd_score, esd_ans_group, esd_pre_post, esd_setb_answers) 
	VALUES ("' . $userID . '",' . $edID . ',' . $qIDArry[$i] . ',' . $aIDArry[$i] . ',' . $currQScore . ',"' . $ansGroupArry[$i] . '","' . $prePost . '","' . $setBAnswersArry[$i] . '")';
	$esdQResult = $db->query($esdQ);
	// =====================================================
	if($prePost=='pre'){
		if($ansGroupArry[$i]=='setA'){
			$preTestConf+=1;
		}
		if($ansGroupArry[$i]=='setB'){
			$preTestConf+=.5;
		}
		if($ansGroupArry[$i]=='setC'){
			$preTestConf+=0;
		}
	}	
}
// ================ [ REVISED SCORE CALCULATION ] =======================
//$revisedTotalScoresTotal  = $revisedTotalScoresTotal*2;
$revisedTotalScoresTotal = $totalPtsPossNew-$doNotKnowCountTotal;
$niceScore = ($revisedScoreTotalsFinal/$revisedTotalScoresTotal)*100;
// WAS USING '$percentScore', but was too harsh
$_SESSION['totalScore'] = $totalPtsPossNew-$doNotKnowCountTotal;
// ======================================================================

// add data for pre test confidence ==========
if($prePost=='pre'){
	$preConfidenceScore = ($preTestConf/count($ansIDArry))*100;
	$preConfQ = 'INSERT INTO end_user_pretest_conf (eupc_ed_link, eupc_end_user_link, eupc_conf_score) VALUES (' . $edID . ',"' . $userID . '",' . $preConfidenceScore . ')';
	$preConfQResult = $db->query($preConfQ);
}

//===============[ total score query ] =======================
$endUserPtsQ = 'SELECT eu_total_pts FROM end_users WHERE nuid="' . $userID . '" LIMIT 1';
$endUserPtsQResult = $db->query($endUserPtsQ);

$totalScoresQ = 'SELECT * FROM cbt_total_scores WHERE end_user_link="' . $userID . '" AND cts_test_id='.$edID.' LIMIT 1';
$totalScoreQResult = $db->query($totalScoresQ);
$tempFieldString ='';
if($prePost=='pre'){
	$tempFieldString = 'cts_pre_test_total';
} else if($prePost=='post'){
	$tempFieldString = 'cts_post_test_total';
} else {
	$tempFieldString = 'cts_sa_total';
}
$tempQueryCount = $totalScoreQResult->num_rows;

if($tempQueryCount!=0){
	$learner = $totalScoreQResult->fetch_assoc();
}
if(($tempQueryCount>0)&&($learner['cts_test_id']==$edID)){
	$tempScore = $learner[$tempFieldString];
	$tempScore = $tempScore + $niceScore; // was $percentScore
	$tempTotalScore = $learner['cts_total'] + $niceScore; // was $percentScore
	$newTestCount = $learner['cts_test_count']+1;
	$newTotal = round($tempTotalScore/$newTestCount);
	$updateScoreQ = 'UPDATE cbt_total_scores SET '.$tempFieldString.'='.$tempScore.', cts_total='.$tempTotalScore.',cts_test_count='.$newTestCount.',cts_new_total='.$newTotal.',cts_test_id='.$edID.' WHERE cts_id='. $learner['cts_id'] . ' LIMIT 1';	
	$updateScoreQResult = $db->query($updateScoreQ);
	
} else { // was using $percentScore, now it is $niceScore
	$insertScoreQ = 'INSERT INTO cbt_total_scores ('.$tempFieldString.',cts_total,cts_test_count,end_user_link,cts_new_total,cts_test_id) VALUES ('.$niceScore.','.$niceScore.','. 1 .',"'.$userID.'",'.$niceScore.','.$edID.')';
	$insertScoreQResult = $db->query($insertScoreQ);
}

$cbtTotalScoresQ = 'SELECT cts_new_total FROM cbt_total_scores WHERE end_user_link="'.$userID.'"';
$cbtTotalScoresQResult = $db->query($cbtTotalScoresQ);
$newPtsForUser=0;
while($row = $cbtTotalScoresQResult->fetch_assoc()){
	$newPtsForUser += $row['cts_new_total'];	
}

$updateUserPtsQ = 'UPDATE end_users SET eu_total_pts='.$newPtsForUser.' WHERE nuid="' . $userID . '"';
$updateUserPtsQResult = $db->query($updateUserPtsQ);

// ===========================================================

// ================ end_user_education query ================
if($niceScore == 100){ // was $percentScore
	$bypass = 'true';
} else {
	$bypass = 'false';
}
if($prePost == 'pre'){
	sendTestCompletion($edID);
	$tempEUEID = checkForExistingRecord();
	if($tempEUEID !=0){
		$eueQ = 'UPDATE end_user_education SET eue_pre_score=' . $niceScore . ', eue_pre_test_date=NOW(), bypass="' . $bypass . '" WHERE eue_id =' . $tempEUEID;
	} else {
		$eueQ = 'INSERT INTO end_user_education (eue_user_link, eue_ed_link, eue_pre_score, eue_pre_test_date, bypass)
	 	VALUES ("' . $userID . '",' . $edID . ',' . $niceScore . ', NOW(),"' . $bypass . '")';
	}
	 $eueQResult = $db->query($eueQ);	
	 
} else if($prePost=='post'){
	sendTestCompletion($edID);
	$tempEUEID = checkForExistingRecord();
	$eueUpdateQ = 'UPDATE end_user_education SET eue_post_score= ' . $niceScore . ', eue_post_test_date=NOW(), eue_complete_date=NOW() WHERE eue_id = ' . $tempEUEID . ' LIMIT 1';
	$eueQUpdateResult = $db->query($eueUpdateQ);
	
} else if($prePost=='sa'){
	sendTestCompletion($edID);
	$tempEUEID = checkForExistingRecord();
	if($tempEUEID !=0){
		$eueUpdateQ = 'UPDATE end_user_education SET eue_sa_score= ' . $niceScore . ', eue_sa_test_date=NOW(), eue_complete_date=NOW() WHERE eue_id = ' . $tempEUEID . ' LIMIT 1';
		$eueQUpdateResult = $db->query($eueUpdateQ);
	} else {
		$eueUpdateQ = $eueQ = 'INSERT INTO end_user_education (eue_user_link, eue_ed_link, eue_sa_score, eue_sa_test_date, bypass)'.
		' VALUES ("' . $userID . '",' . $edID . ',' . $niceScore . ', NOW(),"' . $bypass . '")';
		$eueQUpdateResult = $db->query($eueUpdateQ);
	}
}

for($i = 0; $i<count($ansIDArry);$i++){
		$tempQID = $qIDArry[$i];
		$q1='false';
		$q2='false';
		$addStat='true';
		if($prePost=='pre'){
			$tQ = 'SELECT * FROM q_score_stats WHERE '. $tempQID .' = qss_q_link AND qss_pre_post="pre"';
			$tQResult = $db->query($tQ);
			if($tQResult->num_rows>0){
				$q1='true';
				$addStat='false';
				$row = $tQResult->fetch_assoc();
				if($ansGroupArry[$i]=='setA'){
					$tempConfCount = $row['qss_conf_count']+1;
					$tmpAnsGroup = 'qss_conf_count='.$tempConfCount;
				}
				if($ansGroupArry[$i]=='setB'){
					$tempConfCount = $row['qss_uncertain_count']+1;
					$tmpAnsGroup = 'qss_uncertain_count='.$tempConfCount;
				}
				if($ansGroupArry[$i]=='setC'){
					$tempConfCount = $row['qss_do_not_know_count']+1;
					$tmpAnsGroup = 'qss_do_not_know_count='.$tempConfCount;
				}
				if($corrArry[$i]=='true'){
					$tempScoreCount = $row['qss_total_correct']+1;
					$tempScore = 'qss_total_correct='.$tempScoreCount;
				} else {
					$tempScoreCount = $row['qss_total_incorrect']+1;
					$tempScore = 'qss_total_incorrect='.$tempScoreCount;
				}
				$updatePreQ = 'UPDATE q_score_stats SET ' . $tmpAnsGroup . ',' . $tempScore . ' WHERE qss_id = '. $row['qss_id'];
				$updatePreQResult = $db->query($updatePreQ);
			}
		}
		// ================ POST TEST DATA
		if($prePost=='post'){
			$tPostQ = 'SELECT * FROM q_score_stats WHERE '. $tempQID .' = qss_q_link AND qss_pre_post="post"';
			$tPostQResult = $db->query($tPostQ);
			if($tPostQResult->num_rows>0){
				$q2='true';
				$addStat='false';
				$row = $tPostQResult->fetch_assoc();
				if($ansGroupArry[$i]=='setA'){
					$tempConfCount = $row['qss_conf_count']+1;
					$tmpAnsGroup = 'qss_conf_count='.$tempConfCount;
				}
				if($ansGroupArry[$i]=='setB'){
					$tempConfCount = $row['qss_uncertain_count']+1;
					$tmpAnsGroup = 'qss_uncertain_count='.$tempConfCount;
				}
				if($ansGroupArry[$i]=='setC'){
					$tempConfCount = $row['qss_do_not_know_count']+1;
					$tmpAnsGroup = 'qss_do_not_know_count='.$tempConfCount;
				}
				if($corrArry[$i]=='true'){
					$tempScoreCount = $row['qss_total_correct']+1;
					$tempScore = 'qss_total_correct='.$tempScoreCount;
				} else {
					$tempScoreCount = $row['qss_total_incorrect']+1;
					$tempScore = 'qss_total_incorrect='.$tempScoreCount;
				}
				$updatePreQ = 'UPDATE q_score_stats SET ' . $tmpAnsGroup . ',' . $tempScore . ' WHERE qss_id = '. $row['qss_id'];
				$updatePreQResult = $db->query($updatePreQ);
			}
		}
		// ====== FOR STAND ALONDE TESTING ======================================================================
		if($prePost=='sa'){
			$tPostQ = 'SELECT * FROM q_score_stats WHERE '. $tempQID .' = qss_q_link AND qss_pre_post="sa"';
			$tPostQResult = $db->query($tPostQ);
			if($tPostQResult->num_rows>0){
				$q2='true';
				$addStat='false';
				$row = $tPostQResult->fetch_assoc();
				if($ansGroupArry[$i]=='setA'){
					$tempConfCount = $row['qss_conf_count']+1;
					$tmpAnsGroup = 'qss_conf_count='.$tempConfCount;
				}
				if($ansGroupArry[$i]=='setB'){
					$tempConfCount = $row['qss_uncertain_count']+1;
					$tmpAnsGroup = 'qss_uncertain_count='.$tempConfCount;
				}
				if($ansGroupArry[$i]=='setC'){
					$tempConfCount = $row['qss_do_not_know_count']+1;
					$tmpAnsGroup = 'qss_do_not_know_count='.$tempConfCount;
				}
				if($corrArry[$i]=='true'){
					$tempScoreCount = $row['qss_total_correct']+1;
					$tempScore = 'qss_total_correct='.$tempScoreCount;
				} else {
					$tempScoreCount = $row['qss_total_incorrect']+1;
					$tempScore = 'qss_total_incorrect='.$tempScoreCount;
				}
				$updatePreQ = 'UPDATE q_score_stats SET ' . $tmpAnsGroup . ',' . $tempScore . ' WHERE qss_id = '. $row['qss_id'];
				$updatePreQResult = $db->query($updatePreQ);
			}
		}
		//==========================================
		if($addStat=='true'){
			if($corrArry[$i]=='true'){
				$tempCorrectAnsQ = 'qss_total_correct';
			} else {
				$tempCorrectAnsQ = 'qss_total_incorrect';
			}
			if($ansGroupArry[$i]=='setA'){
				$tempConfLvlQ = 'qss_conf_count';
			} else if($ansGroupArry[$i]=='setB'){
				$tempConfLvlQ = 'qss_uncertain_count';
			} else {
				$tempConfLvlQ = 'qss_do_not_know_count';
			}
			
			$insertStatDataQ = 'INSERT INTO q_score_stats (qss_q_link,'. $tempCorrectAnsQ. ',' . $tempConfLvlQ.',qss_test_link,qss_pre_post) VALUES ('.$qIDArry[$i].',1,1,'.$edID.',"'.$prePost.'")';
			$insertStatDataQResult = $db->query($insertStatDataQ);
			
		}
}
// ==========================================================

//echo json_encode('qID= ' . $qIDArry[0] . ', answer group= ' . $ansGroupArry[0] . ' userID = ' . $userID . ' total pts earned = ' . $totalScore . ', total pts poss= ' . $totalPtsPoss . ', % score= ' . $percentScore . ', edID= ' . $edID . ', pre or post = ' . $prePost);
$tempArry = array();
$tempArry[0] = $prePost;
$tempArry[1] = $tempEUEID;
echo json_encode($tempArry);//$prePost
?>