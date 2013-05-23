<?php

define('DB_CONN', 'dbConnect.php');

function grabTitles(){
	require DB_CONN;
	$titleQ = 'SELECT * FROM end_user_titles';
	$titleQResult = $db->query($titleQ);
	$tempArry = array();
	while($row = $titleQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}

function grabOtherTitles(){
	require DB_CONN;
	$titleQ = 'SELECT * FROM other_titles ORDER BY ot_id DESC';
	$titleQResult = $db->query($titleQ);
	$tempArry = array();
	while($row = $titleQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}

function grabMOBs(){
	require DB_CONN;
	$mobQ = 'SELECT * FROM end_user_mob';
	$mobQResult = $db->query($mobQ);
	$tempArry = array();
	while($row = $mobQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabOtherMOBs(){
	require DB_CONN;
	$mobQ = 'SELECT * FROM other_mobs';
	$mobQResult = $db->query($mobQ);
	$tempArry = array();
	while($row = $mobQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabEndUserDepts(){
	require DB_CONN;
	$deptQ = 'SELECT * FROM end_user_depts';
	$deptQResult = $db->query($deptQ);
	$tempArry = array();
	while($row = $deptQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabEndUserOtherDepts(){
	require DB_CONN;
	$deptQ = 'SELECT * FROM end_user_other_depts';
	$deptQResult = $db->query($deptQ);
	$tempArry = array();
	while($row = $deptQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabExtFacilitiesDepts(){
	require DB_CONN;
	$facQ = 'SELECT * FROM ext_facilities_depts';
	$facQResult = $db->query($facQ);
	$tempArry = array();
	while($row = $facQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabAreas(){
	require DB_CONN;
	$areaQ = 'SELECT * FROM end_user_area';
	$areaQResult = $db->query($areaQ);
	$tempArry = array();
	while($row = $areaQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;	
}
function grabTestAuthors(){
	require DB_CONN;
	$tAuthorQ = 'SELECT * FROM end_users WHERE eu_access_level="tEditor"';
	$tAuthorQResult = $db->query($tAuthorQ);	
	$tempArry = array();
	while($row = $tAuthorQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabAllLearnersCompletions(){
	require DB_CONN;
	$learnerEdQ = 'SELECT * FROM end_user_education,end_users WHERE end_users.nuid = end_user_education.eue_user_link';
	$learnerEdQResult = $db->query($learnerEdQ);	
	$tempArry = array();
	while($row = $learnerEdQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
function grabAllTotalScores(){
	require DB_CONN;
	$totalQ = 'SELECT * FROM cbt_total_scores, end_users WHERE cbt_total_scores.end_user_link = end_users.nuid ORDER BY nuid';
	$totalQResult = $db->query($totalQ);
	$tempArry = array();
	while($row = $totalQResult->fetch_assoc()){
		array_push($tempArry,$row);
	}
	return $tempArry;
}
function grabAllEndUsers(){
	require DB_CONN;
	$allUsersQ = 'SELECT * FROM end_users';
	$allUsersQResult = $db->query($allUsersQ);
	$tempArry = array();
	while($row =$allUsersQResult->fetch_assoc()){
		array_push($tempArry,$row);	
	}
	return $tempArry;
}
?>