<?php

require_once('../_Includes/global_vars.php');
require_once(DB_CON);


if (isset($_POST['register']))
{	
	$username = mysql_real_escape_string($_POST['username']);
	$salt = 'a342b943d045';
    $password = sha1(mysql_real_escape_string($_POST['password']).$salt);
    $email = mysql_real_escape_string($_POST['email']);
    $first_name = mysql_real_escape_string($_POST['first_name']);
    $last_name = mysql_real_escape_string($_POST['last_name']);
    $title = $_POST['title'];
    $area = $_POST['area'];
	$val_key = sha1($username.$first_name.date('mY')); //create the validation key
	
	
	$accountquery = mysql_query("SELECT username,email FROM users WHERE username = '".$username."' AND email = '".$email."'");
	$account = mysql_fetch_array($accountquery); //create array to database query to check for duplicate username and email
	
	
	/*
$checkuser = mysql_query("SELECT * FROM users WHERE username = '".$username."'");
	$checkemail = mysql_query("SELECT * FROM users WHERE email = '".$email."'");
*/

    if(mysql_num_rows($account[0]) == 1) //check Username to make sure it isn't already taken
    {
		echo "<h1>Error</h1>";
		echo "<p>Sorry, that Username(NUID) is already registered with us. Please go back and try again.</p>";
    }
    elseif(mysql_num_rows($account[1]) == 1) //check Email to make sure it isn't already registered
    {
    	echo "<h1>Error</h1>";
    	echo "<p>Sorry, that email is already registered with us. Please go back and try again.</p>";
    }
    else
    {
    	if(preg_match("/^[a-zA-Z0-9_.-]+@kp\.org$/",$email)) //check email for @kp.org to validate employee status
    	{
    		$kp_emp = 1;
    		$registerquery = mysql_query("INSERT INTO users (username, password, email, first_name, last_name, title, area, val_key, kp_employee) VALUES('".$username."','".$password."','".$email."','".$first_name."','".$last_name."','".$title."','".$area."','".$val_key."','".$kp_emp."')");
    	}
    	else		//create account without kp employee status
    	{
     		$registerquery = mysql_query("INSERT INTO users (username, password, email, first_name, last_name, title, area, val_key) VALUES('".$username."','".$password."','".$email."','".$first_name."','".$last_name."','".$title."','".$area."','".$val_key."')");
	    }
        if($registerquery)
        {
        	echo "<h1>Success</h1>";
        	echo "<p>Your account was successfully created.</p>";
        }
        else
        {
     		echo "<h1>Error</h1>";
        	echo "<p>Sorry, your registration failed. Please go back and try again.</p>";
        }
    }
}

?>