<?php

require_once('../_Includes/global_vars.php');
require_once(DB_CON);


if (isset($_POST['Submit']))
{
	$nuid = mysql_real_escape_string($_POST['nuid']);
	$salt = 'a342b943d045';
    $password = sha1(mysql_real_escape_string($_POST['password']).$salt);
    
    

}


?>