<?php 
session_start();

$dbhost = "localhost"; //this will usually be "localhost", but can sometimes differ
$dbname = "ambulprac"; //the name of the datebase that you are going to use
$dbuser = "root"; // the username that you created, or were given, to access your database
$dbpass = "Atomicss1"; // the password that you created, or were given, to access your database

mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
mysql_select_db($dbname) or die("MySQL Error: " . mysql_error());


?>