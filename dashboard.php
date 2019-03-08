<?php
function get_user_info($myconnection, $userid) {
	$query = "SELECT * FROM users WHERE userid = " . $userid;
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) == 0) die("FATAL ERROR: Missing userdata for account '$userid'");
	
	return mysqli_fetch_array($result);
}

function create_student_dashboard() {
	echo "Student dashboard created";
}

function create_parent_dashboard() {
	echo "Parent dashboard created";
}

$session_key = md5("database");
session_start();

if (empty($_SESSION[$session_key])) {
	echo "Not logged in! Please <a href='index.html'>CLICK HERE</a> to return to the main page.";
} elseif (intval($_SESSION[$session_key]) <= 0) {
	unset($_SESSION[$session_key]);
	echo "Invalid session key! Please <a href='index.html'>CLICK HERE</a> to return to the main page.";
} else {
	$userid = $_SESSION[$session_key];
}

if (isset($userid)) {
	#start MySQL connection
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

	$row = get_user_info($myconnection, $userid);

	if($row['isStudent'] == 1) {
		create_student_dashboard();
	} elseif ($row['isParent'] == 1) {
		create_parent_dashboard();
	} else {
		echo "Invalid User Data";
	}
}
?>