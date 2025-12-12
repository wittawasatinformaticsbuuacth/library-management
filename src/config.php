<?php
// Database configuration
$db_host = "db";
$db_user = "library_user";
$db_pass = "library_pass";
$db_name = "library_db";

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session configuration
session_start();

// Helper function for debugging
define('DEBUG_MODE', false);

function debug_log($message) {
    if (DEBUG_MODE) {
        error_log($message);
        echo "<!-- DEBUG: $message -->";
    }
}
?>
