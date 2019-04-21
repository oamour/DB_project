<?php
include 'functions.php';

function generate_child_rows($userid) {
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $query = "SELECT childID FROM parentchild WHERE parentID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  $child_array = [];
  
  if ($result->num_rows > 0) {
	  # At least one child
	  $i = 0;
	  while (($row = $result->fetch_row()) != NULL) {
		  $childID = $row[0];
		  $child_data = get_user_info($myconnection, $childID);
		  $child_row = (object)[];
		  $child_row->userID = $child_data["userID"];
		  $child_row->email = $child_data["email"];
		  $child_row->name = $child_data["name"];
		  $child_row->phone = $child_data["phone"];
		  $child_row->city = $child_data["city"];
		  $child_row->state = $child_data["state"];
		  
		  $child_array[$i] = $child_row;
		  $i += 1;
	  }
  }
  return json_encode($child_array);
}

$value = json_decode(file_get_contents('php://input'));

//DEBUG
if($value == null) {
	$value = [];
	$value[0] = 13;
}

if ($value != null) {
	$child_array = generate_child_rows($value[0]);
	
	header("Content-Type: application/json");
	echo $child_array;
}
?>