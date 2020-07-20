<?php
    require("/srv/http/plain/proc/dashboard.php");
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
            <li><a class="navbar__item navbar__item--active" href="index.php">Dashboard</a></li>
            <li><a class="navbar__item" href="customers.php">Customers</a></li>
            <li><a class="navbar__item" href="sessions.php">Sessions</a></li>
            <li><a class="navbar__item" href="reservations.php">Reservations</a></li>
            <li><a class="navbar__item" href="about.html">About</a></li>
        </ul>
    </header> 
    <main>
        <div class="wrapper">
            <h1>Dashboard</h1>

            <!-- Totals -->
            <h3>General</h3>
            <table>
                <tr>
                    <th>Total Customers</th>
                    <th>Total Sessions</th>
                    <th>Total Reservations</th>
                    <th>Total Tickets</th>
                </tr>
                <tr>
                <?php foreach($totals as $item){
                    echo "<td>".$item["total"]."</td>";
                }?>
                </tr>
            </table>

            <!-- Stats about sessions -->
            <h3>Sessions per Type</h3>
            <table>
                <tr>
                    <th>3D</th>
                    <th>IMAX</th>
                    <th>Normal</th>
                </tr>
                <tr>
                <?php foreach($session_types as $item){
                    echo "<td>".$item["sessions"]."</td>";
                }?>
                </tr>
            </table>

            <!-- Stats about reservations -->
            <h2>Reservations</h2>
            <h3>Per type</h3>
            <table>
                <tr>
                    <th>3D</th>
                    <th>IMAX</th>
                    <th>Normal</th>
                </tr>
                <tr>
                <?php foreach($reservation_types as $item){
                    echo "<td>".$item["reservations"]."</td>";
                }?>
                </tr>
                <tr>
            </table>
            <h3>Per session</h3>
            <table>
                <tr>
                    <th>Reservations</th>
                    <th>Session ID</th>
                </tr>
                <?php foreach($res_per_ses as $item){
                    echo "<tr>";
                    echo "<td>".$item["reservations"]."</td>";
                    echo "<td>".$item["session"]."</td>";
                    echo "</tr>";
                }?>
            </table>
            <h3>Per customer</h3>
            <table>
                <tr>
                    <th>Reservations</th>
                    <th>Session ID</th>
                </tr>
                <?php foreach($res_per_cus as $item){
                    echo "<tr>";
                    echo "<td>".$item["reservations"]."</td>";
                    echo "<td>".$item["customer"]."</td>";
                    echo "</tr>";
                }?>
            </table>
            
            <!-- Stats about customers -->
            <h2>Customers</h2>
            <h3>Per day</h3>
            <table>
                <tr>
                    <th>Customers</th>
                    <th>Day</th>
                </tr>
                <tr>
                <?php foreach($cus_per_day as $item){
                    echo "<tr>";
                    echo "<td>".$item["customers"]."</td>";
                    echo "<td>".$item["display_date"]."</td>";
                    echo "</tr>";
                }?>
                </tr>
                <tr>
            </table>
            <h3>Per session</h3>
            <table>
                <tr>
                    <th>Customers</th>
                    <th>Session_ID</th>
                </tr>
                <?php foreach($cus_per_ses as $item){
                    echo "<tr>";
                    echo "<td>".$item["customers"]."</td>";
                    echo "<td>".$item["session"]."</td>";
                    echo "</tr>";
                }?>
            </table>

            <!-- Stats about tickets -->
            <h2>Tickets</h2>
            <h3>Per customer</h3>
            <table>
                <tr>
                    <th>Tickets</th>
                    <th>Customer_ID</th>
                </tr>
                <?php foreach($tick_per_cus as $item){
                    echo "<tr>";
                    echo "<td>".$item["tickets"]."</td>";
                    echo "<td>".$item["customer"]."</td>";
                    echo "</tr>";
                }?>
            </table>
            <h3>Per discount</h3>
            <table>
                <tr>
                    <th>Tickets</th>
                    <th>Discount</th>
                </tr>
                <?php foreach($tick_per_disc as $item){
                    echo "<tr>";
                    echo "<td>".$item["tickets"]."</td>";
                    echo "<td>".$item["discount"]."</td>";
                    echo "</tr>";
                }?>
            </table>
        </div>
    </main>
</body>
</html>
