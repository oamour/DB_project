<?php 
if (isset($_GET['logout']) and $_GET['logout'] == 1) {
	echo "<div>You have been logged out successfully.</div>";
}
?>
<!DOCTYPE html>
<head>
	<title>School Database</title>
</head>
<body>
	<h1>Welcome to ____ University</h1>
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
</body>
