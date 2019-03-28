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
		echo "<th>Moderate</th>";
		echo "</tr>";
		
		while(($row = $result->fetch_array()) != NULL) {
			$section_info = get_section_info($myconnection, $row["sectionID"], $row["courseID"]);
			$course_info = get_course_info($myconnection, $row["courseID"]);
			
			echo "<tr>"; # START SECTION INFO
			echo "<td>" . $course_info['title'] . "</td>";
			echo "<td>" . $section_info['sectionID'] . "</td>";
			echo "<td>" . $section_info['startDate'] . "</td>";
			echo "<td>" . $section_info['endDate'] . "</td>";
			
			echo "<td>"; # START TIMESLOT
			$query = "SELECT * FROM timeSlot WHERE timeSlotID = " . $section_info['timeSlotID'];
			$timeslot_info = mysqli_query($myconnection, $query);
			if($timeslot_info == false) {
				echo "Placeholder Text";
			} else {
				$timeslot_info = $timeslot_info->fetch_array();
				if($timeslot_info['M'] == 1) echo 'm ';
				if($timeslot_info['T'] == 1) echo 't ';
				if($timeslot_info['W'] == 1) echo 'w ';
				if($timeslot_info['Th'] == 1) echo 'th ';
				if($timeslot_info['F'] == 1) echo 'f ';
				if($timeslot_info['Sa'] == 1) echo 's ';
				echo " -- ";
				echo $timeslot_info['startTime'];
				echo "-";
				echo $timeslot_info['endTime'];
			}
			echo "</td>"; # END TIMESLOT
			
			echo "<td>" . $section_info['mentorCapacity'] + $section_info['menteeCapacity'] . "</td>";
			echo "<td>" . get_grade_level($course_info['mentorReq']) . "</td>";
			echo "<td>" . get_grade_level($course_info['menteeReq']) . "</td>";
			
			echo "<td>"; # START MENTOR COUNT
			$query = "SELECT count(mentorID) AS total FROM mentorFor WHERE courseID = " . $section_info['courseID'] . " AND sectionID = " . $section_info['sectionID'] . "";
			$mentor_count = mysqli_query($myconnection, $query);
			if($result->num_rows > 0) {
				$mentor_count = $mentor_count->fetch_row()[0];
				echo $mentor_count . "/" . $section_info['mentorCapacity'];
			} else {
				echo "0/" . $section_info['mentorCapacity'];
			}
			echo "</td>"; # END MENTOR COUNT
			
			echo "<td>"; # START MENTEE COUNT
			$query = "SELECT count(menteeID) AS total FROM menteeFor WHERE courseID = " . $section_info['courseID'] . " AND sectionID = " . $section_info['sectionID'] . "";
			$mentee_count = mysqli_query($myconnection, $query);
			if($result->num_rows > 0) {
				$mentee_count = $mentee_count->fetch_row()[0];
				echo $mentee_count . "/" . $section_info['menteeCapacity'];
			} else {
				echo "0/" . $section_info['menteeCapacity'];
			}
			echo "</td>"; # END MENTEE COUNT
			echo "<td>";
			if($mentor_count >= 2) {
				echo "N/A";
			} else {
				echo "<form method='get' action='moderate_add_mentor.php'>";
				echo "<input type='hidden' name='sectionID' value=" . $section_info['sectionID'] . " />";
				echo "<input type='hidden' name='courseID' value=" . $section_info['courseID'] . " />";
				echo "<input type='submit' name='submit' value='Add Mentor' />";
				echo "</form>";
			}
			echo "</tr>"; # END SECTION INFO
			
			#session header
			echo "<tr>";
			echo "<th colspan=4>Session Info</th>";
			echo "<th colspan=2>Session ID</th>";
			echo "<th colspan=2>Session Name</th>";
			echo "<th colspan=2>Date</th>";
			echo "<th></th>";
			echo "</tr>";
			
			#session loop
			$query = "SELECT * FROM sessions WHERE courseID = " . $section_info['courseID'] . " AND sectionID = " . $section_info['sectionID'];
			$session_list = mysqli_query($myconnection, $query);
			while(($session_info = $session_list->fetch_array()) != NULL) {
				echo "<tr>";
				echo "<td colspan=4>" . $session_info['announcement'] . "</td>";
				echo "<td colspan=2>" . $session_info['sessionID'] . "</td>";
				echo "<td colspan=2>" . $section_info['sectionID'] . "-" . $session_info['sessionID'] . "</td>";
				echo "<td colspan=2>" . $session_info['sessionDate'] . "</td>";
				echo "<td>"; # START STUDY MATERIALS FORM
				echo "<form method='get' action='moderate_add_materials.php'>";
				echo "<input type='hidden' name='sessionID' value=" . $session_info['sessionID'] . " />";
				echo "<input type='hidden' name='sectionID' value=" . $session_info['sectionID'] . " />";
				echo "<input type='hidden' name='courseID' value=" . $session_info['courseID'] . " />";
				echo "<input type='submit' name='submit' value='Post' />";
				echo "</form>";
				echo "</td>"; # END STUDY MATERIALS FORM
				echo "</tr>";
			}
			echo "<tr><td colspan=11>.</td></tr>";
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