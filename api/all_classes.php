<?php
	
	function getConflicts($value,$myconnection){
		$conflicts=array();
		for($i=0;$i<count($value);++$i){
			$course=$value[$i][0];
			$section = $value[$i][1];
			
			$getSecInfo= "SELECT startDate,endDate,timeSlotID 
			FROM sections
			WHERE courseID = ". $course." AND sectionID = ".$section." ;";
			$section= mysqli_query($myconnection, $getSecInfo) or die ("Failed to query database: " . mysqli_error($myconnection));
			$section = $section->fetch_row();
			$start = $section[0];
			$end=$section[1];
			$ts=$section[2];
			
			$getconflicts="SELECT courseID,sectionID
			FROM sections
			WHERE courseID = ".$course." OR( timeSlotID = ".$ts." AND((startDate < '".$start."' AND endDate < '".$end."' AND endDate > '".$start. "') OR (startDate > '".$start."' AND endDate > '".$end."' AND startDate < '".$end."') OR (startDate > '".$start."' AND endDate < '".$end."') OR ( startDate < '".$start."' AND endDate > '".$end."') OR startDate = '".$start."' OR endDate = '".$end."'));";
			$conflict = mysqli_query($myconnection, $getconflicts) or die ("Failed to query database: " . mysqli_error($myconnection));
			$conflict = $conflict->fetch_all();
			
			for ($j =0; $j<count($conflict); $j++){
				if(!in_array(array($conflict[$j][0],$conflict[$j][1]),$conflicts)){
						$conflicts[]=array($conflict[$j][0],$conflict[$j][1]);
				}
			}
			
		}
		return $conflicts;
	}
	
	function getGradeLevel($CID,$myconnection){
		$getGL="SELECT cou.mentorReq, cou.menteeReq
				FROM courses cou
				WHERE cou.courseID = ".$CID.";";
		$GL = mysqli_query($myconnection, $getGL) or die ("Failed to query database: " . mysqli_error($myconnection));
		return $GL->fetch_row();
	}
	
	function getUserClasses($UID,$myconnection){
		$get_classes = "SELECT mf.courseID, mf.sectionID
						FROM mentees ment, menteefor mf
						WHERE ment.userID = ".$UID." AND ment.menteeID = mf.menteeID 
						UNION
						SELECT mf.courseID, mf.sectionID
						FROM mentors ment, mentorfor mf
						WHERE ment.userID = ".$UID." AND ment.mentorID = mf.mentorID;";
		$classes = mysqli_query($myconnection, $get_classes) or die ("Failed to query database: " . mysqli_error($myconnection));
		$classes = $classes->fetch_all();
		return $classes;
	}

	//var_dump(getConflicts(getUserClasses(23,$myconnection),$myconnection));
	$today = new DateTime();
    $value = json_decode(file_get_contents('php://input'));
	if($value == NULL){
		$value = array();
		$value[] = 13;
		$value[]= 6;
		$value[]= 1;
		$value[]= 0;
	}

	if ($value != NULL){
		$uid = $value[0];
		$regClass = [$value[1],$value[2]];
		$regType = $value[3];
		
		$myconnection = mysqli_connect('localhost', 'root', '') 
		or die ('Could not connect: ' . mysql_error());

		$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
		
		mysqli_query($myconnection, "START TRANSACTION");
		
		$getMentee = "SELECT m.menteeID, u.gradeLevel FROM mentees m, users u WHERE m.userID =".$uid." and u.userID = m.userID;";
		$mentee= mysqli_query($myconnection, $getMentee) or die ("Failed to query database: " . mysqli_error($myconnection));
		$mentee = $mentee->fetch_row();
		
		$getMentor = "SELECT m.mentorID, u.gradeLevel FROM mentors m, users u WHERE m.userID =".$uid." and u.userID = m.userID;";
		$mentor= mysqli_query($myconnection, $getMentor) or die ("Failed to query database: " . mysqli_error($myconnection));
		$mentor = $mentor->fetch_row();
		
		$getMod = "SELECT modID FROM moderators WHERE userID =".$uid.";";
		$mod= mysqli_query($myconnection, $getMod) or die ("Failed to query database: " . mysqli_error($myconnection));
		$mod = $mod->fetch_row();
		
		$getFilledModPos = "SELECT courseID,sectionID
						FROM modfor;";
		$filledModPos=  mysqli_query($myconnection, $getFilledModPos) or die ("Failed to query database: " . mysqli_error($myconnection));
		$filledModPos = $filledModPos->fetch_all();
		
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
		
		if($regType == 1){
			if(!in_array($regClass,getConflicts(getUserClasses($uid,$myconnection),$myconnection))and $mentor != NULL AND $mentor[1] >= getGradeLevel($regClass[0],$myconnection)[0]){
				$Enrole = "INSERT INTO mentorfor(mentorID,sectionID,courseID) VALUES (" . $mentor[0] . "," . $regClass[1] . "," . $regClass[0] .");";
				
					$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
				mysqli_query($myconnection, "COMMIT");
			}
		}
		else if($regType == 2){
			if(!in_array($regClass,getConflicts(getUserClasses($uid,$myconnection),$myconnection))and $mentee != NULL AND $mentee[1] >= getGradeLevel($regClass[0],$myconnection)[1]){
				$Enrole = "INSERT INTO menteefor(menteeID,sectionID,courseID) VALUES (" . $mentee[0] . "," . $regClass[1] . "," . $regClass[0] .");";
					$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
					mysqli_query($myconnection, "COMMIT");
			}
		}
		else if($regType == 3){
			if($mod != NULL AND !in_array([$regClass[0],$regClass[1]],$filledModPos)){
				$Enrole = "INSERT INTO modfor(modID,sectionID,courseID) VALUES (" . $mod[0] . "," . $regClass[1] . "," . $regClass[0] .");";
					$enroled = mysqli_query($myconnection, $Enrole) or die ("Failed to query database: " . mysqli_error($myconnection));
					mysqli_query($myconnection, "COMMIT");
				$filledModPos[] = array($regClass[0],$regClass[1]);
			}
		}
		else {
			
		}	
		$conflicts = getConflicts(getUserClasses($uid,$myconnection),$myconnection);
		$result_json = array();
		for($i=0;$i<count($sections);$i++){
			$list = array();
			$timeslot = "";
			$list['courseID'] = $sections[$i][0];
			$list['courseName'] = $sections[$i][2];
			$seclist = array();
			do{
				$subsec = array();
				$subsec['sectionID'] = $sections[$i][1];
				$subsec['sectionName'] = $sections[$i][3];
				$subsec['descrption'] = $sections[$i][4];
				$subsec['startDate'] = $sections[$i][5];
				$subsec['endDate'] = $sections[$i][6];
				$currentTS=$timeSlots[$sections[$i][7]];
				$timeslot="";
				if($currentTS[0]){
					$timeslot = $timeslot . "m";
				}
				if($currentTS[1]){
					$timeslot= $timeslot . "t";
				}
				if($currentTS[2]){
					$timeslot= $timeslot . "w";
				}
				if($currentTS[3]){
					$timeslot= $timeslot . "th";
				}
				if($currentTS[4]){
					$timeslot= $timeslot . "f";
				}
				if($currentTS[5]){
					$timeslot= $timeslot . "s";
				}
				$timeslot= $timeslot . " " . $currentTS[6]. "-" . $currentTS[7];
				$subsec['timeslot'] = $timeslot;
				$subsec['mentorReq'] = $sections[$i][8];
				$subsec['menteeReq'] = $sections[$i][9];
				$endDate = new DateTime($subsec['endDate']);
			if(!in_array(array($sections[$i][0],$sections[$i][1]),$conflicts)and $mentee != NULL AND $mentee[1] >= getGradeLevel($sections[$i][0],$myconnection)[1] and $endDate>$today){
				$subsec['avail_ee'] = true;
			}
			else{
				$subsec['avail_ee'] = false;
			}
			if(!in_array(array($sections[$i][0],$sections[$i][1]),$conflicts)and $mentor != NULL AND $mentor[1] >= getGradeLevel($sections[$i][0],$myconnection)[0] and $endDate>$today){
				$subsec['avail_or'] = true;
			}
			else{
				$subsec['avail_or'] = false;
			}
			if(!in_array([$sections[$i][0],$sections[$i][1]],$filledModPos)and $mod != NULL and $endDate>$today){
				$subsec['avail_mod'] = true;
			}
			else{
				$subsec['avail_mod'] = false;
				$getModerator = "SELECT usr.name 
								FROM users usr, modfor mf , moderators modd
								WHERE mf.courseID = ".$sections[$i][0]." AND mf.sectionID = ".$sections[$i][1]." AND mf.modID = modd.modID AND usr.userID = modd.userID;";
				$Moderator = mysqli_query($myconnection, $getModerator) or die ("Failed to query database: " . mysqli_error($myconnection));
				$Moderator=$Moderator->fetch_row();
				if($Moderator == NULL){
					$subsec['Moderator'] = "None";
				}else {
					$subsec['Moderator'] = $Moderator[0];
				}
			}
			
				$seclist[]=$subsec;
				//echo("". $sections[$i][0] . " ". $sections[$i+1][0]."\n");
				if ($i+1<count($sections) and $sections[$i][0] == $sections[$i+1][0]){
					++$i;
				}
				else{ break;}
			
			}while($i+1<count($sections));
			//echo("breaking");
			$list['sections']=$seclist;
			$list['sec_count']=count($seclist);
			
			$result_json[]=$list;
		}
		//$result_json = $value;
		
		mysqli_close($myconnection);
		header('Content-Type: application/json');
		$encoded = json_encode($result_json);
		
		echo $encoded;
	}
?>