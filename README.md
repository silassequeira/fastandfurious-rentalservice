# Fast & Furious Cars Inc. – Car Rental Platform

## Project Description

This project is a university-level web application built with PHP and PostgreSQL. It simulates an online car rental platform themed around Fast & Furious vehicles.

### Features

Users:
- Create an account and log in
- Search and rent cars for selected date ranges
- View their own reservations

Administrators:
- Add, delete, hide or show cars in the system
- View cars that have been rented
- Access usage statistics for the platform

---

## Technologies Used

- PHP (vanilla) – Backend logic
- PostgreSQL – Database system
- HTML/CSS/JavaScript – Frontend UI and form validation
- XAMPP – Apache server environment (on Windows)

---

## How to Run the Project (Windows + XAMPP)

### 1. Prerequisites

- XAMPP: https://www.apachefriends.org/index.html
- PostgreSQL: https://www.postgresql.org/download/windows/
- A code editor (e.g., VS Code, Notepad++)

---

### 2. Setting Up Your Environment

#### Install XAMPP

1. Download and install XAMPP
2. During setup, ensure Apache and PHP are selected
3. Install to the default path: `C:\xampp`

#### Install PostgreSQL

1. Download PostgreSQL from the official website
2. Install it and remember:
   - Default user: `postgres`
   - Default port: `5432`
   - Set a secure password (e.g., `postgres` for development)

---

### 3. Enable PHP's PostgreSQL Extension

1. Open `C:\xampp\php\php.ini` in a text editor
2. Find and uncomment these lines by removing the semicolon (`;`):
   ```
   extension=pgsql
   extension=pdo_pgsql
   ```
3. Save the file
4. Restart Apache via the XAMPP Control Panel

---

### 4. Set Up the Project Files

1. Copy your project folder (e.g., `fast_furious_cars`) into:
   ```
   C:\xampp\htdocs\
   ```
2. Example final path:
   ```
   C:\xampp\htdocs\fast_furious_cars\index.php
   ```

---

### 5. Create the PostgreSQL Database

1. Open pgAdmin or use the `psql` terminal
2. Create a new database:
   ```sql
   CREATE DATABASE fastcars;
   ```
3. Create the required tables and optionally insert data  
   (Use a SQL dump file like `schema.sql` if available)

---

### 6. Configure the PHP Database Connection

In your PHP scripts, such as `index_scriptForm.php`, ensure your PostgreSQL connection string matches:

```php
$connection = pg_connect("host=localhost dbname=fastcars user=postgres password=postgres port=5432");
```

Replace the database name, user, and password as needed.

---

### 7. Launch the Application

1. Start Apache from the XAMPP Control Panel
2. Visit the application in your web browser:
   ```
   http://localhost/fast_furious_cars/
   ```

---

### 8. Test PostgreSQL Connection (Optional)

You can create a simple test file (`test_pg.php`) to verify the database connection:

```php
<?php
$conn = pg_connect("host=localhost port=5432 dbname=fastcars user=postgres password=postgres");
if ($conn) {
    echo "Connected to PostgreSQL!";
} else {
    echo "Connection failed.";
}
?>
```

Open this file in your browser at:
```
http://localhost/fast_furious_cars/test_pg.php
```

---

## Project Structure

```
fast_furious_cars/
│
├── index.php
├── index_scriptForm.php
├── checkSession.php
├── create_uniqueID.php
├── user_selectCar.php
├── login.php
├── logout.php
├── register.php
├── admin_visualizeAllCars.php
├── style.css
├── javascript/
│   └── dateInputFormatter.js
├── schema.sql        (optional)
└── README.md
```

---

## Notes

- The default language of the website is Portuguese (`lang="pt"`)
- Session-based logic handles user and admin roles
- Credentials and sensitive configuration should be secured for production
- Ensure PostgreSQL is running when accessing pages that query the database

---

## Authors

This project was developed as part of a university assignment.

Contributors:
- Silas Sequeira
- Sam-Coelho