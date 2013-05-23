<?php
// need to create a session var (when user logs in or creates a new profile) that holds the users nuid/email, then query the user based on the session var
session_start();

define('DB_CONN','dbConnect.php');
require DB_CONN;
define('AREAS_AND_TITLES','areasAndTitles.php');
require AREAS_AND_TITLES;
require 'EndUserVO.php';
include('endUserController.php');

// - set the education id variable to use to submit completions to db
$edID = 'null';
if(isset($_SESSION['edID'])){
	$edID = $_SESSION['edID'];
}
//-------------------------------------------------------------------
$userID = $_SESSION['end_user_id'];

$endUserObj = new EndUserVO();
$endUserObj = getUserNFO($userID);

function checkEmpStatus($status,$cb){
	$responseString = '';
	if($status == $cb){
		$responseString = 'checked="checked"';	
	} else {
		$responseString = '';
	}
	return $responseString;
		
}

function wrapThese($label,$content){
	$theString = '<p class="lbl">' . $label . '</p><p class="lblContent">' . $content . '</p><div style="clear:both" />';
	return $theString;	
}

$deptQ = 'SELECT * FROM end_user_depts ORDER BY eud_title';
$deptQResult = $db->query($deptQ);
?>
<?php
if(isset($_POST['editSubbed'])){
	$editUserObj = new EndUserVO();
	$editUserObj->nuid=$userID;
	$editUserObj->f_name=$_POST['f_name'];
	$editUserObj->m_init = $_POST['m_init'];
	$editUserObj->l_name = $_POST['l_name'];
	$editUserObj->title = $_POST['title_link'];
	$editUserObj->eu_kp_emp = $_POST['rb_empStatus'];
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<style type="text/css">
#mainWrapper{
	background-image:url(images/ambPrac_dashboardBG_editing_03.jpg);
	background-repeat:no-repeat;
	background-position:0px 0px;
	min-height:490px;
	width:727px;
	border:solid 1px #FFF;
}
#profileWrapper{
	width:660px;
	height:250px;
	margin-left:30px;
	margin-top:80px;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
}
.lbl{
	width:140px;
	float:left;
	background-color:#000;
	border:solid 1px #666;
	height:22px;
	padding-left:4px;
	font-size:14px;
	margin-bottom:4px;
	color:#CCC;
	text-align:center;
}
.lblContent{
	border-bottom:solid 1px #666;
	float:left;	
	width:450px;
	padding-left:5px;
	color:#000;
	height:22px;
	text-align:center;
	line-height: 0.9;
}
.formUI{
	padding-left:50px;
}
p{
	margin:0px;
}
.edit{
	border:solid 1px #F00;
}
.divHidden{
	display:none;
}
.divShow{
	display:block;
}
.divShowIL{
	display:inline;	
}
.divHiddenIL{
	display:none;
}
.npRow{
	min-height:30px;
}
.npRowHidden{
	min-height:30px;
	display:none;
}	
</style>

<script type="text/javascript">

function showTitles(){
	document.getElementById("selectTitle").style.display="inline";	
}

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
		document.getElementById("kpEmployeeData").className="divShow";
		document.getElementById("nonKPData").className="divHidden";
	} else {
		document.getElementById("kpEmployeeData").className="divHidden";
		document.getElementById("nonKPData").className="divShow";
	}
}
function showAreaNMOB(){
	document.getElementById("selectArea").className="divShowIL";
	document.getElementById("mobsList").className="divShowIL";
	document.getElementById("mobsListContainer").className="npRow";
}
function showDept(){
	document.getElementById("depts").className="divShowIL";
}
<?php

//if(isset($_GET['nf'])){
	echo 'mobArry = new Array();';
	$mobResult = getMOBs();
	while($row = $mobResult->fetch_assoc()){
		echo 'tempMOB=new MOB(' . $row['eum_area_link'] . ',"' . $row['eum_title'] . '",' . $row['eum_id'] . ');';
		echo 'mobArry.push(tempMOB);';
	}
//}

?>
//alert(mobArry[0].mobTitle);
</script>

</head>

<body>
<?php
if($endUserObj->eu_kp_emp=='true'){
	$initVisibleStatusKP = 'divShow';
	$initVisibleStatusNonKP = 'divHidden';
} else {
	$initVisibleStatusKP = 'divHidden';
	$initVisibleStatusNonKP = 'divShow';
}
?>
<div id="mainWrapper">
<div id="profileWrapper">
<form id="edFrm" name="edFrm">
<!-- ============================== BASIC USER DATA ========================= -->
<div id="basicProfileData">
<p class="lbl">Username:</p><p class="lblContent" style="color:#666;"><?php echo $endUserObj->nuid; ?></p>
<div style="clear:both"></div>
<p class="lbl">Name:</p><p class="lblContent"><input name="f_name" class="edit" type="text" size="25" value="<?php echo $endUserObj->f_name; ?>"/>&nbsp;<input name="m_init" class="edit" type="text" size="3" value="<?php echo $endUserObj->m_init; ?>" />&nbsp;<input name="l_name" class="edit" type="text" size="25" value="<?php echo $endUserObj->l_name; ?>" /></p>
<div style="clear:both"></div>

<p class="lbl">Title:</p>
<p class="lblContent">
<?php echo $endUserObj->title; ?> (<span style="color:#06C; text-decoration:underline; cursor:pointer; font-size:12px" onclick="showTitles()">change to ...</span>)
<select onchange="changeSelect(this)" id="selectTitle" name="eut_title" class="showHiLight" style="display:none;">
        <option value="0" selected="selected">Choose Title...</option>
      <?php
	  $titleQResult = getTitles();
while($row1 = $titleQResult->fetch_assoc()){
	echo '<option value="' . $row1['eut_id'] . '">' . $row1['eut_title'] . '</option>';	
}
?><option value="1000">Other...</option>
      </select>
</p>

<div style="clear:both"></div>

<div style="width:550px;">
	<div id="addNewTitle" class="divHidden">
    	<p class="lbl" style="background-color:#FF0; border:solid 1px #F00; width:200px; color:#000; margin-left:75px">Please type in your title:</p>
       <p class="formUI">
          <input name="newTitle" id="newTitleTxt" size="25" style="border:solid 1px #F00;" />
         </p>
     </div>
</div>

<div style="clear:both"></div>

<p class="lbl">KP Employee?</p><p class="lblContent">
      <input name="rb_empStatus" type="radio" id="rb_empStatus" onclick="empStatusHandler(this)" value="true" <?php echo checkEmpStatus($endUserObj->eu_kp_emp,'true');?> />
      <label for="rb_empStatus" style="color:#000">Yes</label> 
      <span style="color:#666;">&nbsp;&nbsp;|&nbsp;</span> 
      <input type="radio" name="rb_empStatus" id="rb_empStatus2" value="false" onclick="empStatusHandler(this)" <?php echo checkEmpStatus($endUserObj->eu_kp_emp,'false');?> />
      <label for="rb_empStatus2" style="color:#000">No</label>
</p>
<div style="clear:both"></div>


</div>
<!-- ===================== END OF BASIC USER DATA =========================== -->
<div style="clear:both"></div>
<!-- =============================== KP EMPLOYEE DATA ====================== -->
<div id="kpEmployeeData" class="<?php echo $initVisibleStatusKP; ?>">

<div id="areaAndFacility" class="npRow">
        <p class="lbl">Area & Facility:</p>
        <p style="text-align:center"><?php echo $endUserObj->area . ', ' . $endUserObj->mob; ?> &nbsp;(<span style="cursor:pointer; text-decoration:underline; color:#039; font-size:12px" onclick="showAreaNMOB()">change to...</span>)</p>
        
        <div style="clear:both"></div>
        
        <p class="formUI" style="padding-left:195px;">
          <select onchange="changeSelect(this)" id="selectArea" name="eua_title" class="divHidden" style="padding-bottom:5px; border:solid 1px #F00;">
      <option value="0" selected="selected">Choose Area...</option>
      
      <?php
	  $areaResult = getAreas();
while($row1 = $areaResult->fetch_assoc()){
	echo '<option value="' . $row1['eua_id'] . '">' . $row1['eua_title'] . '</option>';	
}
?>
</select>
      </p>
</div>
      
      <div class="npRowHidden" id="mobsListContainer">
        <p class="formUI" style="padding-left:195px;">
          <select onchange="changeSelect(this)" class="divHidden" name="mobsList" id="mobsList" style="padding-bottom:5px; border:solid 1px #F00;">
        </select>
        </p>
      </div>
      
      <div style="width:500px;">
        	<div id="otherMOBs" class="divHidden">
        		<p class="lbl" style="background-color:#FF0; color:#000;width:200px;">Enter MOB/Med Ctr</p>
        		<p class="formUI">
          			&nbsp;&nbsp;<input name="otherMOB" id="otherMOB" size="30" style="border: solid 1px #F00;" />
         		</p>
         	</div>
      </div>
        
<div style="clear:both"></div>
      <div id="kpDept" class="npRow" style="border-top:solid 1px #999;">
        <p class="lbl">Department</p>
        <p style="text-align:center"><?php echo $endUserObj->dept; ?> &nbsp;(<span style="cursor:pointer; text-decoration:underline; color:#039; font-size:12px" onclick="showDept()">change to...</span>)</p>
        <div style="clear:both"></div>
        
        <p class="formUI" style="margin-left:200px;">
          <select onchange="changeSelect(this)" class="divHidden" name="depts" id="depts">
          <option value="0" selected="selected">Choose Department...</option>
          <?php
		  
		  while($row = $deptQResult->fetch_assoc()){
			echo '<option value="' . $row['eud_id'] . '">' . $row['eud_title'] . '</option>';
		  }
		  
		  ?>
          <option value="2000">Other...</option>
        </select>
        </p>
</div>

<div style="clear:both">
</div>
      <div style="width:600px;">
        	<div id="otherDept" class="divHidden">
        		<p class="lbl" style="background-color:#FF0; color:#000;width:240px;">Enter your Dept.</p>
        		<p class="formUI" style="margin-left:200px;">
          			<input name="otherDept" id="otherDept" size="30" style="border: solid 1px #F00;"/>
         		</p>
         	</div>
      </div>

<div style="clear:both"></div>
</div>
<!-- ========================== END OF KP EMP DATA ========================= -->

<!-- =========================== NON KP DATA ============================== -->
<div id="nonKPData" class="<?php echo $initVisibleStatusNonKP; ?>">

        <div id="nonKPWrapper" class="<?php echo $nonKPVisible; ?>">
        	<div id="addNewClinic">
        		<p class="lbl">Facility & Dept.</p>
        		<p style="display:inline; padding-left:75px;">
          			<input name="newFacilityTxt" id="newFacilityTxt" size="20" style="border:solid 1px #F00" value="<?php echo $endUserObj->newFacility; ?>"/> <input name="newDeptTxt" id="newDeptTxt" size="20" style="border:solid 1px #F00" value="<?php echo $endUserObj->dept; ?>"/>
                    
         		</p>
         	</div>
      </div>

</div>
<div style="clear:both"></div>
<!-- ========================= END OF NON KP DATA ========================= -->

<!-- remaining info -->
<div style="margin-top:25px; border:dashed 1px #999;">
    <input name="editSubbed" type="hidden" id="editSubbed" value="true" />
<div id="btnCancel" style="float:left; padding:5px; border:solid 1px #F00; background-color:#FFDDDE; font-size:12px;"><a href="user_dashboard.php">CANCEL</a></div>
<div id="btnSub" style="float:right"><input type="button" value="Submit" /></div>
<div style="clear:both" /></div>
<!-- end of remaining info -->

</form>
</div>

</div>
</body>
</html>