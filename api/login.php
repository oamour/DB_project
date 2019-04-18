<?php
function check_credentials ($username, $pass) {
	# connect to db
	$myconnection = mysqli_connect('localhost', 'root', '') 
	or die ('Could not connect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

	$query = "SELECT * FROM accounts WHERE username = '" . $username . "'";
	$result = mysqli_query($myconnection, $query) or die("Failed to retrieve account info for " . $username);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		if ($username != $row["username"] or !password_verify($pass, $row["hash"])) {
			return null;
		}
		
		//success, get user info
		$userid = $row["userID"];
		$query = "SELECT userID, name, email FROM users WHERE userID = " . $userid;
		$result = mysqli_query($myconnection, $query) or die("Failed to retrieve user info for " . $username);
		
		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$row["success"] = 1;
			return json_encode($row);
		}
		
	} else {
		return null;
	}
}

$session_key = md5("database");

$value = json_decode(file_get_contents('php://input'));

//DEBUG
if ($value == null) {
	$value = (object)[];
	$value->email = "nickbishop97@gmail.com";
	$value->pass = "testtest";
}

if ($value != null) {
	$email = $value->email;
	$pass = $value->pass;
	
	$user_info = check_credentials($email, $pass);
	
	if ($user_info != null) {
		return $user_info;
	} else {
		$user_info = (object)[];
		$user_info["success"] = 0;
		return json_encode($user_info);
	}
}
?>