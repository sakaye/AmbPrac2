<?php
class User{
	public  $ID, $username, $last_name, $first_name, $password, $email, $kp_employee, $reloginDigest, $position, $service_area, $campus, $active, $val_key, $creation_date;
	
	
	function __construct($username = null){
		if($username !== null){
			$this->getUser($username);
		}
	}

	function getUser($username){
		$username = db()->real_escape_string($_POST['username']);
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		$password = sha1(db()->real_escape_string($_POST['password']).$salt);
		
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' LIMIT 1";
		$result = db()->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_object();
			$this->fillData($row);
			return true;
		}else{
			return false;
		}
	}

	function fillData($row){
		$this->ID = $row->ID;
		$this->username = $row->username;
		$this->last_name = $row->last_name;
		$this->first_name = $row->first_name;
		$this->password = $row->password;
		$this->email = $row->email;
		$this->kp_employee = $row->kp_employee;
		$this->reloginDigest = $row->reloginDigest;
		$this->position = $row->position;
		$this->service_area = $row->service_area;
		$this->campus = $row->campus;
		$this->active = $row->active;
		
	}

	function loginUser($environment){
		$error = array();
		$username = db()->real_escape_string($_POST['username']);
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		$password = sha1(db()->real_escape_string($_POST['password']).$salt);

		//Search for user in DB if they are active first. If found, set sessions and return.
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' AND `active` = 'yes' LIMIT 1";
		$result = db()->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_object();
			$this->fillData($row);
			$this->setSessions();
			//If remember_me was checked set cookies for re-login
			if(isset($_POST['remember']) && $_POST['remember'] == true){
				$this->setCookies($environment);
				return $error;
			}
			return $error;
		}
		//If an active user was not found, search for user that is not active.
		//If found and not active return error to remind them to active their account from email.
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' LIMIT 1";
		$result = db()->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_object();
			$this->fillData($row);
			if ($this->active === 'yes'){
				$this->setSessions();				
			}
			else {
				$error['login_error'] = "Your account is not yet active. Please check your email to activate.";
			}
			if(isset($_POST['remember']) && $_POST['remember'] == true){
				$this->setCookies($environment);
				return $error;
			}
		}
		else {
			$error['login_error'] = "Username/Password combination were invalid.<br/>Please try again.";
		}
		return $error;
	}

	function setSessions(){
		$_SESSION['First_name'] = $this->first_name;
		$_SESSION['Logged_in'] = true;
		$_SESSION['User_ID'] = $this->ID;
		$_SESSION['kp_employee'] = $this->kp_employee;
	}
	
	function setCookies($environment){
		$name = 'reloginID';
		$digest = sha1(rand(1,20).$this->username.rand(1,20));
		$expire = '30 days';
		$path = '/';
		$domain = $environment;
		$secure = false;
		$httponly = true;
		$sql = "UPDATE `users` SET reloginDigest = '$digest' WHERE username = '$this->username' LIMIT 1";
		db()->query($sql);
		global $app;		
		$app->setCookie($name, $digest, $expire, $path, $domain, $secure, $httponly);
	}
	
	function checkReloginID($environment){
		if (isset($_SESSION['Logged_in']) && $_SESSION['Logged_in'] === true ){
			return;
		}
		if (isset($_COOKIE['reloginID'])){
			$digest = db()->real_escape_string($_COOKIE['reloginID']);
			$sql = "SELECT * FROM `users` WHERE `reloginDigest` = '$digest' LIMIT 1";
			$result = db()->query($sql);
			if($result && $result->num_rows > 0){
				$row = $result->fetch_object();
				$this->fillData($row);
				if ($this->active === 'yes'){
				$this->setSessions();
				$this->setCookies($environment);
				}
			}
		}
	}
	
	function getPositions(){
		$sql = "SELECT `name` FROM `kpsc_positions` ORDER BY `name` ASC";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$positions[] = $row->name;
		}
		return $positions;
	}
	
	function getAreas(){
		$sql = "SELECT `name` FROM `kpsc_areas` ORDER BY `name` ASC";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$areas[] = $row->name;
		}
		return $areas;
	}
	
	function getCampuses($area){
		$sql = "SELECT `name` FROM `kpsc_campuses` WHERE `area_id` = (SELECT `id` FROM `kpsc_areas` WHERE `name` = '$area') ORDER BY `name` ASC";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$campuses[] = $row->name;
		}
		return $campuses;
	}

	function createKPUser(){
		//sql injection cleaning;
		$this->username = db()->real_escape_string($_POST['username']);
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		$this->password = sha1(db()->real_escape_string($_POST['password']).$salt);
		$this->email = db()->real_escape_string(strtolower($_POST['email']));
		$this->first_name = db()->real_escape_string(ucfirst(strtolower($_POST['first_name'])));
		$this->last_name = db()->real_escape_string(ucfirst(strtolower($_POST['last_name'])));
		$this->val_key = sha1(rand(1,20).$this->username.rand(1,20));
		$this->position = $_POST['position'];
		$this->service_area = $_POST['area'];
		if (isset($_POST['campus'])){
			$this->campus = $_POST['campus'];
		} 
		else {
			$this->campus = NULL;
		}
		$this->active = "no";
		$error = array();
		
		//check to see if username is already in use
		$sql = "SELECT count(*) AS count FROM `users` WHERE `username` = '$this->username' AND `active` = 'yes'";
		$result = db()->query($sql);
		$row = $result->fetch_object();
		if($row->count > 0){
			$error['username_error'] = "Username is already registered";
		}
		else{
			//check to see if email is already in use
			$sql = "SELECT count(*) AS count FROM `users` WHERE `email` = '$this->email' AND `active` = 'yes'";
			$result = db()->query($sql);
			$row = $result->fetch_object();
			if($row->count > 0){
				$error['email_error'] = "Email is already registered";
			}
			else{
				//create the user
				$sql = "INSERT INTO `users`(username, last_name, first_name, password, email, position, service_area, campus, active, val_key)
				VALUES('$this->username','$this->last_name','$this->first_name',
					   '$this->password','$this->email','$this->position',
					   '$this->service_area','$this->campus','$this->active','$this->val_key')";
				$result = db()->query($sql);
				if($result){
					$emailDomain = explode("@", $this->email);
					if ($emailDomain[1] === "kp.org"){
						$this->sendRegisterEmailKP();
					}
					else {
						$this->sendRegisterEmail();
					}
					return $error;
				}
				else {
					//echo $sql;
					//echo db()->error;
				}
			}
		}	
		return $error;
	}
	
	function createNonKPUser(){
		//sql injection cleaning;
		$this->username = db()->real_escape_string($_POST['username']);
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		$this->password = sha1(db()->real_escape_string($_POST['password']).$salt);
		$this->email = db()->real_escape_string(strtolower($_POST['email']));
		$this->first_name = db()->real_escape_string(ucfirst(strtolower($_POST['first_name'])));
		$this->last_name = db()->real_escape_string(ucfirst(strtolower($_POST['last_name'])));
		$this->val_key = sha1(rand(1,20).$this->username.rand(1,20));
		$this->active = "no";
		$error = array();
		
		//check to see if username is already in use
		$sql = "SELECT count(*) AS count FROM `users` WHERE `username` = '$this->username' AND `active` = 'yes'";
		$result = db()->query($sql);
		$row = $result->fetch_object();
		if($row->count > 0){
			$error['username_error'] = "Username is already registered";
		}
		else {
			//check to see if email is already in use
			$sql = "SELECT count(*) AS count FROM `users` WHERE `email` = '$this->email' AND `active` = 'yes'";
			$result = db()->query($sql);
			$row = $result->fetch_object();
			if($row->count > 0){
				$error['email_error'] = "Email is already registered";
			}
			else{
				//create the user
				$sql = "INSERT INTO `users`(username, last_name, first_name, password, email, position, service_area, campus, active, val_key)
				VALUES('$this->username','$this->last_name','$this->first_name','$this->password','$this->email','NULL','NULL', 'NULL', '$this->active','$this->val_key')";
				$result = db()->query($sql);
				if ($result){
					$this->sendRegisterEmailCommunity();
				}
				
			}
		}	
		return $error;
	}
	
	function emailConfirmKP(){
		$error = array();
		if (isset($_GET['login']) && isset($_GET['code'])){
			$username = $_GET['login'];
			$code = $_GET['code'];
			$sql = "SELECT * FROM `users` WHERE `val_key` = '$code' AND `username` = '$username' AND `active` = 'no'";
			$result = db()->query($sql);
			if ($result && $result->num_rows > 0){
				$sql = "UPDATE `users` SET `active` = 'yes', `kp_employee` = 'yes' WHERE `val_key` = '$code' AND `username` = '$username' AND `active` = 'no' LIMIT 1";
				db()->query($sql);
				$sql = "DELETE FROM `users` WHERE `username` = '$this->username' AND `active` = 'no'";
				db()->query($sql);
				return $error;
			}
			else {
				$error['db_error'] = "There was an error with the database.";
				return $error;
			}
		}
		else {
			$error['db_error'] = "If you are seeing this error, please copy the link from your email in its entirety and paste it into your browser's URL bar then press enter to process your registration.";
			return $error;
		}
	}
	
	function emailConfirm(){
		$error = array();
		$username = $_GET['login'];
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `active` = 'no'";
		$result = db()->query($sql);
		if ($result && $result->num_rows > 0){
			$sql = "UPDATE `users` SET `active` = 'yes' WHERE `username` = '$username' AND `active` = 'no' LIMIT 1";
			db()->query($sql);
			$sql = "DELETE FROM `users` WHERE `username` = '$this->username' AND `active` = 'no'";
			db()->query($sql);
			return $error;
		}
		else {
			$error['db_error'] = "There was an error with the database.";
			return $error;
		}
	}
	
	function sendRegisterEmailKP(){
		$to = $this->email;
		$link = 'http://www.ambulatorypractice.org/user/email-confirmation-kp?code='.$this->val_key.'&login='.$this->username.'';
		$subject = 'AmbulatoryPractice.org';
		$message = '
					<html>
					<head>
					<title>'.$subject.'</title>
					</head>
					<body>
					<p>Hello, '.$this->first_name.'</p>
					<p>Thank you for creating a profile at <a href="http://ambulatorypractice.org">AmbulatoryPractice.org</a>. Please click the following link to complete the registration process.</p>
					<p><a href="'.$link.'">'.$link.'</a></p>
					<p>Thank you,<br/>
					AmbulatoryPractice.org<br/>
					SCPMG Regional Education & Research<br/>
					(626) 564-3082, internal tie-line 338</p>
					</body>
					</html>
					';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
		// Additional headers
		$headers .= 'From: AmbulatoryPractice.org <no-reply@ambulatorypractice.org>' . "\r\n";
						
		// Mail it
		mail($to, $subject, $message, $headers);
	}
	
	function sendRegisterEmail(){
		$to = $this->email;
		$link = 'http://www.ambulatorypractice.org/user/email-confirmation-kp?code='.$this->val_key.'&login='.$this->username.'';
		$subject = 'AmbulatoryPractice.org';
		$message = '
					<html>
					<head>
					<title>'.$subject.'</title>
					</head>
					<body>
					<p>Hello, '.$this->first_name.'</p>
					<p>Thank you for creating a profile on <a href="http://ambulatorypractice.org>AmbulatoryPractice.org</a>. You are receiving this email because you registered as a Kaiser Permanente employee or physician and provided a personal email address. For security purposes, your NUID will need to be verified before access to Kaiser Permanente pages is granted. <strong>Please complete both steps below to finish your registration and obtain full access to the Kaiser Permanente section of the website.</strong></p>
					<ol>
					<li>Click the following link to confirm your request for a profile: <a href="'.$link.'">'.$link.'</a></li>
					<li>Email Amanda.C.Schwartz@kp.org (or forward this email) a request for access to the Kaiser Permanente pages along with your full name, NUID#, and complete date of birth (month/day/year).</li>
					</ol>
					<p>Thank you,<br/>
					AmbulatoryPractice.org<br/>
					SCPMG Regional Education & Research<br/>
					(626) 564-3082, internal tie-line 338</p>
					</body>
					</html>
					';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
		// Additional headers
		$headers .= 'From: AmbulatoryPractice.org <no-reply@ambulatorypractice.org>' . "\r\n";
						
		// Mail it
		mail($to, $subject, $message, $headers);	
	}
	
	function sendRegisterEmailCommunity(){
		$to = $this->email;
		$link = 'http://www.ambulatorypractice.org/user/email-confirmation?login='.$this->username.'';
		$subject = 'AmbulatoryPractice.org';
		$message = '
					<html>
					<head>
					<title>'.$subject.'</title>
					</head>
					<body>
					<p>Hello, '.$this->first_name.'</p>
					<p>Thank you for registering at AmbulatoryPractice.org. Please click the following link to complete the process.</p>
					<p><a href="'.$link.'">'.$link.'</a></p>
					<p>If you are a Kaiser Permanente employee but were not able to use a @KP.org email address during registration you will need to send us an email with the "Contact Us" section to gain access as an employee. Sorry to the inconvenience.</p>
					<p>Thanks, </p>
					<p>AmbulatoryPractice.org</p>
					</body>
					</html>
					';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
		// Additional headers
		$headers .= 'From: AmbulatoryPractice.org <no-reply@ambulatorypractice.org>' . "\r\n";
						
		// Mail it
		mail($to, $subject, $message, $headers);
	}
	
	function forgotUsername(){
		$email = db()->real_escape_string($_POST['email']);
		if (preg_match("/\b^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$\b/", $email)){
			$sql = "SELECT `username` FROM `users` WHERE `email` = '$email' LIMIT 1";
			$result = db()->query($sql);
			if ($result && $result->num_rows > 0){
				$row = $result->fetch_object();
				$username = $row->username;
				$this->sendUsernameEmail($username, $email);
			}
			else {
				$error['email'] = 'Our records indicate there is no username associated with that email address.';
				return $error;
			}
		}
		else {
			$error['email'] = 'Please enter a valid email address';
			return $error;
		}
	}
	
	function sendUsernameEmail($username, $email){
		$to = $email;
		$subject = 'Your AmbulatoryPractice.org Username';
		$message = '
					<html>
					<head>
					<title>'.$subject.'</title>
					</head>
					<body>
					<p>Hello,</p>
					<p>Below is the username that is a active AmbulatoryPractice.org account connected to the email address: '.$email.'
					<ul><li>'.$username.'</li></ul>
					</body>
					</html>
					';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
		// Additional headers
		$headers .= 'From: AmbulatoryPractice.org <no-reply@ambulatorypractice.org>' . "\r\n";
						
		// Mail it
		mail($to, $subject, $message, $headers);
	}
	
	function forgotPassword($username){
		$username = db()->real_escape_string($username);
		$sql = "SELECT `first_name`, `email` FROM `users` WHERE `username` = '$username' LIMIT 1";
		$result = db()->query($sql);
		if ($result && $result->num_rows > 0){
			$row = $result->fetch_object();
			//create new val_key
			$time = time();
			$val_key = sha1($row->first_name.$username.$time);
			//update database
			$sql = "UPDATE `users` SET `val_key` = '$val_key' WHERE username = '$username' LIMIT 1";
			if (db()->query($sql)){
				$this->sendPasswordResetEmail($row, $val_key, $username);
				$emailSent = true;
			}
			else {
				$emailSent = 'dbError';
			}
		}
		else {
				$emailSent = 'noUser';
		}
		return $emailSent;
	}
	
	function sendPasswordResetEmail($row, $val_key, $username){
		$to = $row->email;
		$link = 'http://www.ambulatorypractice.org/user/reset-password/'.$username.'/'.$val_key.'';
		$subject = 'Reset Your AmbulatoryPractice.org Password';
		$message = '
					<html>
					<head>
					<title>'.$subject.'</title>
					</head>
					<body>
					<p>Hello, '.$row->first_name.'</p>
					<p>Someone (hopefully you) has asked us to reset the password for your AmbulatoryPractice account. Please follow the link below to do so. If you did not request this password reset, you can go ahead and ignore this email.</p>
					<p><a href="'.$link.'">'.$link.'</a></p>
					<p>Thanks, </p>
					<p>Admin at AmbulatoryPractice.org</p>
					</body>
					</html>
					';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
		// Additional headers
		$headers .= 'From: AmbulatoryPractice.org <no-reply@ambulatorypractice.org>' . "\r\n";
						
		// Mail it
		mail($to, $subject, $message, $headers);
	}
	
	function resetPasswordfromEmail($username, $val_key){
		//check val_key and username
		$error = array();
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `val_key` = '$val_key' LIMIT 1";
		$result = db()->query($sql);
		if ($result && $result->num_rows > 0){
			//$error = 'User was found';
			return;
		}
		else{
			$notice['error'] = 'Opps there was an error. Discard the old email and please try again.';
			return $notice;
		}
	}
	
	function resetPasswordEmailProcess(){
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		$this->password = sha1(db()->real_escape_string($_POST['password']).$salt);
		$this->username = $_POST['username'];
		$this->val_key = $_POST['val_key'];
		$sql = "UPDATE `users` SET `password` = '$this->password' WHERE `username` = '$this->username' AND `val_key` = '$this->val_key'";
		$result = db()->query($sql);
		if ($result){
			$notice['success'] = true;	
		}
		else {
			$notice['error'] = "There was a error with the database. Please try again.";
		}
		return $notice;
	}
	
	function sendContactUsEmail(){
		if(isset($_POST['submit'])){
			if(!empty($_POST['name'])){
				if(!empty($_POST['email'])){
					if (preg_match("/\b^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$\b/", $_POST['email'])){ //email address is correctly formatted
						if(!empty($_POST['phone'])){
							if(!empty($_POST['message'])){
					
							//fill variables
							$name = $_POST['name'];
							$email = $_POST['email'];
							$phone = $_POST['phone'];
							$subject = $_POST['subject'];
							$message = $_POST['message'];
							
							//recepitents
							if ($subject == 'Technical Question'){
								$to = 'Bryun.T.Sakaye@kp.org';
							}
							else {
								$to = 'Amanda.C.Schwartz@kp.org';
							}
							
							//subject
							$subject = 'Email from AmbPrac.org - '.$subject;
							
							//message
							$message = '
							<html>
							<head>
							<title>'.$subject.'</title>
							</head>
							<body>
								<p>This message was sent to you from the AmbulatoryPractice.org server. The message is as follows.</p>
								<p>*************************************************************************************************</p>
								<p>'.$message.'</p>
								<p>*************************************************************************************************</p>
								<p>From: '.$name.'</p>
								<p>Email: '.$email.'</p>
								<p>Phone Number: '.$phone.'</p>
							</body>
							</html>
							';
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							
							// Additional headers
							$headers .= 'From: '.$name.' <'.$email.'>' . "\r\n";
							
							// Mail it
							mail($to, $subject, $message, $headers);
							$error['sent'] = "Your email has been sent";
							}
							else{ $error['message'] = "Message can not be blank"; }
						}
						else{ $error['phone'] = "Phone number can not be blank"; }
					}
					else { $error['email'] = "Email format is incorrect"; }
				}
				else{ $error['email'] = "Email address can not be blank"; }
			}
			else{ $error['name'] = "Name can not be blank";	}
			return $error;
		}
	}

	public static function logout_User(){
		global $app;
		unset($_SESSION['First_name']);
		unset($_SESSION['Logged_in']);
		unset($_SESSION['User_ID']);
		unset($_SESSION['kp_employee']);
		$app->deleteCookie('reloginID', '/', '.ambulatorypractice.org');
	}

	public static function check_registration(){
		$error = array();
		//check username for bad characters
		if (!empty($_POST['username'])){
			if (preg_match("/[^a-zA-Z0-9]+$/", $_POST['username'])) {
				$error['username_error'] = "Username contains invalid character(s)"; 
			}
		}
		else {
			$error['username_error'] = "Username can not be blank";
		}
		//check passwords
		if (!empty($_POST['password']) && (!empty($_POST['confirm_password']))){
			if ($_POST['password'] == $_POST['confirm_password']){
				if (preg_match("/^(?=.*\d+)(?=.*[a-zA-Z])[0-9a-zA-Z!@#$%]{6,}$/", $_POST['password'])){
					//password is valid
				}
				else {
					$error['password_error'] = "Password contains invalid character(s)";
				}
			}
			else {
				$error['password_error'] = "Passwords do not match";
				$error['comfirmpassword_error'] = "Passwords do not match";
			}
		}
		else {
			if (empty($_POST['password'])){
				$error['password_error'] = "Password can not be left blank";
			}
			if (empty($_POST['confirm_password'])){
				$error['comfirmpassword_error'] = "Confirm password can not be left blank";
			}
		}
		//check first/last name for bad characters
		if (!empty($_POST['first_name'])){
			if (preg_match("/[^a-zA-Z]+$/", $_POST['first_name'])) { $error['firstname_error'] = "First name contains invalid character(s)"; }
		}
		else { $error['firstname_error'] = "First name can not be blank"; }
		
		if (!empty($_POST['last_name'])){
			if (preg_match("/[^a-zA-Z]+$/", $_POST['last_name'])) { $error['lastname_error'] = "Last name contains invalid character(s)"; }
		}
		else {
			$error['lastname_error'] = "Last name can not be blank";
		}
		//check valid email
		if (!empty($_POST['email'])){
			if (preg_match("/\b^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$\b/", $_POST['email'])) {
				//email address is correctly formatted
			}
			else { $error['email_error'] = "Email format is incorrect"; }
		}
		else {
			$error['email_error'] = "Email can not be blank";
		}
		//check title/area isn't blank
		if (empty($_POST['position'])){
			$error['position_error'] = "Title can not be blank";
		}
		if (empty($_POST['area'])){
			$error['area_error'] = "Area can not be blank";
		}	
		return $error;
	}
	
	public static function check_registrationNonKP(){
		$error = array();
		//check username for bad characters
		if (!empty($_POST['username'])){
			if (preg_match("/[^a-zA-Z0-9]+$/", $_POST['username'])) {
				$error['username_error'] = "Username contains invalid character(s)"; 
			}
		}
		else {
			$error['username_error'] = "Username can not be blank";
		}
		//check passwords
		if (!empty($_POST['password']) && (!empty($_POST['confirm_password']))){
			if ($_POST['password'] == $_POST['confirm_password']){
				if (preg_match("/^(?=.*\d+)(?=.*[a-zA-Z])[0-9a-zA-Z!@#$%]{6,}$/", $_POST['password'])){
					//password is valid
				}
				else {
					$error['password_error'] = "Password contains invalid character(s)";
				}
			}
			else {
				$error['password_error'] = "Passwords do not match";
				$error['comfirmpassword_error'] = "Passwords do not match";
			}
		}
		else {
			if (empty($_POST['password'])){
				$error['password_error'] = "Password can not be left blank";
			}
			if (empty($_POST['confirm_password'])){
				$error['comfirmpassword_error'] = "Confirm password can not be left blank";
			}
		}
		//check first/last name for bad characters
		if (!empty($_POST['first_name'])){
			if (preg_match("/[^a-zA-Z]+$/", $_POST['first_name'])) { $error['firstname_error'] = "First name contains invalid character(s)"; }
		}
		else { $error['firstname_error'] = "First name can not be blank"; }
		
		if (!empty($_POST['last_name'])){
			if (preg_match("/[^a-zA-Z]+$/", $_POST['last_name'])) { $error['lastname_error'] = "Last name contains invalid character(s)"; }
		}
		else {
			$error['lastname_error'] = "Last name can not be blank";
		}
		//check valid email
		if (!empty($_POST['email'])){
			if (preg_match("/\b^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$\b/", $_POST['email'])) {
				//email address is correctly formatted
			}
			else { $error['email_error'] = "Email format is incorrect"; }
		}
		else {
			$error['email_error'] = "Email can not be blank";
		}
		return $error;
	}
}

?>

