<?php

function load_json(){
  global $config;

  $file = file_get_contents($config->documentRoot . '/json/data.json');
  $json = json_decode($file);

  return $json;
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
