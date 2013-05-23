<?php session_start();
require('dbConnect.php');
include('tableDataGrabber.php');
require('classes/EndUserVO.php');
require('classes/EndUserEducationVO.php');
//$edID = 1;
$edID = $_POST['edID'];

$titlesArry = grabTitles();
$titleOtherArry = grabOtherTitles();
$areasArry = grabAreas();
$mobArry = grabMOBs();
$mobOtherArry = grabOtherMOBs();
$deptsArry = grabEndUserDepts();
$deptsOtherArry = grabEndUserOtherDepts();
$extFacArry = grabExtFacilitiesDepts();
$tAuthorsArry = grabTestAuthors();

function grabThisTitle($titleID,$nuid){
	global $titlesArry;
	global $titleOtherArry;
	if($titleID!='1000'){
		for($i=0; $i<count($titlesArry);$i++){
			if($titlesArry[$i]['eut_id']==$titleID){
				$theTitle = $titlesArry[$i]['eut_title'];
			}
		}
	} else {
		for($i=0; $i<count($titleOtherArry);$i++){
			if($nuid == $titleOtherArry[$i]['ot_user_link']){
				$theTitle = $titleOtherArry[$i]['ot_title'];	
			}
		}
	}
	return $theTitle;
}
function checkTestScore($theScore){
	$theResponse='';
	if($theScore=='5000'){
		$theResponse='not taken';
	} else {
		$theResponse=$theScore;
	}
	return $theResponse;
}
function areaCheck($kpEmp,$areaID){
	$theResponse='';
	global $areasArry;
	if($kpEmp=='true'){
		for($i=0; $i<count($areasArry);$i++){
			if($areasArry[$i]['eua_id']==$areaID){
				$theResponse = $areasArry[$i]['eua_title'];
			}
		}
	} else {
		$theResponse='non kp';
	}
	return $theResponse;
		
}
function mobCheck($mobID,$kpEmp,$userID){
	global $mobArry;
	global $mobOtherArry;
	$theResponse='';
	
	if($kpEmp=='true'){
		if($mobID!='3000'){
			for($i=0; $i<count($mobArry);$i++){
				if($mobID == $mobArry[$i]['eum_id']){
					$theResponse = $mobArry[$i]['eum_title'];	
				}
			}
		} else {
			for($i=0; $i<count($mobOtherArry);$i++){
				if($userID == $mobOtherArry[$i]['omobs_user_link']){
					$theResponse = $mobOtherArry[$i]['omobs_title'];	
				}
			}
		}
			
		} else {
			$theResponse = 'non kp';
	}
	return $theResponse;
}
function deptCheck($nuid,$kpEmp,$deptID){
	global $deptsArry;
	global $deptsOtherArry;
	global $extFacArry;
	$theResponse = '';
	if($kpEmp=='true'){
		if($deptID!='2000'){
			for($i=0; $i<count($deptsArry);$i++){
				if($deptID == $deptsArry[$i]['eud_id']){
					$theResponse = $deptsArry[$i]['eud_title'];
				}
			}
		} else {
			for($i=0; $i<count($deptsOtherArry);$i++){
				if($nuid == $deptsOtherArry[$i]['euod_user_id']){
					$theResponse = 	$deptsOtherArry[$i]['euod_title'];
				}
			}
		}
	} else {
		for($i=0; $i<count($extFacArry);$i++){
			if($nuid == $extFacArry[$i]['efd_user_link']){
				$theResponse = 	$extFacArry[$i]['efd_fac'] . ', ' . $extFacArry[$i]['efd_dept'];
			}
		}
	}
	return $theResponse;
}
function checkCompleteDate($theDate){
	if($theDate==''){
		$theResponse = 'not taken';	
	} else {
		$theResponse = $theDate;
	}
	return $theResponse;
}
function grabThisAuthor($authorID){
	global $tAuthorsArry;
	$theResponse;
	for($i = 0; $i<count($tAuthorsArry);$i++){
		if($authorID == $tAuthorsArry[$i]['nuid']){
			$theResponse = $tAuthorsArry[$i]['f_name'] . ' ' . 	$tAuthorsArry[$i]['m_init'] . ' ' . $tAuthorsArry[$i]['l_name'];
		}
	}
	return $theResponse;
}
$endUserArry = array();
$endUserQ = 'SELECT *, DATE_FORMAT(eue_pre_test_date,"%a, %b, %D %Y, %r") as preTestDt, '.
					  'DATE_FORMAT(eue_post_test_date,"%a, %b, %D %Y, %r") as postTestDt, ' .
					  'DATE_FORMAT(eue_sa_test_date,"%a, %b, %D %Y, %r") as saTestDt ' .
					  'FROM end_user_education,end_users,education_table WHERE end_user_education.eue_user_link=end_users.nuid ' .
			'AND eue_ed_link=et_id AND eue_ed_link=' . $edID;
$endUserQResult = $db->query($endUserQ);

while($row = $endUserQResult->fetch_assoc()){
	$tempEndUserEdVO = new EndUserEducationVO();
	$tempEndUserEdVO->testTitle = $row['et_title'];
	$tempEndUserEdVO->testDescript = $row['et_description'];
	$tempEndUserEdVO->testAuthor = grabThisAuthor($row['et_test_author_link']);
	$tempEndUserEdVO->endUserVO->f_name = $row['f_name'];
	$tempEndUserEdVO->endUserVO->l_name = $row['l_name'];
	$tempEndUserEdVO->endUserVO->m_init = $row['m_init'];
	$tempEndUserEdVO->endUserVO->eu_kp_emp = $row['eu_kp_emp'];
	
	$tempEndUserEdVO->endUserVO->title = grabThisTitle($row['title_link'],$row['nuid']);
	$tempEndUserEdVO->endUserVO->area = areaCheck($row['eu_kp_emp'],$row['area_link']);
	$tempEndUserEdVO->endUserVO->mob = mobCheck($row['mob_link'],$row['eu_kp_emp'],$row['nuid']);
	$tempEndUserEdVO->endUserVO->dept = deptCheck($row['nuid'],$row['eu_kp_emp'],$row['dept_link']);
	
	$tempEndUserEdVO->preTestDate = checkCompleteDate($row['preTestDt']);
	$tempEndUserEdVO->postTestDate = checkCompleteDate($row['postTestDt']);
	$tempEndUserEdVO->saTestDate = checkCompleteDate($row['saTestDt']);
	
	// ===================================== TURN OFF SCORE FROM USERS =================
	//$tempEndUserEdVO->eue_pre_score = checkTestScore($row['eue_pre_score']);
	//$tempEndUserEdVO->eue_post_score = checkTestScore($row['eue_post_score']);
	//$tempEndUserEdVO->eue_sa_score = checkTestScore($row['eue_sa_score']);
	// =================================================================================
	
	array_push($endUserArry,$tempEndUserEdVO);
}

echo json_encode($endUserArry);

?>