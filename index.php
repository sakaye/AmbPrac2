<?php
require 'lib/Slim/Slim.php';
require 'helpers/Settings.php';

//config setup
$config = new Settings();
$config->siteRoot = str_replace("index.php", "", $_SERVER['PHP_SELF']);
$config->documentRoot = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']);
$config->webRoot = $_SERVER['SERVER_NAME'] . $config->siteRoot; 
$config->viewsPath = $config->documentRoot . "views/";
$config->modelsPath = $config->documentRoot . "models/";
$config->jsPath = $config->siteRoot . "js/";
$config->cssPath = $config->siteRoot . "css/";
$config->imagePath = $config->siteRoot . "images/Slides/";

//default script file name (all of them)
$config->scripts = array('jquery-1.7.1.min.js', 'jquery.hoverIntent.minified.js', 'scripts.js');
$config->styles	 = array('main.css');

$app = new Slim();

require 'helpers/helper.php';

//routes

$app->get('/', 'home');
$app->get('/test(/)', 'test');
$app->get('/testing/:id','testing');
$app->get('/register(/)', 'register');
$app->post('/register(/)', 'registerComplete');
$app->get('/login(/)', 'login');
$app->post('/login(/)', 'login');
$app->get('/driver(/)', 'driver');
$app->get('/:main(/)', 'mainSection');
$app->get('/:main/:subsection(/)', 'listContent');
$app->get('/:main/:subsection/:content(/)', 'content');
$app->get('/:subsection(/)','subSection');
$app->get('/:main/:nav(/)','listContent');


$app->run();

//controllers here!

function home(){
	global $config;
	$title = 'Ambulatory Practice - Home';
	$body_ID = 'home';
	$scripts = $config->scripts;
	$scripts[] = 'jquery.nivo.slider.pack.js'; 
	$scripts[] = 'jquery.slides.min.js';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'home.php';
	require $config->viewsPath . 'footer.php';
	
}

function mainSection($slug){
	global $config;
	
}

function changeSettings(){
	$u = new User($_SESSION['username']);
}

function register(){
	global $config;
	$title = 'Register Page';
	$post = array();
	$errors = array();

	showRegistrationForm(array(),array());
}

function registerComplete(){
	global $config;
	//look into autoloading classes.
	require $config->modelsPath . "User.php";
	//grab post variable
	$errors = check_registration($_POST);
	if(count($errors) > 0){
		showRegistrationForm($errors,$_POST);
		return false;
	}
	//here to load post data
	$obj = (object)array(
		"username" => $_POST[username],
		"first_name" => $_POST[first_name],
		"last_name" => $_POST[last_name],
		"password" => $_POST[password]
		);
	$u = new User();
	$status = $u->createUser($obj);
	if($status){
		home();
	}else{
		$errors = array(
			"username" => "Already in use"
			);
		showRegistrationForm($errors,$_POST);
	}
}

function showRegistrationForm($errors,$post){
	global $config;
	$title = 'Registration Form';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'register.php';
	require $config->viewsPath . 'footer.php';
}

function login(){
	global $config;
	$title = 'Login In';
	$body_ID = 'login';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'login.php';
	require $config->viewsPath . 'footer.php';

}

function subSection($slug){
	global $config;
	require $config->modelsPath . 'Subsection.php';
	require $config->modelsPath . 'Content.php';
	
	$s = new Subsection();
	$s->getSubsectionBySlug($slug);
	$s->getContent();
	
	
	print_r($s);
}

function testing($id){
	global $config;
	require $config->modelsPath . 'Subsection.php';
	require $config->modelsPath . 'Content.php';
	
	$s = new Subsection($id);
	$s->getContent();
	
	print_r($s);
}

function driver(){
	global $config;
	//look into autoloading classes.
	require $config->modelsPath . "User.php";
	$u = new User();
	$u->loginUser("dustin","test");
}


function showSubNav(){
	
}


function test(){
	phpinfo();
}

?>