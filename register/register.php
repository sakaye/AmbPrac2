<?php

require_once('../_Includes/global_vars.php');
require_once(DB_CON);


if(!empty($_POST['nuid']) && !empty($_POST['password']))
{
	$nuid = mysql_real_escape_string($_POST['nuid']);
	$salt = 'a342b943d045';
    $password = sha1(mysql_real_escape_string($_POST['password']).$salt);
    $email = mysql_real_escape_string($_POST['email']);
    $first_name = mysql_real_escape_string($_POST['first_name']);
    $last_name = mysql_real_escape_string($_POST['last_name']);
    $title = $_POST['title'];
    $area = $_POST['area'];
	$val_key = sha1($nuid.$first_name.date('mY'));

	$checknuid = mysql_query("SELECT * FROM users WHERE nuid = '".$nuid."'");
/* 	$checkemail = mysql_query("SELECT * FROM users WHERE email = '".$email."'"); */

    if(mysql_num_rows($checknuid) == 1)
    {
		echo "<h1>Error</h1>";
		echo "<p>Sorry, that Username(NUID) is already registered with us. Please go back and try again.</p>";
    }
    /*
elseif(mysql_num_rows($checkemail) == 1)
    {
    	echo "<h1>Error</h1>";
    	echo "<p>Sorry, that email is already registered with us. Please go back and try again.</p>";
    }
*/
    else
    {
     	$registerquery = mysql_query("INSERT INTO users (nuid, password, email, first_name, last_name, title, area, val_key) VALUES('".$nuid."','".$password."','".$email."','".$first_name."','".$last_name."','".$title."','".$area."','".$val_key."')");
        if($registerquery)
        {
        	echo "<h1>Success</h1>";
        	echo "<p>Your account was successfully created. Please <a href=\"index.php\">click here to login</a>.</p>";
        }
        else
        {
     		echo "<h1>Error</h1>";
        	echo "<p>Sorry, your registration failed. Please go back and try again.</p>";
        }
    }
}

?>