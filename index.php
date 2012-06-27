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
require $config->modelsPath . "Section.php";
require $config->modelsPath . "Subsection.php";
require $config->modelsPath . "Content.php";

//routes

$app->get('/', 'home');
$app->get('/test(/)', 'test');
$app->get('/testing/:id','testing');
$app->get('/register(/)', 'register');
$app->post('/register(/)', 'registerValidate');
$app->get('/login(/)', 'login');
$app->post('/login(/)', 'login');
$app->get('/driver(/)', 'driver');
$app->get('/:section(/)', 'showSection');
$app->get('/:section/:subsection(/)', 'showSubsection');
$app->get('/:section/:subsection/:content(/)', 'showContent');


$app->run();

//controllers here!

function home(){  //displays the Home page with required JS scripts for sliders
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

function showSection($section_slug){
	global $config;
	$section = new Section($section_slug); //find section ID from DB
	$title = $section->section_name;
	$body_ID = "section";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'section.php';
	require $config->viewsPath . 'footer.php';	
}

function showSubsection($section_slug, $subsection_slug){
	global $config;
	$subsection = new Subsection($subsection_slug); //find subsection ID from DB
	$title = $subsection->subsection_name;
	$body_ID = "subsection";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'subsection.php';
	require $config->viewsPath . 'footer.php';
}

function showContent($section_slug, $subsection_slug, $content_slug){
	global $config;
	$content = new Content($content_slug); //find content ID from DB
	$title = $content->content_name;
	$body_ID = "content";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'content.php';
	require $config->viewsPath . 'footer.php';
}

function changeSettings(){
	$u = new User($_SESSION['username']);
}

function register(){
	$post = array();
	$errors = array();
	showRegistrationForm(array(),array());
}

function registerValidate(){
	global $config;
	//look into autoloading classes.
	require $config->modelsPath . "User.php";
	//grab post variable
	print_r($_POST);
	$errors = User::check_registration($_POST);
	print_r($errors);
		
	if(count($errors) > 0){

		showRegistrationForm($errors,$_POST);
		return false;
	}
	//here to load post data
	$obj = (object)array(
		"username" => $_POST['username'],
		"first_name" => $_POST['first_name'],
		"last_name" => $_POST['last_name'],
		"email" => $_POST['email'],
		"password" => $_POST['password'],
		"title" => $_POST['title'],
		"area" => $_POST['area']
		);
	$u = new User();
	$status = $u->createUser($obj);
	if($status){
		registerComplete();
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
	$body_ID = "";
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


function driver(){
	global $config;
	//look into autoloading classes.
	require $config->modelsPath . "User.php";
	$u = new User();
	$u->loginUser("dustin","test");
}

function test(){
	global $config;
	$t = new Subsection();
	print_r($t);
	phpinfo();
}

?>