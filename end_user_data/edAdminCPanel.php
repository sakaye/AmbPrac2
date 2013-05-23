<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
if(!isset($_SESSION['cbtAdminID'])){
	header('Location: edAdmin.php');	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
		<style type="text/css" title="currentStyle">
			@import "css/demo_page.css";
			@import "css/demo_table.css";
		</style>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jscr/learnerDetails.js"></script>
<script type="text/javascript" src="jscr/componentCreator.js"></script>
<script type="text/javascript" src="jscr/jquery.dataTables.min.js"></script>
<script type="text/javascript">
function init(){
	$('#newTestBtn').click(function(){
		window.location.href="cbt_qa_creator.php";	
	})
	
	var tempObj = new Object();
	tempObj['noVal']='true';
	$.post("processors/grabAdminData.php",tempObj,adminLoadResult,"json");
}

function adminLoadResult(result){
	var tempObj = new Object();
	tempObj['noVal']='true';
	$.post("processors/grabTestStatData.php",tempObj,testsLoadResult,"json");
}
function testsLoadResult(result){
	testDataAC = result;
	var arryCounter=0;
	$(result).each(function(){
		var tempEdID = result[arryCounter].testID;
		createTestDataObj(tempEdID);
		// ========================= APPEND ALL TEST DATA TO NEWLY CREATED OBJS ==================
		$('#testTitle_' + tempEdID).append(result[arryCounter].testTitle);
		$('#totalQs_' + tempEdID).append('Total Number of Questions: ' + result[arryCounter].numQuestions);
		$('#tDescript_' + tempEdID).append('Test Description: ' + result[arryCounter].testDescription);
		$('#tArea_' + tempEdID).append('Topic Area: ' + result[arryCounter].topicArea);
		$('#tCompCount_' + tempEdID).append('Total Learner Completions = ' + result[arryCounter].testCompletions);
		$('#lastComp_' + tempEdID).append('Last Completion Date = ' + result[arryCounter].lastCompletion);
		// =======================================================================================
		if(result[arryCounter].liveOnWeb == 'false'){
			createEditTestBtn(tempEdID);
		}
		
		createTestScoreDetailsBtn(tempEdID);
		
		arryCounter+=1;
	})
}
function createTestDataObj(theTestID){
	var testDataObj = '<div id="testDataContainer_' + theTestID + '" ' +
							'edID="' + theTestID  + '" ' +
							'class="testDataRowClss">' +
								'<div id="testTitleContainer_' + theTestID + '" ' +
								'class="testTitleContainerClss">' +
									'<div id="testTitle_' + theTestID + '" ' +
										  'class="testTitleClss">' +
							        '</div>' +
								'</div>' +
								'<div id="tDescript_' + theTestID + '" ' +
									  'class="tDescriptClss">' +
								'</div>' +
								'<div id="tArea_' + theTestID + '" ' +
								 	  'class="tAreaClss">' +
								'</div>' +
								'<div id="totalQs_' + theTestID + '" ' +
									  'class="totalQCountClss">' +
								'</div>' +
								'<div id="tCompCount_' + theTestID + '" ' +
									  'class="compCountClss">' +
								'</div>' +
								'<div id="lastComp_' + theTestID + '" ' +
								      'class="lastCompClss">' +
								'</div>' +
								'<div style="clear:both"></div>' +
								'<table id="testLinks_' + theTestID + '" ' +
									  'class="testLinksClss">' +
									  '<tr><td>Stand-Alone Test Link:</td><td> http://ambulatorypractice.org/end_user_data/index.php?educationID=' + theTestID + '&pre_post=sa</td>' +
									  '<tr><td>Pre Test Link:</td><td> http://ambulatorypractice.org/end_user_data/index.php?educationID=' + theTestID + '&pre_post=pre</td>' +
									  '<tr><td>Post Test Link:</td><td> http://ambulatorypractice.org/end_user_data/index.php?educationID=' + theTestID + '&pre_post=post</td>' +
								'</table>' +
								'<div id="detailedStatsControlBar_' + theTestID + '" ' +
									  'class="detailedStatsControlBarClss">' +
								'</div>' +
								 '<div id="avgTestScore_' + theTestID + '" ' +
									  		'class="avgTestScoreClss">' +
								'</div>' +
								'<div id="detailsContainerMain_' + theTestID + '" ' +
									  'class="detailsContainerMainClss">' +
								'</div>' +
						'</div>';
	$('#testObjsContainer').append(testDataObj);
	$('#detailsContainerMain_'+theTestID).hide();
	
}
function createEditTestBtn(theIndex){
	var tempEditTestBtn = '<div id="testEditBtn_' + theIndex + '" ' +
								'class="editTestBtnClss" ' +
								'edID="' + theIndex + '">Edit Test' +
						  '</div><div id="edTestBtnClr_' + theIndex + '" style="clear:both"></div>';
	$('#testTitleContainer_'+theIndex).append(tempEditTestBtn);
	$('#testEditBtn_'+theIndex).click(function(){
		$(this).remove();
		$('#edTestBtnClr_' + theIndex).remove();
		$('#testTitleContainer_'+theIndex).append(getProcessingMsg());
		window.location.href="cbt_qa_creator.php?eMode=" + theIndex;
	})
}
function createTestScoreDetailsBtn(theIndex){
	var showScoreStatsBtn = '<div id="minimizeDetailsBtn_' + theIndex + '" ' +
								  'class="minimizeDetailsBtnClss">' +
								  'Minimize Details' +
							'</div>' +
							'<div id="showLearnerDetailsBtn_' + theIndex + '" ' +
								 'class="showLearnerDetailsBtnClss">' +
								 'Show Detailed Learner Stats' +
							'</div>' +
							'<div id="showScoreStatsBtn_' + theIndex + '" ' +
								 'class="showScoreStatsBtnClss">' +
								 'Show Detailed Score Stats' +
							'</div>' +
							'<div style="clear:both"></div>';
	$('#detailedStatsControlBar_'+theIndex).append(showScoreStatsBtn);
	$('#showScoreStatsBtn_'+theIndex).click(function(){
		$('#detailsContainerMain_'+theIndex).empty();
		$('#avgTestScore_'+theIndex).empty();
		var tempObj = new Object();
		tempObj['edID']=theIndex;
		tempObj['noVal']='true';
		$.post('processors/grabDetailedQStats.php',tempObj,scoreDetailsLoaded,"json");
	})
	
	$('#minimizeDetailsBtn_'+theIndex).click(function(){
		hideDetailsContainer(theIndex);
	})
	
	$('#showLearnerDetailsBtn_'+ theIndex).click(function(){
		var tempObj = new Object();
		tempObj['edID']=Number(theIndex);
		$.post('processors/getEdLearners.php',tempObj,learnerDetailsLoaded,"json");
	})
}
function learnerDetailsLoaded(result){
	var tempEdID = result[0].eue_ed_link;
	$('#detailsContainerMain_' + tempEdID).empty();
	$('#detailsContainerMain_' + tempEdID).hide();
	var tempEdTitle = $('#testTitle_'+ tempEdID).text();
	var learnerDetTbl = createLearnerDetailsTable(tempEdID,tempEdTitle); // in linked js doc componentCreator
	$('#detailsContainerMain_' + tempEdID).append(learnerDetTbl);
	var counter=0;
	$(result).each(function(){
		var learnerTR = createLearnerTR(result[counter],tempEdTitle);
		$('#learnerDetailsTbl_' + tempEdID).append(learnerTR);
		counter +=1;
	})

	$('#learnerDetailsTbl_'+tempEdID).dataTable({
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
		"bAutoWidth": false,
		"bJQueryUI": false,
		"asStripClasses":['tr1','tr2']
	})
	$('#detailsContainerMain_' + tempEdID).slideDown("normal");
}
function scoreDetailsLoaded(result){
	var counter=0;
	var correctCount = 0;
	var confCount = 0;
	var totalCount = 0;
	$(result).each(function(){
		var tableLblsExist = 'false';
		var tempQID = result[counter].tq_id;
		var tempQADisplayContainer = '<div id="qaDisplayCont_' + tempQID + '" ' +
										  'class="qaDisplayContClss">' +
									'</div>';
		$('#detailsContainerMain_'+result[counter].tq_ed_link).append(tempQADisplayContainer);
		
		//var tempQDisplayLbl = '<div class="qDisplayLblClss">Question:</div>';
	
		var tempQDisplay = '<div id="qDisplay_' + tempQID + '" ' +
								'class="qDisplayClss"><p>' +
								result[counter].tq_question +
							'</p></div><div class="clear:both"></div>';
							
		var tempTableDisplay = '<table id="ansStatTbl_' + tempQID + '" ' +
									'class="ansStatTblClss">' +
							   '</table>' +
							   '<div style="clear:both"></div>';
		
		//$('#qaDisplayCont_'+result[counter].tq_id).append(tempQDisplayLbl);				
		$('#qaDisplayCont_'+result[counter].tq_id).append(tempQDisplay);
		$('#qaDisplayCont_'+result[counter].tq_id).append(tempTableDisplay);
			
			var subCounter = 0;
			$(result[counter].scoresVOArry).each(function(){
				var tempScoresVO = result[counter].scoresVOArry[subCounter];
				
				if(tableLblsExist=='false'){
				var tempTblLbls = '<tr class="statsTR">' +
				                      '<td class="testTypeTD">Test Type</td>' +
									  '<td class="numCorrTD"># Correct</td>' +
									  '<td class="numIncorrTD"># Incorrect</td>' +
									  '<td class="confLvlTD">Confidence Level</td>' +
								 '</tr>';
					$('#ansStatTbl_'+tempQID).append(tempTblLbls);
				}
				tableLblsExist = 'true';
				
				var tempTR = createDetailedScoreTR(tempScoresVO);
				$('#ansStatTbl_'+tempQID).append(tempTR);
				
				// ====================== AVERAGE THE TEST SCORES =================
				
				totalCount += Number(tempScoresVO.totalCorrect)+Number(tempScoresVO.totalIncorrect);
				correctCount += Number(tempScoresVO.totalCorrect);
				
				confCount += Number(tempScoresVO.confCount);
				
				// ================================================================
				subCounter+=1;
			})
		counter+=1;
	})
	var finalAvgScore = Math.round((Number(correctCount)/Number(totalCount)*100));
	var finalConfScore = Math.round((Number(confCount)/Number(totalCount)*100));
	var finalAvgScoreMsg = 'Average Test Score = ' + finalAvgScore + '% || Average Confidence Level = ' + finalConfScore + '%';
	$('#avgTestScore_' + result[0].tq_ed_link).append(finalAvgScoreMsg);
	
	$('#detailsContainerMain_'+result[0].tq_ed_link).slideDown("normal",function(){
		
	});
	
}
function createDetailedScoreTR(tempScoresVO){
	var tempTR='';
	if(tempScoresVO.testType=="pre"){
		var tempTotal = Number(tempScoresVO.totalCorrect) + Number(tempScoresVO.totalIncorrect);
		var percCorrect = (Number(tempScoresVO.totalCorrect)/Number(tempTotal)) * 100;
		percCorrect = Math.round(percCorrect);
		var percIncorrect = (Number(tempScoresVO.totalIncorrect)/Number(tempTotal)) * 100;
		percIncorrect = Math.round(percIncorrect);
		var percConf = (Number(tempScoresVO.confCount)/Number(tempTotal))*100;
		percConf = Math.round(percConf);
		tempTR = '<tr class="preTestClss"><td>Pre Test</td>' +
						 '<td>' + tempScoresVO.totalCorrect + ' of ' + tempTotal +' = <span class="percentTxtCorr">'+ percCorrect +'%</span></td>' +
						 '<td>' + tempScoresVO.totalIncorrect + ' of ' + tempTotal +' = <span class="percentTxtIncorr">'+ percIncorrect  +'%</span></td>' +
						 '<td class="percentTxt">' + percConf + '%</td>' +
					'</tr>';
	}
	else if(tempScoresVO.testType=="post"){
		var tempTotal = Number(tempScoresVO.totalCorrect) + Number(tempScoresVO.totalIncorrect);
		var percCorrect = (Number(tempScoresVO.totalCorrect)/Number(tempTotal)) * 100;
		percCorrect = Math.round(percCorrect);
		var percIncorrect = (Number(tempScoresVO.totalIncorrect)/Number(tempTotal)) * 100;
		percIncorrect = Math.round(percIncorrect);
		var percConf = (Number(tempScoresVO.confCount)/Number(tempTotal))*100;
		percConf = Math.round(percConf);
		tempTR = '<tr class="postTestClss"><td>Post Test</td>' +
						 '<td>' + tempScoresVO.totalCorrect + ' of ' + tempTotal +' = <span class="percentTxtCorr">'+ percCorrect +'%</span></td>' +
						 '<td>' + tempScoresVO.totalIncorrect + ' of ' + tempTotal +' = <span class="percentTxtIncorr">' + percIncorrect  +'%</span></td>' +
						 '<td class="percentTxt">' + percConf + '%</td>' +
					'</tr>';
	}
	else if(tempScoresVO.testType=="sa"){
		var tempTotal = Number(tempScoresVO.totalCorrect) + Number(tempScoresVO.totalIncorrect);
		var percCorrect = (Number(tempScoresVO.totalCorrect)/Number(tempTotal)) * 100;
		percCorrect = Math.round(percCorrect);
		var percIncorrect = (Number(tempScoresVO.totalIncorrect)/Number(tempTotal)) * 100;
		percIncorrect = Math.round(percIncorrect);
		var percConf = (Number(tempScoresVO.confCount)/Number(tempTotal))*100;
		percConf = Math.round(percConf);
		tempTR = '<tr class="standAloneClss"><td>Stand-Alone</td>' +
						 '<td>' + tempScoresVO.totalCorrect + ' of ' + tempTotal +' = <span class="percentTxtCorr">'+ percCorrect +'%</span></td>' +
						 '<td>' + tempScoresVO.totalIncorrect + ' of ' + tempTotal +' = <span class="percentTxtIncorr">' + percIncorrect  +'%</span></td>' +
						 '<td class="percentTxt">' + percConf + '%</td>' +
					'</tr>';
	}
	return tempTR;
}
function showDetailsContainer(edID){
	$('#detailsContainerMain_'+edID).slideDown("normal");
}
function hideDetailsContainer(edID){
	$('#detailsContainerMain_'+edID).slideUp("normal",function(){
		$('#detailsContainerMain_'+edID).empty();
		$('#avgTestScore_' +edID).empty();
	})
}

function getProcessingMsg(){
	var procMessage = '<div class="processingMsgClss">processing...</div>' +
					  '<div style="clear:both"></div>';
	return procMessage;	
}
var testDataAC = new Object();

$("document").ready(init);

</script>
<style type="text/css">
body{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
}
#mainPageWrapper{
	width:950px;
	margin:auto;
	border:solid 1px #CCC;
	background-color:#6f8c9b;
}
#testObjsContainer{
	width:700px;
	border:solid 1px red;
	float:right;	
}
.testDataRowClss{
	margin-top:25px;
	background-image:url(images/adminCPanel_edBG.jpg);
	background-repeat:no-repeat;
	background-position:top;
	border:solid 1px black;
	background-color:#E8E8E8;
}
.editTestBtnClss{
	width:75px;
	border:solid 1px black;
	cursor:pointer;
	text-align:center;
	float:right;
}
#subNav{
	float:left;
	width:240px;
	border:solid 1px black;	
}
.testTitleClss{
	width:480px;
	font-size:16px;
	font-weight:bold;
	float:left;
	padding-bottom:10px;
	padding-top:4px;
	color:#F0F0F0;
}
.testTitleContainerClss{
	background-image:url(images/adminCPanel_titleBar.jpg);
	background-repeat:no-repeat;
	background-position:bottom;
	padding-left:120px;
	padding-top:6px;
	border:#CCC;
}
.processingMsgClss{
	font-style:italic;
	float:right;
	width:120px;
	border:solid 1px red;
	padding-left:5px;
	padding-right:5px;
	text-align:center;
}
.detailedStatsControlBarClss{
	border:solid 2px green;	
}
.detailsContainerMainClss{
	background-color:#C3C3C3;
}
.ansStatTblClss{
	width:600px;
	border:solid 1px black;
	float:right;	
	background-color:#FFF;
	font-size:12px;
}
.ansStatTblClss td{
	border:solid 1px #CCC;
	padding:0px;	
}
.qaDisplayContClss{
	border:solid 1px #666;
	margin-bottom:4px;	
	background-color:#999;
	padding-top:4px;
	padding-bottom:4px;
}
.statsTR{
	color:#FFF;
	background-color:#666;	
}
td{
	text-align:center;	
}
.qDisplayClss{
	background-color:#333;
	border:solid 1px #999;
	width:598px;
	float:right;
}
.qDisplayLblClss{
	float:left;
	width:80px;
	border:solid 1px black;
	background-color:#F90;
	text-align:center;
	padding:4px;
}
.qDisplayClss p{
	font-size:14px;
	color:#F3F3F3;
	margin:0px;
	padding:4px;
}
.testLinksClss{
	border:solid 1px #666;
	width:700px;
	font-size:12px;
}
.testLinksClss td{
	text-align:left;
	border-bottom:solid 1px #999;	
}
.procTxtClss{
	text-align:center;
	color:#03C;
	font-style:italic;	
}
.showScoreStatsBtnClss{
	float:right;
	border:solid 1px black;
}
.showLearnerDetailsBtnClss{
	float:right;
	border:solid 1px black;	
}
.minimizeDetailsBtnClss{
	float:right;
	border:solid 1px black;
}
.avgTestScoreClss{
 text-align:center;
 font-weight:bold;
 font-size:16px;
}
.standAloneClss{
	background-color:#FBFBFB;
}
.preTestClss{
	background-color:#F2F2F2;
}
.postTestClss{
	background-color:#E0E0E0;	
}
.learnerDetailsTblClss{
	font-size:12px;
	border:solid 1px #999;	
	background-color:#FFF;
}
.learnerDetailsTblClss th{
	cursor:pointer;
	/*background-color:#036;*/
	color:#FFF;	
}
.learnerDetailsTblClss thead{
	background-image:url(images/tableHeaderBG.jpg);
	background-repeat:no-repeat;
	background-position:0px -5px;
}
.learnerDetailsTblClss td{
	border:solid 1px #E6E6E6;	
}
.tr1{
	background-color:#DBEEFD;
}
.tr2{
	background-color:#FBFBFB;
}
.percentTxt{
	color:#039;
	font-weight:bold;
}
.percentTxtCorr{
	color:#090;
	font-weight:bold;
}
.percentTxtIncorr{
	color:#F00;
	font-weight:bold;
}
.testTypeTD{
	background-image:url(images/tblLbl_testType.jpg);
	background-repeat:repeat-x;
	background-position:0px -5px;
}
.numCorrTD{
	background-image:url(images/tblLbl_correct.jpg);
	background-repeat:repeat-x;
	background-position:0px -5px;
}
.numIncorrTD{
	background-image:url(images/tblLbl_incorrect.jpg);
	background-repeat:repeat-x;
	background-position:0px -5px;
}
.confLvlTD{
	background-image:url(images/tblLbl_confLvl.jpg);
	background-repeat:repeat-x;
	background-position:0px -5px;
}
</style>

</head>

<body>
<div id="mainPageWrapper">
	<div id="mainControlBar">
    	NAV GOES HERE
    </div>
    <div id="subNav">
    	<div id="newTestBtn">Create a New Test</div>
    </div>
	<div id="testObjsContainer"></div>
    <div style="clear:both"></div>
</div>
</body>
</html>