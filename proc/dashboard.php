<?php
require("/srv/http/plain/config/database.php"); // Connection to the database


// Totals
$query = 'SELECT COUNT(*) AS total FROM customer UNION SELECT COUNT(*) FROM session UNION SELECT COUNT(*) FROM reserves UNION SELECT COUNT(*) FROM ticket';
$totals = $conn->query($query);

// Sessions per types
$query = 'SELECT COUNT(session_id) sessions, type FROM session GROUP BY session.type';
$session_types = $conn->query($query);

// Reservations per type
$query = 'SELECT COUNT(reserves.session_id) reservations, session.type FROM reserves RIGHT JOIN session ON session.session_id = reserves.session_id GROUP BY session.type';
$reservation_types = $conn->query($query);

// Reservations per session
$query = 'SELECT COUNT(session_id) reservations, session_id session FROM reserves GROUP BY session_id';
$res_per_ses = $conn->query($query);

// Reservations per customer
$query = 'SELECT COUNT(session_id) reservations, customer_id customer FROM reserves GROUP BY customer';
$res_per_cus = $conn->query($query);

// Customers per day
$query = 'SELECT COUNT(reserves.customer_id) customers, session.display_date FROM reserves RIGHT JOIN session ON session.session_id = reserves.session_id GROUP BY session.display_date';
$cus_per_day = $conn->query($query);

//  Customers per session
$query ='SELECT COUNT(customer_id) customers, session_id session FROM reserves GROUP BY session_id';
$cus_per_ses = $conn->query($query);

//  Tickets per customer
$query = 'SELECT tickets, customer_id customer FROM reserves GROUP BY customer';
$tick_per_cus = $conn->query($query);

//  Tickets per discount
$query = 'SELECT COUNT(ticket_id) tickets, discount FROM ticket GROUP BY discount';
$tick_per_disc = $conn->query($query);
