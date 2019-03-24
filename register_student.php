<?php
# default values for each field
$student_email = "";
$parent_email = "";
$pass = "";
$pass_confirm = "";
$role = "";
$grade = "";
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
  $student_email = $_POST['student_email'];
  $parent_email = $_POST['parent_email'];
  $pass = $_POST['password'];
  $pass_confirm = $_POST['pass_confirm'];
  $role = $_POST['role'];
  $grade = $_POST['grade'];
  $name = $_POST['name'];
  $phone_num = $_POST['phone_num'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  
  $input_valid = 1;

  # Verify student email address
  # $student_email_valid = preg_match("/[^@]+@[^@]+\.[^@]+/", $student_email);
  $email_regex = "/^[^\s@]+@([^\s@]+\.[^\s@]+[^\s@\.]|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/";
  $student_email_valid = preg_match($email_regex, $student_email);
  if($student_email_valid == 0) {
	  $input_valid = 0;
  } else {
	# Check whether email address exists in database
	$result = mysqli_query($myconnection, "SELECT * FROM accounts WHERE username = '" . $student_email . "'");
	if($result->num_rows > 0) {
	  $student_email_already_exists = 1;
	  $input_valid = 0;
    }
  }
  
  # Verify parent email address
  $parent_email_valid = preg_match($email_regex, $parent_email);
  if($parent_email_valid == 0) {
	  $input_valid = 0;
  } else {
	# Check whether parent with given email address exists in database
	$result = mysqli_query($myconnection, "SELECT userID FROM accounts WHERE username = '" . $parent_email . "'");
	
	if($result->num_rows > 0) {
	  # Email exists, check that associated account is a parent
	  $row = $result->fetch_row();
	  $parent_id = $row[0];
	  $result = mysqli_query($myconnection, "SELECT isParent FROM users WHERE userID = " . $parent_id);
	  
	  if($result->num_rows > 0) {
	    $row = $result->fetch_row();
		$is_parent = $row[0];
		
		if($is_parent == 0) {
		  $parent_email_is_parent = 1;
		  $input_valid = 0;
		}
	  } else {
		  # Should never get here
		  die ("No userid matching account ID#" . $row[0] . "; " . mysqli_error($myconnection)); 
	  }
    } else {
	  $parent_email_is_parent = 1;
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
	$query = "INSERT INTO users(name, email, phone, gradeLevel, isParent, isStudent, city, state) VALUES('" . $name . "', '" . $student_email . "', '" . $phone_num_sanitized . "', " . $grade . ", 0, 1, '" . $city . "', '" . $state . "')";
    $result = mysqli_query($myconnection, $query);
	$new_user_id = mysqli_insert_id($myconnection);
	
	# create account for user
	if($result != false) {
	  $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
	  $query = "INSERT INTO accounts(userID, username, hash) VALUES(" . $new_user_id . ", '" . $student_email . "', '" . $hashed_pass . "')";
	  $result = mysqli_query($myconnection, $query);
	}
	
	# create mentee and mentor listings if applicable
	if (($role == "mentee" or $role == "both") and $result != false) {
	  $query = "INSERT INTO mentees(menteeID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
	  $result = mysqli_query($myconnection, $query);
	}
	if (($role == "mentor" or $role == "both") and $result != false) {
	  $query = "INSERT INTO mentors(mentorID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
	  $result = mysqli_query($myconnection, $query);
	}
	
	# create parent listing
	if(isset($parent_id) and $result != false) {
		$query = "INSERT INTO parentChild(parentID, childID) VALUES(" . $parent_id . ", " . $new_user_id . ")";
		$result = mysqli_query($myconnection, $query);
	}
	
	# if failed to create account, remove user data
	if($result == false) {
	  # Close connection rolls back transaction
	  mysqli_query($myconnection, "ROLLBACK");
	  mysqli_close($myconnection);
	  echo "Failed to create new user. Please try again.";
	} else {
	  # User creation succeeded; commit transaction
	  mysqli_query($myconnection, "COMMIT");
	  $message = "Success! New user created."; 
	  mysqli_close($myconnection);
	}
  } else {
	mysqli_query($myconnection, "ROLLBACK");
	mysqli_close($myconnection);
	$message = "Failure! Invalid input.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>School Database</title>
</head>
<body>
	<a href="index.php">Back to Start</a>
	<h1>Register as Student</h1>
	<form method="post" action="register_student.php">
		<?php if(isset($message)){
			echo $message; 
		} ?>
		<h3>Account Info</h3>
		<table>
			<tr>
				<td><label for="student_email">Student Email:</label></td>
				<td><input type="text" name="student_email" id="student_email" value="<?php echo $student_email ?>" required></td>
				<?php if(isset($student_email_valid) and $student_email_valid == 0) echo "<td>Entered email address is invalid.</td>"; ?>
				<?php if(isset($student_email_already_exists) and $student_email_already_exists == 1) echo "<td>Account with this email address already exists</td>"; ?>
			</tr>
			<tr>
				<td><label for="parent_email">Parent Email:</label></td>
				<td><input type="text" name="parent_email" id="parent_email" value="<?php echo $parent_email ?>" required></td>
				<?php if(isset($parent_email_valid) and $parent_email_valid == 0) echo "<td>Entered email address is invalid.</td>"; ?>
				<?php if(isset($parent_email_is_parent) and $parent_email_is_parent == 1) echo "<td>Email address not associated with parent</td>"; ?>
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
					<option value="mentee">Mentee</option>
					<option value="mentor">Mentor</option>
					<option value="both">Mentee and Mentor</option>
				</select></td>
			</tr>
			<tr>
				<td><label for="grade">Grade Level:</label></td>
				<td><select name="grade" id="grade">
					<option value="1">Freshman</option>
					<option value="2">Sophomore</option>
					<option value="3">Junior</option>
					<option value="4">Senior</option>
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
