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


function class_cancel($myconnection){
	$GetSessions = "SELECT sessionDate, sessionID, sectionID, courseID FROM sessions WHERE announcement != 'Cancelled' AND sessionDate != 'NULL';";
	$sessions= mysqli_query($myconnection, $GetSessions) or die ("Failed to query database: " . mysqli_error($myconnection));
	$sessions = $sessions->fetch_all();
	
	$today =  new DateTime();
	if($today->format('N')>=5){
		while($today->format('N')<7){
			$today = $today ->add(new DateInterval("P1D"));
		}
		$plusWeek1 = new DateInterval("P7D");
		$plusWeek =  new DateTime($today->add($plusWeek1)->format('Y-m-d'));
		$today = $today->sub($plusWeek1);
		
		$CurrentWeekSessions= array();
		
		for($i=0;$i< count($sessions);$i++){
			if($sessions[$i][0] != NULL){
				$sesDate = new DateTime($sessions[$i][0]);
				if($sesDate-> diff($today)->invert AND !$sesDate->diff($plusWeek)->invert){
					$CurrentWeekSessions[]= array($sessions[$i][1],$sessions[$i][2],$sessions[$i][3],$sessions[$i][0]);
				}
			}
		}
		var_dump($CurrentWeekSessions);
		for($i=0;$i<count($CurrentWeekSessions);$i++){
			$menteeCount = "SELECT COUNT(mentee),COUNT(mentor) FROM participatingin WHERE sessionID =". $CurrentWeekSessions[$i][0]." AND sectionID = ". $CurrentWeekSessions[$i][1]." AND courseID =". $CurrentWeekSessions[$i][2]." ;";
			$MenteeCount = mysqli_query($myconnection, $menteeCount) or die ("Failed to query database: " . mysqli_error($myconnection));
			$MenteeCount = $MenteeCount->fetch_row();
			
			echo($MenteeCount[0] . "1");
			
			if($MenteeCount[0] < 3){
				$menteeEmail = "SELECT usr.name,usr.email FROM participatingin p, users usr WHERE p.sessionID =". $CurrentWeekSessions[$i][0]." AND p.sectionID = ". $CurrentWeekSessions[$i][1]." AND p.courseID =". $CurrentWeekSessions[$i][2]." AND p.userID = usr.userID;";
				$MenteeEmail = mysqli_query($myconnection, $menteeEmail) or die ("Failed to query database: " . mysqli_error($myconnection));
				$MenteeEmail = $MenteeEmail->fetch_all();
				
				var_dump($MenteeEmail);
				
				$cancelSection = "UPDATE sessions SET announcement = 'Cancelled' WHERE courseID = ". $CurrentWeekSessions[$i][2]." AND sectionID = ".$CurrentWeekSessions[$i][1]." AND sessionID = ".$CurrentWeekSessions[$i][0].";";
				$canceled = mysqli_query($myconnection, $cancelSection) or die ("Failed to query database: " . mysqli_error($myconnection));

				$filename = "Cancel-".$CurrentWeekSessions[$i][0].".".$CurrentWeekSessions[$i][1].".".$CurrentWeekSessions[$i][2]." on ".$CurrentWeekSessions[$i][3].".txt";
				$myfile = fopen($filename, "w") or die("Unable to open file!");
				for($j =0; $j<count($MenteeEmail) and $MenteeCount != NULL;$j++){
					$message = $MenteeEmail[$j][0]. " ". $MenteeEmail[$j][1]. "\n";
					fwrite($myfile, $message);
				}
				fclose($myfile);
				
			}
			else if($MenteeCount[1] < 2 ){
				$mentorEmail = "SELECT usr.name,usr.email FROM modfor p, moderators m, users usr WHERE p.sectionID = ". $CurrentWeekSessions[$i][1]." AND p.courseID = ". $CurrentWeekSessions[$i][2]." AND p.modID = m.modID AND m.userID = usr.userID;";
				$MentorEmail = mysqli_query($myconnection, $mentorEmail) or die ("Failed to query database: " . mysqli_error($myconnection));
				$MentorEmail = $MentorEmail->fetch_all();
				
				$filename = "Alert-".$CurrentWeekSessions[$i][0].".".$CurrentWeekSessions[$i][1].".".$CurrentWeekSessions[$i][2]." on ".$CurrentWeekSessions[$i][3].".txt";
				$myfile = fopen($filename, "w") or die("Unable to open file!");
				for($j =0; $j<count($MentorEmail);$j++){
					$message = $MentorEmail[$j][0]. " ". $MentorEmail[$j][1]. "\n";
					fwrite($myfile, $message);
				}
				fclose($myfile);
			}
		}
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
		$GetActiveMentee= "SELECT sec.name, sec.sectionID, sec.courseID, ses.sessionID,ses.sessionDate, ts.startTime,ts.endTime, ses.announcement 
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
						echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\"><span title = \"".$activeMentee[$i][7]."\">" . substr($activeMentee[$i][7],0,30) . "<span></td>");
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
		$GetActiveMentor= "SELECT sec.name, sec.sectionID, sec.courseID, ses.sessionID,ses.sessionDate, ts.startTime,ts.endTime,ses.announcement 
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
						echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\"><span title = \"".$activeMentor[$i][7]."\">" . substr($activeMentor[$i][7],0,30) . "<span></td>");
					}
				echo("</tr>");
			}
		}
	}
	
}

function enrolInSec($myconnection,$row){
	if(isset($_POST['register'])){
		$sel_sec = $_POST['register'];
		$Session = "";
		$Section = "";
		$Course = "";
		$offset = 0;
		for($i = 2; $i<strlen($sel_sec);$i++){
			if($sel_sec[$i] == '-'){
				$offset = $i+1;
				break;
			}
			$Course = $Course . $sel_sec[$i];
		}
		for($i=$offset;$i<strlen($sel_sec);$i++){
			if($sel_sec[$i] == '-'){
				$offset = $i+1;
				break;
			}
			$Section = $Section . $sel_sec[$i];
		}
		for($i=$offset;$i<strlen($sel_sec);$i++){
			$Session = $Session . $sel_sec[$i];
		}
		if($sel_sec[0] == "0" ){
			$mentor =False;
			$mentee = True;
		} else{
			$mentor =True;
			$mentee = False;
		}
		$participate= "INSERT INTO participatingin (userID, sessionID, sectionID, courseID, mentor, mentee) VALUES (". $row[0]. ", ". $Session.", ". $Section. ", ". $Course . ", " . ($mentor ? '1' : '0') . ", " . ($mentee ? '1' : '0'). " );";
		$update= mysqli_query($myconnection, $participate) or die ("Failed to query database: " . mysqli_error($myconnection));
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
	<form method=\"post\" action=\"dashboard.php\">
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
	
	class_cancel($myconnection);
	
	$row = get_user_info($myconnection, $userid);
	enrolInSec($myconnection,$row);
	
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