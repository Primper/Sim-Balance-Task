# Sim-Balance-Task

## Description

This project allows for transferring balances between SIM cards. It uses PHP and MySQL for the backend logic and database management. The solution includes functionality for deducting and adding balance amounts between two SIM cards, and handles transaction integrity using SQL triggers and PHP transactions.

## Prerequisites

- PHP 7.x or 8.x
- MySQL 5.7 or higher
- Composer (for dependency management if needed)
- Postman or a similar tool for testing API requests

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/your_username/Sim-Balance-Task.git
   cd sim-balance-transfer

2. **Set up the database:**

   ```Create a MySQL database and import the sql/database.sql file to create the required tables and triggers:
   mysql -u your_username -p your_database < sql/database.sql

3. **Configure database connection:**

   ```Update src/transfer.php with your database credentials:
   $dsn = 'mysql:host=localhost;dbname=your_database;charset=utf8';
   $username = 'your_username';
   $password = 'your_password';

4. **Start the PHP server:**

   ```Run the following command in the src directory to start a local PHP server:
   php -S localhost:8000 -t src/
