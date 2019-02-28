<?php
$email = "";
$pass = "";
$pass_confirm = "";
$role = "";
$name = "";
$phone_num = "";
$city = "";
$state = "";

if(isset($_POST['register'])){ //check if form was submitted
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'school') or die ('Could not select database');
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $pass_confirm = $_POST['pass_confirm'];
  $role = $_POST['role'];
  $name = $_POST['name'];
  $phone_num = $_POST['phone_num'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  
  $input_valid = 1;

  $email_matches = preg_match("/[^@]+@[^@]+\.[^@]+/", $email);
  if($email_matches == 0) {
	  $input_valid = 0;
  }
  
  if($pass != $pass_confirm) {
	  $pass_matches == 0;
  }
  
  $phone_num_sanitized = preg_replace("/[^\d]/","", $phone_num);
  $phone_num_matches = preg_match("/\d{10}/", $phone_num_sanitized);
  echo $phone_num_sanitized . " - " . $phone_num_matches;
  
  #$query = 'SELECT DISTINCT title, length FROM movies WHERE year = ' . $year;
  #$result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysql_error());

  $input = $_POST['email']; //get input text
  $message = "Success! You entered: ".$input;
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<a href="index.html">Back to Start</a>
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
		<td><?php if(isset($email_matches) and $email_matches == 0) echo "Entered email address is invalid"; ?></td>
		</tr>
		<tr>
		<td><label for="password">Password:</label></td>
		<td><input type="password" name="password" id="password" required></td>
		<tr>
		<td><label for="pass_confirm">Confirm Password:</label></td>
		<td><input type="password" name="pass_confirm" id="pass_confirm" required></td>
		</tr>
		<tr>
		<td><label for="role">Role:</label></td>
		<td><select name="role" id="role">
			<option value="none">None</option>
			<option value="mod">Moderator</option>
		</select></td>
		</tr>
		<table>
		<h3>Personal Info</h3>
		<table>
		<tr>
		<td><label for="name">Name:</label></td>
		<td><input type="text" name="name" id="name" value="<?php echo $name ?>" required></td>
		</tr>
		<td><label for="phone_num">Phone#:</label></td>
		<td><input type="text" name="phone_num" id="phone_num" value="<?php echo $phone_num ?>" required></td>
		</tr>
		<tr>
		<td><label for="city">City:</label></td>
		<td><input type="text" name="city" id="city" value="<?php echo $city ?>" required></td>
		</tr>
		<tr>
		<td><label for="state">State:</label></td>
		<td><input type="text" name="state" id="state" value="<?php echo $state ?>" required></td>
		<table>
		<input type="submit" name="register" id="register" value="Register">
</body>
</html>
