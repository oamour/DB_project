<?php
	$session_key = md5("database");
	session_start();
	if (empty($_SESSION[$session_key])) {
		echo "Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page.";
		exit();
	} elseif (intval($_SESSION[$session_key]) <= 0) {
		unset($_SESSION[$session_key]);
		echo "Invalid session key! Please <a href='index.php'>CLICK HERE</a> to return to the main page.";
		exit();
	} else {
		$userid = $_SESSION[$session_key];
	}
	$myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());

	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	$today = new DateTime();
	
	
	
	$GetModID = "SELECT modID FROM moderators WHERE userID = " . $userid . ";";
	$modID = mysqli_query($myconnection, $GetModID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$modID = $modID->fetch_array()[0];
	
	if($modID != NULL){
		$getFilledSections = "SELECT sectionID, courseID FROM modfor;";
		$filledSections = mysqli_query($myconnection, $getFilledSections) or die ("Failed to query database: " . mysqli_error($myconnection));
		$filledSections = $filledSections->fetch_all();
		if(isset($_POST['register'])){
			$sel_sec = $_POST['register'];
			$Section = "";
			$Course = "";
			$offset = 0;
			for($i = 0; $i<strlen($sel_sec);$i++){
				if($sel_sec[$i] == '-'){
					$offset = $i+1;
					break;
				}
				$Course = $Course . $sel_sec[$i];
			}
			for($i=$offset;$i<strlen($sel_sec);$i++){
				$Section = $Section . $sel_sec[$i];	
			}
			
			if(!in_array(array($Section,$Course),$filledSections)){
				$filledSections[]=array($Section,$Course);
				$insert = "INSERT INTO modfor(modID, sectionID, courseID) VALUES (".$modID.", ".$Section.", ".$Course.");";
				$Insert = mysqli_query($myconnection, $insert) or die ("Failed to query database: " . mysqli_error($myconnection));
			}
		}
		
		$getSections = "SELECT sec.courseID, sec.sectionID, cou.title, sec.name, sec.timeSlotID, sec.startDate,sec.endDate
						FROM sections sec, courses cou
						WHERE sec.courseID = cou.courseID
						ORDER BY sec.courseID,sec.sectionID ASC";
		$sections= mysqli_query($myconnection, $getSections) or die ("Failed to query database: " . mysqli_error($myconnection));
		$sections = $sections->fetch_all();
		$usableSections=array();
		for($i= 0;$i<count($sections);$i++){
			$checkStart = new DateTime($sections[$i][5]);
			if(!$checkStart->diff($today)->invert){
				$usableSections[] = $sections[$i];
			}
		}
		$sections = $usableSections;
		
		
		$timeSlots = array();
		$usedTS = array();
		$modMap= array();
		$mods= array();
		for($i = 0; $i<count($sections);$i++){
			if(!in_array($sections[$i][4],$usedTS)){
				$usedTS[] = $sections[$i][4];
				$GetTS = "SELECT ts.m,ts.t,ts.w,ts.th,ts.f,ts.sa,ts.startTime, ts.endTime FROM timeslot ts WHERE ts.timeSlotID = ". $sections[$i][4].";";
				$ts= mysqli_query($myconnection, $GetTS) or die ("Failed to query database: " . mysqli_error($myconnection));
				$ts = $ts->fetch_row();
				$timeSlots[$sections[$i][4]] = $ts;
			}
			$getModFor = "SELECT m.modID,u.name,mf.courseId,mf.sectionID FROM modfor mf, moderators m, users u WHERE courseID = ".$sections[$i][0]." AND sectionID = ".$sections[$i][1]." AND m.modID = mf.modID AND m.userID = u.userID;";
			$modFor= mysqli_query($myconnection, $getModFor) or die ("Failed to query database: " . mysqli_error($myconnection));
			$modFor = $modFor->fetch_row();
			if($modFor!=NULL){
				$modMap[$modFor[0]] = $modFor[1];
				if (!in_array($sections[$i][0],array_keys($mods))){
					$mods[$sections[$i][0]]= array($sections[$i][1] => $modFor[0]);
				}
				else{
					$mods[$sections[$i][0]][$sections[$i][1]] = $modFor[0];
				}
			}
			else{
				if (!in_array($sections[$i][0],array_keys($mods))){
					$mods[$sections[$i][0]]= array($sections[$i][1] => NULL);
				}
				else{
					$mods[$sections[$i][0]][$sections[$i][1]] = NULL;
				}
			}
			
		}
	}
	mysqli_close($myconnection);
?>
<!DOCTYPE html>
<html>
<?php if ($modID != NULL):?>
<head>
</head>
<body>
	<a href="dashboard.php">Back to Start</a>
	<h1>Section Moderators</h1>
	<form method="post" action="moderator.php">
		<table style="border:1px solid;border-collapse: collapse;">
				<tr style = "border:1px solid;border-collapse: collapse;">
					<th style="min-width:50px;border:1px solid;border-collapse: collapse;">ID</th>
					<th style="min-width:125px;border:1px solid;border-collapse: collapse;">Course</th>
					<th style="min-width:100px;border:1px solid;border-collapse: collapse;">Section</th>
					<th style="min-width:175px;border:1px solid;border-collapse: collapse;">Time</th>
					<th style="min-width:100px;border:1px solid;border-collapse: collapse;">startDate</th>
					<th style="min-width:100px;border:1px solid;border-collapse: collapse;">endDate</th>
					<th style="min-width:100px;border:1px solid;border-collapse: collapse;">Current Mod.</th>
					<th style="border:1px solid;border-collapse: collapse;">Moderate</th>
				</tr>
				<?php
				for($i = 0; $i<count($sections);$i++){
					$currentTS =$timeSlots[$sections[$i][4]];
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
					<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">"); 

					if($mods[$sections[$i][0]][$sections[$i][1]]==NULL){
						echo("No Mod");
					}
					else{
						echo($modMap[$mods[$sections[$i][0]][$sections[$i][1]]]);
					}
					echo( "</td>");
					if(!in_array(array($sections[$i][1], $sections[$i][0]),$filledSections)){
					echo("<td style = \"border:1px solid;border-collapse: collapse;text-align:center\"><button type=\"submit\" name=\"register\" id=\"register\" value=\"". $sections[$i][0]. "-" . $sections[$i][1] . "\">Moderate</button></td>");
					}
					else{
						echo("<td style = \"border:1px solid;border-collapse: collapse;text-align:center\">space Full</td>");
					}
					echo("</tr>");
					
				}
					?>
		</table>
	</form>
	
</body>
<?php else : ?>
<head></head>
<body> User is not a moderator please <a href='index.php'>CLICK HERE</a> to return to the main page.</body>
<?php endif;?>
</html>
