<?php
require("/srv/http/awesome_cinema/config/database.php"); // Connection to the database
session_start();                                // Start session;

// Default values
$update = false;

$customer_id = 0;
$fname = "";
$lname = "";
$phone_num = "";
$gender = "";
$email = "";

// // CREATE customer
if (isset($_POST["save"])) {
    
    // Getting values from post
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone_num = $_POST['phone_num'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    echo $gender;

    // Create query
    $query = "INSERT INTO customer(fname, lname, phone_num, gender, email) VALUES(\"$fname\", \"$lname\", \"$phone_num\", \"$gender\", \"$email\")";

    // Execute query 
    if ($conn->query($query)) {
        $_SESSION['message'] = "New Customer has been added!";  // Success message
        $_SESSION['msg-type'] = "success";                      // Used for selecting css classes
        header("location: /awesome_cinema/customers.php");               // Redirect to the sessions.php page
    } else {
        die($conn->error);    // Print error
    }
}

// DELETE customer
if (isset($_GET["delete"])) {

    $customer_id = $_GET['delete'];  // Getting data from GET

    $query = "DELETE FROM customer WHERE customer_id=$customer_id";  // Create query

    // Execute query
    if ($conn->query($query)) {
        $_SESSION['message'] = "Customer has been removed!";        // Success message
        $_SESSION['msg-type'] = "danger";                           // Used for selecting css classes
    } else {
        die($conn->error);    // Print error
    }
}

// Preparing the editing mode
if (isset($_GET['edit'])) {

    $customer_id = $_GET['edit'];                                    // Getting data from GET
    $update = true;
    $query = "SELECT * FROM customer WHERE customer_id=$customer_id";  // Create query
    $result = $conn->query($query);                                 // Execute query
    
    // Checking
    if ($result) {
        if (count($result) == 1) {
            $row = $result->fetch_array();      // Fetch data as an array

            // Inserting fetched data to the variables
            $customer_id = $row['customer_id'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $phone_num = $row['phone_num'];
            $gender = $row['gender'];
            $email = $row['email'];
        }
    } else {
        die($conn->error);    // Print error
    }
}

// Updating customer
if (isset($_POST['update'])) {

    // Getting values from post
    $customer_id = $_POST['customer_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone_num = $_POST['phone_num'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    $query = "UPDATE customer SET fname=\"$fname\", lname=\"$lname\", phone_num=\"$phone_num\", gender=\"$gender\", email=\"$email\" WHERE customer_id=$customer_id"; // Create query

    // Execute query
    if ($conn->query($query)) {
        $_SESSION['message'] = "Customer has been updated!";    // Success message
        $_SESSION['msg-type'] = "warning";                      // Used for selecting css classes
        header("location: /awesome_cinema/customers.php");               // Redirect to the sessions.php page
    } else {
        die($conn->error);    // Print error
    }
}

// Display all customers
$c_arr = $conn->query("SELECT * FROM customer");