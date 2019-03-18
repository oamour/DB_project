<?php
# default values for each field
$email = "";
$pass = "";
$pass_confirm = "";
$role = "";
$name = "";
$phone_num = "";
$city = "";
$state = "";

# Submission handler
if(isset($_POST['register'])){
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  mysqli_query($myconnection, "START TRANSACTION");
  
  # Receive data from POST header
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $pass_confirm = $_POST['pass_confirm'];
  $role = $_POST['role'];
  $name = $_POST['name'];
  $phone_num = $_POST['phone_num'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  
  $input_valid = 1;

  # Verify email address
  $email_regex = "/^[^\s@]+@([^\s@]+\.[^\s@]+[^\s@\.]|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/";
  $email_matches = preg_match($email_regex, $email);
  if($email_matches == 0) {
	  $input_valid = 0;
  } else {
	# Check whether email address exists in database
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
	$query = 'INSERT INTO users(name, email, phone, gradeLevel, isParent, isStudent) VALUES(\'' . $name . '\', \'' . $email . '\', \'' . $phone_num_sanitized . '\', NULL, 1, 0)';
    $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	$new_user_id = mysqli_insert_id($myconnection);
	
	# create account for user
	$hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
	$query = "INSERT INTO accounts(userID, username, hash) VALUES(" . $new_user_id . ", '" . $email . "', '" . $hashed_pass . "')";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	
	# create moderator listing if applicable
	if ($role == "mod") {
	  $query = "INSERT INTO moderators(modID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
	  mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error($myconnection));
	}
	
	# if failed to create account, remove user data
	if($result == false) {
	  # Close connection rolls back transaction
	  mysqli_close($myconnection);
	  echo "Failed to create new user. Please try again.";
	} else {
	  # User creation succeeded; commit transaction
	  mysqli_query($myconnection, "COMMIT;");
	  $message = "Success! New user created.";  
	}
  } else {
	mysqli_close($myconnection);
	$message = "Failure! Invalid input.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<a href="index.php">Back to Start</a>
	<h1>Register as Parent</h1>
	<form method="post" action="register_parent.php">
		<?php if(isset($message)){
			echo $message; 
		} ?>
		<h3>Account Info</h3>
		<table>
			<tr>
				<td><label for="email">Email:</label></td>
				<td><input type="text" name="email" id="email" value="<?php echo $email ?>" required></td>
				<?php if(isset($email_matches) and $email_matches == 0) echo "<td>Entered email address is invalid.</td>"; ?>
				<?php if(isset($email_already_exists) and $email_already_exists == 1) echo "<td>Account with this email address already exists</td>"; ?>
			</tr>
			<tr>
				<td><label for="password">Password:</label></td>
				<td><input type="password" name="password" id="password" required></td>
				<?php if(isset($pass_error_code) and $pass_error_code == 1) echo "<td>Passwords must be at least 8 characters long.</td>"; ?>
			</tr>
			<tr>
				<td><label for="pass_confirm">Confirm Password:</label></td>
				<td><input type="password" name="pass_confirm" id="pass_confirm" required></td>
				<?php if(isset($pass_error_code) and $pass_error_code == 2) echo "<td>Passwords do not match.</td>"; ?>
			</tr>
			<tr>
				<td><label for="role">Role:</label></td>
				<td><select name="role" id="role">
					<option value="none">None</option>
					<option value="mod">Moderator</option>
				</select></td>
			</tr>
		</table>
		<h3>Personal Info</h3>
		<table>
			<tr>
				<td><label for="name">Name:</label></td>
				<td><input type="text" name="name" id="name" value="<?php echo $name ?>" required></td>
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
</body>
</html>
