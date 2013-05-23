// JavaScript Document
function dayCountProcessor(dayCount){
	tempString = '';
	if(dayCount==0){
		tempString = 'created today';
	} else {
		tempString = 'posted ' + dayCount + ' day(s) ago';
	}
	return tempString;
}
function createTestBtns(testType,testID){
	var tempTestBtns = '';
	if(testType=='sa'){
		tempTestBtns = '<div class="btnWrapper">' +
							'<a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=' + testID + '&pre_post=sa&testID=000887288278ffedd8829" target="_blank">Take Stand Alone Test</a>' +
						'</div>';
	} else if(testType == 'pt') {
		tempTestBtns = '<div class="btnWrapper">' +
							'<a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=' + testID + '&pre_post=post&testID=00088728827898898" target="_blank">Take Post Test</a>' +
						'</div>' +
						'<div class="btnWrapper">' +
							'<a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=' + testID + '&pre_post=pre&testID=000887288278918829" target="_blank">Take Pre Test</a>' +
						'</div>';	
	
	} else {
		tempTestBtns = '<div class="btnWrapper">' +
							'<a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=' + testID + '&pre_post=sa&testID=000887288278ffedd8829" target="_blank">Take Stand Alone Test</a>' +
						'</div>' +
						'<div class="btnWrapper">' +
							'<a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=' + testID + '&pre_post=post&testID=00088728827898898" target="_blank">Take Post Test</a>' +
						'</div>' +
						'<div class="btnWrapper">' +
							'<a href="http://ambulatorypractice.org/end_user_data/index.php?educationID=' + testID + '&pre_post=pre&testID=000887288278918829" target="_blank">Take Pre Test</a>' +
						'</div>';
	}
	return tempTestBtns;
}
function createEdObj(edObj){
	
	var tempObj = '<div id="edObjContainer_' + edObj.edVO.et_id + '" class="edObjWrapperClss">' +
						'<div id="newTest_'+ edObj.edVO.et_id + '">'+ dayCountProcessor(edObj.edVO.daycount) +'</div>' +
						'<div id="edTitle_' + edObj.edVO.et_id + '" class="edTitle">' + edObj.edVO.et_title + '</div>' +
						'<div class="adminContainer">' +
							'<div class="tAuthorWrapperClss">' +
								'<div id="tAuthorName_' + edObj.edVO.et_id + '" class="tAuthorClss">Test Author: ' + edObj.tAdminVO.f_name + ' ' + edObj.tAdminVO.l_name + '</div>' +
							'</div>' +
							'<div class="numCompTxtClss"># of Completions / Last Comp Date</div>' +
							'<div class="numComplWrapperClss">' +
								'<div class="numCompletionsClss">' + edObj.completionsObj.cbtc_comp_count + '</div>' +
								'<div class="lastDateCompClss">' + edObj.completionsObj.lastCompFrm + '</div>' +
							'</div>' +
							'<div id="viewTDetailsBtn_'+ edObj.edVO.et_id +'" class="tDetailsBtnContainerClss" edID="'+edObj.edVO.et_id+'">VIEW TEST STATS</div>' +
						'</div>' + 
						'<div id="descript_' + edObj.edVO.et_id + '" class="tDescript">' + edObj.edVO.et_description + '</div>' +
						'<div id="topicArea_' + edObj.edVO.et_id + '" class="tArea">Topic Area: ' + edObj.edVO.topicArea + '</div>' +
						'<div id="tControlsContainer_' + edObj.edVO.et_id + '" class="tControlsContainer">' + 
							createTestBtns(edObj.edVO.et_test_type,edObj.edVO.et_id) +
						'<div style="clear:both"></div>' +
						'</div>' +
						'<div style="clear:both"></div>' +
						'<div class="footerEdObjClss"></div>' +
				  '</div>';
	return tempObj;
		
}