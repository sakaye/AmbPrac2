<?php
require 'lib/Slim/Slim.php';
require 'helpers/Settings.php';
session_start();

//config setup
$config = new Settings();
$config->siteRoot = str_replace("index.php", "", $_SERVER['PHP_SELF']);
$config->documentRoot = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']);
$config->webRoot = $_SERVER['SERVER_NAME'] . $config->siteRoot; 
$config->viewsPath = $config->documentRoot . "views/";
$config->modelsPath = $config->documentRoot . "models/";
$config->jsPath = $config->siteRoot . "js/";
$config->cssPath = $config->siteRoot . "css/";
$config->cssImg = $config->siteRoot . "css/images/";
$config->picsPath = $config->siteRoot . "css/ACPC_member_pics/";
$config->slidesPath = $config->siteRoot . "images/Slides/";

//default script file name (all of them)
$config->scripts = array('jquery-1.7.1.min.js', 'jquery.hoverIntent.minified.js', 'scripts.js');
$config->styles	 = array('main.css');

$app = new Slim();

require 'helpers/helper.php';
require $config->modelsPath . "Section.php";
require $config->modelsPath . "Subsection.php";
require $config->modelsPath . "Content.php";
require $config->modelsPath . "User.php";
require $config->modelsPath . "Search.php";

//routes

$app->get('/', 'home');
$app->get('/test(/)', 'test');
$app->get('/register(/)', 'register');
$app->post('/register(/)', 'registerValidate');
$app->get('/login(/)', 'login');
$app->post('/login(/)', 'loginValidate');
$app->get('/logout(/)', 'logout');
$app->get('/search-results(/)', 'search');
$app->get('/employee-only(/)', 'employeeOnly');
$app->get('/connections-links(/)', 'connectionsLinks');
$app->get('/ambulatory-clinical-practice-committee/message-from-admin(/)', 'messageAdmin');
$app->get('/ambulatory-clinical-practice-committee/committee-charter(/)', 'charter');
$app->get('/ambulatory-clinical-practice-committee/members(/)', 'acpcMembers');
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
	$title = $section->name;
	$body_ID = "section";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'section.php';
	require $config->viewsPath . 'footer.php';	
}

function showSubsection($section_slug, $subsection_slug){
	global $config;
	$subsection = new Subsection($subsection_slug); //find subsection ID from DB
	$title = $subsection->name;
	$body_ID = "subsection";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'subsection.php';
	require $config->viewsPath . 'footer.php';
}

function showContent($section_slug, $subsection_slug, $content_slug){
	global $config;
	$content = new Content($content_slug); //find content ID from DB
	$title = $content->name;
	$body_ID = "content";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'content.php';
	require $config->viewsPath . 'footer.php';
}

function messageAdmin(){
	global $config;
	$title = 'A Message From Administration';
	$body_ID = "content";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'messageAdmin.php';
	require $config->viewsPath . 'footer.php';
}

function charter(){
	global $config;
	$title = 'Regional ACPC Charter';
	$body_ID = "content";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'charter.php';
	require $config->viewsPath . 'footer.php';
}

function acpcMembers(){
	global $config;
	$title = 'ACPC Members';
	$body_ID = "content";
	$c = new Content();
	$regions = $c->getACPCRegions();
	$members = $c->getACPCMembers();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'acpcMembers.php';
	require $config->viewsPath . 'footer.php';
}

function connectionsLinks(){
	global $config;
	$title = 'Connections & Links';
	$body_ID = '';
	$c = new Content();
	$Conn_sections = $c->getConnLinkSections();
	$links = $c->getConnLinkLinks();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'connectionsLinks.php';
	require $config->viewsPath . 'footer.php';
}

function changeSettings(){
	$u = new User($_SESSION['username']);
}

function register(){
	$errors = array();
	showRegistrationForm($errors,$_POST);
}

function registerValidate(){
	global $config;
	$errors = User::check_registration($_POST);
	if(count($errors) > 0){
		showRegistrationForm($errors,$_POST);
		return false;
		}
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
	$errors = $u->createUser($obj);
	if(count($errors) > 0){
		showRegistrationForm($errors,$_POST);
		return false;
	}else{
		registerComplete($u);
		}
}

function showRegistrationForm($errors,$_POST){
	global $config;
	$title = 'Registration Form';
	$body_ID = "register";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'register.php';
	require $config->viewsPath . 'footer.php';
}
function registerComplete($user){
	global $config;
	$title = 'Registration Complete';
	$body_ID = 'register';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'register_complete.php';
	require $config->viewsPath . 'footer.php';
}

function login(){
	$errors = array();
	showLoginForm($errors);
}

function loginValidate(){
	global $config;
	global $app;
	$u = new User();
	$errors = $u->loginUser($_POST);
	if ($errors == false){ //redirect to home page
		$app->redirect($config->siteRoot, 301);
	}
	else{ //show errors on login page
		showLoginForm($errors, $_POST);	
	}
}

function showLoginForm($errors){
	global $config;
	$title = 'Login In';
	$body_ID = 'login';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'login.php';
	require $config->viewsPath . 'footer.php';
}

function logout(){
	global $config;
	global $app;
	$logout = User::logout_User();
	$app->redirect($config->siteRoot, 301);
}

function search(){
	global $config;
	$title = 'Search Results';
	$body_ID = 'search';
	$s = new Search();
	$searchResults = $s->doSearch();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'search.php';
	require $config->viewsPath . 'footer.php';
}

function checkUser(){
	global $config;
	global $app;
	$check = User::checkEmployee();
	if ($check == 'kp_employee'){
		return true;
	}
	elseif ($check == 'other'){
		$app->redirect('/employee-only');
	}
	else {
		$errors['login_error'] = "You must be logged in to view that page";
		showLoginForm($errors);
	}
}

function employeeOnly(){
	global $config;
	$title = 'Employees Only';
	$body_ID = '';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'employee.php';
	require $config->viewsPath . 'footer.php';
}

function test(){
	global $config;
	phpinfo();
}





















?>