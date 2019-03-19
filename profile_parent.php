<?php
function get_user_info($myconnection, $userid) {
	$query = "SELECT * FROM users WHERE userid = " . $userid;
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) == 0) die("FATAL ERROR: Missing userdata for account '$userid'");
	
	return mysqli_fetch_array($result);
}

$session_key = md5("database");
session_start();

if (empty($_SESSION[$session_key])) {
	echo "Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page.";
} elseif (intval($_SESSION[$session_key]) <= 0) {
	unset($_SESSION[$session_key]);
	echo "Invalid session key! Please <a href='index.php'>CLICK HERE</a> to return to the main page.";
} else {
	$userid = $_SESSION[$session_key];
}

# Submission handler
if(isset($_POST['register']) and isset($userid)){
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
	/*
	# create moderator listing if applicable
	if ($role == "mod") {
	  $query = "INSERT INTO moderators(modID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
	  mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
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
	}
  } else {
	mysqli_close($myconnection);
	$message = "Failure! Invalid input.";
  }
}

if (isset($userid)) {
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
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php if (isset($userid)) : ?>
	<a href="dashboard.php">Back to Dashboard</a>
	<h1>Change Profile as Parent</h1>
	<form method="post" action="profile_parent.php">
		<?php if(isset($message)){
			echo $message; 
		} ?>
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
			<!--<tr>
				<td><label for="role">Role:</label></td>
				<td><select name="role" id="role">
					<option value="none">None</option>
					<option value="mod">Moderator</option>
				</select></td>
			</tr>-->
		</table>
		<input type="submit" name="register" id="register" value="Register">
	</form>
	<?php endif; ?>
</body>
</html>
