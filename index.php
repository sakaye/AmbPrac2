<?php
require 'lib/Slim/Slim.php';
require 'helpers/Settings.php';

//config setup
$config = new Settings();
$config->siteRoot = str_replace("index.php", "", $_SERVER['PHP_SELF']);
$config->documentRoot = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']);
$config->webRoot = $_SERVER['SERVER_NAME'] . $config->siteRoot; 
$config->viewsPath = $config->documentRoot . "views/";
$config->jsPath = $config->siteRoot . "js/";
$config->cssPath = $config->siteRoot . "css/";
$config->imagePath = $config->siteRoot . "images/Slides/";

//default script file name (all of them)
$config->scripts = array('jquery-1.7.1.min.js', 'jquery.hoverIntent.minified.js', 'scripts.js');
$config->styles	 = array('main.css');

$app = new Slim();

require 'helpers/helper.php';

$app->get('/', 'home');
$app->get('/test(/)', 'test');

$app->run();

//controllers here!

function home(){
	global $config;
	$title = 'Ambulatory Practice - Home';
	$body_ID = 'home';
	$scripts = $config->scripts;
	array_push($scripts,'jquery.nivo.slider.pack.js','jquery.slides.min.js');
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'home.php';
	require $config->viewsPath . 'footer.php';
	
}

function login(){
	global $config;
	$account = new db_account();
	$account->makeQuery();
	$title = 'Ambulatory Practice - Login';
	$body_ID = 'login';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'footer.php';
}


function test(){
	phpinfo();
}

?>