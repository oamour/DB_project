<?php
/*
get_grade_leve()

Takes an integer from 1-4 and returns the corresponding
grade level, or a placeholder string if value is invalid.
*/
function get_grade_level($grade_level) {
	if ($grade_level == 1) return "Freshman";
	if ($grade_level == 2) return "Sophomore";
	if ($grade_level == 3) return "Junior";
	if ($grade_level == 4) return "Senior";
	return "None";
}

/*
get_user_info()

Takes an open MySQL database connection and a valid userid, returns
the corresponding row in the users table
*/
function get_user_info($myconnection, $userid) {
  $query = "SELECT * FROM users WHERE userid = " . $userid;
  $result = mysqli_query($myconnection, $query);
  if(mysqli_num_rows($result) == 0) die("FATAL ERROR: Missing userdata for account '$userid'");
  
  return mysqli_fetch_array($result);
}

/*
get_section_info()

Takes an open MySQL database connection and a valid sectionID, returns
the corresponding row in the sections table
*/
function get_section_info($myconnection, $sectionID, $courseID) {
	$query = "SELECT * FROM sections WHERE courseID = $courseID AND sectionID = $sectionID";
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) == 0) die("FATAL ERROR: Missing userdata for account '$userid'");
	
	return mysqli_fetch_array($result);
}

/*
get_course_info()

Takes an open MySQL database connection and a valid courseID, returns
the corresponding row in the courses table
*/
function get_course_info($myconnection, $courseID) {
	$query = "SELECT * FROM courses WHERE courseID = $courseID";
	$result = mysqli_query($myconnection, $query);
	if(mysqli_num_rows($result) == 0) die("FATAL ERROR: Missing userdata for account '$userid'");
	
	return mysqli_fetch_array($result);
}

/* 
check_session()

Checks whether a valid session is currently in progress
If session is valid, returns the userid of the current user
If session is not valid, returns False
 */
function check_session() {
  $session_key = md5('database');
  if(isset($_SESSION)) {
    if (empty($_SESSION[$session_key])) {
	  return false;
    } elseif (intval($_SESSION[$session_key]) <= 0) {
      unset($_SESSION[$session_key]);
	  return false;
    } else {
      return $_SESSION[$session_key];
    }
  } else {
	  return false;
  }
}

/*
check_is_parent()

Checks whether a given user is the parent of another user.
Takes an active connection to a database, the parent's user ID, and
the child's user ID.
Returns true if the user is the parent of the child, and false otherwise
*/
function check_is_parent($myconnection, $parentID, $childID) {
	$query = "SELECT * FROM parentchild WHERE parentID = $parentID and childID = $childID";
	$result = mysqli_query($myconnection, $query);
	if($result != false and $result->num_rows > 0) {
		return true;
	}
	return false;
}
?>