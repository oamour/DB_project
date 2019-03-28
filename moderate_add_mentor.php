<?php
include "functions.php";

function get_mentor_count($myconnection, $courseID, $sectionID) {
	$query = "SELECT count(mentorID) AS total FROM mentorFor WHERE courseID = $courseID AND sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query);
	if($result != false) {
		$result = $result->fetch_array()['total'];
	}
	return $result;
}

function generate_mentor_list() {
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());

	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	if(isset($_GET['submit'])) {
		$sectionID = $_GET['sectionID'];
		$courseID = $_GET['courseID'];
		
		#check that adding mentors is necessary
		$mentor_count = get_mentor_count($myconnection, $courseID, $sectionID);
		if($mentor_count >= 2) {
			echo "Section has more than two mentors.";
			return;
		}
		
		$query = "SELECT * FROM sections WHERE sectionID = $sectionID AND courseID = $courseID";
		$section_info = mysqli_query($myconnection, $query);
		$section_info = $section_info->fetch_array();
		
		$query = "SELECT * FROM courses WHERE courseID = $courseID";
		$course_info = mysqli_query($myconnection, $query);
		$course_info = $course_info->fetch_array();
		
		echo "<table border=1>"; # START MENTOR TABLE
		echo "<tr><th colspan=4>Candidate for " . $course_info['title'] . " - Section $sectionID</th></tr>";
		
		echo "<tr>"; # HEADER
		echo "<th>Mentor ID</th>";
		echo "<th>Student Name</th>";
		echo "<th>Student Grade</th>";
		echo "<th>Assign</th>";
		echo "</tr>"; # END HEADER
		
		$query = "SELECT * FROM users NATURAL JOIN mentors WHERE userID NOT IN (SELECT mentorID FROM mentorFor WHERE sectionID = $sectionID AND courseID = $courseID) AND userID NOT IN (SELECT menteeID FROM menteeFor WHERE sectionID = $sectionID AND courseID = $courseID)";
		$result = mysqli_query($myconnection, $query);
		if($result->num_rows > 0) {
			while(($mentor_info = $result->fetch_array()) != NULL) {
				echo "<tr>";
				echo "<td>" . $mentor_info['userID'] . "</td>";
				echo "<td>" . $mentor_info['name'] . "</td>";
				echo "<td>" . get_grade_level($mentor_info['gradeLevel']) . "</td>";
				
				echo "<td>"; # MENTOR ASSIGN FORM
				echo "<form method='post' action='moderate_add_mentor.php'>";
				echo "<input type='hidden' name='sectionID' value=$sectionID />";
				echo "<input type='hidden' name='courseID' value=$courseID />";
				echo "<input type='hidden' name='mentorID' value=" . $mentor_info['userID'] . " />";
				echo "<input type='submit' name='submit' value='Assign' />";
				echo "</td>";
			}
		}
	}
}

if(isset($_POST['submit'])) {
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());

	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	$query = "INSERT INTO mentorFor(mentorID, courseID, sectionID) VALUES (" . $_POST['mentorID'] . ", " . $_POST['courseID'] . ", " . $_POST['sectionID'] . ")";
	$result = mysqli_query($myconnection, $query);
	if($result == false) {
		$message = "Failed to add mentor";
	} else {
		$message = "Mentor added successfully!";
	}
	
	# set get info for building table if more mentors needed
	$_GET['submit'] = 1;
	$_GET['courseID'] = $_POST['courseID'];
	$_GET['sectionID'] = $_POST['sectionID'];
	
}

session_start();

$userid = check_session();
?>
<!DOCTYPE html>
<html>
<head>
	<title>School Database</title>
</head>
<body>
	<?php if(isset($userid) and $userid != false) : ?>
		<a href="dashboard.php">Back to Dashboard</a><br />
		<a href="moderator.php">Back to Moderation Page</a>
		<h1>Add Mentor to Section</h1>
		<?php generate_mentor_list($userid) ?>
	<?php else : ?>
		<span>"Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page."</span>
	<?php endif; ?>
</body>
</html>