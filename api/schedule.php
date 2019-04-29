<?php
include 'functions.php';

function get_class_sections($userid) {
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $query = "SELECT sectionID, courseID FROM mentorFor WHERE mentorID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  $section_arr = [];
  
  $i = 0;
  if ($result->num_rows > 0) {
	  # At least one section
	  while (($row = $result->fetch_array()) != NULL) {
		  $section = (object)[];
		  
		  $section_info = get_section_info($myconnection, $row["sectionID"], $row["courseID"]);
		  $course_info = get_course_info($myconnection, $row["courseID"]);
		  
		  $section->courseName = $course_info["title"];
		  $section->courseDesc = $course_info["description"];
		  $section->sectionID = $section_info["sectionID"];
		  $section->sectionName = $section_info["name"];
		  $section->timeSlot = $section_info["timeSlotID"];
		  $section->startDate = $section_info["startDate"];
		  $section->endDate = $section_info["endDate"];
		  $section->userType = "mentor";
		  
		  $section_arr[$i] = $section;
		  $i += 1;
	  }
  }
  
  $query = "SELECT sectionID, courseID FROM menteeFor WHERE menteeID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  if ($result->num_rows > 0) {
	  # At least one section
	  while (($row = $result->fetch_array()) != NULL) {
		  $section = (object)[];
		  
		  $section_info = get_section_info($myconnection, $row["sectionID"], $row["courseID"]);
		  $course_info = get_course_info($myconnection, $row["courseID"]);
		  
		  $section->courseName = $course_info["title"];
		  $section->courseDesc = $course_info["description"];
		  $section->sectionID = $section_info["sectionID"];
		  $section->sectionName = $section_info["name"];
		  $section->timeSlot = $section_info["timeSlotID"];
		  $section->startDate = $section_info["startDate"];
		  $section->endDate = $section_info["endDate"];
		  $section->userType = "mentee";
		  
		  $section_arr[$i] = $section;
		  $i += 1;
	  }
  }
  
  $query = "SELECT sectionID, courseID FROM modFor WHERE modID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  if ($result->num_rows > 0) {
	  # At least one section
	  while (($row = $result->fetch_array()) != NULL) {
		  $section = (object)[];
		  
		  $section_info = get_section_info($myconnection, $row["sectionID"], $row["courseID"]);
		  $course_info = get_course_info($myconnection, $row["courseID"]);
		  
		  $section->courseName = $course_info["title"];
		  $section->courseDesc = $course_info["description"];
		  $section->sectionID = $section_info["sectionID"];
		  $section->sectionName = $section_info["name"];
		  $section->timeSlot = $section_info["timeSlotID"];
		  $section->startDate = $section_info["startDate"];
		  $section->endDate = $section_info["endDate"];
		  $section->userType = "moderator";
		  
		  $section_arr[$i] = $section;
		  $i += 1;
	  }
  }
  return json_encode($section_arr);
}

$value = json_decode(file_get_contents('php://input'));

// DEBUG
/*if ($value == null) {
	$value = [];
	$value[0] = 23;
}*/

if ($value != null) {
	$userid = $value[0];
	$result = get_class_sections($userid);
	
	header("Content-Type: application/json");
	echo $result;
}
?>
