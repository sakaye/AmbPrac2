<?php
require 'lib/Slim/Slim.php';
require 'helpers/Settings.php';
session_cache_limiter(false);
session_start();

//config setup
$config = new Settings();
$config->siteRoot = str_replace("index.php", "", $_SERVER['PHP_SELF']);
$config->documentRoot = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']);
$config->webRoot = $_SERVER['SERVER_NAME'] . $config->siteRoot;
$config->serverRoot	= str_replace("www.", "", $_SERVER['SERVER_NAME']);
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

//default sessions
$_SESSION['isLocked'] = false;

$app = new Slim();

$req = $app->request();

require 'helpers/helper.php';
require $config->modelsPath . "Section.php";
require $config->modelsPath . "Subsection.php";
require $config->modelsPath . "Content.php";
require $config->modelsPath . "Subcontent.php";
require $config->modelsPath . "User.php";
require $config->modelsPath . "Search.php";

//hooks

$app->hook('slim.before.dispatch', 'checkRelogin');
$app->hook('slim.after.dispatch', 'checkEmployee');

//routes

$app->get('/', 'home');

/* $app->get('/test(/)', 'test'); */

$app->get('/kponly(/)', 'kponly');
$app->get('/register(/)', 'register');
$app->get('/register/kp-employee', 'registerKP');
$app->post('/register/kp-employee', 'registerKPValidate');
$app->get('/register/kp-employee/:area', 'loadCampuses');
$app->get('/user/email-confirmation-kp', 'emailConfirmKP');
$app->get('/user/email-confirmation','emailConfirm');
$app->get('/register/non-kp-employee', 'registerNonKP');
$app->post('/register/non-kp-employee', 'registerNonKPValidate');

$app->get('/login(/)', 'login');
$app->post('/login(/)', 'loginValidate');
$app->get('/logout(/)', 'logout');

$app->get('/forgot-username(/)', 'forgotUsername');
$app->post('/forgot-username(/)', 'forgotUsernameSendEmail');

$app->get('/forgot-password(/)','forgotPassword');
$app->get('/forgot-password/:nuid', 'forgotPasswordSendEmail');
$app->get('/user/reset-password/:username/:val_key','showPasswordReset');
$app->post('/user/reset-password','resetPasswordEmailProcess');

$app->get('/search-results(/)', 'search');
$app->get('/connections-links(/)', 'connectionsLinks');
$app->get('/contact-us(/)', 'contactUs');
$app->post('/contact-us(/)', 'contactUsProcess');
$app->get('/about-us(/)', 'aboutUs');
$app->get('/site-map(/)', 'siteMap');
$app->get('/announcement(/)', 'announcement');
$app->get('/ambulatory-clinical-practice-committee/message-from-admin(/)', 'messageAdmin');
$app->get('/ambulatory-clinical-practice-committee/committee-charter(/)', 'charter');
$app->get('/ambulatory-clinical-practice-committee/members(/)', 'acpcMembers');
$app->get('/tools-resources/validation-tools(/)', 'validationTools');
$app->get('/tools-resources/caring-book', 'caringBook');


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
	$subSections = $section->getSubsections();
	$title = $section->name;
	$body_ID = "section";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'section.php';
	require $config->viewsPath . 'footer.php';	
}

function showSubsection($section_slug, $subsection_slug){
	global $config;
	$subsection = new Subsection($subsection_slug); //find subsection ID from DB
	$contents = $subsection->getContent();
	$title = $subsection->name;
	$body_ID = "subsection";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'subsection.php';
	require $config->viewsPath . 'footer.php';
}

function showContent($section_slug, $subsection_slug, $content_slug){
	global $config;
	$content = new Content($content_slug); //find content ID from DB
	$subContents = $content->getSubcontent();
	$title = $content->name;
	$body_ID = "content";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'content.php';
	require $config->viewsPath . 'footer.php';
}

function kponly(){
	global $config;
	$title = 'Restricted Area';
	$body_ID = '';
	$processHeader = 'This area is restricted to KP employee\'s only';
	$processInfo = 'If you feel this is an error and you are a KP employee please <a href="http://ambulatorypractice.org/contact-us">contact us</a>.';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'processComplete.php';
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
	$regions = $c->combineRegions();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'acpcMembers.php';
	require $config->viewsPath . 'footer.php';
}

function connectionsLinks(){
	global $config;
	$title = 'Connections & Links';
	$body_ID = '';
	$c = new Content();
	$sections = $c->combineSectionLinks();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'connectionsLinks.php';
	require $config->viewsPath . 'footer.php';
}

function validationTools(){
	global $config;
	$title = 'Competency Validation Tools (CVTs)';
	$body_ID = '';
	$c = new Content();
	$sections = $c->combineSectionCvts();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'cvts.php';
	require $config->viewsPath . 'footer.php';
}

function caringBook(){
	global $config;
	$title = 'Caring: Making a Difference One Story at a Time';
	$body_ID = '';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'caringBook.php';
	require $config->viewsPath . 'footer.php';
}

function showContactUs($errors){
	global $config;
	$title = 'Contact Us';
	$body_ID = 'contact_us';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'contactUs.php';
	require $config->viewsPath . 'footer.php';
}

function contactUs(){
	$errors = array();
	showContactUs($errors);
}

function contactUsProcess(){
	$u = new User();
	$errors = $u->sendContactUsEmail();
	showContactUs($errors);
}

function aboutUs(){
	echo "<h1>Coming Soon</h1>";
}

function siteMap(){
	echo "<h1>Coming Soon</h1>";
}

function forgotUsername(){
	$errors = array();
	showForgotUsername($errors);
}

function showForgotUsername($errors){
	global $config;
	$title = 'Forgot Username';
	$body_ID = '';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'forgotUsername.php';
	require $config->viewsPath . 'footer.php';
}

function forgotUsernameSendEmail(){
	global $config;
	$title = 'Forgot Username';
	$body_ID = '';
	$u = new User();
	$errors = $u->forgotUsername();
	if ($errors){
		showForgotUsername($errors);
	}
	else {
	$processHeader = 'Username has been sent';
	$processInfo = 'Please check your email to view your username.';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'processComplete.php';
	require $config->viewsPath . 'footer.php';
	}
}

function forgotPassword(){
	$errors = array();
	showForgotPassword($errors);
}

function showForgotPassword($errors){
	global $config;
	$title = 'Forgot Password';
	$body_ID = 'forgotPassword';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'forgotPassword.php';
	require $config->viewsPath . 'footer.php';
}

function forgotPasswordSendEmail($username){
	$u = new User();
	$emailSent = $u->forgotPassword($username);
	echo json_encode($emailSent);
}

function showPasswordReset($username, $val_key){
	global $config;
	$title = 'Reset Password';
	$body_ID = 'resetPassword';
	$u = new User();
	$notice = $u->resetPasswordfromEmail($username, $val_key);
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'resetPasswordEmail.php';
	require $config->viewsPath . 'footer.php';
}

function resetPasswordEmailProcess(){
	global $config;
	$title = 'Reset Password';
	$body_ID = '';
	$u = new User();
	$notice = $u->resetPasswordEmailProcess();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'resetPasswordEmail.php';
	require $config->viewsPath . 'footer.php';
}

function register(){
	global $config;
	$title = 'Registration Form';
	$body_ID = "register";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'register.php';
	require $config->viewsPath . 'footer.php';
}

function registerKPValidate(){
	global $config;
	$errors = User::check_registration();
	if(count($errors) > 0){
		showRegisterKP($errors);
		return;
		}
	$u = new User();
	$errors = $u->createKPUser();
	if(count($errors) > 0){
		showRegisterKP($errors);
		return;
	}
	else {
		registerComplete();
	}
}

function registerNonKPValidate(){
	global $config;
	$errors = User::check_registrationNonKP();
	if(count($errors) > 0){
		showRegisterNonKP($errors);
		return;
		}
	$u = new User();
	$errors = $u->createNonKPUser();
	if(count($errors) > 0){
		showRegisterNonKP($errors);
		return;
	}
	else {
		registerComplete();
	}
}

function loadCampuses($area){
	$u = new User();
	$campuses = $u->getCampuses($area);
	echo json_encode($campuses);
}

function registerKP(){
	$errors = array();
	showRegisterKP($errors);
}

function showRegisterKP($errors){
	global $config;
	$title = 'Registration Form';
	$body_ID = "registerKP";
	$u = new User();
	$areas = $u->getAreas();
	$positions = $u->getPositions();
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'registerKP.php';
	require $config->viewsPath . 'footer.php';
}

function registerNonKP(){
	$errors = array();
	showRegisterNonKP($errors);
}

function showRegisterNonKP($errors){
	global $config;
	$title = 'Registration Form';
	$body_ID = "";
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'registerNonKP.php';
	require $config->viewsPath . 'footer.php';
}

function emailConfirmKP(){
	global $config;
	$title = 'Complete Registration';
	$body_ID = '';
	$u = new User();
	$errors = $u->emailConfirmKP();
	if (count($errors) > 0){
		$processHeader = 'There was an error';
		$processInfo = $errors['db_error'];
	}
	else {
		$processHeader = 'Your registration is complete!';
		$processInfo = 'You may now log in with your new username and password.';
	}
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'processComplete.php';
	require $config->viewsPath . 'footer.php';
}

function emailConfirm(){
	global $config;
	$title = 'Complete Registration';
	$body_ID = '';
	$u = new User();
	$errors = $u->emailConfirm();
	if (count($errors) > 0){
		$processHeader = 'There was an error';
		$processInfo = $errors['db_error'];
	}
	else {
		$processHeader = 'Your registration is complete!';
		$processInfo = 'You may now log in with your new username and password.';
	}
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'processComplete.php';
	require $config->viewsPath . 'footer.php';
}

function registerComplete(){
	global $config;
	$title = 'Registration Complete';
	$body_ID = 'register';
	$processHeader = 'Your registration is almost complete!';
	$processInfo = 'Please check the email you used during registration to activate your account. You will not be able to log in until you activate. It may take up to several minutes to receive the email.';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'processComplete.php';
	require $config->viewsPath . 'footer.php';
}

function login(){
	$errors = array();
	showLoginForm($errors);
}

function loginValidate(){
	global $app, $config;
	$u = new User();
	$errors = $u->loginUser($config->serverRoot);
	if ($errors == false){ //redirect to home page
		unset($_SESSION['locked']);		
		if (isset($_SESSION['resourceUri'])){
			$URL = $_SESSION['resourceUri'];
			unset($_SESSION['resourceUri']);
			$app->redirect($URL);
		}
		else {
			$app->redirect($config->siteRoot);
		}
	}
	else{ //show errors on login page
		showLoginForm($errors);	
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

function checkRelogin(){
	global $config;
	$u = new User();
	$u->checkReloginID($config->serverRoot);

}

function logout(){
	global $config, $app;
	$logout = User::logout_User();
	$app->redirect($config->siteRoot);
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

function announcement(){
	global $config;
	$title = 'Announcement';
	$body_ID = '';
	require $config->viewsPath . 'header.php';
	require $config->viewsPath . 'announcement.php';
	require $config->viewsPath . 'footer.php';
}

function test(){
	global $config;
	phpinfo();
}


















?>