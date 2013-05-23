<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;

// ======================== LOG USER IN AND SET A SESSION FOR THEIR ID ===================
if(!isset($_SESSION['cbtAdminID'])){
	header('Location: edAdmin.php');	
} else {
	$cbtAdminID = $_SESSION['cbtAdminID'];
}
// =======================================================================================
if(isset($_GET['eMode'])){
	$_SESSION['admin_EdID'] = $_GET['eMode'];
} else {
	if(isset($_SESSION['admin_EdID'])){
		unset($_SESSION['admin_EdID']);	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AmbulatoryPractice - Test Creator</title>
<script type="text/javascript" src="../jquery.min.js"></script>
<script type="text/javascript">
function init(){
	// ========== hide not immediately needed ===
	$('#newTopicAreaContainer').hide();
	if(editMode=='true'){
		$('#testCreatorFrmContainer').hide();
		getEdInfo();
	}
	//==========================================
	setUpEvents();
	grabAllTopics();
	var tempObj = new Object();
	tempObj['noVal'] = 'true';
	$.post('processors/qaCreator.php',tempObj,qaAddedResult,"json");
}
function getEdInfo(){
	var tempObj = new Object();
	tempObj['noVal'] = 'true';
	$.post('processors/educationCreator.php',tempObj,edSubResult,"json");	
}
function setUpEvents(){
	$('#addNewTopicTxt').click(function(){
		$('#newTopicAreaContainer').slideDown("normal");
		$('#newTopicArea').attr('value','');
	})
	$('#subNewTopicBtn').click(function(){
		$('#newTopicAreaContainer').slideUp("normal");
		var tempObj = new Object();
		tempObj['addTA']='true';
		tempObj['newTopic']=$('#newTopicArea').val();
		$.post('processors/topicAreas.php',tempObj,topicAreaAdded,"json");
	})
	$('#subNewTestBtn').click(function(){
		grabAllEdData();
	})
	
	$('#createNewAnsObjBtn').click(function(){
		createNewAnsObj(1);
	})
	
	$('#submitNewQADataBtn').click(function(){
		var questionObj = new Object();
		questionObj['question'] = $('#qTxtData').val();
		questionObj['qExplanation'] = $('#qTxtExplanation').val();
		var answerArry = new Array();
		var answerCorrectArry = new Array();
		$('[id^=ansObj_]').each(function(){
			var tempID = $(this).attr('theID');
			answerArry.push($('#ansTxt_'+tempID).val());
			answerCorrectArry.push($('#ansRB_'+tempID).attr("correctAns"));
		})
		
		questionObj['ansArry'] = answerArry.toString();
		questionObj['answerCorrectArry'] = answerCorrectArry.toString();
		questionObj['insertNewQ'] = 'true';
		$.post('processors/qaCreator.php',questionObj,qaAddedResult,"json");
		clearQAForm();
	})
	// =================== FOR EDIING MAIN EDUCATION OBJ ======================
	$('#newEdObjContainer').dblclick(function(){
		$('#newEdObjContainer').empty();
		if(edInEditMode=='false'){
			edInEditMode='true';
			var tempTestTitle = educationObj.et_title;
			var tempTestDescript = educationObj.et_description;
			var tempTestTitleEd = '<div id="testEdTitleContainer">' +
										'<input id="testEdTitle" ' +
										 	'type="text" ' +
										 	'class="testEdTitleClss" ' +
										 	'value="' + tempTestTitle + '" ' +
								  		'/>' +
								  '</div>';
			var tempTestDescriptEd = '<div id="testDescriptEdContainer">' +
												'<textarea id="testDecriptEd" ' +
													'class="testDescriptEdClss">' +
													tempTestDescript + 
										'</textarea>' +
									'</div>';			
			$('#newEdObjContainer').append(tempTestTitleEd);
			$('#newEdObjContainer').append(tempTestDescriptEd);					  
			var taDD = createTopicAreaDD();
			$('#newEdObjContainer').append(taDD);
			$('#editTANewTAWrapper').hide();
			$('#editAddNewTA').click(function(){
				$('#editTANewTAWrapper').show();
			})
			var tempAreaObj = new Object();
			tempAreaObj['getTopics'] = 'true';
			$.post('processors/topicAreas.php',tempAreaObj,populateTAEditSelect,"json");
			var editEdControlBar = '<div id="editEdControlBar" ' +
										'class="editEdControlBarClss">' +
										'<div id="subEdEditBtn" ' +
											 'class="subEdEditBtnClss">' +
											 'Submit Edit' +
										'</div>' +
										'<div style="clear:both"></div>' +
									'</div>';
			$('#newEdObjContainer').append(editEdControlBar);
			setupEdEditEvents();
		}
	})
}
function createTopicAreaDD(){
	var tempSelect = '<div id="editTopicAreaContainer">' +
						'<select id="editTopicAreaSelect" ' +
							'name="editTopicAreaSelect" ' +
							'class="editTopicAreaSelectClss">' +
						'</select>' +
						'<div id="editAddNewTA" ' +
							 'class="editAddNewTAClss">' +
							 ' add new topic area ' +
					    '</div>' +
						'<div id="editTANewTAWrapper">' +
							'<input id="editNewTA" ' +
									'type="text" ' +
									'class="editNewTAClss"/>' +
							'<div id="editNewTASubBtn" ' +
								  'class="editNewTASubBtnClss">' +
								  'Submit New Topic Area' +
							'</div>' +
							'<div style="clear:both"></div>' +
						'</div>' +
						'<div style="clear:both"></div>' +
					'</div>';
	return tempSelect;
}
function populateTAEditSelect(result){
	var counter=0;
	$(result).each(function(){
		if(educationObj.et_topic_area_link == result[counter].tta_id){
			var tempSelected = 'selected="selected"';
		} else {
			var tempSelected = '';
		}
		var tempOption = '<option id="editTA_' + result[counter].tta_id + '" ' +
								 tempSelected + ' ' +
								 'value=' + result[counter].tta_id + '>' +
								 	result[counter].tta_topic + 
						'</option>';
		$('#editTopicAreaSelect').append(tempOption);
		counter+=1;
	})
}
function setupEdEditEvents(){
	$('#editNewTASubBtn').click(function(){
		var tempTA = $('#editNewTA').val();
		var tempObj = new Object();
		tempObj['addTA']='true';
		tempObj['newTopic'] = tempTA;
		$.post('processors/topicAreas.php',tempObj,edTAResult,"json");
	})
	$('#subEdEditBtn').click(function(){
		var editedTitle = $('#testEdTitle').val();
		var editedDescript = $('#testDecriptEd').val();
		var taID = $('#editTopicAreaSelect option:selected').val();
		var tempObj = new Object();
		tempObj['edTitle'] = editedTitle;
		tempObj['edDescript'] = editedDescript;
		tempObj['taID'] = taID;
		$.post('processors/educationUpdater.php',tempObj,edEditedResult,"json");
	})
}
function edEditedResult(result){
	window.location.href="cbt_qa_creator.php?eMode=" + result;	
}
function edTAResult(result){
	newTopicEdID = result;
	var tempObj = new Object();
	tempObj['getTopics']='true';
	$.post('processors/topicAreas.php',tempObj,reloadTAs,"json");
}
function reloadTAs(result){
	$('#editTopicAreaSelect option').remove();
	var counter=0;
	$(result).each(function(){
		if(result[counter].tta_id == newTopicEdID){
			var tempSelected = 'selected="selected"';	
		} else {
			var tempSelected ='';
		}
		var tempOption = '<option value=' + result[counter].tta_id + ' ' +
								 tempSelected + '>' +
								 result[counter].tta_topic +
						 '</option>';
		
		$('#editTopicAreaSelect').append(tempOption);					 
		counter+=1; 
	})
	$('#editTANewTAWrapper').hide();
	$('#editNewTA').val('');
}
// ====================================================

function qaAddedResult(result){
	qaAC = result;
	$('#qaDisplayContainer').empty();
	var resultCounter =0;
	$(result).each(function(){
		var questionObjVO = result[resultCounter].qObj;
		var qaDisplayObj = '<div id="qaDisplayObjContainer_' + questionObjVO.tq_id + '" ' +
								 'class="qaDispObjContClss" ' +
								 'qID="' + questionObjVO.tq_id + '" ' +
								 'layoutIndex="' + resultCounter + '">' +
								 	'<div id="q_' + questionObjVO.tq_id + '" ' +
										  'qID="' + questionObjVO.tq_id + '" ' +
										  'class="qTxtClss" ' +
										  '>' + questionObjVO.tq_question + 
									'</div>' +
									'<div id="ansContainer_' + questionObjVO.tq_id + '" ' +
										  'class="ansContainerClss">' +
									'</div>' +
									'<div id="ansExpl_' + questionObjVO.tq_id + '" ' +
										  'class="ansExplClss">' + questionObjVO.tq_answer +
									'</div>' +
							'</div>';
		$('#qaDisplayContainer').append(qaDisplayObj);
		$('#qaDisplayObjContainer_'+questionObjVO.tq_id).dblclick(function(){
			var tempID = $(this).attr("layoutIndex");
			createEditableObjs(tempID);
		})
		resultCounter+=1;
	})
	
	var qCounter=0;
	$(result).each(function(){
		var ansArryCounter =0;
		$(result[qCounter].ansObjArry).each(function(){
			var ansObjVO = result[qCounter].ansObjArry[ansArryCounter];
			var ansObj = '<div id="ansID_' + ansObjVO.ta_id + '"> ' +
								'<p class="ansTxtClss">' +
									ansObjVO.ta_answer +
								'</p>' +
						 '</div>';
			$('#ansContainer_'+result[qCounter].qObj.tq_id).append(ansObj);
			$('#ansID_'+ansObjVO.ta_id).attr("ansDisplayCorrect",ansObjVO.ta_correct);
			ansArryCounter+=1;
		})
		qCounter+=1;
	})
	$('[ansDisplayCorrect="true"]').addClass("ansCorrClss");
}
/* ================ EDITABLE OBJECTS ======================== */

function createEditableObjs(theIndex){
	if(activeEdit=='false'){
	var tempIndex = qaAC[theIndex].qObj.tq_id;
	$('#qaDisplayObjContainer_'+tempIndex).empty();
	var editObj = '<div id="editObj_' + theIndex + '" class="editObjClss">' +
						'<textarea id="editingQ" qID="' + tempIndex + '">' +
							qaAC[theIndex].qObj.tq_question +
						'</textarea>';
				  '</div>';
	$('#qaDisplayObjContainer_'+tempIndex).append(editObj);
	var editableAnsContainer = '<div id="editAnsContainer" class="editAnsContainerClss"></div>';
	$('#editObj_' + theIndex).append(editableAnsContainer);
	var qExpl = '<div id="editQExpl"> ' +
						'<input type="text" ' +
								'id="editQExplTxt" ' +
								'class="ansExplClss" ' +
								'value="' + qaAC[theIndex].qObj.tq_answer + '" />';
	$('#editObj_' + theIndex).append(qExpl);
								
	var editControlBar = '<div id="editControlBar">' +
								'<div id="subEditBtn" class="editBtns">Submit</div>' +
								'<div id="cancelBtn" class="editBtns">Cancel</div>' +
								'<div id="deleteBtn" class="editBtns">Delete Question</div>' +
								'<div style="clear:both"></div>' +
						  '</div>';
	$('#editObj_' + theIndex).append(editControlBar);
	var ansArryCounter = 0;
	$(qaAC[theIndex].ansObjArry).each(function(){
		var tempAnswer = qaAC[theIndex].ansObjArry[ansArryCounter].ta_answer;
		var tempAnsID = qaAC[theIndex].ansObjArry[ansArryCounter].ta_id;
		var tempAnsCorr = qaAC[theIndex].ansObjArry[ansArryCounter].ta_correct;
		var tempSelected='';
		if(tempAnsCorr == 'true'){
			tempSelected=' checked="checked" ';
		}
		var editableAnsObj = '<div class="editAnsRowClss">' +
								   '<input id="aID_' + tempAnsID + '" ' +
								   		   'aID="' + tempAnsID + '" ' +
										   'type="text" value="' + tempAnswer + '" />' +
								   '<input id="aRBID_' + tempAnsID + '" ' +
								   			'aID="' + tempAnsID + '" ' +
											'name="edRB_' + tempIndex + '" ' +
											'type="radio"' + tempSelected + '/>' +
							 '</div>';
		$('#editAnsContainer').append(editableAnsObj);
		ansArryCounter+=1;
	})
	
	
	} else {
		alert('you can only edit 1 question at a time');	
	}
	activeEdit='true';
	setUpEditBtnEvents();
}
function setUpEditBtnEvents(){
	$('#subEditBtn').click(function(){
		$('#editControlBar').empty();
		var processingTxt = '<p class="processingTxtClss">Processing...</p>';
		$('#editControlBar').append(processingTxt);
		
		var qID = $('#editingQ').attr("qID");
		var editedQText = $('#editingQ').val();
		var qExpl = $('#editQExplTxt').val();
		var ansTxtArry = new Array();
		var rbAnsArry = new Array();
		var aIDArry = new Array();
		
		$('[id^=aID_]').each(function(){
			ansTxtArry.push($(this).val());
			aIDArry.push($(this).attr("aID"));
		})
		$('[id^=aRBID_]').each(function(){
			rbAnsArry.push($(this).attr("checked"));
		})
		
		var editData = new Object();
		editData['qID'] = qID;
		editData['editedQText'] = editedQText;
		editData['qExpl'] = qExpl;
		editData['ansTxtArry'] = ansTxtArry.toString();
		editData['rbAnsArry'] = rbAnsArry.toString();
		editData['aIDArry'] = aIDArry.toString();
		$.post("processors/qaEditor.php",editData,editResult,"json");
	})
	
	$('#deleteBtn').click(function(){
		$('#editControlBar').empty();
		var processingTxt = '<p class="processingTxtClss">Processing...</p>';
		$('#editControlBar').append(processingTxt);
		var qID = $('#editingQ').attr("qID");
		var tempObj = new Object();
		tempObj['delQAndA'] = 'true';
		tempObj['qID'] = qID;
		$.post("processors/delData.php",tempObj,editResult,"json");
		
	})
	
	$('#cancelBtn').click(function(){
		$('#editControlBar').empty();
		var processingTxt = '<p class="processingTxtClss">Processing...</p>';
		$('#editControlBar').append(processingTxt);
		editResult('noVal');
	})
}
function editResult(result){
	activeEdit='false';
	$('#qaDisplayObjContainer_'+result).empty();
	var questionObj = new Object();
	questionObj['noVal']='true';
	$.post('processors/qaCreator.php',questionObj,qaAddedResult,"json");
}
/* ========================================================== */
function grabAllTopics(){
	$('#topicArea option').remove();
	var tempObj = new Object();
	tempObj['getTopics']='true';
	$.post('processors/topicAreas.php',tempObj,topicAreasResult,"json");
}
function topicAreasResult(result){
	var counter = 0;
	var tempOptionDefault = '<option id="ta_choose_default" value="noVal">Choose Topic Area...</option>';
	$('#topicArea').append(tempOptionDefault);
	$(result).each(function(){
		var tempOption = '<option id="ta_' + result[counter].tta_id + '" value=' + result[counter].tta_id + '>' + result[counter].tta_topic + '</option>';
		$('#topicArea').append(tempOption);
		counter +=1;
	})
	if(newTopicAdded == 'true'){
		$('#ta_'+newTopicID).attr('selected','selected');	
	}
}
function topicAreaAdded(result){
	newTopicAdded = 'true';
	newTopicID = result;
	grabAllTopics();
}
function grabAllEdData(){
	var edDataObj = new Object();
	edDataObj['addNewEd']='564';
	edDataObj['educationTitle']= $('#testTitle').val();
	edDataObj['areaLink'] = $('#topicArea option:selected').val();
	edDataObj['testDescription'] = $('#testDescription').val();
	$.post('processors/educationCreator.php',edDataObj,edSubResult,"json");
}
function edSubResult(result){
	educationObj = result;
	$('#testCreatorFrmContainer').hide();
	displayNewEdObj();
}
function displayNewEdObj(){
	$('#newTestTitle').append('<p class="newEdTitleClss">Test Title: ' + educationObj.et_title + '</p>');
	$('#newTopicAreaDisplay').append('<p class="newTopicAreaClss">Topic Area: ' + educationObj.topicArea + '</p>');
	$('#newTestDescription').append('<p class="newDescriptionClss">Description: ' + educationObj.et_description + '</p>');
	createNewAnsObj(4);
}
function clearQAForm(){
	$('#qTxtData').val("");
	$('#qTxtExplanation').val("");
	$('#newAnsContainer').empty();
	createNewAnsObj(4);	
}
function createNewAnsObj(numObjs){
	for(var i=0; i<numObjs; i++){
	var ansObj = '<div id="ansObj_' + ansCounter + '" theID=' + ansCounter + ' class="ansObjClss">' +
				 '<div class="aLabel">Answer:</div>' +
				 '<input type="text" id="ansTxt_' + ansCounter + '" class="aTxtClss" />' +
				 '<input type="radio" id="ansRB_' + ansCounter + '" class="aRBCls" name="ansRB" correctAns="false"/>' +
				 '<div style="clear:both"></div>';
				 '</div>';
	$('#newAnsContainer').append(ansObj);
	$('#ansObj_'+ansCounter).hide();
	$('#ansObj_'+ansCounter).slideDown("normal");
	$('#ansRB_'+ansCounter).click(function(){
		$('[id^=ansRB_]').each(function(){
			$(this).attr("correctAns","false");
		})
		$(this).attr("correctAns","true");
	})
	ansCounter+=1;
	}
}
// ==================== HOLD TILL NEEDED - NEXT RELEASE =============
function createNewAnsObjEditMode(numObjs){
	for(var i=0; i<numObjs; i++){
	var ansObj = '<div id="ansObj_' + ansCounter + '" theID=' + ansCounter + ' class="ansObjClss">' +
				 '<div class="aLabel">Answer:</div>' +
				 '<input type="text" id="ansTxt_' + ansCounter + '" class="aTxtClss" />' +
				 '<input type="radio" id="ansRB_' + ansCounter + '" class="aRBCls" name="ansRB" correctAns="false"/>' +
				 '<div style="clear:both"></div>';
				 '</div>';
	$('#newAnsContainer').append(ansObj);
	$('#ansObj_'+ansCounter).hide();
	$('#ansObj_'+ansCounter).slideDown("normal");
	$('#ansRB_'+ansCounter).click(function(){
		$('[id^=ansRB_]').each(function(){
			$(this).attr("correctAns","false");
		})
		$(this).attr("correctAns","true");
	})
	ansCounter+=1;
	}
}
// ==================================================================
<?php
	if(isset($_SESSION['admin_EdID'])){
		echo 'var editMode="true";';
	} else {
		echo 'var editMode="false";';
	}
?>
var newTopicAdded='false';
var newTopicID=0;
var newTopicEdID=0;
var educationObj = new Object();
var newAreaTopic='';
var ansCounter=0;
var qaAC = new Object();
var activeEdit = 'false';
var edInEditMode='false';
var educationID = 0;
$("document").ready(init);
</script>
<style type="text/css">
p{
	margin:0px;	
}
#mainPageWrapper{
	width:950px;
	border:solid 1px #999;
	margin:auto;
}
#userLoginArea{
	width:100%;
	background-color:#F2F2F2;
}
#testCreatorFrmContainer{
	width:100%;
	border:solid 1px #CCC;
}
#testCreatorFrm{
	width:100%;
}
.frmDataContainer{
	width:100%;
	border:solid 1px #999;
}
.formLabel{
	float:left;
	width:120px;
}
.textInputs{
	float:left;
	width:200px;
}
#addNewTopicTxt{
	float:left;
	cursor:pointer;
	width:150px;
	padding-left:10px;
	padding-right:10px;	
}
#newTopicAreaContainer{
	float:left;
	width:250px;
	padding-left:10px;
	padding-right:10px;
}
#newTopicArea{
	float:left;
	width:150px;
}
#subNewTopicBtn{
	float:left;
	width:80px;
	cursor:pointer;
}
.newObj{
		
}
.subNewTestBtnClss{
	width:75px;
	height:35px;
	border:solid 1px black;
	float:right;
	cursor:pointer;
}

#ansCreatorContainer{
	background-color:#CCC;
}

.ansObjClss{
	width:100%;
}
.aLabel{
	width:125px;
	float:left;
	background-color:yellow;	
}
.aTxtClss{
	width:250px;
	border:solid 1px black;
	float:left;	
}
.aRBClss{
	width:35px;
	float:left;	
}
#createNewAnsObjBtn{
	float:left;
	clear:both;
	width:250px;
	border:solid 1px black;
	cursor:pointer;	
}
#submitNewQADataBtn{
	float:right;
	width:250px;
	border:solid 1px black;
	cursor:pointer;
}
.qLabel{
	float:left;
	width:75px;
	border:solid 1px #999;
}
.qTxtClss{
	float:left;
	width:500px;	
}

#newAnsContainer{
	width:100%;	
}
#ansExplanationContainer{
	width:100%;	
}
.expLabel{
	float:left;
	width:150px;	
}
.qTxtExpClss{
	float:left;
	width:500px;
	border:solid 1px black;
	margin-top:6px;
}
/* STYLE FOR DISPLAY CONTAINERS AND OBJECTS */
.qaDispObjContClss{
	border:solid 3px blue;
	margin-top:10px;
	margin-bottom:10px;
}
.ansExplClss{
	margin-top:5px;
	background-color:#E1E1E1;
}
.qTxtClss{
	background-color:#333;
	width:100%;
	color:#FFF;
}
.ansContainerClss{
	background-color:#FFF;
}
#editControlBar{
	width:100%;
	border:solid 1px black;	
}
.editBtns{
	width:145px;
	border:solid 1px black;
	float:right;
	text-align:center;
}
.ansCorrClss{
	background-color:#D9F9C1;
	border:solid 1px #090;
}
#createNewAnsObjBtn{
	width:225px;
	border:solid 1px black;	
	cursor:pointer;
}
#addAnsChoiceContainer{
	width:100%;
	border:solid 1px #333;
}
.processingTxtClss{
	font-style:italic;
	text-align:center;
	font-size:18px;	
}
/* ============== STYLES FOR EDUCATION OBJ EDITING =========== */
.editTopicAreaSelectClss{
	float:left;
	width:250px;	
}
.editAddNewTAClss{
	float:left;
	width:200px;
	text-align:center;
	cursor:pointer;	
}
.editNewTAClss{
	float:left;
	width:200px;	
}
.editNewTASubBtnClss{
	float:left;
	width:180px;
	border:solid 1px black;
	cursor:pointer;
	text-align:center;
}
#editTANewTAWrapper{
	float:left;
	width:400px;
	border:solid 1px red;
	padding:4px;
}
.subEdEditBtnClss{
	width:150px;
	border:solid 1px black;
	text-align:center;
	float:right;
	cursor:pointer;
}
#testEdTitleContainer{
	width:850px;	
}
#testDescriptEdContainer{
	width:850px;	
}
/* =========================================================== */
</style>
</head>

<body>
<div id="mainPageWrapper">
<div id="userLoginArea"><a href="edAdminCPanel.php">BACK TO ADMINISTRATOR HOME PAGE</a></div>

<div id="testCreatorFrmContainer">
	<form id="testCreatorFrm" action="cbt_qa_creator.php" method="post">
    	<div class="frmDataContainer">
        	<p class="formLabel">Test Title:</p>
            <input id="testTitle" type="text" class="textInputs"/>
            <div style="clear:both"></div>
        </div>
    	<div class="frmDataContainer">
        	<p class="formLabel">Topic Area:</p>
            <select id="topicArea" name="topicArea" class="textInputs"></select>
            <div id="addNewTopicTxt"> + add new topic area</div>
            <div id="newTopicAreaContainer">
            	<input type="text" id="newTopicArea" class="newObj" />
            	<div id="subNewTopicBtn">Submit</div>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="frmDataContainer">
       	  <p class="formLabel">Test Description:</p>
            <textarea class="textInputs" id="testDescription" style="width:500px;"></textarea>
            <div style="clear:both"></div>
        </div>
        <div class="frmDataContainer">
        	<div class="subNewTestBtnClss" id="subNewTestBtn">Submit</div>
            <div style="clear:both"></div>
        </div>
    
    </form>
</div>

<div id="newEdObjContainer">
	<div id="newTestTitle"></div>
    <div id="newTopicAreaDisplay"></div>
    <div id="newTestDescription"></div>
</div>

<div id="ansCreatorContainer">
	<div id="newQuestionContainer">
   	  <div class="qLabel">Question:</div>
        <textarea id="qTxtData" class="qTxtClss"></textarea>
        <div style="clear:both"></div>
    </div>
    <div id="newAnsContainer"></div>
    <div id="addAnsChoiceContainer">
    	<div id="createNewAnsObjBtn">Add a new answer choice</div>
        <div style="clear:both"></div>
    </div>
    <div id="ansExplanationContainer">
    	<div class="expLabel">Answer Explanation:</div>
        <textarea id="qTxtExplanation" class="qTxtExpClss"></textarea>
        <div style="clear:both"></div>
    </div>
</div>
<div id="ansControllerBar">
    <div id="submitNewQADataBtn">Submit Question</div>
    <div style="clear:both"></div>
</div>

<div id="qaDisplayContainer"></div>
<div id="testingOutput">
	
</div>

</div>
</body>
</html>