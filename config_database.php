<?php
// Database configuration for CNN Clone
$db_host = 'localhost';
$db_user = 'rsoa_rsoa276_2';
$db_pass = '123456';
$db_name = 'rsoa_rsoa276_2';
 
// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
