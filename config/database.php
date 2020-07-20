<?php 
$db_uri = "localhost";	
$db_user = "root";          // Enter your own mysql username
$db_pass = "";              // Enter your own mysql password
$db_name = "Cinema";	

// Attempting to connected to the database
$conn = new mysqli($db_uri, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: ".mysqli_error($conn));
}
