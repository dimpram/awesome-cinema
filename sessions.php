<?php
    require("/srv/http/awesome_cinema/proc/sessions.php");
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
            <li><a class="navbar__item navbar__item--active" href="sessions.php">Sessions</a></li>
            <li><a class="navbar__item" href="reservations.php">Reservations</a></li>
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
            <h1>Sessions</h1>

            <!-- Manage sessions form -->
            <h3>Manage sessions</h3>
            <form action="proc/sessions.php" method="POST">
                <input type=hidden name="session_id" value="<?php echo $session_id; ?>">
                <label for="display_date">Display Date</label>
                <input type="date" name="display_date" value="<?php echo $display_date; ?>" required>
                <label>Type</label>
                <?php 
                if ($update == true) {
                    echo '<select id="type" name="type">
                            <option value="'.$type.'">Unchanged</option>
                            <option value="normal">Normal</option>
                            <option value="3d">3D</option>
                            <option value="imax">IMAX</option>
                        </select>';
                } else {
                    echo '<select id="type" name="type">
                            <option value="normal">Normal</option>
                            <option value="3d">3D</option>
                            <option value="imax">IMAX</option>
                        </select>';
                }
                ?>
                <div style="float:left;width:49%;">
                    <label for="start_time">Start Time</label>
                    <input type="time" name="start_time" value="<?php echo $start_time; ?>" required> 
                </div>
                <div style="float:right;width:49%;">
                    <label for="end_time">End Time</label>
                    <input type="time" name="end_time" value="<?php echo $end_time; ?>" required>
                </div>
                <div style="float:left;width:49%;">
                    <label for="movie_id">Movie ID</label>
                    <input type="number" name="movie_id" placeholder="Select movie" min="1" max="69" value="<?php echo $movie_id; ?>" required>
                </div>
                <div style="float:right;width:49%;">
                    <label for="room_id">Room ID</label>
                    <input type="number" name="room_id" placeholder="Select room" min="1" max="10" value="<?php echo $room_id; ?>" required>
                </div>
                <?php 
                if ($update == true) {
                    echo '<button type="submit" name="update" class="btn">Update session</button>';
                } else {
                    echo '<button type="submit" name="save" class="btn">Save session</button>';
                }
                ?>
            </form>

            <!-- Display active sessions -->
            <h3>Active Sessions</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Movie ID</th>
                    <th>Room ID</th>
                    <th>Action</th>
                </tr>
            <?php 
                foreach($s_arr as $item) {
                    echo "<tr>";
                    echo "<td>".$item["session_id"]."</td>
                        <td>".$item["display_date"]."</td>
                        <td>".$item["type"]."</td>
                        <td>".$item["start_time"]."</td>
                        <td>".$item["end_time"]."</td>
                        <td>".$item["movie_id"]."</td>
                        <td>".$item["room_id"]."</td>
                        <td style=\"display:flex;flexdirection:row;\">
                            <a class=\"btn btn__table btn--edit\" 
                            href=\"sessions.php?edit=".$item["session_id"]."\">Edit</a>
                            <a class=\"btn btn__table btn--delete\" 
                            href=\"sessions.php?delete=".$item["session_id"]."\">Delete</a>
                        </td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>
        </div>
    </main>
</body>
</html>
