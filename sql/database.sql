--------------------
-- Database creation
--------------------
DROP DATABASE Cinema;
CREATE DATABASE Cinema;
USE Cinema;

--------------------
-- Table creation
--------------------
CREATE TABLE customer(
  -- Values
  customer_id INT NOT NULL AUTO_INCREMENT,
  fname VARCHAR(30) NOT NULL,
  lname VARCHAR(30) NOT NULL,
  phone_num VARCHAR(30) NOT NULL,
  gender VARCHAR(1) NOT NULL,
  email NVARCHAR(255) DEFAULT 'unspecified',

  -- Keys
  PRIMARY KEY (customer_id)
);

CREATE TABLE session(
  -- Values
  session_id INT NOT NULL AUTO_INCREMENT,
  display_date DATE NOT NULL,
  type VARCHAR(10) NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  
  -- Soon to be foreign keys
  movie_id INT,
  room_id INT,

  -- Keys
  PRIMARY KEY (session_id)
);

CREATE TABLE movie(
  -- Values
  movie_id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(50) NOT NULL,
  genre VARCHAR(10) NOT NULL,
  studio VARCHAR(50) NOT NULL,
  year YEAR NOT NULL,

  -- Keys
  PRIMARY KEY (movie_id)
);

CREATE TABLE ticket(
  -- Values
  ticket_id INT NOT NULL AUTO_INCREMENT,
  value DECIMAL(4,2) NOT NULL,
  discount VARCHAR(10) DEFAULT 'normal',

  -- Soon to be foreign keys
  session_id INT,
  customer_id INT,

  -- Keys
  PRIMARY KEY (ticket_id)
);

CREATE TABLE room(
  -- Values
  room_id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) DEFAULT 'unnamed',
  seat_rows INT NOT NULL,
  seats_per_row INT NOT NULL,

  -- Keys
  PRIMARY KEY (room_id)
);

CREATE TABLE reserves(
  -- Soon to be foreign keys
  customer_id INT,
  session_id INT,

  -- Values
  tickets INT NOT NULL,

  -- Keys
  PRIMARY KEY (customer_id, session_id)
);


-- Prints the tables created
SHOW TABLES;

----------------------
-- Addign foreign keys
----------------------
-- Session table
ALTER TABLE session
ADD (
  FOREIGN KEY(movie_id) REFERENCES movie(movie_id) ON DELETE CASCADE,
  FOREIGN KEY(room_id) REFERENCES room(room_id) ON DELETE CASCADE
);

-- Ticket table
ALTER TABLE ticket
ADD (
  FOREIGN KEY(session_id) REFERENCES session(session_id) ON DELETE CASCADE,
  FOREIGN KEY(customer_id) REFERENCES customer(customer_id) ON DELETE CASCADE
);

-- Reserves table
ALTER TABLE reserves
ADD (
  FOREIGN KEY(customer_id) REFERENCES customer(customer_id) ON DELETE CASCADE,
  FOREIGN KEY(session_id) REFERENCES session(session_id) ON DELETE CASCADE 
);
