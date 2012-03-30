<?php

/****** WEBROOT ******/
define('WEBROOT','AmbPrac2/');


/****** URLROOT ******/
define('URL','http://localhost/');
define('URLROOT',URL.WEBROOT);

/****** Paths for include files ******/
define('INC',dirname(__FILE__).'/');



/****** PHP Vars ******/
define('PHP_LIB',INC.'PHP_lib/');
define('DB_CON',PHP_LIB.'db_connect.php');
define('PHP_FNS',PHP_LIB.'output_fns.php');
define('LOGIN', URLROOT.'login/');
define('REGISTER',URLROOT.'register/');

/****** CSS Paths ******/
define('CSS',URLROOT.'_Includes/CSS_lib/');
define('MAIN',CSS.'main.css');

/****** JAVA Paths ******/
define('JS_LIB',URLROOT.'_Includes/JS_lib/');
define('JS_FNS',JS_LIB.'output_fns.js');
?>