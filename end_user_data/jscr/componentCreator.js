// JavaScript Document
function createLearnerDetailsTable(theID,testTitle){
	var learnerDetailsTable = '<table id="learnerDetailsTbl_' + theID + '" ' +
									 'class="learnerDetailsTblClss">' +
									 '<thead><tr><th>Name</th>' +
									     '<th>Title</th>' +
										 '<th>Area</th>' +
										 '<th>MOB</th>' +
										 '<th>Dept</th>' +
										 '<th>KP Empl</th>' +
										 '<th>Pre Test Score</th>' +
										 '<th>Post Test Score</th>' +
										 '<th>Stand-Alone Score</th>' +
									 '</tr></thead>' +
									 '</table>';
	return learnerDetailsTable;	
}
function createLearnerTR(EndUserEducationVO,edTitle){
	
	var preTestScore = EndUserEducationVO.eue_pre_score;
	var postTestScore = EndUserEducationVO.eue_post_score;
	var saScore = EndUserEducationVO.eue_sa_score;
	var area='';
	var mob='';
	if(EndUserEducationVO.endUserVO.eu_kp_emp=='false'){
		area = EndUserEducationVO.endUserVO.newFacility;
		mob = ''
	} else {
		area = EndUserEducationVO.endUserVO.area;
		mob = EndUserEducationVO.endUserVO.mob;
	}
	
	if(preTestScore == 5000){
		preTestScore = 'not taken';	
	} else {
		preTestScore +='%';
	}
	
	if(postTestScore == 5000){
		postTestScore = 'not taken';	
	} else {
		postTestScore = '<a href="endUserResults.php?review=true&uID=' + EndUserEducationVO.eue_user_link + '&pre_post=post&edID=' + EndUserEducationVO.eue_ed_link + '&edTitle=' + edTitle + '" target="_blank">' + postTestScore + '%</a>';
	}
	
	if(saScore == 5000){
		saScore = 'not taken';	
	} else {
		saScore = '<a href="endUserResults.php?review=true&uID=' + EndUserEducationVO.eue_user_link + '&pre_post=sa&edID=' + EndUserEducationVO.eue_ed_link + '&edTitle=' + edTitle + '" target="_blank">' + saScore + '%</a>';
	}
	
	var tempTR = '<tr><td>' + EndUserEducationVO.endUserVO.l_name + ', ' + EndUserEducationVO.endUserVO.f_name + ' ' + EndUserEducationVO.endUserVO.m_init + '</td>' +
				 '<td>' + EndUserEducationVO.endUserVO.title + '</td>' +
				 '<td>' + area + '</td>' +
				 '<td>' + mob + '</td>' +
				 '<td>' + EndUserEducationVO.endUserVO.dept + '</td>' +
				 '<td>' + EndUserEducationVO.endUserVO.eu_kp_emp + '</td>' +
				 '<td>' + preTestScore + '</td>' +
				 '<td>' + postTestScore + '</td>' +
				 '<td>' + saScore + '</td>' +
				 '</tr>';
	
	return tempTR;
	
}