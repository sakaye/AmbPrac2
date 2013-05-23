<?php
define('CONN_EU', 'dbConnect.php');
require CONN_EU;

function getAreas(){
	require CONN_EU;
	$areaQ = 'SELECT * FROM end_user_area ORDER BY eua_title';
	$areaQResult = $db->query($areaQ);
	return $areaQResult;
}

function getTitles(){
	require CONN_EU;
	$titleQ = 'SELECT * FROM end_user_titles ORDER BY eut_title';
	$titleQResult = $db->query($titleQ);
	return $titleQResult;
}
function getMOBs(){
	require CONN_EU;
	$mobQ = 'SELECT * FROM end_user_mob ORDER BY eum_title ASC';
	$mobQResult = $db->query($mobQ);
	return $mobQResult;
}
function getThisMOB($mobID){
	require CONN_EU;
	$mobQ = 'SELECT * FROM end_user_mob WHERE eum_area_link = ' . $mobID . ' ORDER BY eum_title ASC';
	$mobQResult = $db->query($mobQ);
	return $mobQResult;
}
?>