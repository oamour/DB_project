<?php
function check_credentials ($username, $pass) {
	# connect to db
	$myconnection = mysqli_connect('localhost', 'root', '') 
	or die ('Could not connect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

	$query = "SELECT * FROM accounts WHERE username = '" . $username . "'";
	$result = mysqli_query($myconnection, $query) or die("Failed to retrieve account info for " . $username);
	
	$user_info = (object)[];
	$user_info->success = 0;
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		if ($username == $row["username"] and password_verify($pass, $row["hash"])) {
			//account exists
			$user_info->success = 1;
			$userid = $row["userID"];
			//get user info
			$query = "SELECT userID, name, email, gradeLevel, isParent FROM users WHERE userID = " . $userid;
			$result = mysqli_query($myconnection, $query) or die("Failed to retrieve user info for " . $username);
			
			if(mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				$user_info->name = $row["name"];
				$user_info->userID = $row["userID"];
				$user_info->email = $row["email"];
				$user_info->gradeLevel = $row["gradeLevel"];
				if($row["isParent"] == 1) {
					$user_info->isParent = true;
				} else {
					$user_info->isParent = false;
				}
				
			}
			
			//get whether user is moderator, mentor, mentee
			$query = "SELECT * FROM mentors WHERE userID = " . $userid;
			$result = mysqli_query($myconnection, $query) or die("Failed to retrieve mentor info for " . $username);
			if(mysqli_num_rows($result) > 0) {
				$user_info->isMentor = true;
			} else {
				$user_info->isMentor = false;
			}
			
			$query = "SELECT * FROM mentees WHERE userID = " . $userid;
			$result = mysqli_query($myconnection, $query) or die("Failed to retrieve mentor info for " . $username);
			if(mysqli_num_rows($result) > 0) {
				$user_info->isMentee = true;
			} else {
				$user_info->isMentee = false;
			}
			
			$query = "SELECT * FROM moderators WHERE userID = " . $userid;
			$result = mysqli_query($myconnection, $query) or die("Failed to retrieve mentor info for " . $username);
			if(mysqli_num_rows($result) > 0) {
				$user_info->isModerator = true;
			} else {
				$user_info->isModerator = false;
			}
		}
	}
	
	return json_encode($user_info);
}

$value = json_decode(file_get_contents('php://input'));

//DEBUG
/*if ($value == null) {
	$value = (object)[];
	$value->email = "nickbishop97@gmail.com";
	$value->pass = "testtest";
}*/

if ($value != null) {
	$email = $value->email;
	$pass = $value->pass;
	
	$user_info = check_credentials($email, $pass);
	
	header('Content-Type: application/json');
	echo $user_info;
}
?>