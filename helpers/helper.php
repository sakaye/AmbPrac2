<?php

function getAllSections(){
	$allSections = array();
	$sql = "SELECT * FROM `section`";
	$result  = db()->query($sql);
	while($row = $result->fetch_object()){
		$s = new Section();
		$s->fillData($row);
		$allSections[] = $s;
	}
	
	return $allSections;
}

function checkEmployee(){
	global $app, $config, $req;
	//dont check if page is unlocked
	if ($_SESSION['isLocked'] === false){
		return;
	}
	//dont check if user is logged in as KP employee
	if (isset($_SESSION['kp_employee']) && $_SESSION['kp_employee'] === 'yes'){
		return;
	}
	//check if page is locked AND user is 'Logged in'
	if ($_SESSION['isLocked'] === true && isset($_SESSION['Logged_in']) && $_SESSION['Logged_in'] === true) {
		$app->redirect($config->siteRoot.'kponly');
	}
	//check if page is locked and user is NOT 'Logged in'
	elseif ($_SESSION['isLocked'] === true) {
		$_SESSION['locked'] = 'You must be logged in to view this page';
		$_SESSION['resourceUri'] = $req->getResourceUri();
		$app->redirect($config->siteRoot.'login');
	}
	//should never get here but just in case
	else{
		$app->redirect('/');
	}
}

?>
