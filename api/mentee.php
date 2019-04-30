<?php
include 'functions.php';

function get_mentees($myconnection, $sectionID, $courseID) {
	$query = "SELECT menteeID FROM menteeFor WHERE courseID = $courseID AND sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error());
	
	$mentee_arr = [];
	
	if($result->num_rows > 0) {
		$i = 0;
		while(($row = $result->fetch_array()) != NULL) {
			$mentee_arr[$i] = (object)[];
			$user_info = get_user_info($myconnection, $row['menteeID']);
			$grade_level = get_grade_level($user_info['gradeLevel']);
			
			$mentee_arr[$i]->name = $user_info['name'];
			$mentee_arr[$i]->grade = $grade_level;
			$i += 1;
		}
	}
	
	return $mentee_arr;
}

function get_mentors($myconnection, $sectionID, $courseID) {
	$query = "SELECT mentorID FROM mentorFor WHERE courseID = $courseID AND sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error());
	
	$mentor_arr = [];
	
	if($result->num_rows > 0) {
		$i = 0;
		while(($row = $result->fetch_array()) != NULL) {
			$mentor_arr[$i] = (object)[];
			$user_info = get_user_info($myconnection, $row['mentorID']);
			$grade_level = get_grade_level($user_info['gradeLevel']);
			
			$mentor_arr[$i]->name = $user_info['name'];
			$mentor_arr[$i]->grade = $grade_level;
			
			$i += 1;
		}
	}
	
	return $mentor_arr;
}

function get_class_sections($userid) {
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $query = "SELECT sectionID, courseID FROM menteeFor WHERE menteeID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  $section_arr = [];
  
  if ($result->num_rows > 0) {
	  # At least one section
	  $i = 0;
	  while (($row = $result->fetch_array()) != NULL) {
		  $section = (object)[];
		  
		  $section_info = get_section_info($myconnection, $row["sectionID"], $row['courseID']);
		  $course_info = get_course_info($myconnection, $row["courseID"]);
		  
		  $section->name = $section_info["name"];
		  $section->startDate = $section_info["startDate"];
		  $section->endDate = $section_info["endDate"];
		  $section->mentees = get_mentees($myconnection, $row["sectionID"], $row["courseID"]); 
		  $section->mentors = get_mentors($myconnection, $row["sectionID"], $row["courseID"]);
		  
		  /*
		  # STUDY MATERIALS FOR SECTION
		  $query = "SELECT * FROM materialFor WHERE courseID = " . $row["courseID"] . " AND sectionID = " . $row["sectionID"];
		  $material_for = mysqli_query($myconnection, $query);
		  
		  if ($material_for != false and $material_for->num_rows > 0) {
			echo "<h2>Study Materials</h2>";
			echo "<table border=1>";
			echo "<tr>"; # Header
			echo "<td>Session Name</td>";
			echo "<td>Session Date</td>";
			echo "<td>Announcement</td>";
			echo "<td>Material Title</td>";
			echo "<td>Author</td>";
			echo "<td>Type</td>";
			echo "<td>URL</td>";
			echo "<td>Assigned Date</td>";
			echo "<td>Notes</td>";
			echo "</tr>";
			
			while (($materials_for_session = $material_for->fetch_array()) != NULL) {
				$query = "SELECT * FROM sessions WHERE sessionID = " . $materials_for_session['sessionID'] . " AND sectionID = " . $row["sectionID"] . " AND courseID = " . $row["courseID"];
				$session_info = mysqli_query($myconnection, $query);
				$session_info = $session_info->fetch_array();
				
				$query = "SELECT * FROM studyMaterials WHERE studyMaterialID = " . $materials_for_session['studyMaterialID'];
				$study_material_info = mysqli_query($myconnection, $query);
				$study_material_info = $study_material_info->fetch_array();
				
				echo "<tr>";
				echo "<td>" . $session_info['sessionID'] . "</td>";
				echo "<td>" . $session_info['sessionDate'] . "</td>";
				echo "<td>" . $session_info['announcement'] . "</td>";
				echo "<td>" . $study_material_info['title'] . "</td>";
				echo "<td>" . $study_material_info['author'] . "</td>";
				echo "<td>" . $study_material_info['materialType'] . "</td>";
				echo "<td>" . $study_material_info['url'] . "</td>";
				echo "<td>" . $materials_for_session['assignedDate'] . "</td>";
				echo "<td>" . $study_material_info['notes'] . "</td>";
				echo "</tr>";
			}
		  }
		  */
		  $section_arr[$i] = $section;
		  $i += 1;
	  }
  } 
  return json_encode($section_arr);
}

$value = json_decode(file_get_contents('php://input'));

// DEBUG
if ($value == null) {
	$value = [];
	$value[0] = 23;
}

if($value != null) {
	$userid = $value[0];
	$result = get_class_sections($userid);
	
	header("Content-Type: application/json");
	echo $result;
}
?>
