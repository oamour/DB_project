<?php
	$courseID="";
	$sectionID="";
	$sessionID="";
	$title = "";
	$author = "";
	$materialType= "";
	$url = "";
	$notes = "";
	$assigned= (new DateTime())->format('Y-m-d');
	$pastMaterial = "";
	
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
	$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connnect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	$GetModID = "SELECT modID FROM moderators WHERE userID = " . $userid . ";";
	$modID = mysqli_query($myconnection, $GetModID) or die ("Failed to query database: " . mysqli_error($myconnection));
	$modID = $modID->fetch_array()[0];
	
	if($modID != NULL){
		$session_info= array();
		if (isset($_GET['submit'])){
			$session_info['sessionID'] = $_GET['sessionID'];
			$session_info['sectionID'] = $_GET['sectionID'];
			$session_info['courseID'] = $_GET['courseID'];
		}
		if (isset($_POST['submit'])){
			$session_info['sessionID'] = $_POST['sessionID'];
			$session_info['sectionID'] = $_POST['sectionID'];
			$session_info['courseID'] = $_POST['courseID'];
			
			$getMaxSMID = "SELECT MAX(studyMaterialID) FROM studymaterials;";
			$maxSMID = mysqli_query($myconnection, $getMaxSMID) or die ("Failed to query database: " . mysqli_error($myconnection));
			$maxSMID = $maxSMID->fetch_array()[0]+1;
	
			
			$InsertStudyMat = "INSERT INTO studymaterials(studyMaterialID, title, author, materialType, url, notes) VALUES (". $maxSMID .", '".$_POST['Title'] ."','".$_POST['Author'] ."','".$_POST['type'] ."','".$_POST['url'] ."','".$_POST['Notes'] ."');";
			mysqli_query($myconnection, $InsertStudyMat) or die ("Failed to query database: " . mysqli_error($myconnection));
			
			$InsertMatFor = "INSERT INTO materialfor(studyMaterialID, courseID, sectionID,sessionID,assignedDate) VALUES (".$maxSMID .", ".$_POST['courseID'] .",".$_POST['sectionID'] .",".$_POST['sessionID'] .",'". $assigned ."');";
			mysqli_query($myconnection, $InsertMatFor) or die ("Failed to query database: " . mysqli_error($myconnection));
			
			$InsertPostMAT = "INSERT INTO postmaterials (modID, studyMaterialID) VALUES (" .$modID. ",".$maxSMID ." )";
			mysqli_query($myconnection, $InsertPostMAT) or die ("Failed to query database: " . mysqli_error($myconnection));
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<a href="dashboard.php">Back to Start</a>
	<h1>Post Study Materials</h1>
	<form method="post" action="moderate_add_materials.php">
	<input type='hidden' name='sessionID' value="<?php echo $session_info['sessionID'] ?>" />
	<input type='hidden' name='sectionID' value="<?php echo $session_info['sectionID'] ?>" />
	<input type='hidden' name='courseID' value=" <?php echo $session_info['courseID'] ?> " />
	<h3>Post a New Study Material</h3>
		<table>
			<tr>
				<td><label for="Title">Title:</label></td>
				<td><input type="text" name="Title" id="title" value="<?php echo $title ?>" required></td>
			</tr>
			<tr>
				<td><label for="Author">Author:</label></td>
				<td><input type="text" name="Author" id="author" value="<?php echo $author ?>"></td>
			</tr>
			<td><label for="type">Material Type:</label></td>
				<td><select name="type" id="materialType">
					<!--<option value="none">None</option>-->
					<option value="Book">Book</option>
					<option value="Slides">Slides</option>
					<option value="Homework">Homework</option>
					<option value="Other">Other</option>
				</select></td>
			<tr>
			<tr>
				<td><label for="URL">URL:</label></td>
				<td><input type="text" name="url" id="url" value="<?php echo $url ?>" required></td>
			</tr>
			<tr>
				<td><label for="Notes">Notes:</label></td>
				<td><textarea name="Notes" id="notes" value="<?php echo $notes ?>" ></textarea></td>
			</tr>
			<td><button type="submit" name="submit" id="register" value="0">mentor</button></td>
			</tr>
		</table>
		<?php $OtherMats = NULL;
		if ($OtherMats != NULL):?>
		<?php endif;?>
	</form>
</body>
</html>