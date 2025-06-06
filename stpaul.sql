CREATE DATABASE IF NOT EXISTS saint_paul_holy_party;
USE saint_paul_holy_party;

CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    attendance ENUM('yes', 'no') NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);