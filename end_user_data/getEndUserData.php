<?php

function getArea($areaID){
	require DB_CONN;
	$areaQ = 'SELECT * FROM end_user_area WHERE eua_id = ' . $areaID;
	$areaQResult = $db->query($areaQ);
	$tempArea='';
	if($areaQResult->num_rows>0){
		$area = $areaQResult->fetch_assoc();
		$tempArea=$area['eua_title'];
	} else {
		$tempArea='';	
	}
		return $area['eua_title'];
}
function getTitle($titleID){
	require DB_CONN;
	$titleQ = 'SELECT * FROM end_user_titles WHERE eut_id = ' . $titleID;
	$titleQResult = $db->query($titleQ);
	$title =  $titleQResult->fetch_assoc();
	return $title['eut_title'];
}
function getMOB($mobID){
	require DB_CONN;
	$titleQ = 'SELECT * FROM end_user_mob WHERE eum_id = ' . $mobID;
	$titleQResult = $db->query($titleQ);
	$title='';
	if($titleQResult->num_rows>0){
		$titleQResult->fetch_assoc();
		$title = $title['eum_title'];
	}
	return $title;
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
		$tempEndUserObj->title_link = getTitle($row['title_link']);
		$tempEndUserObj->area_link = getArea($row['area_link']);
		$tempEndUserObj->mob_link = getMOB($row['mob_link']);
	}
	return $tempEndUserObj;
		
}

?>