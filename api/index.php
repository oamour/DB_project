<?php 
include "functions.php";

$session_key = md5("database");
session_start();
$userid = check_session();

if (isset($userid) and $userid != false) {
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

	$row = get_user_info($myconnection, $userid);
	
	$email = $row['email'];
}

if (isset($_GET['logout']) and $_GET['logout'] == 1) {
	echo "<div>You have been logged out successfully.</div>";
}
?>
<!DOCTYPE html>
<head>
	<title>School Database</title>
</head>
<body>
	<h1>Welcome to Database University</h1>
	<?php if (isset($userid) and $userid != false) : ?>
	<h2>Welcome, <?php echo $email; ?></h2>
	<div>
		<span><a href="dashboard.php">CLICK HERE</a> to view your dashboard.</span><br />
		<span><a href="logout.php">Logout</a>
	</div>
	<?php else : ?>
	<h2>Register</h2>
	<div class="register">
		<a href="register_student.php">Register as Student</a><br/>
		<a href="register_parent.php">Register as Parent</a>
	</div>
	<h2>Login</h2>
	<div class="login">
		<a href="login_student.php">Login as Student</a><br/>
		<a href="login_parent.php">Login as Parent</a>
	</div>
	<?php endif; ?>
</body>
