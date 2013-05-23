<?php
session_start();
require('dbConnect.php');
include("footerInfo.php"); ?>


<?php
// ---------- ED COUNTER ----------------------------------
if(isset($_GET['lid'])){
	$getEdCount = 'SELECT * FROM education_hit_count WHERE ehc_ed_link = ' . $_GET['lid'] . ' LIMIT 1';
	$getEdCountResult = $db->query($getEdCount);
	$tempCount = 0;
	while($row = $getEdCountResult->fetch_assoc()){
		$tempCount = $row['ehc_count'];
	}
	$tempCount +=1;
	if($getEdCountResult->num_rows ==0){
		$addEdQ = 'INSERT INTO education_hit_count (ehc_ed_link,ehc_count) VALUES (' . $_GET['lid'] . ',0)';
		$addEdQResult = $db->query($addEdQ);	
	}
	
	$subCountQ = 'UPDATE education_hit_count SET ehc_count = ' . $tempCount . ' WHERE ehc_ed_link = ' . $_GET['lid'] . ' LIMIT 1';
	$subCountQResult = $db->query($subCountQ);
}
// ---------------- END OF ED COUNTER -----------------------
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<link href="css/apStyle1.css" rel="stylesheet" type="text/css" />
<script src="swfobject_modified.js" type="text/javascript"></script>

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="creators/edObjCreator.js"></script>
<script type="text/javascript">
function init(){
	$.post('cbtGrabber.php','',edObjsLoaded,"json");
}

function edObjsLoaded(result){
	var counter=0;
	var totalTestCount=0;
	$(result).each(function(){
		tempEdObj = createEdObj(result[counter]);
		$('#cbtObjWrapper').append(tempEdObj);
		if(result[counter].edVO.daycount<8){
			$('#newTest_'+result[counter].edVO.et_id).addClass('newTestClss');
		} else {
			$('#newTest_'+result[counter].edVO.et_id).addClass('notNewTestClss');
		}
		totalTestCount += Number(result[counter].completionsObj.cbtc_comp_count);
		
		$('#viewTDetailsBtn_'+result[counter].edVO.et_id).mouseover(function(){
			$(this).addClass('tDetailsBtnContainerHiLiteClss');
		})
		$('#viewTDetailsBtn_'+result[counter].edVO.et_id).mouseout(function(){
			$(this).removeClass('tDetailsBtnContainerHiLiteClss');
		})
		
		$('#viewTDetailsBtn_'+result[counter].edVO.et_id).click(function(){
			var locationString = 'cbt_test_details.php?edID=' + $(this).attr('edID');
			window.open(locationString, "testStatsWindow");
		})
		counter +=1;
	})
	$('#totalTestCountContainer').append('<div class="totalTestCount">Total Test Completions: '+totalTestCount+'</div>');
	$.post('grabHighestScorers.php','',hiScoresLoaded,"json");
	
}
function hiScoresLoaded(result){
	
	var tempCounter=0;
	$('#hiScorerTable').append('<tr><td id="tableHeader"></td></tr>');
	$(result).each(function(){
		var tempTR = '<tr class="place'+ tempCounter +'"><td>' + result[tempCounter].l_name + ', ' 
								+ result[tempCounter].f_name + ' '
								+ result[tempCounter].m_init + ' <span style="font-size:11px;">('
								+ result[tempCounter].eu_total_pts + ' pts)</span>'
								+ '</td></tr>';
		$('#hiScorerTable').append(tempTR);
		tempCounter+=1;
	})
}
$('document').ready(init);
</script>
<?php
//Sample CSS
echo "
<style>
#breadcrumb ul li{
   list-style-image: none;
   display:inline;
   padding: 0 3px 0 0;
   margin: 3px 0 0 0;
}
#breadcrumb ul{
   margin:0;padding:0;
   list-style-type: none;
   padding-left: 1em;
}
</style>
";

?>
<style type="text/css">
#subContentWrapper { float:left;	width:210px; border:solid 1px black; }
ul { margin:0px; }
.totalTestCount { background-color:#042B42; color:#91D1F9; width:100%; text-align:center; }
.edObjWrapperClss {	margin-bottom:22px;	border:solid 1px #CC9; background-image:url(images/testObjGraphics_mainBG.png);
															   background-repeat:repeat-x;
															   background-position:top;
															   padding:2px; }
#cbtObjWrapper { float:right; width:700px; }
.edTitle { font-size:20px; color:#FFF; width:477px;	float:left;	background-image:url(images/testObjGraphics_titleBar.png);
																background-repeat:no-repeat;
																padding:5px;
																border: solid 1px #CCC;
																background-color:#000; }
.adminContainer { float:right; width:200px; border:solid 1px #CCC; color:#0D6A97; text-align:center; background-color:#ECF5FD; }
.tDescript { float:left; width:458px; padding-left:20px; color:#333; }
.tArea { float:left; width:350px; padding-left:20px; color:#666; font-size:11px; }
.btnWrapper { float:right; width:145px; border:solid 1px #E2E2E2; text-align:center; margin:3px; padding:2px;
								background-image:url(images/testObjGraphics_testBtnBg.jpg);
								background-repeat:no-repeat;
								background-position:center; }
.btnWrapper a { color:#E9F8FE; }
.tControlsContainer { float:left; width:477px; border:solid 1px #999; }
.numComplWrapperClss { background-image:url(images/testObjGraphics2_qCountBG.jpg); color:#FFF; font-size:20px; font-weight:bold; height:40px; padding-top:8px; }
.tAuthorWrapperClss { background-color:#333; color:#87CDF8; }
.numCompTxtClss { font-size:11px; color:#FFF; }
.footerEdObjClss { width:100%; background-color:#333; height:3px; border-bottom:solid 2px #CC000F; }
.newTestClss { background-image:url(images/testObjGraphics2_newTest.jpg); background-repeat:no-repeat; height:20px; width:400px; padding-left:90px; padding-top:2px; font-size:11px; color:#858585; }
.notNewTestClss { height:20px; width:400px; padding-top:2px; font-size:11px; color:#858585; }
.tDetailsBtnContainerClss { cursor:pointer; width:100%;	}
.tDetailsBtnContainerHiLiteClss { cursor:pointer; width:100%; background-color:#FC0; color:#060; }
#hiScorerTable { width:212px; }
#hiScorerTable td { border-bottom:solid 1px #999; padding-left:48px; }
#hiScorerTable tr {	height:30px; }
.place0 { background-image:url(images/hiScorePlaces_1st.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place1 { background-image:url(images/hiScorePlaces_2nd.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place2 { background-image:url(images/hiScorePlaces_3rd.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place3 { background-image:url(images/hiScorePlaces_4th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place4 { background-image:url(images/hiScorePlaces_5th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place5 { background-image:url(images/hiScorePlaces_6th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place6 { background-image:url(images/hiScorePlaces_7th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place7 { background-image:url(images/hiScorePlaces_8th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place8 { background-image:url(images/hiScorePlaces_9th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
.place9 { background-image:url(images/hiScorePlaces_10th.jpg); background-repeat:no-repeat; background-color:#e8f8ff; }
#tableHeader { background-image:url(images/hiScorePlaces_tableBanner.jpg); background-repeat:no-repeat; height:60px; }
#cbtInstructions { border:solid 1px #CCC; padding:4px; margin-bottom:30px; }
#cbtInstructions a { background-color:#FF0;	font-weight:bold; }
#cbtScreenShot { margin-left:20px; margin-top:5px; }
.confident { background-color:#B1F731; border:solid 1px #333; padding-left:4px;	}
.uncertain { background-color:#F8E143; border:solid 1px #333; padding-left:4px;	}
.doNotKnow { background-color:#C00; border:solid 1px #333; padding-left:4px; color:#FFF; }
#confContainer img { margin-left:5px; }
.confMod { width:225px; height:160px; float:left; border:solid 1px #CCC; }
.confMod li { color:#333; }
#testAdminRequest { margin-top:10px; }
</style>
</head>

<body>
<?php // =============- BEGINNING OF MAIN PAGE WRAPPER ====; ?>
<div id="mainPageWrapper">

<?php // -------------------- beginning of banner div ------------ ?>
<div id="banner"></div>
<?php // --------------------- end of banner ----------------------- ?>


<?php // --------------------------- MAIN CONTENT DIV -------------------------------- ?>
<div id="mainContent">

<!--<div id="myTestDiv">
<div style="margin-left:2px"><img src="assets/webLayout/testContent.jpg" width="950" height="779" /></div>
</div>-->
<div id="mainContentGroup" style="background-color:#FFF; background-image:url(../../../assets/images/onlineEd_03.jpg); background-position:bottom right; background-repeat:no-repeat;"><?php // ------------------------------------------------------------------ ?>

<div id="mainContentWrapper">
	<div id="subContentWrapper">
    	<div id="totalTestCountContainer"></div>
		<div id="userList"><table id="hiScorerTable">
        
        </table>
        <p style="font-size:12px;color:#333; font-style:italic; padding-left:4px;">Note: the more tests you take/more questions you answer, the greater the opportunity to gain more points. You can re-take a test multiple times and your score for that test will be averaged.</p>
        </div>
        
        <div id="testAdminRequest">
       <img src="images/testAdminRequest.jpg"/>
        </div>
    </div> 
	<div id="cbtObjWrapper">
    
    <div id="cbtInstructions">
    <p style="color:#333"><span style="font-size:16px;">Welcome!</span> Thank you for your interest in the Confidence-Based Testing tool! Confidence-Based testing (CBT) is very similar to your average multiple-choice test to assess one's knowledge in a particular subject. However, CBT adds the element of 'Confidence' into the equation. <span style="font-style:italic; font-weight:bold">(<a href="Confidence_Based_Testing_OverviewF.pdf" target="_blank">Click here</a> to learn more about the model/theory).</span></p>
    
    <p style="color:#333; font-weight:bold;margin-top:5px;">There are 3 ways to answer a question, and each will have an impact on your score. You can answer:</p>
	<div id="confContainer">
    <div class="confMod">
    <p class="confident">Confident</p>
    <ul><li>if you are confidently correct, you score +10 pts</li>
    <li>if you are confidently 'incorrect' you get penalty of -5 pts</li>
    </ul>
    <img src="images/confLevelsForInstructions_conf.jpg"/>
    </div>
    
    <div class="confMod">
    <p class="uncertain">Uncertain</p>
    <ul><li>if you are uncertainly correct, you score +5 pts</li>
    <li>if you are uncertainly 'incorrect' you get penalty of -2.5 pts</li>
    </ul>
    <img src="images/confLevelsForInstructions_uncertain.jpg"/>
    </div>
    
    <div class="confMod">
    <p class="doNotKnow">I do not know the answer</p>
    <ul><li>if you do not know the answer, you are not penalized any points, nor will you gain any points.</li>
   
    </ul>
    <img style="margin-left:15px; margin-top:18px;" src="images/confLevelsForInstructions_doNotKnow.jpg"/>
   	</div>
    <div style="clear:both"></div>
    </div>
    
    <div id="cbtScreenShot"><br />&nbsp;</div>
    <div style="font-weight:bold; font-size:13px; color:#036; border-top:dashed 1px #666;">Please give it a try! For a fun introduction to the testing tool, please try one (or both) of these tests;<br/> 
      <a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=1&pre_post=sa&testID=000887288278ffedd8829" target="_blank">JUST FOR FUN A&amp;P</a> or test your nursing history with the <a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=7&pre_post=sa&testID=000887288278ffedd8829" target="_blank">FLORENCE NIGHTINGALE</a> test. See if you can make it on the top 10 scorer list! We hope to have more test topics available soon! Note: you will also have the opportunity to create your learner profile which will allow you to review/view transcripts of all the web-based training courses you take on ambulatorypractice.org.</div>
    </div>
    
    <div>CONFIDENCE-BASED TEST LIST</div>
    </div>

<div style="clear:both"></div>
</div>


</div>
<?php // --------------------------- END OF MAIN CONTENT DIV ------------------------ ?>

<?php // ----------- FOOTER ------------------------------------ ;?>
<div id="footer"></div><div id="footerText"><?php getFooterInfo();?></div>
<?php // ---------- end of footer ------------------------------- ?>

<div style="clear:both"></div>
</div>
<?php // -------------------- END OF MAIN PAGE WRAPPER -------------------------------------------------------------- ?>


</body>
</html>