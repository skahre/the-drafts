<?php
// Start a user session.
// Since this file is included in every page, we can be sure that the session is always started
session_start();

// Define a constant for the base URL of the project, making sure routing works regardless of current directory.
define("BASE", "/PROJEKT");
?>
