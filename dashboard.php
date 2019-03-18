<?php
function get_user_info($myconnection, $userid) {
	$query = "SELECT * FROM users WHERE userid = " . $userid;
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) == 0) die("FATAL ERROR: Missing userdata for account '$userid'");
	
	return mysqli_fetch_array($result);
}

function get_grade_level($grade_level) {
	if ($grade_level == 1) return "Freshman";
	if ($grade_level == 2) return "Sophomore";
	if ($grade_level == 3) return "Junior";
	if ($grade_level == 4) return "None";
}

function get_mentor_row($myconnection, $row) {
	$query = "SELECT * FROM mentors WHERE userid = " . $row['userID'];
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) > 0) {
		echo "<tr>";
		echo "<td>Mentor</td>";
		echo "<td>Mentor</td>";
		echo "<td><a href='mentor.php'>View Mentor Status</a></td>";
		echo "</tr>";
	}
}

function get_mentee_row($myconnection, $row) {
	$query = "SELECT * FROM mentees WHERE userid = " . $row['userID'];
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) > 0) {
		echo "<tr>";
		echo "<td>Mentee</td>";
		echo "<td>Mentee</td>";
		echo "<td><a href='mentee.php'>View Mentee Status</a></td>";
		echo "</tr>";
	}
}

function get_moderator_row($myconnection, $row) {
	$query = "SELECT * FROM moderators WHERE userid = " . $row['userID'];
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) > 0) {
		echo "<tr>";
		echo "<td>Moderator</td>";
		echo "<td>Moderator</td>";
		echo "<td><a href='moderator.php'>View Moderator Status</a></td>";
		echo "</tr>";
	}
}

function create_student_dashboard($myconnection, $row) {
	echo "<a href='dashboard.php'>Student Dashboard</a><br />";
	echo "<a href='logout.php'>Logout</a><br />";
	echo "<h3>Welcome, " . $row['name'] . "</h3>";
	echo "<span>Your grade is " . get_grade_level($row['gradeLevel']) . ".</span><br /><br />";
	echo "<table border='1'>";
	echo "<tr>";
	echo "<td>User Type</td>";
	echo "<td></td>";
	echo "<td>Action</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>User</td>";
	echo "<td>Profile</td>";
	echo "<td><a href='profile_student.php'>Change Your Profile</a></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Student</td>";
	echo "<td>Section</td>";
	echo "<td><a href='sections_student.php'>View Sections</a></td>";
	echo "</tr>";
	get_mentor_row($myconnection, $row);
	get_mentee_row($myconnection, $row);
	echo "</table>";
}

function create_parent_dashboard($myconnection, $row) {
	echo "<a href='dashboard.php'>Parent Dashboard</a><br />";
	echo "<a href='logout.php'>Logout</a><br />";
	echo "<h3>Welcome, " . $row['name'] . "</h3>";
	echo "<table border='1'>";
	echo "<tr>";
	echo "<td>User Type</td>";
	echo "<td></td>";
	echo "<td>Action</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>User</td>";
	echo "<td>Profile</td>";
	echo "<td><a href='profile_parent.php'>Change Your Profile</a></td>";
	echo "</tr>";
	echo "<td>User</td>";
	echo "<td>Profile</td>";
	echo "<td><a href='child_list.php'>Change Your Child's Profile</a></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Parent</td>";
	echo "<td>Section</td>";
	echo "<td><a href='sections_parent.php'>View Sections</a></td>";
	echo "</tr>";
	get_moderator_row($myconnection, $row);
	echo "</table>";
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

if (isset($userid)) {
	#start MySQL connection
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

	$row = get_user_info($myconnection, $userid);

	if($row['isStudent'] == 1) {
		create_student_dashboard($myconnection, $row);
	} elseif ($row['isParent'] == 1) {
		create_parent_dashboard($myconnection, $row);
	} else {
		echo "Invalid User Data";
	}
}
?>

<!DOCTYPE html>
<html>
<head></head>
<body></body>
</html>