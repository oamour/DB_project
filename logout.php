<?php
$session_key = md5("database");
session_start();

unset($_SESSION[$session_key]);

echo "You are now logged out.";
echo "Redirecting...";
header("Location: index.php?logout=1");
?>