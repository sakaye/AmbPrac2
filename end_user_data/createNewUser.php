<?php
session_start();

define('DB_CONN','dbConnect.php');
require DB_CONN;
include 'utilities/safe_strings.php';

$newUserID = $_POST['newUser_nuid'];
$newUserID = strtolower($newUserID);
$fName = $_POST['f_name'];
$mInit = $_POST['m_init'];
$lName = $_POST['l_name'];
$title = $_POST['eut_title'];
$area = $_POST['eua_title'];
$mob = $_POST['mobsList'];
$empStatus = $_POST['rb_empStatus'];
$newTitle = $_POST['newTitle'];
$newFacilityTxt = $_POST['newFacilityTxt'];
$newDeptTxt = $_POST['newDeptTxt'];
$dept = $_POST['depts'];
$otherMOB = $_POST['otherMOB'];
$deptOtherKP = $_POST['otherDept'];

$newUserID = makeSafe($newUserID);
$fName = makeSafe($fName);
$mInit = makeSafe($mInit);
$lName = makeSafe($lName);
$title = makeSafe($title);
$area = makeSafe($area);
$mob = makeSafe($mob);
$newTitle = makeSafe($newTitle);
$newFacilityTxt = makeSafe($newFacilityTxt);
$newDeptTxt = makeSafe($newDeptTxt);
$dept = makeSafe($dept);
$otherMOB = makeSafe($otherMOB);
$deptOtherKP = makeSafe($deptOtherKP);

/*
foreach($_POST as $name => $value) {
print "$name : $value<br/>";
}
*/


if($empStatus == 'false'){
$insertNewUserQ = 'INSERT INTO end_users (nuid,f_name,l_name,m_init,title_link, eu_kp_emp) VALUES ("' . $newUserID . '","' . $fName . '","' . $lName . '","' . $mInit . '",' . $title . ', "false")';
} else {
	$insertNewUserQ = 'INSERT INTO end_users (nuid,f_name,l_name,m_init,title_link,area_link,mob_link,dept_link) VALUES ("' . $newUserID . '","' . $fName . '","' . $lName . '","' . $mInit . '",' . $title . ',' . $area . ',' . $mob . ',' . $dept. ')';
	
	if($dept==2000){
		$insertNewDeptQ = 'INSERT INTO end_user_other_depts (euod_title,euod_user_id) VALUES ("' . $deptOtherKP . '","' . $newUserID . '")';
		$insertNewDeptQResult = $db->query($insertNewDeptQ);
	}
}
$insertNewUserQResult = $db->query($insertNewUserQ);

// ----------- CHECK - IF NEW TITLE, SEND TO other_titles TABLE ------------------ 
if($title == 1000){
	$newTitleQ = 'INSERT INTO other_titles (ot_title,ot_user_link) VALUES ("' . $newTitle . '","' . $newUserID . '")';
	$newTitleQResult = $db->query($newTitleQ);	
}
// -------------------------------------------------------------------------------

// ------------ CHECK - IF NEW MOB, SEND TO other_mobs TABLE ----------------------
if($mob == 3000){
	$newMOBQ = 'INSERT INTO other_mobs (omobs_title,omobs_user_link) VALUES ("' . $otherMOB . '","' . $newUserID. '")';
	$newMOBQResult = $db->query($newMOBQ);	
}
// -------------------------------------------------------------------------------------

// ----------- CHECK - IF NOT KP, ADD FACILITY / DEPT INTO ext_facilities_depts --------
if($empStatus == 'false'){
	$nonKPQ = 'INSERT INTO ext_facilities_depts (efd_fac,efd_dept,efd_user_link) VALUES ("' . $newFacilityTxt . '","' . $newDeptTxt . '","' . $newUserID . '")';
	$nonKPQResult = $db->query($nonKPQ);	
}
// -------------------------------------------------------------------------------------


$_SESSION['end_user_id'] = $newUserID;
header('Location: user_dashboard.php');


?>