<?php
	# default values for each field
	$errorMessage = NULL;
	$deltaMentee = false;
	$deltaMentor = false;
	$MenteeIn = array();
	$MentorIn =array();
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

	$findcourses = "SELECT cou.title,sec.sectionID,ts.startTime,ts.endTime,ts.m,ts.t,ts.w,ts.th,ts.f,ts.sa,cou.menteeReq,cou.mentorReq,sec.capacity,sec.courseID,sec.timeSlotID,sec.startDate,sec.endDate FROM sections sec, courses cou, timeslot ts WHERE sec.courseID = cou.courseID AND ts.timeSlotID = sec.timeSlotID;";
	$result = mysqli_query($myconnection, $findcourses) or die ("Failed to query database: " . mysqli_error($myconnection));

	$answer = $result->fetch_all();
	//var_dump($answer);
	
	$today = new DateTime();
	
	$GetMenteeID = "SELECT menteeID FROM mentees WHERE userID = " . $userid . ";";
	$menteeID = mysqli_query($myconnection, $GetMenteeID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$menteeID = $menteeID->fetch_array()[0];

	$GetMentorID = "SELECT mentorID FROM mentors WHERE userID = " . $userid . ";";
	$mentorID = mysqli_query($myconnection, $GetMentorID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$mentorID = $mentorID->fetch_array()[0];
	
	if($menteeID!=NULL){
		$GetMenteeIn = "SELECT courseID,sectionID FROM menteefor WHERE menteeID = " . $menteeID . ";";
		$MenteeIn = mysqli_query($myconnection, $GetMenteeIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MenteeIn = $MenteeIn->fetch_all();
	}
	
	if($mentorID!=NULL){
		$GetMentorIn = "SELECT courseID,sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ";";
		$MentorIn = mysqli_query($myconnection, $GetMentorIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MentorIn = $MentorIn->fetch_all();
	}
	

	
	$GetUserLevel = "SELECT gradeLevel FROM users WHERE userID = " . $userid . ";";
	$UserLevel = mysqli_query($myconnection, $GetUserLevel) or die ("Failed to query database: " . mysqli_error($myconnection));
	$UserLevel = $UserLevel->fetch_array()[0];
	//var_dump($UserLevel);
	
if(isset($_POST['register'])){
	$sel_sec = $_POST['register'];
	$regType = $sel_sec[0];
	if ($regType == "0"){
		if($menteeID!=NULL){
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
				$Section = $Section . $sel_sec[$i];
			}
			$GetSection = "SELECT sectionID,courseID FROM sections WHERE courseID = ".$Course." AND sectionID = " . $Section . ";";
			$section = mysqli_query($myconnection, $GetSection) or die ("Failed to query database: " . mysqli_error($myconnection));
			$section = $section->fetch_array();
			
			$temp= array($Course,$Section);
			if($section!=NULL){
				if (in_array($temp,$MenteeIn)){
					$errorMessage = "Already Registered for this Course";
				}
				else{
					$deltaMentee = True;
					$Enrole = "INSERT INTO menteefor(menteeID,sectionID,courseID) VALUES (" . $menteeID . "," . $Section . "," . $Course .");";
					$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
				}
			}
		}
		else{
			$errorMessage = "unable to register, user is not registered as a mentee;";
		}
	}
	else{
		if($mentorID!=NULL){
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
				$Section = $Section . $sel_sec[$i];
			}
			$GetSection = "SELECT sectionID,courseID FROM sections WHERE courseID = ".$Course." AND sectionID = " . $Section . ";";
			$section = mysqli_query($myconnection, $GetSection) or die ("Failed to query database: " . mysqli_error($myconnection));
			$section = $section->fetch_array();
			// $GetUserLevel = "SELECT gradeLevel FROM users WHERE userID = " . $userid . ";";
			// $UserLevel = mysqli_query($myconnection, $GetUserLevel) or die ("Failed to query database: " . mysqli_error($myconnection));
			// $UserLevel = $UserLevel->fetch_array()[0];
			//$GetMentorIn = "SELECT sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ";";
			// $MentorIn = mysqli_query($myconnection, $GetMentorIn) or die ("Failed to query database: " . mysqli_error($myconnection));
			// $MentorIn = $MenteeIn->fetch_all();
			// $alredyRegistered=false;
			$temp= array($Course,$Section);
			if($section!=NULL){
				if (in_array($temp,$MentorIn)){
					$errorMessage = "Already Registered for this Course";
				}
				else{
						$deltaMentor = True;
						$Enrole = "INSERT INTO mentorfor(mentorID,sectionID,courseID) VALUES (" . $mentorID . "," . $section[0] . "," . $section[1] .");";
						$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
						//$Update = "UPDATE sections SET capacity = capacity-1 WHERE sectionID = ". $section[0].";";
						//$updated = mysqli_query($myconnection, $Update) or die ("Failed to query database: " . mysqli_error($myconnection));
				}
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
	
	if($mentorID != NULL){
		$GetMentorTS = "SELECT timeSlotID FROM sections WHERE sectionID in (SELECT sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ");";
		$MentorTS = mysqli_query($myconnection, $GetMentorTS) or die ("Failed to query database: " . mysqli_error($myconnection));
		$mentorTS = $MentorTS->fetch_all();
	}
	else{
		$mentorTS = array();	
	}
	if($menteeID !=NULL){
		$GetMenteeTS = "SELECT timeSlotID FROM sections WHERE sectionID in (SELECT sectionID FROM menteefor WHERE menteeID = " . $menteeID . ");";
		$MenteeTS = mysqli_query($myconnection, $GetMenteeTS) or die ("Failed to query database: " . mysqli_error($myconnection));
		$menteeTS = $MenteeTS->fetch_all();
	}
	else{
		$menteeTS = array();	
	}
	
	$usedTS = array_merge($menteeTS,$mentorTS);
	
	//var_dump($mentorCount);
	if($deltaMentee == True){
		$GetMenteeIn = "SELECT courseID,sectionID FROM menteefor WHERE menteeID = " . $menteeID . ";";
		$MenteeIn = mysqli_query($myconnection, $GetMenteeIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MenteeIn = $MenteeIn->fetch_all();
	}
	if($deltaMentor ==True){
		$GetMentorIn = "SELECT courseID,sectionID FROM mentorfor WHERE mentorID = " . $mentorID . ";";
		$MentorIn = mysqli_query($myconnection, $GetMentorIn) or die ("Failed to query database: " . mysqli_error($myconnection));
		$MentorIn = $MentorIn->fetch_all();
	}
	$Courses =array();
	for($i=0; $i<count($MentorIn);$i++){
		if(!in_array($MentorIn[$i][0],$Courses)){
			$Courses[] = $MentorIn[$i][0];
		}
	}
	for($i=0; $i<count($MenteeIn);$i++){
		if(!in_array($MenteeIn[$i][0],$Courses)){
			$Courses[] = $MenteeIn[$i][0];
		}
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
				<?php if ($menteeID != NULL){echo("<th style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">Mentee Slots</th>");}?>
				<?php if ($mentorID != NULL){echo("<th style=\"min-width:100px;border:1px solid;border-collapse: collapse;\">Mentor Slots</th>");}?>
				<th <?php if($menteeID == NULL or $mentorID == NULL){echo("colspan =\"1\"");} else {echo("colspan=\"2\"");}?> style="border:1px solid;border-collapse: collapse;">Reg. As</th>
			</tr>
			<?php
			for($i = 0; $i<count($answer);$i++){
				$courseDate = new DateTime($answer[$i][16]);
				if($courseDate->diff($today)->invert){
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
					$temp= array($answer[$i][13],$answer[$i][1]);
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
					if($menteeID != NULL){
						echo("<td style = \"border:1px solid;border-collapse: collapse;text-align:center\"> " . $CurrentMenteeCount . "</td>");
					}
					if ($mentorID != NULL){
					echo("<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">" . $CurrentMentorCount . "</td>");
					}
					//var_dump($usedTS);
					if($menteeID != NULL){
						if( !in_array($answer[$i][13],$Courses) and $CurrentMenteeCount > 0 and $menteeID!=NULL and $UserLevel >= $answer[$i][10] and !(in_array($temp,$MenteeIn) or in_array($temp,$MentorIn))){
							$conflict = False;
							if(in_array($TS,$usedTS)){
								$GetNonConflict = "SELECT sec.courseID,sec.sectionID,sec.startDate,sec.endDate FROM sections sec WHERE sec.timeSlotID = " . $TS[0] . ";";
								$NonConflictList = mysqli_query($myconnection, $GetNonConflict) or die ("Failed to query database: " . mysqli_error($myconnection));
								$NonConflictList = $NonConflictList->fetch_all();
								
								$currentStart = new DateTime($answer[$i][15]);
								$currentEnd = new DateTime($answer[$i][16]);
								
								for ($j=0;$j<count($NonConflictList);$j++){
									if(in_array(array($NonConflictList[$j][1]),$MenteeIn)or in_array(array($NonConflictList[$j][1]),$MentorIn)){
										$checkStart = new DateTime($NonConflictList[$j][2]);
										$checkEnd = new DateTime($NonConflictList[$j][3]);
										if(!($currentStart->diff($checkStart)->invert and $currentStart->diff($checkEnd)->invert and $currentEnd ->diff($checkStart)->invert) and !(!$currentStart->diff($checkStart)->invert and !$currentStart->diff($checkEnd)->invert and !$currentEnd ->diff($checkStart)->invert)){
											$conflict = True;
											break;
										}
									}
									
								}
							}
							if(!$conflict){
								echo("<td><button type=\"submit\" name=\"register\" id=\"register\" value=\"0-". $answer[$i][13]. "-" . $answer[$i][1] . "\">mentee</button></td>");
							}else{
								echo("<td>N/A</td>");
							}
							
						} else{
							echo("<td>N/A</td>");
						}
					}
					if($mentorID != NULL){
						if(!in_array($answer[$i][13],$Courses) and $CurrentMentorCount > 0 and $mentorID!=NULL and $UserLevel >= $answer[$i][11]and !(in_array($temp,$MentorIn) or in_array($temp,$MenteeIn))){
							$conflict = False;
							if(in_array($TS,$usedTS)){
								$GetNonConflict = "SELECT sec.courseID,sec.sectionID,sec.startDate,sec.endDate FROM sections sec WHERE sec.timeSlotID = " . $TS[0] . ";";
								$NonConflictList = mysqli_query($myconnection, $GetNonConflict) or die ("Failed to query database: " . mysqli_error($myconnection));
								$NonConflictList = $NonConflictList->fetch_all();
								
								$currentStart = new DateTime($answer[$i][15]);
								$currentEnd = new DateTime($answer[$i][16]);
								
								for ($j=0;$j<count($NonConflictList);$j++){
									if(in_array(array($NonConflictList[$j][1]),$MentorIn) or in_array(array($NonConflictList[$j][1]),$MenteeIn)){
										$checkStart = new DateTime($NonConflictList[$j][2]);
										$checkEnd = new DateTime($NonConflictList[$j][3]);
										if(!($currentStart->diff($checkStart)->invert and $currentStart->diff($checkEnd)->invert and $currentEnd ->diff($checkStart)->invert) and !(!$currentStart->diff($checkStart)->invert and !$currentStart->diff($checkEnd)->invert and !$currentEnd ->diff($checkStart)->invert)){
											$conflict = True;
											break;
										}
									}
									
								}
							}
							if(!$conflict){
								echo("<td><button type=\"submit\" name=\"register\" id=\"register\" value=\"1-". $answer[$i][13]. "-" . $answer[$i][1] . "\">mentor</button></td>");
							}else{
								echo("<td>N/A</td>");
							}
							
						} else{
						echo("<td>N/A</td>");
						}
					}
					echo("</tr>");
				}
			}
				mysqli_close($myconnection);
			?>
		</table>
	</form>
</body>
</html>
