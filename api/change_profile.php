<?php
include 'functions.php';

$value = json_decode(file_get_contents('php://input'));

// DEBUG
/*if($value == null){
	$value = (object)[];
	$value->userID = 41;
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
  
  # Receive data from POST header
  $userid = $value->userID;
  $pass = $value->pass;
  $pass_confirm = $value->pass_confirm;
  $name = $value->name;
  $phone_num = $value->phone;
  $city = $value->city;
  $state = $value->state;

  $input_valid = 1;
  
  # Verify password (must be at least 8 characters
  if($pass != "" and strlen($pass) < 8) {
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
  
  if($input_valid == 1) {
	# update user
	$query = "UPDATE users SET name = '$name', phone = '$phone_num_sanitized', city = '$city', state = '$state' WHERE userID = $userid";
    $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	
	#update password if set
	if($result != false and $pass != "") {
		$hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
		$query = "UPDATE accounts SET hash = '$hashed_pass' WHERE userID = $userid";
		$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	}
	
	# if failed to create account, undo update
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
	$success = 0;
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
	} elseif (isset($pass_error_code) and $pass_error_code == 1) {
		$result_json->result = "3";
	} elseif (isset($pass_error_code) and $pass_error_code == 2) {
		$result_json->result = "4";
	} elseif (isset($phone_num_matches) and $phone_num_matches == 0) {
		$result_json->result = "5";
	} elseif (isset($success) and $success == 0) {
		$result_json->result = "6";
	} else {
		$result_json->result = "-1";
	}
	
	header('Content-Type: application/json');
	$encoded = json_encode($result_json);
	
	echo $encoded;
}

?>