<?php
include 'functions.php';

session_start();

$userid = check_session();

if (isset($_GET["childID"])) {
  $childID = $_GET["childID"];
  echo $childID;
} else {
  $childID = false;
}

# Submission handler
if(isset($_POST['register']) and isset($userid) and $userid != false){
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
  
  $childID = $_POST['childID'];
  
  $is_parent = check_is_parent($myconnection, $parentID, $childID);

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
	$query = "UPDATE users SET name = '$name', phone = '$phone_num', city = '$city', state = '$state' WHERE userID = $childID";
    $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	
	#update password if set
	if($result != false and $pass != "") {
		$hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
		$query = "UPDATE accounts SET hash = '$hashed_pass' WHERE userID = $childID";
		$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	}
	/*
	# create mentee and mentor listings if applicable
	if (($role == "mentee" or $role == "both") and $result != false) {
	  $query = "INSERT INTO mentees(menteeID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
	  $result = mysqli_query($myconnection, $query);
	}
	if (($role == "mentor" or $role == "both") and $result != false) {
	  $query = "INSERT INTO mentors(mentorID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
	  $result = mysqli_query($myconnection, $query);
	}
	*/
	
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

if (isset($userid) and $userid != false and isset($childID) and $childID != false) {
  #start MySQL connection
  $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $row = get_user_info($myconnection, $childID);
  
  $is_parent = check_is_parent($myconnection, $userid, $childID);
  
  $name = $row['name'];
  $phone_num = $row['phone'];
  $city = $row['city'];
  $state = $row['state'];
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php if (isset($userid) and $userid != false) : ?>
	<a href="dashboard.php">Back to Dashboard</a>
	<h1>Change Profile as Student</h1>
	<form method="post" action="child_profile_parent.php">
		<?php if(isset($message)){
			echo $message; 
		} ?>
		<input type="hidden" name="childID" value="<?php echo $childID; ?>">
		<h3>Account Info</h3>
		<table>
			<tr>
				<td><label for="name">Name:</label></td>
				<td><input type="text" name="name" id="name" value="<?php echo $name ?>" required></td>
			</tr>
			<tr>
				<td><label for="password">Password:</label></td>
				<td><input type="password" name="password" id="password" ></td>
				<?php if(isset($pass_error_code) and $pass_error_code == 1) echo "<td>Passwords must be at least 8 characters long.</td>"; ?>
			</tr>
			<tr>
				<td><label for="pass_confirm">Confirm Password:</label></td>
				<td><input type="password" name="pass_confirm" id="pass_confirm" ></td>
				<?php if(isset($pass_error_code) and $pass_error_code == 2) echo "<td>Passwords do not match.</td>"; ?>
			</tr>
			<tr>
				<td><label for="phone_num">Phone#:</label></td>
				<td><input type="text" name="phone_num" id="phone_num" value="<?php echo $phone_num ?>" required></td>
				<?php if(isset($phone_num_matches) and $phone_num_matches == 0) echo "<td>Phone number must be ten numbers long.</td>" ?>
			</tr>
			<tr>
				<td><label for="city">City:</label></td>
				<td><input type="text" name="city" id="city" value="<?php echo $city ?>" required></td>
			</tr>
			<tr>
				<td><label for="state">State:</label></td>
				<td><input type="text" name="state" id="state" value="<?php echo $state ?>" required></td>
			</tr>
		</table>
		<input type="submit" name="register" id="register" value="Register">
	</form>
	<?php endif; ?>
</body>
</html>
