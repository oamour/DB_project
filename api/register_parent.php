<?php

$value = json_decode(file_get_contents('php://input'));

// DEBUG
/*if($value == null){
	$value = (object)[];
	$value->email = "nickbishop97@gmail.com";
	$value->pass = "testtest";
	$value->pass_confirm = "testtest";
	$value->role = "Moderator";
	$value->name = "Nick Bishop";
	$value->phone = "7777777777";
	$value->city = "Nahant";
	$value->state = "MA";
}*/

if ($value != null){
	$myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	mysqli_query($myconnection, "START TRANSACTION");
  
	
	$email = $value->email;
	$pass = $value->pass;
	$pass_confirm = $value->pass_confirm;
	$role = $value->role;
	$name = $value->name;
	$phone_num = $value->phone;
	$city = $value->city;
	$state = $value->state;

	$input_valid = 1;

	# Verify email address
	$email_regex = "/^[^\s@]+@([^\s@]+\.[^\s@]+[^\s@\.]|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/";
	$email_matches = preg_match($email_regex, $email);
	
	if($email_matches == 0) {
		$input_valid = 0;
	} else {
		# Check whether email address exists in database
		file_put_contents($file, "Email does match!\r\n", FILE_APPEND | LOCK_EX);
		$result = mysqli_query($myconnection, "SELECT * FROM accounts WHERE username = '" . $email . "'");
		if($result->num_rows > 0) {
			$email_already_exists = 1;
			$input_valid = 0;
		}
	}
	
	# Verify password (must be at least 8 characters
	if(strlen($pass) < 8) {
		$input_valid = 0;
		$pass_error_code = 1; 
	} elseif($pass != $pass_confirm) {
		$input_valid = 0;
		$pass_error_code = 2;
	}
	
	# Verify phone number
	$phone_num_sanitized = preg_replace("/[^\d]/","", $phone_num);
	$phone_num_matches = preg_match("/^\d{10}$/", $phone_num_sanitized);
	if($phone_num_matches == 0) {
		$input_valid = 0;
	}

	# add user to database if valid
	if($input_valid == 1) {
		# create user
		$query = "INSERT INTO users(name, email, phone, gradeLevel, isParent, isStudent, city, state) VALUES('" . $name . "', '" . $email . "', '" . $phone_num_sanitized . "', NULL, 1, 0, '" . $city . "', '" . $state . "')";
		$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
		$new_user_id = mysqli_insert_id($myconnection);
		
		# create account for user
		$hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
		$query = "INSERT INTO accounts(userID, username, hash) VALUES(" . $new_user_id . ", '" . $email . "', '" . $hashed_pass . "')";
		$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
		
		# create moderator listing if applicable
		if ($role == "Moderator") {
			$query = "INSERT INTO moderators(modID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
			mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
		}
		
		# if failed to create account, remove user data
		if($result == false) {
			# Close connection rolls back transaction
			mysqli_close($myconnection);
			$success = 0;
		} else {
			# User creation succeeded; commit transaction
			mysqli_query($myconnection, "COMMIT;");
			$success = 1;
			mysqli_close($myconnection);
		}
	} else {
		mysqli_close($myconnection);
	}
	# ERROR CODES:
	# 1 - email not valid
	# 2 - email already registered
	# 3 - password does not meet requirements
	# 4 - password confirmation failure
	# 5 - phone number invalid
	# 6 - Database error
	
	$result_json = (object)[];
	if(isset($success) and $success == 1) {
		$result_json->result = "0";
	} elseif (isset($email_matches) and $email_matches == 0) {
		$result_json->result = "1";
	} elseif (isset($email_already_exists) and $email_already_exists == 1) {
		$result_json->result = "2";
	} elseif (isset($pass_error_code) and $pass_error_code == 1) {
		$result_json->result = "3";
	} elseif (isset($pass_error_code) and $pass_error_code == 2) {
		$result_json->result = "4";
	} elseif (isset($phone_num_matches) and $phone_num_matches == 0) {
		$result_json->result = "5";
	} elseif (isset($success) and $success == 0) {
		$result_json->result = "6";
	} else {
		$result_json->result = "7";
	}
	
	header('Content-Type: application/json');
	$encoded = json_encode($result_json);
	
	echo $encoded;
}
?>