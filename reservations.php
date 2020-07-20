<?php
    require("/srv/http/plain/proc/reservations.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awesome Cinema</title>
    <link rel="stylesheet" href="style.css">
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
        <?php
        // Printing the status message
        if (isset($_SESSION['message'])){
            echo "<div class=\"alert alert--". $_SESSION['msg-type'] ."\">
                    <p>". $_SESSION['message'] ."</p>
                </div>";
        }
        unset($_SESSION['message']);
        ?>
        <div class="wrapper">
            <h1>Reservations</h1>
            <div style="float:left;width:43%;">

                <!-- Manage reservations form -->
                <h3>Manage reservations</h3>
                <form action="proc/reservations.php" method="POST">
                    <input type="hidden" name="type" value="<?php echo $type; ?>" required>
                    <?php 
                    if ($update == true) {
                        echo '<label for="customer_id">Customer ID - <b>Non editable</b></label>
                        <input type="number" name="customer_id" min="1" value="'.$customer_id.'" readonly required>
                        <label for="session_id">Session ID - <b>Non editable</b></label>
                        <input type="number" name="session_id" min="1" value="'.$session_id.'" readonly required>';
                    } else {
                        echo '<label for="customer_id">Customer ID</label>
                        <input type="number" name="customer_id" min="1" max="'; 

                        // Setting as max value the current number of customers
                        $query = "SELECT MAX(customer_id) AS c_cnt FROM customer";
                        $c_cnt = $conn->query($query);
                        $row = $c_cnt->fetch_array();
                        $c_cnt = $row['c_cnt'];
                        echo $c_cnt;
                        echo '"value="'.$customer_id.'" required>
                        <label for="session_id">Session ID</label>
                        <input type="number" name="session_id" min="1" max="'; 

                        // Setting as max value the current number of sessions
                        $query = "SELECT MAX(session_id) AS s_cnt FROM session";
                        $s_cnt = $conn->query($query);
                        $row = $s_cnt->fetch_array();
                        $s_cnt = $row['s_cnt'];
                        echo $s_cnt;
                        echo '" value="'.$session_id.'" required>';
                    }
                    ?>
                    <label for="tickets">Tickets</label>
                    <input type="number" name="tickets" min="1" max="5" value="<?php echo $tickets; ?>" required>
                    <?php 
                    if ($update == true) {
                        echo "<label>Discount: <b>".$discount."</b></label>";
                        echo '<select id="discount" name="discount">
                                <option value="'.$discount.'">Unchanged</option>
                                <option value="normal">Normal</option>
                                <option value="student">Student</option>
                                <option value="kid">Kid</option>
                                <option value="unemployed">Unemployed</option>
                            </select>';
                        echo '<button type="submit" name="update" class="btn">Update reservation</button>';
                    } else {
                        echo "<label>Discount</label>";
                        echo '<select id="discount" name="discount">
                                <option value="normal">Normal</option>
                                <option value="student">Student</option>
                                <option value="kid">Kid</option>
                                <option value="unemployed">Unemployed</option>
                            </select>';
                        echo '<button type="submit" name="save" class="btn">Save reservation</button>';
                    }
                    ?>
                </form>
            </div>
            <div style="float:right;width:52%;">

                <!-- Display reservations -->
                <h3>Display reservations</h3>
                <table>
                    <tr>
                        <th>Customer ID</th>
                        <th>Session ID</th>
                        <th>Tickets</th>
                        <th>Action</th>
                    </tr>
                <?php 
                    foreach($r_arr as $item) {
                        echo "<tr>";
                        echo "<td>".$item["customer_id"]."</td>
                            <td>".$item["session_id"]."</td>
                            <td>".$item["tickets"]."</td>
                            <td style=\"display: flex;flexdirection:row;\">
                                <a class=\"btn btn__table btn--edit\" 
                                href=\"reservations.php?edit_c=".$item["customer_id"]."&edit_s=".$item["session_id"]."\">Edit</a>
                                <a class=\"btn btn__table btn--delete\" 
                                href=\"reservations.php?delete_c=".$item["customer_id"]."&delete_s=".$item["session_id"]."\">Delete</a>
                                <a class=\"btn btn__table\" 
                                href=\"tickets.php?print_c=".$item["customer_id"]."&print_s=".$item["session_id"]."\">Print</a>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                ?>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
