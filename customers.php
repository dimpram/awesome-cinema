<?php
    require("/srv/http/plain/proc/customers.php");
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
            <li><a class="navbar__item navbar__item--active" href="customers.php">Customers</a></li>
            <li><a class="navbar__item" href="sessions.php">Sessions</a></li>
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
            <h1>Customers</h1>

            <!-- Manage customers form -->
            <h3>Manage customers</h3>
            <form action="proc/customers.php" method="POST">

                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

                <div style="float:left;width:49%;">
                    <label for="fname">First name</label>
                    <input type="text" name="fname" placeholder="Billy" value="<?php echo $fname; ?>" required>
                    <label for="lname">Last name</label>
                    <input type="text" name="lname" placeholder="Karlson" value="<?php echo $lname; ?>" required>
                </div>
                <div style="float:right;width:49%;">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="billy@email.com" value="<?php echo $email; ?>" required>
                    <label for="phone_num">Phone number <small><i>+111 (or +11) 111 111 1111</i></small></label>
                    <input type="tel" name="phone_num" value="<?php echo $phone_num; ?>" placeholder="+123 453 454 6783" pattern="\+[0-9]{2,3} [0-9]{3} [0-9]{3} [0-9]{4}" required>
                </div>
                <label>Gender</label>
                <?php
                if ($update == true) {
                    echo '<select id="gender" name="gender">
                            <option value="'.$gender.'">Unchanged</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>';
                    echo '<button type="submit" name="update" class="btn">Update customer</button>';
                } else {
                    echo '<select id="gender" name="gender">
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>';
                    echo '<button type="submit" name="save" class="btn">Add customer</button>';
                }
                ?>
            </form>

            <!-- Display customers -->
            <h3>Display customers</h3>
            <table class="table--customers">
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Phone number</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            <?php 
                foreach($c_arr as $item) {
                    echo "<tr>";
                    echo "<td>".$item["customer_id"]."</td>
                        <td>".$item["fname"]."</td>
                        <td>".$item["lname"]."</td>
                        <td>".$item["phone_num"]."</td>
                        <td>".$item["gender"]."</td>
                        <td>".$item["email"]."</td>
                        <td style=\"display:flex;flexdirection:row;\">
                            <a class=\"btn btn__table btn--edit\" 
                            href=\"customers.php?edit=".$item["customer_id"]."\">Edit</a>
                            <a class=\"btn btn__table btn--delete\" 
                            href=\"customers.php?delete=".$item["customer_id"]."\">Delete</a>
                        </td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>
        </div>
    </main>
</body>
</html>
