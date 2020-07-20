<?php
require("/srv/http/awesome_cinema/config/database.php"); // Connection to the database
session_start();                                // Start session;

// Variables & default values
$update = false;

$type = 0;          // For getting the session type and therefore calculation the ticket value
$customer_id = 0;
$session_id = 0;
$tickets = 0;

// CREATE reservation
if (isset($_POST["save"])) {
    
    // Getting values from post
    $customer_id = $_POST['customer_id'];
    $session_id = $_POST['session_id'];
    $tickets = $_POST['tickets'];
    $discount = $_POST['discount'];

    // Get the session type
    $query = "SELECT type FROM session WHERE session_id=$session_id";   // Create query
    $type = $conn->query($query);
    $row = $type->fetch_array();      // Fetch data as an array
    $type = $row['type'];

    echo 'Got type '. $type ;
    echo 'Got discount'. $discount;

    // Check for room availability
    $query = "SELECT session.session_id, room.seat_rows, room.seats_per_row FROM session JOIN room ON session.room_id=room.room_id AND session_id=$session_id";   // Create query
    $room = $conn->query($query) or die($conn->error);  // Print error
    $row = $room->fetch_array();                        // Fetch data as an array
    $room_rows = $row['seat_rows'];
    $room_seats = $row['seats_per_row'];
    $room_capacity = $room_rows * $room_seats;          // Calculating room capacity

    $query = "SELECT COUNT(*) seats FROM ticket WHERE session_id=$session_id";  // Create query
    $seats_reserved = $conn->query($query) or die($conn->error);                // Print error
    $row = $seats_reserved->fetch_array();                                      // Fetch data as an array
    $seats_reserved = $row['seats'];

    $seats_remaining = $room_capacity - $seats_reserved;    // Free seats

    if ($seats_remaining > $tickets) {
        
        // Insert into reserves
        $query = "INSERT INTO reserves(customer_id, session_id, tickets) VALUES($customer_id, $session_id, $tickets)";

        // Execute query
        $conn->query($query) or die($conn->error);    // Print error

        // if ticket == 1  check session type and add the appropriate value to tickets
        if ($tickets >= 1) {
            echo 'From here its == 1';

            // Only the first ticket is with the special discount
            switch($type) {
                case "normal":
                    $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(8.00, \"$discount\", $session_id, $customer_id)";
                    $conn->query($query) or die($conn->error);    // Print error
                    echo ' Added 1 normal';
                break;
                case "3d":
                    $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(10.00, \"$discount\", $session_id, $customer_id)";
                    $conn->query($query) or die($conn->error);    // Print error
                    echo ' Added 1 3d';
                break;
                case "imax":
                    $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(12.00, \"$discount\", $session_id, $customer_id)";
                    $conn->query($query) or die($conn->error);    // Print error
                    echo ' Added 1 imax';
                break;
            }
            if ($tickets > 1) {
                echo ' From here its > 1';

                // ...and then insert the remaining with the normal discount
                for ($i=0; $i < ($tickets-1) ; $i++) { 
                    switch($type) {
                        case "normal":
                            $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(8.00, \"normal\", $session_id, $customer_id)";
                            $conn->query($query) or die($conn->error);    // Print error
                            echo ' Added normal2';
                        break;
                        case "3d":
                            $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(10.00, \"normal\", $session_id, $customer_id)";
                            $conn->query($query) or die($conn->error);    // Print error
                            echo ' Added 3d2';
                        break;
                        case "imax":
                            $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(12.00, \"normal\", $session_id, $customer_id)";
                            $conn->query($query) or die($conn->error);    // Print error
                            echo ' Added imax3';
                        break;
                    }
                }
            }
            $_SESSION['message'] = "New Reservation has been added!";   // Success message
            $_SESSION['msg-type'] = "success";                          // Used for selecting css classes
            header("location: /awesome_cinema/reservations.php");                // Redirect to the sessions.php page
        }
    }

}

// DELETE reservation
if (isset($_GET["delete_c"]) && isset($_GET["delete_s"])) {

    $customer_id = $_GET['delete_c'];                                  // Getting data from GET
    $session_id = $_GET['delete_s'];                                  // Getting data from GET
    
    $query = "DELETE FROM reserves WHERE customer_id=$customer_id AND session_id=$session_id";    // Create query

    // Execute query
    if ($conn->query($query)) {
        
        // Delete also from tickets
        $query = "DELETE FROM ticket WHERE customer_id=$customer_id AND session_id=$session_id";    // Create query
        $conn->query($query) or die($conn->error);  // Print error

        // Set defaults back
        $customer_id = 0;
        $session_id = 0;
        $_SESSION['message'] = "Reservation has been deleted!"; // Success message
        $_SESSION['msg-type'] = "danger";                   // Used for selecting css classes
    } else {
        die($conn->error);    // Print error
    }
}

// EDIT reservation
if (isset($_GET["edit_c"]) && isset($_GET["edit_s"])) {

    // Getting data from get
    $customer_id = $_GET['edit_c'];
    $session_id = $_GET['edit_s'];
    
    $update = true;

    // Get the discount type
    $query = "SELECT ticket_id, discount FROM ticket WHERE session_id=$session_id AND customer_id=$customer_id"; 
    $discount = $conn->query($query);
    $row = $discount->fetch_array();      // Fetch data as an array
    $discount = $row['discount'];


    $query = "SELECT * FROM reserves WHERE customer_id=$customer_id AND session_id=$session_id";  // Create query
    $result = $conn->query($query);                                 // Execute query
    
    // Checking
    if ($result) {
        if (count($result) == 1) {
            $row = $result->fetch_array();      // Fetch data as an array

            // Inserting fetched data to the variables
            $customer_id = $row['customer_id'];
            $session_id = $row['session_id'];
            $tickets = $row['tickets'];
        }
    } else {
        die($conn->error);    // Print error
    }
}
if (isset($_POST['update'])) {

    // Getting values from post
    $customer_id = $_POST['customer_id'];
    $session_id = $_POST['session_id'];
    $tickets = $_POST['tickets'];
    $discount = $_POST['discount'];
   
    // Create query
    $query = "UPDATE reserves SET tickets=$tickets WHERE customer_id=$customer_id AND session_id=$session_id";
    $conn->query($query) or die($conn->error);    // Print error

    // Delete also from tickets
    $query = "DELETE FROM ticket WHERE customer_id=$customer_id AND session_id=$session_id";    // Create query
    $conn->query($query) or die($conn->error);  // Print error

    // if ticket == 1  check session type and add the appropriate value to tickets
    if ($tickets >= 1) {
        echo 'From here its == 1';

        // Only the first ticket is with the special discount
        switch($type) {
            case "normal":
                $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(8.00, \"$discount\", $session_id, $customer_id)";
                $conn->query($query) or die($conn->error);    // Print error
                echo ' Added 1 normal';
            break;
            case "3d":
                $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(10.00, \"$discount\", $session_id, $customer_id)";
                $conn->query($query) or die($conn->error);    // Print error
                echo ' Added 1 3d';
            break;
            case "imax":
                $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(12.00, \"$discount\", $session_id, $customer_id)";
                $conn->query($query) or die($conn->error);    // Print error
                echo ' Added 1 imax';
            break;
        }
        if ($tickets > 1) {
            echo ' From here its > 1';

            // ...and then insert the remaining with the normal discount
            for ($i=0; $i < ($tickets-1) ; $i++) { 
                switch($type) {
                    case "normal":
                        $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(8.00, \"normal\", $session_id, $customer_id)";
                        $conn->query($query) or die($conn->error);    // Print error
                        echo ' Added normal2';
                    break;
                    case "3d":
                        $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(10.00, \"normal\", $session_id, $customer_id)";
                        $conn->query($query) or die($conn->error);    // Print error
                        echo ' Added 3d2';
                    break;
                    case "imax":
                        $query = "INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(12.00, \"normal\", $session_id, $customer_id)";
                        $conn->query($query) or die($conn->error);    // Print error
                        echo ' Added imax3';
                    break;
                }
            }
        }
    }
    $_SESSION['message'] = "New Reservation has been updated!";   // Success message
    $_SESSION['msg-type'] = "warning";                          // Used for selecting css classes
    header("location: /awesome_cinema/reservations.php");                // Redirect to the sessions.php page
}

// Display all the reservations
$r_arr = $conn->query("SELECT * FROM reserves");