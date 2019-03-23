<?php
	# default values for each field
	$errorMessage = NULL;
	$deltaMentee = false;
	$deltaMentor = false;
	//function register_for_class

	# Submission handler
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

	$findcourses = "SELECT cou.title,sec.sectionID,ts.startTime,ts.endTime,ts.m,ts.t,ts.w,ts.th,ts.f,ts.sa,cou.menteeReq,cou.mentorReq,sec.capacity,sec.courseID,sec.timeSlotID FROM sections sec, courses cou, timeslot ts WHERE sec.courseID = cou.courseID AND ts.timeSlotID = sec.timeSlotID;";
	$result = mysqli_query($myconnection, $findcourses) or die ("Failed to query database: " . mysqli_error($myconnection));

	$answer = $result->fetch_all();
	
	
	
	$GetMenteeID = "SELECT menteeID FROM mentees WHERE userID = " . $userid . ";";
	$menteeID = mysqli_query($myconnection, $GetMenteeID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$menteeID = $menteeID->fetch_array()[0];

	$GetMentorID = "SELECT mentorID FROM mentors WHERE userID = " . $userid . ";";
	$mentorID = mysqli_query($myconnection, $GetMentorID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentorID = $mentorID->fetch_array()[0];
	
	if($menteeID!=NULL){
		$GetMenteeIn = "SELECT sectionID FROM menteefor WHERE menteeID = " . $menteeID . ";";
		$MenteeIn = mysqli_query($myconnection, $GetMenteeIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MenteeIn = $MenteeIn->fetch_all();
		
		$GetMenteeTS = "SELECT timeSlotID FROM sections WHERE sectionID in (SELECT sectionID FROM menteefor WHERE menteeID = " . $menteeID . ");";
		$MenteeTS = mysqli_query($myconnection, $GetMenteeTS) or die ("Failed to query database: " . mysqli_error($myconnection));
		$menteeTS = $MenteeTS->fetch_all();
	}
	if($mentorID!=NULL){
		$GetMentorIn = "SELECT sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ";";
		$MentorIn = mysqli_query($myconnection, $GetMentorIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MentorIn = $MentorIn->fetch_all();
		
		$GetMentorTS = "SELECT timeSlotID FROM sections WHERE sectionID in (SELECT sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ");";
		$MentorTS = mysqli_query($myconnection, $GetMentorTS) or die ("Failed to query database: " . mysqli_error($myconnection));
		$mentorTS = $MentorTS->fetch_all();
	}
	
	$usedTS = array_merge($menteeTS,$mentorTS);
	
	$GetUserLevel = "SELECT gradeLevel FROM users WHERE userID = " . $userid . ";";
	$UserLevel = mysqli_query($myconnection, $GetUserLevel) or die ("Failed to query database: " . mysqli_error($myconnection));
	$UserLevel = $UserLevel->fetch_array()[0];
	
if(isset($_POST['register'])){
	$sel_sec = $_POST['register'];
	$regType = $sel_sec[0];
	if ($regType == "0"){
		if($menteeID!=NULL){
			$Section = "";
			for($i=2;$i<5;$i++){
				$Section = $Section . $sel_sec[$i];
			}
			$GetSection = "SELECT sec.sectionID,cou.courseID,cou.menteeReq,sec.capacity FROM sections sec, courses cou WHERE cou.courseID = sec.courseID AND sec.sectionID = " . $Section . ";";
			$section = mysqli_query($myconnection, $GetSection) or die ("Failed to query database: " . mysqli_error($myconnection));
			$section = $section->fetch_array();
			
			//$GetMenteeIn = "SELECT sectionID FROM menteefor WHERE menteeID = " . $menteeID . ";";
			//$MenteeIn = mysqli_query($myconnection, $GetMenteeIn) or die ("Failed to query database: " . mysqli_error($myconnection));
			//$MenteeIn = $MenteeIn->fetch_all();
			$alredyRegistered=false;
			if($section!=NULL){
				// if (in_array($Section,$MenteeIn)){
					// $errorMessage = "Already Registered for this Course";
				// }
				// else{
					if($UserLevel >= $section[2] && $section[3] > 0){
						$deltaMentee = True;
						$Enrole = "INSERT INTO menteefor(menteeID,sectionID,courseID) VALUES (" . $menteeID . "," . $section[0] . "," . $section[1] .");";
						$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
						$Update = "UPDATE sections SET capacity = capacity-1 WHERE sectionID = ". $section[0].";";
						$updated = mysqli_query($myconnection, $Update) or die ("Failed to query database: " . mysqli_error($myconnection));
					}
				// }
			}
		}
		else{
			$errorMessage = "unable to register, user is not registered as a mentee;";
		}
	}
	else{
		if($mentorID!=NULL){
			$Section = "";
			for($i=2;$i<5;$i++){
				$Section = $Section . $sel_sec[$i];
			}
			$GetSection = "SELECT sec.sectionID,cou.courseID,cou.mentorReq,sec.capacity FROM sections sec, courses cou WHERE cou.courseID = sec.courseID AND sec.sectionID = " . $Section . ";";
			$section = mysqli_query($myconnection, $GetSection) or die ("Failed to query database: " . mysqli_error($myconnection));
			$section = $section->fetch_array();
			// $GetUserLevel = "SELECT gradeLevel FROM users WHERE userID = " . $userid . ";";
			// $UserLevel = mysqli_query($myconnection, $GetUserLevel) or die ("Failed to query database: " . mysqli_error($myconnection));
			// $UserLevel = $UserLevel->fetch_array()[0];
			//$GetMentorIn = "SELECT sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ";";
			// $MentorIn = mysqli_query($myconnection, $GetMentorIn) or die ("Failed to query database: " . mysqli_error($myconnection));
			// $MentorIn = $MenteeIn->fetch_all();
			// $alredyRegistered=false;
			if($section!=NULL){
				// if (in_array($Section,$MenteeIn)){
					// $errorMessage = "Already Registered for this Course";
				// }
				// else{
					if($UserLevel >= $section[2] && $section[3] > 0){
						$deltaMentor = True;
						$Enrole = "INSERT INTO mentorfor(mentorID,sectionID,courseID) VALUES (" . $mentorID . "," . $section[0] . "," . $section[1] .");";
						$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
						$Update = "UPDATE sections SET capacity = capacity-1 WHERE sectionID = ". $section[0].";";
						$updated = mysqli_query($myconnection, $Update) or die ("Failed to query database: " . mysqli_error($myconnection));
					}
				// }
			}
		}
		else{
			$errorMessage = "unable to register, user is not registered as a mentor;";
		}
	}
	
}
	$MenteeCount= "SELECT  courseID,sectionID, COUNT(menteeId) FROM menteefor GROUP BY sectionId,courseID;";
	$menteeCount = mysqli_query($myconnection, $MenteeCount) or die ("Failed to query database: " . mysqli_error($myconnection));
	$menteeCount = $menteeCount->fetch_all();
	//var_dump($menteeCount);
	
	$MentorCount= "SELECT  courseID,sectionID,COUNT(mentorId) FROM mentorfor GROUP BY sectionId,courseID;";
	$mentorCount = mysqli_query($myconnection, $MentorCount) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentorCount = $mentorCount->fetch_all();
	//var_dump($mentorCount);
	if($deltaMentee == True){
		$GetMenteeIn = "SELECT sectionID FROM menteefor WHERE menteeID = " . $menteeID . ";";
		$MenteeIn = mysqli_query($myconnection, $GetMenteeIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MenteeIn = $MenteeIn->fetch_all();
	}
	if($deltaMentor ==True){
		$GetMentorIn = "SELECT sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ";";
		$MentorIn = mysqli_query($myconnection, $GetMentorIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MentorIn = $MentorIn->fetch_all();
	}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<a href="index.html">Back to Start</a>
	<h1>Register for a Course</h1>
	<?php 
		if($errorMessage != ""){
			echo($errorMessage . "<br />");
		}
	?>
	<form method="post" action="CourseSignup.php">
		<table style="border:1px solid;border-collapse: collapse;">
			<tr style = "border:1px solid;border-collapse: collapse;">
				<th style="min-width:125px;border:1px solid;border-collapse: collapse;">Course</th>
				<th style="min-width:100px;border:1px solid;border-collapse: collapse;">Section</th>
				<th style="min-width:175px;border:1px solid;border-collapse: collapse;">Time</th>
				<th style="min-width:100px;border:1px solid;border-collapse: collapse;">Mentee Slots</th>
				<th style="min-width:100px;border:1px solid;border-collapse: collapse;">Mentor Slots</th>
				<th colspan="2" style="border:1px solid;border-collapse: collapse;">Reg. As</th>
			</tr>
			<?php
			for($i = 0; $i<count($answer);$i++){
				echo("<tr style = \"border:1px solid;border-collapse: collapse;\" >
				<td style = \"border:1px solid;border-collapse: collapse;\">" . $answer[$i][0] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $answer[$i][1] . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:right \">");
				if($answer[$i][4]){
					echo("m");
				}
				if($answer[$i][5]){
					echo("t");
				}
				if($answer[$i][6]){
					echo("w");
				}
				if($answer[$i][7]){
					echo("th");
				}
				if($answer[$i][8]){
					echo("f");
				}
				if($answer[$i][9]){
					echo("s");
				}
				echo(" - " . $answer[$i][2] . "-" . $answer[$i][3] . "</td>");
				$temp= array($answer[$i][1]);
				$TS = array($answer[$i][14]);
				$CurrentMenteeCount = 6;
				for ($j = 0; $j < count($menteeCount);$j++){
					if($menteeCount[$j][0] == $answer[$i][13] and $menteeCount[$j][1] == $answer[$i][1]){
						$CurrentMenteeCount -= $menteeCount[$j][2];
						break;
					}
				}
				$CurrentMentorCount = 3;
				for ($j = 0; $j < count($mentorCount);$j++){
					if($mentorCount[$j][0] == $answer[$i][13] and $mentorCount[$j][1] == $answer[$i][1]){
						$CurrentMentorCount -= $mentorCount[$j][2];
						break;
					}
				}
				echo("<td style = \"border:1px solid;border-collapse: collapse;text-align:center\"> " . $CurrentMenteeCount . "</td>
				<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $CurrentMentorCount . "</td>");
				//var_dump($usedTS);
				if(!in_array($TS,$usedTS)and $CurrentMenteeCount > 0 and $menteeID!=NULL and $UserLevel >= $answer[$i][10] and !(in_array($temp,$MenteeIn) or in_array($temp,$MentorIn))){
					echo("<td><button type=\"submit\" name=\"register\" id=\"register\" value=\"0-" . $answer[$i][1] . "\">mentee</button></td>");
				} else{
					echo("<td>N/A</td>");
				}
				
				if(!in_array($TS,$usedTS) and $CurrentMentorCount > 0 and $mentorID!=NULL and $UserLevel >= $answer[$i][11]and !(in_array($temp,$MentorIn) or in_array($temp,$MenteeIn))){
					echo("<td><button type=\"submit\" name=\"register\" id=\"register\" value=\"1-" . $answer[$i][1] . "\">mentor</button></td>");
				} else{
				echo("<td>N/A</td>");
				}
				echo("</tr>");
			}
			?>
		</table>
	</form>
</body>
</html>