<?php
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
check_session()

Checks whether a valid session is currently in progress
If session is valid, returns the userid of the current user
If session is not valid, returns False
 */
function check_session() {
  $session_key = md5('database');
  if(isset($_SESSION)) {
    if (empty($_SESSION[$session_key])) {
      echo "Not logged in! Please <a href='index.php'>CLICK HERE</a> to return to the main page.";
	  return false;
    } elseif (intval($_SESSION[$session_key]) <= 0) {
      unset($_SESSION[$session_key]);
      echo "Invalid session key! Please <a href='index.php'>CLICK HERE</a> to return to the main page.";
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
	$query = "SELECT * FROM parentchild WHERE parentID = $parentID, childID = $childID";
	$result = mysqli_query($myconnection, $query);
	if($result != false and $result->num_rows > 0) {
		return true;
	}
	return false;
}
?>