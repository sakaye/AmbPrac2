<?php

require_once('../_Includes/global_vars.php');
require_once(DB_CON);


if (isset($_POST['login']))
{    
    if(!empty($_POST['username']) && !empty($_POST['password']))
	{
		$username = mysql_real_escape_string($_POST['username']);
		$salt = 'a342b943d045';
   		$password = sha1(mysql_real_escape_string($_POST['password']).$salt);
			
		$checklogin = mysql_query("SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."'");
	
	    if(mysql_num_rows($checklogin) == 1)
	    {
	    	$row = mysql_fetch_array($checklogin);
	        $email = $row['email'];
	
	        $_SESSION['username'] = $username;
	        $_SESSION['email'] = $email;
	        $_SESSION['LoggedIn'] = 1;
	
	    	echo "<h1>Success</h1>";
	    }
	}
    else
    {
    	echo "<h1>Error</h1>";
        echo "<p>Sorry, your account could not be found. Please <a href=\"index.php\">click here to try again</a>.</p>";
    }
}




?>