<!DOCTYPE html>
<html>
<head>
	<title>Login Test Page</title>
	
</head>
<body>
	
	<div class="form_container">
			<h1 class="underlined">Sign In</h1>
			<form method="post" action="login.php" name="loginform" id="loginform">
				<label for="nuid">Username (NUID)</label>
					<input type="text" name="nuid" id="nuid" autofocus="autofocus" /> <br/>
				<label for="password">Password</label>
					<input type="password" name="password" id="password" /> <br/>
				<label for="remember" id="remember_label" >Keep me logged in</label>
					<input type="checkbox" name="remember" id="remember" /> <br/>
					<input type="submit" name="login" id="login" value="Sign In" />
			</form>
			<span class="forgot">
				<a id="forgot_user" href="#">Forgot Username? </a>| <a id="forgot_pass" href="#">Forgot Password?</a>
			</span>
		</div>


</body>
</html>