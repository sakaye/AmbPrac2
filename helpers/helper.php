<?php

function load_json(){
  global $config;

  $file = file_get_contents($config->documentRoot . '/json/data.json');
  $json = json_decode($file);

  return $json;
}

function check_registration($post){
	//check evertyhing here.
	$errors = array();
	if(strlen($post['username']) === 0){
		$errors["username"] = "Username is not long enough";
	}
	

	//more here

	return $errors;
}

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

?>
