<!DOCTYPE html>
<html>
	<head>
		<title>School Database</title>
	</head>
	<body>
		<a href="index.php">Back to Start</a>
		<h1>Parent Login</h1>
		<form method="post" action="login.php">
			<table>
			<tr>
				<td><label for="username">Username:</label></td>
				<td><input type="email" id="username" name="username" required></td>
			</tr>
			<tr>
				<td><label for="password">Password:</label></td>
				<td><input type="password" id="password" name="password" required></td>
			</tr>
			<tr>
				<td><input type="submit" id="login" name="login" value="Login">
			</tr>
			</table>
		</form>
		<div> Or <a href="register_parent.php">CLICK HERE</a> to register as a parent</div>
	</body>
</html>