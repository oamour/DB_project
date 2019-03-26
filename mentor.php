<?php
include 'functions.php';

function get_mentees($myconnection, $sectionID) {
	echo "<tr><td>MENTEES</td></tr>";
	$query = "SELECT menteeID FROM menteeFor WHERE sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysqli_error());
	
	if($result->num_rows > 0) {
		$user_info = get_user_info($myconnection, $result['menteeID']);
		echo "<tr>"; # Start printing info
		echo "<td>" . $user_info['name'] . "</td>";
		echo "<td>" . "</td>";
		echo "<td>" . "</td>";
		echo "</tr>";
	}
}

function get_mentors($myconnection, $sectionID) {
	echo "<tr><td>MENTORS</td></tr>";
}

function get_class_sections($userid) {
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $query = "SELECT sectionID, courseID FROM mentorFor WHERE mentorID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  if ($result->num_rows > 0) {
	  # At least one section
	  while (($row = $result->fetch_row()) != NULL) {
		  $section_info = get_section_info($sectionID);
		  $course_info = get_course_info($courseID);
		  
		  echo "<div><span align='center'>" . $section_info["name"] . "</span><table>";
		  echo "<tr>"; # Header
		  echo "<td>Student Name</td>";
		  echo "<td>Student Grade</td>";
		  echo "<td>Student Role</td>";
		  echo "</tr>";
		  echo "<tr><td colspan=3 align='center'>Mentees</td></tr>";
		  get_mentees($myconnection, $sectionID);
		  echo "<tr><td colspan=3 align='center'>Mentors</td></tr>";
		  get_mentors($myconnection, $sectionID);
		  echo "</table></div>";
		  
		  /*echo "<tr>";
		  echo "<td>" . $child_data["userID"] . "</td>";
		  echo "<td>" . $child_data["email"] . "</td>";
		  echo "<td>" . $child_data["name"] . "</td>";
		  echo "<td>" . $child_data["phone"] . "</td>";
		  echo "<td>" . $child_data["city"] . "</td>";
		  echo "<td>" . $child_data["state"] . "</td>";
		  echo "<td><form method='get' action='child_profile_parent.php'>";
		  echo "<input type='hidden' name='childID' value=$childID>";
		  echo "<input type='submit' value='Change Profile'>";
		  echo "</form></td>";
		  echo "</tr>";*/
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
		<h1>Section List</h1>
		<?php get_class_sections($userid); ?>
	<?php else : ?>
		<span>"Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page."</span>
	<?php endif; ?>
	</body>
</html>