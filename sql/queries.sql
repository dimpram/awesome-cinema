-- All the queries here are presented with example values. Inside the php code
-- some of those values are replaced by variables.
-- In some cases there are more than 1 queries for each operation.

-- Select Cinema database
USE Cinema;

-----------
-- Sessions
-----------
-- Print active sessions
SELECT * FROM session;

-- Search session by movie
SELECT * FROM session WHERE movie_id=3;

-- Search sessions by type
SELECT * FROM session WHERE type="3d";

-- Add session
INSERT INTO session(display_date, type, start_time, end_time, movie_id, room_id) VALUES('2020-06-22', 'imax', '20:15:00', '22:00:00', 4, 7);

-- Delete session
DELETE FROM ticket WHERE session_id=1;      -- Deleting from ticket for consistency
DELETE FROM reserves WHERE session_id=1;    -- Deleting from reserves for consistency
DELETE FROM session WHERE session_id=1;     -- And finally deleting from session

-- Modify session
SELECT * FROM session WHERE session_id=1;
UPDATE session SET display_date='2020-06-30', type='imax', start_time='21:00:00', end_time='22:00:00', movie_id=3, room_id=4 WHERE session_id=1; 
UPDATE ticket SET value=8 WHERE session_id=1;   --OR
UPDATE ticket SET value=10 WHERE session_id=1;  --OR
UPDATE ticket SET value=12 WHERE session_id=1;  --OR


---------------
-- Reservations
---------------
-- Print all the reservations
SELECT * FROM reserves;

-- Search reservations by customer
SELECT * FROM reserves WHERE customer_id=1;

-- Search reservations by tickets
SELECT * FROM reserves WHERE tickets=1;

-- Make a reservation
SELECT type FROM session WHERE session_id=1;
SELECT session.session_id, room.seat_rows, room.seats_per_row FROM session JOIN room ON session.room_id=room.room_id AND session_id=1;
SELECT COUNT(*) seats FROM ticket WHERE session_id=1;
INSERT INTO reserves(customer_id, session_id, tickets) VALUES(1, 12, 1);
INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(8.00, "normal", 3, 1);  -- OR
INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(10.00, "normal", 3, 1); -- OR
INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(12.00, "normal", 3, 1); -- OR

-- Delete a reservation
DELETE FROM reserves WHERE session_id=1 AND customer_id=1;  -- Deleting from reserves for consistency
DELETE FROM ticket WHERE session_id=1 AND customer_id=1;    -- And finally deleting from ticket

-- Modify reservation 
SELECT ticket_id, discount FROM ticket WHERE session_id=1 AND customer_id=3;                -- Get discount
SELECT * FROM reserves WHERE customer_id=3 AND session_id=1;                                -- Get reservation data
UPDATE reserves SET session_id=1 WHERE customer_id=3 AND tickets=1;                         -- Update reserves
DELETE FROM ticket WHERE customer_id=3 AND session_id=1;                                    -- Deleting...
INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(8.00, "normal", 1, 3);  -- And reentering updated reservations
INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(10.00, "normal", 1, 3); -- OR
INSERT INTO ticket(value, discount, session_id, customer_id) VALUES(12.00, "normal", 1, 3); -- OR                   

-- Confirm/Print reservation
SELECT r.session_id, r.customer_id, r.tickets, ticket.discount, fname, lname, session.movie_id, movie.title, session.display_date, session.start_time, room.name FROM reserves AS r JOIN customer AS c ON r.customer_id=c.customer_id AND r.customer_id=2 AND r.session_id=1 JOIN session ON r.session_id=session.session_id AND r.session_id=1 JOIN movie ON session.movie_id=movie.movie_id JOIN room ON session.room_id=room.room_id JOIN ticket ON r.customer_id=ticket.customer_id AND r.session_id=ticket.session_id;

-- Extra queries used for form validation
SELECT MAX(customer_id) AS c_cnt FROM customer;
SELECT MAX(session_id) AS s_cnt FROM session;


------------
-- Customers
------------
-- Add customer
INSERT INTO customer(fname, lname, phone_num, gender, email, status) VALUES("jim", "Prama", "+6994157766", "M", "prama@gmail.com", "student");

-- Delete customer
DELETE FROM ticket WHERE customer_id=4;     -- Deleting from ticket for consistency
DELETE FROM reserves WHERE customer_id=4;   -- Deleting from reserves for consistancy
DELETE FROM customer WHERE customer_id=4;   -- And finally deleting from customer

-- Modify customer
SELECT * FROM customer WHERE customer_id=1; -- Get the data of the desired customer using id
UPDATE customer SET fname="John", lname="Smith", phone_num="+111 111 111 1111", gender="M", email="example@email.com" WHERE customer_id=1;


--------------------
-- Stats / Dashboard
--------------------
-- Active sessions
SELECT * FROM session;

-- Totals
SELECT COUNT(*) AS total FROM customer UNION SELECT COUNT(*) FROM session UNION SELECT COUNT(*) FROM reserves UNION SELECT COUNT(*) FROM ticket;

-- Sessions per type
SELECT COUNT(session_id) sessions, type FROM session GROUP BY session.type;

-- Reservations per session
SELECT COUNT(session_id) reservations, session_id session FROM reserves GROUP BY session_id;

-- Reservations per type
SELECT COUNT(reserves.session_id) reservations, session.type FROM reserves RIGHT JOIN session ON session.session_id = reserves.session_id GROUP BY session.type;

-- Reservations per customer
SELECT COUNT(session_id) reservations, customer_id customer FROM reserves GROUP BY customer;

-- Customers per day
SELECT COUNT(reserves.customer_id) customers, session.display_date FROM reserves RIGHT JOIN session ON session.session_id = reserves.session_id GROUP BY session.display_date;

-- Customers per session
SELECT COUNT(customer_id) customers, session_id session FROM reserves GROUP BY session_id;

-- Tickets per customer
SELECT tickets, customer_id customer FROM reserves GROUP BY customer;

-- Tickets per discount
SELECT COUNT(ticket_id) tickets, discount FROM ticket GROUP BY discount;
