<?php
// Admin panel to manage content
include('db_connect.php');
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');  
    exit();
}


$inactive_limit = 900; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_limit) {
    session_unset();  
    session_destroy(); 
    header('Location: login.php'); 
    exit();
}
$_SESSION['last_activity'] = time(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        nav {
            margin-top: 20px;
            text-align: center;
        }
        nav a {
            margin: 0 15px;
            padding: 10px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #45a049;
        }
        .logout-btn {
            margin-top: 30px;
            display: block;
            text-align: center;
        }
        .logout-btn a {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn a:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Panel</h1>
    </header>

    <nav>
        <a href="add_destination.php">Add Destination</a>
        <a href="add_package.php">Add Package</a>
        <a href="view_bookings.php">View Bookings</a>
        
        
    </nav>

    <div class="logout-btn">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
