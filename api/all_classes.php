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
	
	$GetSections = "SELECT sec.courseID, sec.sectionID, cou.title, sec.name, cou.description, sec.startDate, sec.endDate, sec.timeSlotID, cou.mentorReq, cou.menteeReq
				   FROM sections sec, courses cou
				   WHERE cou.courseID = sec.courseID
				   ORDER BY sec.courseID,sec.sectionID ASC;";
	$sections= mysqli_query($myconnection, $GetSections) or die ("Failed to query database: " . mysqli_error($myconnection));
	$sections = $sections->fetch_all();
	
	$timeSlots = array();
	$usedTS = array();
	for($i = 0; $i<count($sections);$i++){
		if(!in_array($sections[$i][7],$usedTS)){
			$usedTS[] = $sections[$i][7];
			$GetTS = "SELECT ts.m,ts.t,ts.w,ts.th,ts.f,ts.sa,ts.startTime, ts.endTime FROM timeslot ts WHERE ts.timeSlotID = ". $sections[$i][7].";";
			$ts= mysqli_query($myconnection, $GetTS) or die ("Failed to query database: " . mysqli_error($myconnection));
			$ts = $ts->fetch_row();
			$timeSlots[$sections[$i][7]] = $ts;
		}
	}
	
	$GetMod = "SELECT modID FROM moderators WHERE userID= ".$userid.";";
	$mod= mysqli_query($myconnection, $GetMod) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mod = $mod->fetch_row()[0];
	
	$GetMentor = "SELECT mentorID FROM mentors WHERE userID= ".$userid.";";
	$mentor= mysqli_query($myconnection, $GetMentor) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentor = $mentor->fetch_row()[0];
	
	$GetMentee = "SELECT menteeID FROM mentees WHERE userID= ".$userid.";";
	$mentee= mysqli_query($myconnection, $GetMentee) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentee = $mentee->fetch_row()[0];
	
	mysqli_close($myconnection);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<a href="dashboard.php">Back to Start</a>
	<h1>Class List</h1>
	<?php if ($mentor != NULL or $mentee != NULL) :?>
	<p>To sign up for classes <a href="course_signup.php">CLICK HERE</a></p>
	<?php elseif ($mod != NULL) :?>
	<p>To sign up to moderate <a href="course_moderate.php">CLICK HERE</a></p>
	<?php else :?>
	<?php endif;?>
	<table style="border:1px solid;border-collapse: collapse;">
			<tr style = "border:1px solid;border-collapse: collapse;">
				<th style="min-width:50px;border:1px solid;border-collapse: collapse;">ID</th>
				<th style="min-width:125px;border:1px solid;border-collapse: collapse;">Course</th>
				<th style="min-width:100px;border:1px solid;border-collapse: collapse;">Section</th>
				<th style="min-width:175px;border:1px solid;border-collapse: collapse;">Time</th>
				<th style="border:1px solid;border-collapse: collapse;">startDate</th>
				<th style="border:1px solid;border-collapse: collapse;">endDate</th>
				<th style="border:1px solid;border-collapse: collapse;">mentor Req.</th>
				<th style="border:1px solid;border-collapse: collapse;">mentee Req.</th>
				<th style="border:1px solid;border-collapse: collapse;">Description</th>
			</tr>
			<?php
			for($i = 0; $i<count($sections);$i++){
				$currentTS =$timeSlots[$sections[$i][7]];
				echo("<tr style = \"border:1px solid;border-collapse: collapse;\" >");
				echo("
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][0].".".$sections[$i][1] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][2] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][3] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:right \">");
				if($currentTS[0]){
					echo("m");
				}
				if($currentTS[1]){
					echo("t");
				}
				if($currentTS[2]){
					echo("w");
				}
				if($currentTS[3]){
					echo("th");
				}
				if($currentTS[4]){
					echo("f");
				}
				if($currentTS[5]){
					echo("s");
				}
				echo(" - " . $currentTS[6] . "-" . $currentTS[7] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][5] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][6] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][8] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $sections[$i][9] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\"><span title = \"".$sections[$i][4]."\">" . substr($sections[$i][4],0,33) . "<span></td>
				</tr>");
				
			}
				?>
			</table>
	
</body>
</html>