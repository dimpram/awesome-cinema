<?php 
$db_uri = "localhost";	
$db_user = "root";
$db_pass = "";            // Mariadb is set to not use password on user root
$db_name = "Cinema";	

// Attempting to connected to the database
$conn = new mysqli($db_uri, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: ".mysqli_error($conn));
}
