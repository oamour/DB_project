<?php
	$session_key = md5("database");
	session_start();
	if (empty($_SESSION[$session_key])) {
		echo "Not logged in! Please <a href='index.html'>CLICK HERE</a> to return to the main page.";
		exit();
	} elseif (intval($_SESSION[$session_key]) <= 0) {
		unset($_SESSION[$session_key]);
		echo "Invalid session key! Please <a href='index.html'>CLICK HERE</a> to return to the main page.";
		exit();
	} else {
		$userid = $_SESSION[$session_key];
	}
	$myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());

	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	$GetMenteeID = "SELECT menteeID FROM mentees WHERE userID = " . $userid . ";";
	$menteeID = mysqli_query($myconnection, $GetMenteeID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$menteeID = $menteeID->fetch_array()[0];
	
	$GetMentorID = "SELECT mentorID FROM mentors WHERE userID = " . $userid . ";";
	$mentorID = mysqli_query($myconnection, $GetMentorID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentorID = $mentorID->fetch_array()[0];
	
	$today = $today = new DateTime();
	
	if($menteeID != NULL){
		$GetActiveMentee= "SELECT sec.name, sec.sectionID, sec.courseID, ses.sessionID,ses.sessionDate, ts.startTime,ts.endTime 
		FROM sessions ses, sections sec,timeslot ts,menteefor mf 
		WHERE mf.menteeID =" . $menteeID . " AND mf.courseID = sec.courseID AND mf.sectionID = sec.sectionID AND ses.sectionID = sec.sectionID AND ses.courseID = sec.courseID AND sec.timeSlotID = ts.timeSlotID;";
		$activeMentee = mysqli_query($myconnection, $GetActiveMentee) or die ("Failed to query database: " . mysqli_error($myconnection));
		$activeMentee = $activeMentee->fetch_all();
	}
	if($mentorID != NULL){
		$GetActiveMentor= "SELECT sec.name, sec.sectionID, sec.courseID, ses.sessionID,ses.sessionDate, ts.startTime,ts.endTime 
		FROM sessions ses, sections sec,timeslot ts,mentorfor mf 
		WHERE mf.mentorID =" . $mentorID . " AND mf.courseID = sec.courseID AND mf.sectionID = sec.sectionID AND ses.sectionID = sec.sectionID AND ses.courseID = sec.courseID AND sec.timeSlotID = ts.timeSlotID;";
		$activeMentor = mysqli_query($myconnection, $GetActiveMentor) or die ("Failed to query database: " . mysqli_error($myconnection));
		$activeMentor = $activeMentor->fetch_all();
	}
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
		$participate= "INSERT INTO participatingin (userID, sessionID, sectionID, courseID, mentor, mentee) VALUES (". $userid. ", ". $Session.", ". $Section. ", ". $Course . ", " . ($mentor ? '1' : '0') . ", " . ($mentee ? '1' : '0'). " );";
		$update= mysqli_query($myconnection, $participate) or die ("Failed to query database: " . mysqli_error($myconnection));
	}
	
	$getParticipating = "SELECT * FROM participatingin WHERE userID =".$userid.";";
	$participating = mysqli_query($myconnection, $getParticipating) or die ("Failed to query database: " . mysqli_error($myconnection));
	$participating = $participating->fetch_all();
	
	mysqli_close($myconnection);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<a href="index.html">Back to Start</a>
	<h1>Attendance Conformation</h1>
	<form method="post" action="session_conformation.php">
		<table style="border:1px solid;border-collapse: collapse;">
			<tr style="border:1px solid;border-collapse: collapse;">
				<th style="border:1px solid;border-collapse: collapse;">Course</th>
				<th style="border:1px solid;border-collapse: collapse;">Section</th>
				<th style="border:1px solid;border-collapse: collapse;">Session</th>
				<th style="min-width:150px;border:1px solid;border-collapse: collapse;">Date</th>
				<th style="border:1px solid;border-collapse: collapse;">Time</th>
				<th style="border:1px solid;border-collapse: collapse;">Participating</th>
			</tr>
			<?php
				if($menteeID != NULL){
					echo("<tr style=\"min-width:100px;border:1px solid;border-collapse: collapse;\"><th style=\"min-width:100px;border:1px solid;border-collapse: collapse;\" colspan = \"6\" >Mentee Courses</th></tr>");
					for($i=0;$i<count($activeMentee);$i++){
						$sesDate = new DateTime($activeMentee[$i][4]);
						echo("<tr style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentee[$i][0] ."</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentee[$i][1] . "</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentee[$i][3] . "</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$sesDate->format("l m/d") . "</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentee[$i][5]. "-".$activeMentee[$i][6] . "</td>");
							$row_check=array($userid,$activeMentee[$i][3],$activeMentee[$i][1],$activeMentee[$i][2],0,1);
							if(!in_array($row_check,$participating)){
								echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\"><button type=\"submit\" name=\"register\" id=\"register\" value=\"0-". $activeMentee[$i][2]. "-" . $activeMentee[$i][1] . "-". $activeMentee[$i][3]."\">Participate</button></td>");
							}
							else{
								echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">Confirmed</td>");
							}
						echo("</tr>");
					}
				}
				if($mentorID != NULL){
					echo("<tr style=\"min-width:100px;border:1px solid;border-collapse: collapse;\"><th style=\"min-width:100px;border:1px solid;border-collapse: collapse;\"colspan = \"6\" >Mentor Courses</th></tr>");
					for($i=0;$i<count($activeMentor);$i++){
						$sesDate = new DateTime($activeMentor[$i][4]);
						echo("<tr>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentor[$i][0] ."</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentor[$i][1] . "</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">".$activeMentor[$i][3] . "</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$sesDate->format("l m/d") . "</td>
							<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">".$activeMentor[$i][5]. "-".$activeMentor[$i][6] . "</td>");
							$row_check=array($userid,$activeMentor[$i][3],$activeMentor[$i][1],$activeMentor[$i][2],1,0);
							if(!in_array($row_check,$participating)){
								echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\"><button type=\"submit\" name=\"register\" id=\"register\" value=\"1-". $activeMentor[$i][2]. "-" . $activeMentor[$i][1] . "-". $activeMentor[$i][3]."\">Participate</button></td>");
							}
							else{
								echo("<td style=\"min-width:100px;border:1px solid;border-collapse: collapse;text-align:center\">Confirmed</td>");
							}
						echo("</tr>");
					}
				}
			?>
		</table>
	</form>
</body>
</html>