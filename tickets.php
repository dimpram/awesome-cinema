<?php
    require("/srv/http/plain/config/database.php"); // Connection to the database
    
    // Function that returns percent of something
    function getPercentOfNumber($number, $percent){
        return ($percent / 100) * $number;
    }

    // Discounts
    $student_d = 25;
    $kid_d = 10;
    $unemployed_d = 30;

    if (!isset($_GET["print_c"]) && !isset($_GET["print_s"])) {
        header("location: /plain/reservations.php");                // Redirect to the reservations.php page
    } else if(isset($_GET["print_c"]) && isset($_GET["print_s"])) {

        // Getting values from GET
        $customer_id = $_GET['print_c'];
        $session_id = $_GET['print_s'];
       
        // Find extra 
        $query = "SELECT r.session_id, r.customer_id, r.tickets, ticket.discount, ticket.value, fname, lname, session.movie_id, movie.title, session.display_date, session.start_time, room.name FROM reserves AS r JOIN customer AS c ON r.customer_id=c.customer_id AND r.customer_id=$customer_id AND r.session_id=$session_id JOIN session ON r.session_id=session.session_id AND r.session_id=$session_id JOIN movie ON session.movie_id=movie.movie_id JOIN room ON session.room_id=room.room_id JOIN ticket ON r.customer_id=ticket.customer_id AND r.session_id=ticket.session_id";
        $data = $conn->query($query);
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Awesome Cinema</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <header>
            <ul class="navbar">
                <li class="navbar__item"><h3>Awesome Cinema</h3></li>
                <li><a class="navbar__item" href="index.php">Dashboard</a></li>
                <li><a class="navbar__item" href="customers.php">Customers</a></li>
                <li><a class="navbar__item" href="sessions.php">Sessions</a></li>
                <li><a class="navbar__item navbar__item--active" href="reservations.php">Reservations</a></li>
                <li><a class="navbar__item" href="about.html">About</a></li>
            </ul>
        </header>
        <main>
            <div class="wrapper">
                <h1>Tickets</h1>
                <h3>Print reservation tickets</h3>
                <button style="margin: 1.5rem 0 3rem;" type="submit" name="save" class="btn">Print all</button>
                <?php 
                foreach($data as $item) {
                    echo '<div class="cardWrap">
                        <div class="card cardLeft">
                            <h3>Awesome <span>Cinema</span></h3>
                            <div class="title">
                                <h2>'.$item['title'].'</h2>
                                <span>movie</span>
                            </div>
                            <div class="name">
                                <h2>'.$item['fname'].' '.$item['lname'].'</h2>
                                <span>name</span>
                            </div>
                            <div class="seat">
                                <h2>'.$item['display_date'].'</h2>
                                <span>date</span>
                            </div>
                            <div class="seat">
                                <h2>'.$item['name'].'</h2>
                                <span>room</span>
                            </div>
                            <div class="time">
                                <h2>'.$item['start_time'].'</h2>
                                <span>time</span>
                            </div>
                            <div class="seat">
                                <h2>'.$item['discount'].'</h2>
                                <span>discount</span>
                            </div>
                            <div class="seat">
                                <h2>';
                                switch($item['discount']) {
                                    case "unemployed":
                                        echo $item['value'] = $item['value'] - getPercentOfNumber($item['value'], $unemployed_d);
                                    break;
                                    case "student":
                                        echo $item['value'] = $item['value'] - getPercentOfNumber($item['value'], $student_d);
                                    break;
                                    case "kid":
                                        echo $item['value'] = $item['value'] - getPercentOfNumber($item['value'], $kid_d);
                                    break;
                                    default:
                                        echo $item['value'];
                                }
                                echo '€</h2>
                                <span>price</span>
                            </div>
                        </div>
                        <div class="card cardRight">
                            <div class="eye"></div>
                            <div class="number">
                                <h3>any</h3>
                                <span>seat</span>
                            </div>
                            <div class="barcode"></div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </main>
    </body>
</html>
