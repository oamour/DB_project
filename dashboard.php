<?php
include 'functions.php';

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


function mentee_confirmation( $myconnection, $row){
	$GetMenteeID = "SELECT menteeID FROM mentees WHERE userID = " . $row[0] . ";";
	$menteeID = mysqli_query($myconnection, $GetMenteeID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$menteeID = $menteeID->fetch_array()[0];
	
	$getParticipating = "SELECT * FROM participatingin WHERE userID =".$row[0].";";
	$participating = mysqli_query($myconnection, $getParticipating) or die ("Failed to query database: " . mysqli_error($myconnection));
	$participating = $participating->fetch_all();
	
	$today =  new DateTime();
	while($today->format('N')<7){
		$today ->add(new DateInterval("P1D"));
	}
	$plusWeek1 = new DateInterval("P7D");
	$plusWeek =  new DateTime($today->add($plusWeek1)->format('Y-m-d'));
	$today = $today->sub($plusWeek1);
	
	if($menteeID != NULL){
		$GetActiveMentee= "SELECT sec.name, sec.sectionID, sec.courseID, ses.sessionID,ses.sessionDate, ts.startTime,ts.endTime 
		FROM sessions ses, sections sec,timeslot ts,menteefor mf 
		WHERE mf.menteeID =" . $menteeID . " AND mf.courseID = sec.courseID AND mf.sectionID = sec.sectionID AND ses.sectionID = sec.sectionID AND ses.courseID = sec.courseID AND sec.timeSlotID = ts.timeSlotID;";
		$activeMentee = mysqli_query($myconnection, $GetActiveMentee) or die ("Failed to query database: " . mysqli_error($myconnection));
		$activeMentee = $activeMentee->fetch_all();
		
		echo("<tr style=\"min-width:100px;border:1px solid;border-collapse: collapse;\"><th style=\"min-width:100px;border:1px solid;border-collapse: collapse;\" colspan = \"6\" >Mentee Courses</th></tr>");
		for($i=0;$i<count($activeMentee);$i++){
			$sesDate = new DateTime($activeMentee[$i][4]);
			if($sesDate-> diff($today)->invert AND !$sesDate->diff($plusWeek)->invert){
				echo("<tr style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentee[$i][0] ."</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentee[$i][1] . "</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentee[$i][3] . "</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$sesDate->format("l m/d") . "</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentee[$i][5]. "-".$activeMentee[$i][6] . "</td>");
					$row_check=array($row[0],$activeMentee[$i][3],$activeMentee[$i][1],$activeMentee[$i][2],0,1);
					if(!in_array($row_check,$participating)){
						echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\"><button type=\"submit\" name=\"register\" id=\"register\" value=\"0-". $activeMentee[$i][2]. "-" . $activeMentee[$i][1] . "-". $activeMentee[$i][3]."\">Participate</button></td>");
					}
					else{
						echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">Confirmed</td>");
					}
				echo("</tr>");
			}
		}
	}
}

function mentor_confirmation( $myconnection, $row){
	$GetMentorID = "SELECT mentorID FROM mentors WHERE userID = " . $row[0] . ";";
	$mentorID = mysqli_query($myconnection, $GetMentorID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentorID = $mentorID->fetch_array()[0];
	
	$getParticipating = "SELECT * FROM participatingin WHERE userID =".$row[0].";";
	$participating = mysqli_query($myconnection, $getParticipating) or die ("Failed to query database: " . mysqli_error($myconnection));
	$participating = $participating->fetch_all();
	
	$today =  new DateTime();
	while($today->format('N')<7){
		$today ->add(new DateInterval("P1D"));
	}
	$plusWeek1 = new DateInterval("P7D");
	$plusWeek =  new DateTime($today->add($plusWeek1)->format('Y-m-d'));
	$today = $today->sub($plusWeek1);
	
	if($mentorID != NULL){
		$GetActiveMentor= "SELECT sec.name, sec.sectionID, sec.courseID, ses.sessionID,ses.sessionDate, ts.startTime,ts.endTime 
		FROM sessions ses, sections sec,timeslot ts,mentorfor mf 
		WHERE mf.mentorID =" . $mentorID . " AND mf.courseID = sec.courseID AND mf.sectionID = sec.sectionID AND ses.sectionID = sec.sectionID AND ses.courseID = sec.courseID AND sec.timeSlotID = ts.timeSlotID;";
		$activeMentor = mysqli_query($myconnection, $GetActiveMentor) or die ("Failed to query database: " . mysqli_error($myconnection));
		$activeMentor = $activeMentor->fetch_all();
		
		echo("<tr style=\"min-width:100px;border:1px solid;border-collapse: collapse;\"><th style=\"min-width:100px;border:1px solid;border-collapse: collapse;\"colspan = \"6\" >Mentor Courses</th></tr>");
		for($i=0;$i<count($activeMentor);$i++){
			$sesDate = new DateTime($activeMentor[$i][4]);
			if($sesDate-> diff($today)->invert AND !$sesDate->diff($plusWeek)->invert){
				echo("<tr>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentor[$i][0] ."</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentor[$i][1] . "</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentor[$i][3] . "</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$sesDate->format("l m/d") . "</td>
					<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentor[$i][5]. "-".$activeMentor[$i][6] . "</td>");
					$row_check=array($row[0],$activeMentor[$i][3],$activeMentor[$i][1],$activeMentor[$i][2],1,0);
					if(!in_array($row_check,$participating)){
						echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\"><button type=\"submit\" name=\"register\" id=\"register\" value=\"1-". $activeMentor[$i][2]. "-" . $activeMentor[$i][1] . "-". $activeMentor[$i][3]."\">Participate</button></td>");
					}
					else{
						echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">Confirmed</td>");
					}
				echo("</tr>");
			}
		}
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
	echo "<td><a href='all_classes.php'>View Sections</a></td>";
	echo "</tr>";
	get_mentor_row($myconnection, $row);
	get_mentee_row($myconnection, $row);
	echo "</table>";
	
	echo("<h3>Attendance confirmation</h3>
	<form method=\"post\" action=\"session_confirmation.php\">
		<table style=\"border:1px solid;border-collapse: collapse;\">
			<tr style=\"border:1px solid;border-collapse: collapse;\">
				<th style=\"border:1px solid;border-collapse: collapse;\">Course</th>
				<th style=\"border:1px solid;border-collapse: collapse;\">Section</th>
				<th style=\"border:1px solid;border-collapse: collapse;\">Session</th>
				<th style=\"min-width:150px;border:1px solid;border-collapse: collapse;\">Date</th>
				<th style=\"border:1px solid;border-collapse: collapse;\">Time</th>
				<th style=\"border:1px solid;border-collapse: collapse;\">Participating</th>
			</tr>");
	mentee_confirmation($myconnection, $row);
	mentor_confirmation($myconnection, $row);
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
	echo "<td><a href='all_classes.php'>View Sections</a></td>";
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