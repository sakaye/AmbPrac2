<?php 
session_start();
define('CONN_EU','dbConnect.php'); 
require CONN_EU;
define('AREAS_AND_TITLES','areasAndTitles.php');

if(isset($_GET['educationID'])){
	$_SESSION['edID'] = $_GET['educationID'];
}
if(isset($_GET['pre_post'])){
	if(($_GET['pre_post'] == 'pre') || ($_GET['pre_post'] == 'post')){
		$prePost = $_GET['pre_post'];
		$_SESSION['pre_post'] = $prePost;	
	} else {
		$_SESSION['pre_post']='sa';
	}

}
// REMOVE USER SESSIONS===========
if(isset($_SESSION['end_user_id'])){
	unset($_SESSION['end_user_id']);
}
if(isset($_SESSION['userEdID'])){
	unset($_SESSION['userEdID']);
}
//================================
?>
<?php 

$btnTxt = 'Submit';
$nuidTxt = '';
$userNotFound = '';
$loginSuccess = false;
$newUserFrmSwitch = 'display:none;';

function getArea($areaID){
	require CONN_EU;
	$areaQ = 'SELECT * FROM end_user_area WHERE eua_id = ' . $areaID;
	$areaQResult = $db->query($areaQ);
	$area = $areaQResult->fetch_assoc();
	return $area['eua_title'];
}
function getTitle($titleID){
	require CONN_EU;
	$titleQ = 'SELECT * FROM end_user_titles WHERE eut_id = ' . $titleID;
	$titleQResult = $db->query($titleQ);
	$title =  $titleQResult->fetch_assoc();
	return $title['eut_title'];
}
if(isset($_POST['nuidSubbed'])){
	$nuidTxt = $_POST['nuid'];
	$endUserCheckQ = 'SELECT * FROM end_users WHERE nuid = "' . $_POST['nuid'] . '"';
	$endUserCheckQResult = $db->query($endUserCheckQ);
	if($endUserCheckQResult->num_rows == 0){
		$userNotFound = '<span style="color:#F00; font-size:14px;"> < No record found...</span>';
		$btnTxt = 'Try Again';
		$loginSuccess = false;
		$loginFrmSwitch = 'display: block;';
	} else {
		$user = $endUserCheckQResult->fetch_assoc();
		$_SESSION['end_user_id'] = $user['nuid'];
		header("Location: user_dashboard.php");
		$loginFrmSwitch = 'display: none;';
		$loginSuccess = true;
	}
}

if(isset($_GET['nf'])){
	$loginFrmSwitch = 'display: none;';	
	$newUserFrmSwitch = 'display: block;';
	require AREAS_AND_TITLES;
	$formContainerClass="formContainerC1";
} else {
	$formContainerClass="formContainerC2";
}

$deptQ = 'SELECT * FROM end_user_depts ORDER BY eud_title';
$deptQResult = $db->query($deptQ);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Login</title>
<style type="text/css">
body{
	margin:0px;
	background-color:#626262;
}
#pageWrapper{
	width:780px;
	margin:auto;
}
p{
	margin:0;	
}
#loginFrm{
	<?php echo $loginFrmSwitch; ?>
}

#frmNewUser{
	<?php echo $newUserFrmSwitch; ?>
}
.removeHiLight{
	border:solid 1px #666;
	width:250px;
}
.showHiLight{
	border:solid 1px #F00;
	background-color:#FFFDFD;
	width:250px;
}
#newProfileWrapper{
	width:650px;
	border-bottom:dashed 1px #666;
	padding-top:15px;
}
.tdUserProfile{
	background-color:#D7E9FD;
	border:solid 1px #06C;
}
.tiStyle{
	border:solid 1px #06C;	
}
.npRow{
	height:30px;
}
.lbl{
	width:220px;
	float:left;
	background-color:#515151;
	border:solid 1px #999;
	height:22px;
	padding-left:4px;
	color:#FFF;
	font-size:14px;
}
.formUI{
	display:inline;
	padding-left:10px;
}
.divHidden{
	display:none;
}
.divShow{
	display:block;
}
.showTitle{
	display:block;
	background-color:#FF0;
	padding-bottom:4px;
	padding-top:4px;
	margin-bottom:4px;
}
#mainWrapper{
	background-image:url(images/loginBG.jpg);
	background-repeat:no-repeat;
	padding-top:70px;
	height:600px;
}
#banner{
	
}
#formContainer{

}
.formContainerC1{
	background-image:url(images/createProfileBG.jpg);
	background-repeat:no-repeat;
	background-position:15px 15px;
	width:720px;
	min-height:405px;
	padding-left:28px;
	padding-top:10px;

}
.formContainerC2{
	width:720px;
	min-height:405px;
	padding-left:28px;
	padding-top:10px;
	margin-top:5px;
}
</style>

<script>
tempAreaID=2;
function changeSelect(el){
	
	if(document.getElementById(el.id).value==0){
		document.getElementById(el.id).className="showHiLight";
	} else {
		document.getElementById(el.id).className="removeHiLight";
	}
	if(el.id=="selectArea"){
		document.getElementById("mobsList").options.length = 0;
		tempAreaID = document.getElementById(el.id).value;
			for(i=0; i<mobArry.length;i++){
				if(mobArry[i].areaLink==tempAreaID){
					var optn = document.createElement("OPTION");
					optn.text = mobArry[i].mobTitle;
					optn.value = mobArry[i].mobID;
					document.getElementById("mobsList").options.add(optn);
				}
				if(i==mobArry.length-1){
					var optnOther = document.createElement("OPTION");
					optnOther.text = 'Other...';
					optnOther.value=3000;
					document.getElementById("mobsList").options.add(optnOther);
				}
			}
	}
	
	if(el.id=="selectTitle"){ 
		if(document.getElementById(el.id).value==1000){
			document.getElementById("addNewTitle").className="showTitle";
			// SHOW ADD NEW TITLE AREA -------------------------------
		} else {
			document.getElementById("addNewTitle").className="divHidden";
		}
	}
	if(el.id=="depts"){
		if(document.getElementById(el.id).value==2000){
			document.getElementById("otherDept").className="showTitle";	
		} else {
			document.getElementById("otherDept").className="divHidden";	
		}
	}
	if(el.id=="mobsList"){
		if(document.getElementById(el.id).value==3000){
			document.getElementById("otherMOBs").className="showTitle";	
		} else {
			document.getElementById("otherMOBs").className="divHidden";
		}
	}
}
function MOB(areaLink,mob,mobID){
this.areaLink = areaLink;
this.mobTitle = mob;
this.mobID = mobID;
}
function empStatusHandler(el){
	if(el.value == "true"){
		document.getElementById("empStatus").className="divShow";
		document.getElementById("addNewClinic").className="divHidden";
	} else {
		document.getElementById("empStatus").className="divHidden";
		document.getElementById("addNewClinic").className="showTitle";
	}
}
<?php

if(isset($_GET['nf'])){
	echo 'mobArry = new Array();';
	$mobResult = getMOBs();
	while($row = $mobResult->fetch_assoc()){
		echo 'tempMOB=new MOB(' . $row['eum_area_link'] . ',"' . $row['eum_title'] . '",' . $row['eum_id'] . ');';
		echo 'mobArry.push(tempMOB);';
	}
}

?>
//alert(mobArry[0].mobTitle);
</script>
</head>

<body style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px;">

<script type="text/javascript">
//for(i=0; i<mobArry.length;i++){
	//if(mobArry[i].areaLink==tempAreaID){
		//document.write(mobArry[i].mobTitle + '<br>');
	//}
//}
</script>
<div id="pageWrapper">
<div id="mainWrapper">
<div id="formContainer" class="<?php echo $formContainerClass; ?>">

<form style="padding-top:5px;" id="loginFrm" action="index.php" method="post">
<table style="width:665px;">
<tr><td style="width:60px; color:#666; font-size:14px; font-weight:bold">Username:</td><td style="width:590px;"> <input style="border: solid 1px #F00;" name="nuid" type="text" id="nuid" value="<?php echo $nuidTxt; ?>" />
  <input type="submit" name="button" id="button" value="<?php echo $btnTxt; ?>" /> <?php echo $userNotFound; ?></td></tr>
<tr>
  <td colspan="2" style="border-top:dashed 1px #999;"><input name="nuidSubbed" type="hidden" id="nuidSubbed" value="true" /><p style="color:#666; font-size:14px;">Please log in using your username (either NUID or email address for KP employees, or email address for community colleagues)<br /><br /><span style="font-style:italic;">* If you have 'not' created a profile, please enter a username (NUID or email for KP employees, or email for community colleagues) in the text box above, then click submit. A link will appear which will allow you to create a new profile.</span> </p>
  </td>
</tr>
<tr><td colspan="2">
  <?php if( ($loginSuccess == false) && (isset($_POST['nuidSubbed'])) ){
	echo '<p><a href="index.php?nf=true&tempEU=' . $nuidTxt . '" style="font-size:16px; font-weight:bold; color:#F00;">- click here to create a new profile</a></p>';  
  }
  ?>
</td>
</table>
</form>

  <form id="frmNewUser" action="createNewUser.php" method="post">
    <div id="newProfileWrapper">
      <div class="npRow"><p class="lbl">NUID (or email address)</p><p class="formUI"><input name="newUser_nuid" type="text" class="tiStyle" id="newUser_nuid" value="<?php echo $_GET['tempEU']; ?>"/>
      </p></div>
      
      <div class="npRow">
      <p class="lbl">First Name, Middle Init, Last Name</p>
      
      <p class="formUI">
        <input class="tiStyle" name="f_name" type="text" id="f_name" size="20" />&nbsp;<input class="tiStyle" name="m_init" type="text" id="f_name" size="3" />&nbsp;<input class="tiStyle" name="l_name" type="text" id="f_name" size="20" />
        </p>
        
        </div>
        
      	<div class="npRow">
        <p class="lbl">Title:</p>
        <p class="formUI">
          <select onchange="changeSelect(this)" id="selectTitle" name="eut_title" class="showHiLight">
        <option value="0">Choose Title</option>
      <?php
	  $titleQResult = getTitles();
while($row1 = $titleQResult->fetch_assoc()){
	echo '<option value="' . $row1['eut_id'] . '">' . $row1['eut_title'] . '</option>';	
}
?><option value="1000">Other...</option>
      </select>
      </p>
      </div>
          
      <div style="width:450px;">
        <div id="addNewTitle" class="divHidden">
        <p class="lbl" style="background-color:#515151; border:solid 1px #F00;">Please type in your title:</p>
        <p class="formUI">
          <input name="newTitle" id="newTitleTxt" size="28" />
         </p>
         </div>
      </div>
          
<div class="npRow">
      <p class="lbl">Are you a KP employee?</p>
      <p class="formUI">
      
      <div style="display:inline; background-color:#F0F0F0; padding-right:10px;">
      <input type="radio" name="rb_empStatus" id="rb_empStatus" value="true" onclick="empStatusHandler(this)" />
      <label for="rb_empStatus">Yes</label>
      </div>
      <div style="display:inline; background-color:#F0F0F0; padding-right:10px;">
      <input type="radio" name="rb_empStatus" id="rb_empStatus2" value="false" onclick="empStatusHandler(this)" />
      <label for="rb_empStatus2">No</label>
      </div>
      </p>
      </div>
          
        <!-- =============================[ EMPLOYEE STATUS ]================================ -->  
        <div id="empStatus" class="divHidden">  
        <div class="npRow">
        <p class="lbl">Geographic Area:</p>
        
        <p class="formUI">
          <select onchange="changeSelect(this)" id="selectArea" name="eua_title" class="showHiLight">
      <option value="0">Choose Area</option>
      <?php
	  $areaResult = getAreas();
while($row1 = $areaResult->fetch_assoc()){
	echo '<option value="' . $row1['eua_id'] . '">' . $row1['eua_title'] . '</option>';	
}
?>
</select>
      </p>
      </div>
      
      <div class="npRow">
        <p class="lbl">Med Office Bld / Med Ctr</p>
        
        <p class="formUI">
          <select onchange="changeSelect(this)" class="showHiLight" name="mobsList" id="mobsList">
        </select>
        </p>
        </div>
     <!-- ================================================================================= -->
                        <!-- ========================================== OTHER MOB ============================================ -->
      <div style="width:500px;">
        	<div id="otherMOBs" class="divHidden">
        		<p class="lbl" style="background-color:#515151; border:solid 1px #F00;">Please enter your MOB/Med Ctr</p>
        		<p class="formUI">
          			<input name="otherMOB" id="otherMOB" size="30" />
         		</p>
         	</div>
      </div>
        <!-- ================================================================================================ -->
      <div class="npRow">
        <p class="lbl">Department</p>
        
        <p class="formUI">
          <select onchange="changeSelect(this)" class="showHiLight" name="depts" id="depts">
          <?php
		  
		  while($row = $deptQResult->fetch_assoc()){
			echo '<option value="' . $row['eud_id'] . '">' . $row['eud_title'] . '</option>';
		  }
		  
		  ?>
          <option value="2000">Other...</option>
        </select>
        </p>
        </div>
     <!-- ============================================================================ -->
     
</div>
        <!-- ===================================== END OF EMPLOYEE STATUS ===================================== -->
        
        <!-- ===================================== EXTERNAL MEDICAL OFFICE ===================================== -->
        <div style="width:600px;">
        	<div id="addNewClinic" class="divHidden">
        		<p class="lbl" style="background-color:#515151; border:solid 1px #F00;">Please enter your facilty & dept.</p>
        		<p class="formUI" style="width:725px;">
          			<input name="newFacilityTxt" id="newFacilityTxt" size="20" />&nbsp;<input name="newDeptTxt" id="newDeptTxt" size="20" />
         		</p>
         	</div>
      </div>
        
        <!-- ================================== END OF EXTERNAL MED OFFICE =================================== --> 
        
        <!-- ========================================== OTHER DEPT ============================================ -->
      <div style="width:600px;">
        	<div id="otherDept" class="divHidden">
        		<p class="lbl" style="background-color:#515151; border:solid 1px #F00;">Please enter your department.</p>
        		<p class="formUI">
          			<input name="otherDept" id="otherDept" size="30" />
         		</p>
         	</div>
      </div>
        <!-- ================================================================================================ -->

        <div class="npRow" style="text-align:right; height:40px;">
        <input name="newUserCheck" type="hidden" id="newUserCheck" value="true" />
        <input type="submit" name="button2" id="button2" value="Create Profile" style="height:40px;"/>
        </div>
        
    </div>
  </form>
  
  <!-- ================== end of form wrapper =================== -->
  </div>
  
  </div>
 
</div>
</body>
</html>