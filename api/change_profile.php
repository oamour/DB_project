<?php
include 'functions.php';

session_start();

$userid = check_session();

# Submission handler
if(isset($_POST['update']) and isset($userid) and $userid != false){
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  mysqli_query($myconnection, "START TRANSACTION");
  
  # Receive data from POST header
  $pass = $_POST['password'];
  $pass_confirm = $_POST['pass_confirm'];
  $name = $_POST['name'];
  $phone_num = $_POST['phone_num'];
  $city = $_POST['city'];
  $state = $_POST['state'];

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
	$query = "UPDATE users SET name = '$name', phone = '$phone_num', city = '$city', state = '$state' WHERE userID = $userid";
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
	  echo "Failed to update user info. Please try again.";
	} else {
	  # User creation succeeded; commit transaction
	  mysqli_query($myconnection, "COMMIT;");
	  $message = "Success! Profile updated.";  
	  mysqli_close($myconnection);
	}
  } else {
	mysqli_close($myconnection);
	$message = "Failure! Invalid input.";
  }
}

if (isset($userid) and $userid != false) {
  #start MySQL connection
  $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $row = get_user_info($myconnection, $userid);
  
  $name = $row['name'];
  $phone_num = $row['phone'];
  $city = $row['city'];
  $state = $row['state'];
}
?>