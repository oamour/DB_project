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
	
	$GetSuperMod = "SELECT userID FROM moderators WHERE superMod = 1;";
	$superMod= mysqli_query($myconnection, $GetSuperMod) or die ("Failed to query database: " . mysqli_error($myconnection));
	$superMod = $superMod->fetch_all();
	
	if(in_array(array($userid),$superMod)){
		
		
		
		$GetSessions = "SELECT sessionDate, sessionID, sectionID, courseID FROM sessions;";
		$sessions= mysqli_query($myconnection, $GetSessions) or die ("Failed to query database: " . mysqli_error($myconnection));
		$sessions = $sessions->fetch_all();
		
		$today =  new DateTime();
		while($today->format('N')<7){
			$today ->add(new DateInterval("P1D"));
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
		for($i=0;$i<count($CurrentWeekSessions);$i++){
			$menteeCount = "SELECT COUNT(mentee),COUNT(mentor) FROM participatingin WHERE sessionID =". $CurrentWeekSessions[$i][0]." AND sectionID = ". $CurrentWeekSessions[$i][1]." AND courseID =". $CurrentWeekSessions[$i][2]." ;";
			$MenteeCount = mysqli_query($myconnection, $menteeCount) or die ("Failed to query database: " . mysqli_error($myconnection));
			$MenteeCount = $MenteeCount->fetch_row();
			
			if($MenteeCount[0] < 3){
				$menteeEmail = "SELECT usr.name,usr.email FROM participatingin p, users usr WHERE p.sessionID =". $CurrentWeekSessions[$i][0]." AND p.sectionID = ". $CurrentWeekSessions[$i][1]." AND p.courseID =". $CurrentWeekSessions[$i][2]." AND p.userID = usr.userID;";
				$MenteeEmail = mysqli_query($myconnection, $menteeEmail) or die ("Failed to query database: " . mysqli_error($myconnection));
				$MenteeEmail = $MenteeEmail->fetch_all();
				
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
	mysqli_close($myconnection);
?>