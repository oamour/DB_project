<?php
include 'functions.php';

function get_grade_level($grade_level) {
	if ($grade_level == 1) return "Freshman";
	if ($grade_level == 2) return "Sophomore";
	if ($grade_level == 3) return "Junior";
	if ($grade_level == 4) return "Senior";
	return "None";
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

###
# NOTIFICATIONS
###
function get_mentor_notifications($myconnection, $row) {
	$userid = $row['userID'];
	$query = "SELECT * FROM sessions WHERE sectionID IN (SELECT sectionID FROM mentorFor WHERE mentorID = $userid) AND sessionID NOT IN (SELECT sessionID FROM participatingIn WHERE userID = $userid)";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database");
	if(mysqli_num_rows($result) > 0) {
		echo "<h1>Mentor Notifications for Next Week</h1>";
		echo "<table border='1'>";
		echo "<tr>";
		echo "<td>Role</td><td>Course Name</td><td>Section Number</td>";
		echo "<td>Session Number</td><td>Session Date</td>";
		echo "<td>Participating Mentee Count</td><td>Participate/Cancel</td>";
		echo "</tr>";
		
		while(($session_info = $result->fetch_array()) != NULL) {
			# Get section info
			$query = "SELECT * FROM sections WHERE sectionID = " . $session_info['sectionID'];
			$section_result = mysqli_query($myconnection, $query) or die ("Failed to query database");
			$section_info = $section_result->fetch_array();
			
			# Get course info
			$query = "SELECT * FROM courses WHERE courseID = " . $section_info['courseID'];
			$course_result = mysqli_query($myconnection, $query) or die ("Failed to query database");
			$course_info = $course_result->fetch_array();
			
			echo "<tr>";
			echo "<td>Mentor</td>";
			echo "<td>" . $course_info['title'] . "</td>";
			echo "<td>" . $section_info['sectionID'] . "</td>";
			echo "<td>" . $session_info['sessionID'] . "</td>";
			echo "<td>" . $session_info['sessionDate'] . "</td>";
			
			#Get participating mentees
			$query = "SELECT count(userID) FROM participatingIn WHERE sessionID = " . $session_info['sessionID'] . " AND userID IN (SELECT userID FROM menteeFor WHERE sectionID = ". $section_info['sectionID'] . ") GROUP BY sessionID";
			$mentee_count = mysqli_query($myconnection, $query) or die ("Failed to query database");
			if(mysqli_num_rows($mentee_count) == 0) {
				$mentee_count = "N/A";
			} else {
				$mentee_count = $mentee_count->fetch_row()[0];
			}
			echo "<td>$mentee_count</td>";
			
			# Form
			echo "<td><form method='post' action='dashboard.php'>";
			echo "<input type='hidden' name='sessionID' value='" . $session_info['sessionID'] . "'>";
			echo "<input type='hidden' name='sectionID' value='" . $section_info['sectionID'] . "'>";
			echo "<select name='participating' id='participating'>";
			echo "<option value='yes'>Participate</option>";
			echo "<option value='no'>Decline</option>";
			echo "</select>";
			echo "<input type='submit' name='submit' id='submit' value='Submit'>";
			echo "</form></td></tr>";
		}
	}
	
}

function get_mentee_notifications($myconnection, $row) {
	$userid = $row['userID'];
	$query = "SELECT * FROM sessions WHERE sectionID IN (SELECT sectionID FROM menteeFor WHERE menteeID = $userid) AND sessionID NOT IN (SELECT sessionID FROM participatingIn WHERE userID = $userid)";
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) > 0) {
		echo "<h1>Mentee Notifications for Next Week</h1>";
		echo "<table border='1'>";
		echo "<tr>";
		echo "<td>Role</td><td>Course Name</td><td>Section Number</td>";
		echo "<td>Session Number</td><td>Session Date</td>";
		echo "<td>Participating Mentee Count</td><td>Participate/Cancel</td>";
		echo "</tr>";
		
		while(($session_info = $result->fetch_array()) != NULL) {
			# Get section info
			$query = "SELECT * FROM sections WHERE sectionID = " . $session_info['sectionID'];
			$section_result = mysqli_query($myconnection, $query) or die ("Failed to query database");
			$section_info = $section_result->fetch_array();
			
			# Get course info
			$query = "SELECT * FROM courses WHERE courseID = " . $section_info['courseID'];
			$course_result = mysqli_query($myconnection, $query) or die ("Failed to query database");
			$course_info = $course_result->fetch_array();
			
			echo "<tr>";
			echo "<td>Mentor</td>";
			echo "<td>" . $course_info['title'] . "</td>";
			echo "<td>" . $section_info['sectionID'] . "</td>";
			echo "<td>" . $session_info['sessionID'] . "</td>";
			echo "<td>" . $session_info['sessionDate'] . "</td>";
			
			#Get participating mentees
			$query = "SELECT count(userID) FROM participatingIn WHERE sessionID = " . $session_info['sessionID'] . " AND userID IN (SELECT menteeID FROM menteeFor WHERE sectionID = ". $section_info['sectionID'] . ") GROUP BY sessionID";
			$mentee_count = mysqli_query($myconnection, $query) or die ("Failed to query database");
			if(mysqli_num_rows($mentee_count) == 0) {
				$mentee_count = "N/A";
			} else {
				$mentee_count = $mentee_count->fetch_row()[0];
			}
			echo "<td>$mentee_count</td>";
			
			# Form
			echo "<td><form method='post' action='dashboard.php'>";
			echo "<input type='hidden' name='sessionID' value='" . $session_info['sessionID'] . "'>";
			echo "<input type='hidden' name='sectionID' value='" . $section_info['sectionID'] . "'>";
			echo "<select name='participating' id='participating'>";
			echo "<option value='yes'>Participate</option>";
			echo "<option value='no'>Decline</option>";
			echo "</select>";
			echo "<input type='submit' name='submit' id='submit' value='Submit'>";
			echo "</form></td></tr>";
		}
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
	
	get_mentor_notifications($myconnection, $row);
	get_mentee_notifications($myconnection, $row);
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
	echo "<td><a href='children_parent.php'>Change Your Child's Profile</a></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Parent</td>";
	echo "<td>Section</td>";
	echo "<td><a href='sections_parent.php'>View Sections</a></td>";
	echo "</tr>";
	get_moderator_row($myconnection, $row);
	echo "</table>";
}

session_start();

$userid = check_session();

if (isset($userid) and $userid != false) {
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
<body>
<?php if (!isset($userid) or $userid == false) : ?>
    <span>"Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page."</span>
<?php endif; ?>
</body>
</html>