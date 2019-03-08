<?php
function check_credentials ($username, $pass) {
	# connect to db
	$myconnection = mysqli_connect('localhost', 'root', '') 
	or die ('Could not connect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

	$query = "SELECT * FROM accounts WHERE username = '" . $username . "'";
	$result = mysqli_query($myconnection, $query) or die("Failed to retrieve account info for " . $username);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		if ($username != $row["username"] or !password_verify($pass, $row["hash"])) {
			return 0;
		}
		return $row["userID"];
	} else {
		return 0;
	}
}

$session_key = md5("database");

if (isset($_POST)) {
	$userid = check_credentials($_POST['username'], $_POST['password']);
	if ($userid != 0) {
		echo "Success! Logged in as " . $_POST['username'] . "<br />";
		session_start();
		$_SESSION[$session_key] = $userid;
		echo "Redirecting to Dashboard...";
		header("Location: dashboard.php");
	} else {
		echo "Failure! Failed to log in as " . $_POST['username'];
	}
}
?>