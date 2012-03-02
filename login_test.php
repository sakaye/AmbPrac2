<?php include "base.php"; ?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Test Page</title>
	
</head>
<body>
	
	<?php 
	if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
		{
	?>
			<p>You are logged in.</p>
	
	
	<?php	
		}
	elseif(!empty($_POST['username']) && !empty($_POST['password']))
		{
	?>
		
			<p>You are being logged in</p>
	
	<?php					
		}
	else
		{
	?>
			<a id="login" href="#">Login</a>
			<a id="new_user" href="#">New User</a>
			<h1>Home Page</h1>
	<?php
		}
	?>

	
	test






</body>
</html>