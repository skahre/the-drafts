<?php
// Logs out the user and redirects them to the login page
session_start();
$_SESSION = [];
session_destroy();

header("Location: ../login.php");
exit();
?>
