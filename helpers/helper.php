<?php

function load_json(){
  global $config;

  $file = file_get_contents($config->documentRoot . '/json/data.json');
  $json = json_decode($file);

  return $json;
}
?>
