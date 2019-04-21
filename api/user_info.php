<?php
function get_user_info($userID) {
	$myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
	$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
	
	mysqli_query($myconnection, "START TRANSACTION");
	
	$query = "SELECT * FROM users WHERE userID = " . $userID;
	$result = mysqli_query($myconnection, $query) or die("Failed to retrieve account info!");
	
	$user_info = (object)[];
	
	if(mysqli_num_rows($result) > 0 ) {
		$row = mysqli_fetch_array($result);
		$user_info->name = $row["name"];
		$user_info->email = $row["email"];
		$user_info->phone = $row["phone"];
		$user_info->city = $row["city"];
		$user_info->state = $row["state"];
	}
	
	return json_encode($user_info);
}

$value = json_decode(file_get_contents('php://input'));

//DEBUG
if ($value == null) {
	$value = (object)[];
	$value->userID = "13";
}

if ($value != null) {
	$userID = $value->userID;
	
	$user_info = get_user_info($userID);
	
	header('Content-Type: application/json');
	echo $user_info;
}
?>