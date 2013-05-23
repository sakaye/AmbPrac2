<?php


function getArea($areaID){
	require DB_CONN;
	$areaQ = 'SELECT * FROM end_user_area WHERE eua_id = ' . $areaID;
	$areaQResult = $db->query($areaQ);
	$area = $areaQResult->fetch_assoc();
	return $area['eua_title'];
}
function getTitle($titleID){
	require DB_CONN;
	$titleQ = 'SELECT * FROM end_user_titles WHERE eut_id = ' . $titleID;
	$titleQResult = $db->query($titleQ);
	$title =  $titleQResult->fetch_assoc();
	return $title['eut_title'];
}
function getMOB($mobID,$nuid){
	require DB_CONN;
	$tempMOBTitle = '';
	if($mobID == 3000){
		$otherMOBQ = 'SELECT omobs_title FROM other_mobs WHERE omobs_user_link = "' . $nuid . '" LIMIT 1';
		$otherMOBQResult = $db->query($otherMOBQ);
		$otherMOBTitle = $otherMOBQResult->fetch_assoc();
		$tempMOBTitle = $otherMOBTitle['omobs_title'];
	} else {
		$titleQ = 'SELECT * FROM end_user_mob WHERE eum_id = ' . $mobID;
		$titleQResult = $db->query($titleQ);
		$title =  $titleQResult->fetch_assoc();
		$tempMOBTitle = $title['eum_title'];
	}
	return $tempMOBTitle;
	
}
function getOtherTitle($nuid){
	require DB_CONN;
	$otQ = 'SELECT ot_title FROM other_titles WHERE ot_user_link = "' . $nuid . '" LIMIT 1';
	$otQResult = $db->query($otQ);
	$tempRow = $otQResult->fetch_assoc();
	$tempTitle = $tempRow['ot_title'];
	return $tempTitle;	
}
function getExtFacDept($nuid){
	require DB_CONN;
	$extFacQ = 'SELECT efd_fac, efd_dept FROM ext_facilities_depts WHERE efd_user_link = "' . $nuid . '" LIMIT 1';
	$extFacQResult = $db->query($extFacQ);
	$tempArry = array();
	while($row = $extFacQResult->fetch_assoc()){
		array_push($tempArry,$row['efd_fac']);
		array_push($tempArry,$row['efd_dept']);
	}
	return $tempArry;
}
function getDept($deptLink,$nuid){
	require DB_CONN;
	$tempDeptString='';
	if($deptLink == 2000){
		$newDeptQ = 'SELECT euod_title FROM end_user_other_depts WHERE euod_user_id = "' . $nuid . '" LIMIT 1';
		$newDeptQResult = $db->query($newDeptQ);
		$tempRow = $newDeptQResult->fetch_assoc();
		$tempDeptString = $tempRow['euod_title'];
	} else {
		$deptQ = 'SELECT eud_title FROM end_user_depts WHERE eud_id = ' . $deptLink . ' LIMIT 1';
		$deptQResult = $db->query($deptQ);
		$tempRow = $deptQResult->fetch_assoc();
		$tempDeptString = $tempRow['eud_title'];
	}
	return $tempDeptString;
}
function getUserNFO($userID){
	require DB_CONN;
	//require 'EndUserVO.php';
	$tempEndUserObj = new EndUserVO();
	
	$userQ = 'SELECT * FROM end_users WHERE nuid = "' . $userID . '" LIMIT 1';
	$userQResult = $db->query($userQ);
	while($row = mysqli_fetch_assoc($userQResult)){
		$tempEndUserObj->nuid = $row['nuid'];
		$tempEndUserObj->f_name = $row['f_name'];
		$tempEndUserObj->m_init = $row['m_init'];
		$tempEndUserObj->l_name = $row['l_name'];
		
		$tempEndUserObj->title_link = $row['title_link'];
		if($tempEndUserObj->title_link == 1000){
			$tempEndUserObj->title = getOtherTitle($row['nuid']);	
		} else {
			$tempEndUserObj->title = getTitle($row['title_link']);
		}
		
		$tempEndUserObj->area_link = $row['area_link'];
		if($row['eu_kp_emp'] == 'false'){
			$tempArry = array();
			$tempArry = getExtFacDept($row['nuid']);
			$tempEndUserObj->newFacility = $tempArry[0];
			$tempEndUserObj->dept = $tempArry[1];
		} else {
			$tempEndUserObj->area = getArea($row['area_link']);
			$tempEndUserObj->mob_link = $row['mob_link'];
			$tempEndUserObj->mob = getMOB($row['mob_link'],$row['nuid']);
			$tempEndUserObj->departmentLink = $row['dept_link'];
			$tempEndUserObj->dept = getDept($row['dept_link'],$row['nuid']);
		}
		$tempEndUserObj->eu_kp_emp = $row['eu_kp_emp'];
	}
	return $tempEndUserObj;
		
}
function sendEdCompletion($userID,$edID){
	require DB_CONN;
	$returnMessage = '';
	// check for dups
	$checkDupsQ = 'SELECT eue_user_link, eue_ed_link FROM end_user_education WHERE eue_user_link = "' . $userID . '" AND eue_ed_link = ' . $edID . ' LIMIT 1';
	$checkDupsResult = $db->query($checkDupsQ);
	if($checkDupsResult->num_rows>0){
		$returnMessage = 'record already exists';
	} else {
		$insertCompQ = 'INSERT INTO end_user_education (eue_user_link, eue_ed_link, eue_complete_date) VALUES ("' . $userID .'",' . $edID . ', NOW())';	
		$insertQResult = $db->query($insertCompQ);
		$returnMessage = 'complete';
	}
	
}
function editUserProfile($endUserObject){
	require DB_CONN;
	$endUserObj = new EndUserVO();
	$endUserObj = $endUserObject;
	//$profileQ = 'UPATE end_users SET f_name="' . $endUserObj->f_name  . '", l_name="' . $endUserObj->l_name . '", m_init="' . $endUserObj->m_init . '", title_link=' . $endUserObj->title_link . ', area_link=' . $endUserObj->area_link . ', mob_link=' . $endUserObj->mob_link . ', eu_kp_emp="' . $endUserObj->eu_kp_emp . '", dept_link=' . $endUserObj->deptartmentLink . ' WHERE nuid="' . $endUserObj->nuid . '"';
	
	if($endUserObj->title_link !=0){
		$title = ', title_link = ' . $endUserObj->title_link;
	} else {
		$title = '';
	}
	
	if($endUserObj->area_link !=0){
		$area = ', area_link = ' . $endUserObj->area_link;
	} else {
		$area = '';
	}
	
	if($endUserObj->mob_link !=0){
		$mob = ', mob_link = ' . $endUserObj->mob_link;
	} else {
		$mob = '';
	}
	
	if($endUserObj->departmentLink !=0){
		$dept = ', dept_link = ' . $endUserObj->departmentLink;
	} else {
		$dept = '';
	}
	
	$addToQ = $title . $area . $mob . $dept;
	
	$profileQ = 'UPDATE end_users SET f_name="' . $endUserObj->f_name  . '", l_name="' . $endUserObj->l_name . '", m_init="' . $endUserObj->m_init . '",eu_kp_emp="'. $endUserObj->eu_kp_emp . '"' . $addToQ . ' WHERE nuid = "'. $endUserObj->nuid . '"'; 
	
	//echo $profileQ;
	$mainEditResult = $db->query($profileQ);
	
	// check new title
	if($endUserObj->title_link == 1000){
		setOtherTitle($endUserObj);	
	}
	
	if($endUserObj->departmentLink == 2000){
		setOtherDept($endUserObj);	
	}
	
	if($endUserObj->mob_link == 3000){
		setOtherMOB($endUserObj);	
	}
	
	if($endUserObj->eu_kp_emp == 'false'){
		setExternalDeptAndFacility($endUserObj);	
	}
	
}
function setOtherTitle($endUserObject){
	require DB_CONN;
	$delQ = 'DELETE FROM other_titles WHERE ot_user_link ="' . $endUserObject->nuid . '"';
	$delResult = $db->query($delQ);
	
	$insertQ = 'INSERT INTO other_titles (ot_title,ot_user_link) VALUES ("' . $endUserObject->title . '","' . $endUserObject->nuid . '")';
	$insertQResult = $db->query($insertQ);
	
}
function setOtherMOB($endUserObject){
	require DB_CONN;
	$delQ = 'DELETE FROM other_mobs WHERE omobs_user_link ="' . $endUserObject->nuid . '"';
	$delResult = $db->query($delQ);
	
	$insertQ = 'INSERT INTO other_mobs (omobs_title,omobs_user_link) VALUES ("' . $endUserObject->mob . '","' . $endUserObject->nuid . '")';
	$insertQResult = $db->query($insertQ);
	
}
function setOtherDept($endUserObject){
	require DB_CONN;
	$delQ = 'DELETE FROM end_user_other_depts WHERE euod_user_id ="' . $endUserObject->nuid . '"';
	$delResult = $db->query($delQ);
	
	$insertQ = 'INSERT INTO end_user_other_depts (euod_title,euod_user_id) VALUES ("' . $endUserObject->dept . '","' . $endUserObject->nuid . '")';
	$insertQResult = $db->query($insertQ);
}
function setExternalDeptAndFacility($endUserObject){
	require DB_CONN;
	$delQ = 'DELETE FROM ext_facilities_depts WHERE efd_user_link = "' . $endUserObject->nuid . '"';
	$delQResult = $db->query($delQ);
	
	$insertQ = 'INSERT INTO ext_facilities_depts (efd_fac,efd_dept,efd_user_link) VALUES ("' . $endUserObject->newFacility . '","' . $endUserObject->newDept . '","' . $endUserObject->nuid . '")';
	$insertQResult = $db->query($insertQ);
}
function generateTest($edID){
	
}

?>