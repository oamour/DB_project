<?php
include 'functions.php';

function get_mentees($myconnection, $sectionID, $courseID) {
	$query = "SELECT menteeID FROM menteeFor WHERE courseID = $courseID AND sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error());
	
	if($result->num_rows > 0) {
		echo "<tr><th colspan=3>MENTEES</th></tr>";
		while(($row = $result->fetch_array()) != NULL) {
			$user_info = get_user_info($myconnection, $row['menteeID']);
			$grade_level = get_grade_level($user_info['gradeLevel']);
			echo "<tr>"; # Start printing info
			echo "<td>" . $user_info['name'] . "</td>";
			echo "<td>" . $grade_level . "</td>";
			echo "<td>Mentee</td>";
			echo "</tr>";
		}
	}
}

function get_mentors($myconnection, $sectionID, $courseID) {
	$query = "SELECT mentorID FROM mentorFor WHERE courseID = $courseID AND sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error());
	
	if($result->num_rows > 0) {
		echo "<tr><th colspan=3>MENTORS</th></tr>";
		while(($row = $result->fetch_array()) != NULL) {
			$user_info = get_user_info($myconnection, $row['mentorID']);
			$grade_level = get_grade_level($user_info['gradeLevel']);
			echo "<tr>"; # Start printing info
			echo "<td>" . $user_info['name'] . "</td>";
			echo "<td>" . $grade_level . "</td>";
			echo "<td>Mentor</td>";
			echo "</tr>";
		}
	}
}

function get_class_sections($userid) {
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $query = "SELECT sectionID, courseID FROM mentorFor WHERE mentorID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  if ($result->num_rows > 0) {
	  # At least one section
	  while (($row = $result->fetch_array()) != NULL) {
		  $section_info = get_section_info($myconnection, $row["sectionID"], $row["courseID"]);
		  $course_info = get_course_info($myconnection, $row["courseID"]);
		  
		  echo "<table border=1>";
		  echo "<th><td colspan=3>" . $section_info["name"] . "</th></tr>";
		  echo "<tr>"; # Header
		  echo "<td>Student Name</td>";
		  echo "<td>Student Grade</td>";
		  echo "<td>Student Role</td>";
		  echo "</tr>";
		  get_mentees($myconnection, $row["sectionID"], $row["courseID"]);
		  get_mentors($myconnection, $row["sectionID"], $row["courseID"]);
		  echo "</table></div>";
		  
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
	  }
  } else { # no sections as mentor
	echo "Sorry, you are not a mentor for any sections.";
  }
}

session_start();

$userid = check_session();
?>

<!--HTML-->

<!DOCTYPE html>
<html>
	<head>
		<title>School Database</title>
	</head>
	<body>
	<?php if(isset($userid) and $userid != false) : ?>
		<a href="dashboard.php">Back to Dashboard</a>
		<h1>Mentor Section List</h1>
		<?php get_class_sections($userid); ?>
	<?php else : ?>
		<span>"Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page."</span>
	<?php endif; ?>
	</body>
</html>