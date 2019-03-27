<?php
include 'functions.php';

function generate_session_list($userid) {
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connnect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	$query = "SELECT sectionID, courseID FROM modFor WHERE modID = $userid";
	$result = mysqli_query($myconnection, $query) or die("Failed to query database: " . mysqli_error());
	
	if ($result->num_rows > 0) {
		# moderating at least 1 section
		echo "<table border=1>"; # start header
		echo "<tr>";
		echo "<th>Course Title</th>";
		echo "<th>Section Title</th>";
		echo "<th>Start Date</th>";
		echo "<th>End Date</th>";
		echo "<th>Time Slot</th>";
		echo "<th>Capacity</th>";
		echo "<th>Mentor Req</th>";
		echo "<th>Mentee Req</th>";
		echo "<th>Enrolled Mentor</th>";
		echo "<th>Enrolled Mentee</th>";
		echo "<th>Moderate as Moderator</th>";
		echo "</tr>";
		
		while(($row = $result->fetch_array()) != NULL) {
			$section_info = get_section_info($myconnection, $row["sectionID"]);
			$course_info = get_course_info($myconnection, $row["courseID"]);
			
			echo "<tr>"; # Section Info
			echo "<td>" . $course_info['title'] . "</td>";
			echo "<td>" . $section_info['sectionID'] . "</td>";
			echo "<td>" . $section_info['startDate'] . "</td>";
			echo "<td>" . $section_info['endDate'] . "</td>";
			echo "<td>Time Slot Placeholder</td>";
			echo "<td>" . $section_info['capacity'] . "</td>";
			echo "<td>" . get_grade_level($course_info['mentorReq']) . "</td>";
			echo "<td>" . get_grade_level($course_info['menteeReq']) . "</td>";
			echo "<td>"; # START MENTOR COUNT
			$query = "SELECT count(mentorID) AS total FROM mentorFor WHERE sectionID = " . $section_info['sectionID'] . " GROUP BY sectionID";
			$mentor_count = mysqli_query($myconnection, $query);
			if($result->num_rows > 0) {
				$mentor_count = $mentor_count->fetch_row()[0];
				echo $mentor_count . "/3";
			} else {
				echo "0/3";
			}
			echo "</td>"; # END MENTOR COUNT
			echo "<td>"; # START MENTEE COUNT
			$query = "SELECT count(menteeID) AS total FROM menteeFor WHERE sectionID = " . $section_info['sectionID'] . " GROUP BY sectionID";
			$mentee_count = mysqli_query($myconnection, $query);
			if($result->num_rows > 0) {
				$mentee_count = $mentee_count->fetch_row()[0];
				echo $mentee_count . "/3";
			} else {
				echo "0/3";
			}
			echo "</td>"; # END MENTOR COUNT
			echo "<td>Add Mentor if Low</td>";
			echo "</tr>";
			
			#session loop
			echo "<tr>"
		}
	} else { # no sections as moderator
		echo "Sorry, you are not a moderator for any sections";
	}
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
		<a href="dashboard.php">Back to Dashboard</a>
		<h1>Moderate Section and Sessions</h1>
		<?php generate_session_list($userid) ?>
	<?php else : ?>
		<span>"Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page."</span>
	<?php endif; ?>
</body>
</html>