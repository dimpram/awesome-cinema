<?php
require("/srv/http/awesome_cinema/config/database.php"); // Connection to the database
session_start();                                // Start session;

// Default values
$update = false;

$session_id = 0;
$display_date = "";
$type = "";
$start_time = "";
$end_time = "";
$movie_id = 0;
$room_id = 0;

// CREATE session
if (isset($_POST["save"])) {
    
    // Getting values from post
    $display_date = $_POST['display_date'];
    $type = $_POST['type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $movie_id = $_POST['movie_id'];
    $room_id = $_POST['room_id'];

    // Create query
    $query = "INSERT INTO session(display_date, type, start_time, end_time, movie_id, room_id) VALUES('$display_date', '$type', '$start_time', '$end_time', '$movie_id', '$room_id')";

    // Execute query 
    if ($conn->query($query)) {
        $_SESSION['message'] = "New Session has been added!";   // Success message
        $_SESSION['msg-type'] = "success";                      // Used for selecting css classes
        header("location: /awesome_cinema/sessions.php");                // Redirect to the sessions.php page
    } else {
        die($conn->error);    // Print error
    }
}

// DELETE session
if (isset($_GET["delete"])) {

    $session_id = $_GET['delete'];                                  // Getting data from GET

    $query = "DELETE FROM session WHERE session_id=$session_id";    // Create query

    // Execute query
    if ($conn->query($query)) {
        $_SESSION['message'] = "Session has been deleted!";             // Success message
        $_SESSION['msg-type'] = "danger";                               // Used for selecting css classes
    } else {
        die($conn->error);    // Print error
    }
}

// Preparing the editing mode
if (isset($_GET['edit'])) {

    $session_id = $_GET['edit'];                                    // Getting data from GET
    $update = true;
    $query = "SELECT * FROM session WHERE session_id=$session_id";  // Create query
    $result = $conn->query($query);                                 // Execute query
    
    // Checking
    if ($result) {
        if (count($result) == 1) {
            $row = $result->fetch_array();      // Fetch data as an array

            // Inserting fetched data to the variables
            $display_date = $row['display_date'];
            $type = $row['type'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $movie_id = $row['movie_id'];
            $room_id = $row['room_id'];
        }
    } else {
        die($conn->error);    // Print error
    }
}

// Updating session
if (isset($_POST['update'])) {

    // Getting values from post
    $session_id = $_POST['session_id'];
    $display_date = $_POST['display_date'];
    $type = $_POST['type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $movie_id = $_POST['movie_id'];
    $room_id = $_POST['room_id'];

    $query = "UPDATE session SET display_date='$display_date', type='$type', start_time='$start_time', end_time='$end_time', movie_id='$movie_id', room_id='$room_id' WHERE session_id=$session_id"; // Create query

    // Execute query
    if ($conn->query($query)) {

        // Update tickets
        switch($type) {
            case "normal":
                $query = "UPDATE ticket SET value=8 WHERE session_id=$session_id";
                $conn->query($query) or die($conn->error);    // Print error
                echo ' Update n';
            break;
            case "3d":
                $query = "UPDATE ticket SET value=10 WHERE session_id=$session_id";
                $conn->query($query) or die($conn->error);    // Print error
                echo ' Update 3d';
            break;
            case "imax":
                $query = "UPDATE ticket SET value=12 WHERE session_id=$session_id";
                $conn->query($query) or die($conn->error);    // Print error
                echo ' Update i';
            break;
        }

        $_SESSION['message'] = "Session has been updated!"; // Success message
        $_SESSION['msg-type'] = "warning";                   // Used for selecting css classes
        header("location: /awesome_cinema/sessions.php");                // Redirect to the sessions.php page
    } else {
        die($conn->error);    // Print error
    }
}

// Display all the sessions
$s_arr = $conn->query("SELECT * FROM session");