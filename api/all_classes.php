<?php
    $value = json_decode(file_get_contents('php://input'));
/* 	$session_key = md5("database");
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
	} */
	
	$myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());

	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	mysqli_query($myconnection, "START TRANSACTION");
	
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
	
	$result_json = (object)[];
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
		$value[]=$list;
	}
	$result_json = $value;
	mysqli_close($myconnection);
	header('Content-Type: application/json');
	$encoded = json_encode($result_json);
	
	echo $encoded;
?>