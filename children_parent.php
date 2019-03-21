<?php
include 'functions.php';

function generate_child_rows($userid) {
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());
	
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
  
  $query = "SELECT childID FROM parentchild WHERE parentID = $userid";
  $result = mysqli_query($myconnection, $query) or die ("Failed to query database: " . mysql_error());
  
  if ($result->num_rows > 0) {
	  # At least one child
	  while (($row = $result->fetch_row()) != NULL) {
		  $childID = $row[0];
		  $child_data = get_user_info($myconnection, $childID);
		  
		  echo "<tr>";
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
		  echo "</tr>";
	  }
  }
}

session_start();

$userid = check_session();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>School Database</title>
	</head>
	<body>
	<?php if(isset($userid) and $userid != false) : ?>
		<a href="dashboard.php">Back to Dashboard</a>
		<h1>List of Children</h1>
		<table border=1>
			<tr>
				<td>User ID</td>
				<td>Email</td>
				<td>Name</td>
				<td>Phone</td>
				<td>City</td>
				<td>State</td>
				<td>Change Profile</td>
			</tr>
			<?php
			generate_child_rows($userid);
			?>
		</table>
	<?php endif; ?>
	</body>
</html>