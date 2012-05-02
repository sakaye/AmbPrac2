<?php
class User{
	public $username, $last_name, $first_name, $password, $email, $kp_employee, $reloginDigest, $title, $area, $active, $val_key, $creation_date;
	
	function __construct($username = null){
		if($username !== null){
			$this->getUser($username);
		}
	}

	function getUser($username){
		$username = mysql_real_escape_string($_POST['username']);
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		$password = sha1(mysql_real_escape_string($_POST['password']).$salt);
		
		$sql = "SELECT * FROM users WHERE `username` = '$username' LIMIT 1";
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
		$this->username = $row->username;
		$this->last_name = $row->last_name;
		$this->first_name = $row->first_name;
		$this->password = $row->password;
		$this->email = $row->email;
		$this->kp_employee = $row->kp_employee;
		$this->reloginDigest = $row->reloginDigest;
		$this->title = $row->title;
		$this->area = $row->area;
		$this->active = $row->active;
		
	}

	function loginUser($username, $password){
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		
		//clean variables
		
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
		$result = db()->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_object();
			$this->fillData($row);
			$this->setSessions();
			return true;
		}else{
			return false;
		}
	}

	function setSessions(){
		//cookie or session here.
		//name or small common used things
		//isAdmin 
		//primary key
		$_SESSION['name'] = $this->last_name . "," . $this->first_name;
	}

	function createUser($obj){
		$salt = "1234124k12ljKJSDklasjdkljj214l1j24j";
		//sql injection cleaning;
		$sql = "INSERT INTO users VALUES('$obj->username','$obj->last_name','$obj->first_name','$obj->password','$obj->email',1,'test','$obj->title','$obj->area',1,'val_key', NULL)";
		if(db()->query($sql)){
			$this->setSessions();
			return true;
		}else{
			echo $sql;
			echo db()->error;
			return false;
		}
	}

	function isEmployee(){
		return $kp_employee === 1;
	}

}

?>