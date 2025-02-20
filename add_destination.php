<?php
include('db_connect.php');
session_start();

// Check if the user is an admin
if ($_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destination_name = $_POST['destination_name'];
    $country = $_POST['country'];
    $description = $_POST['description'];
    $best_time = $_POST['best_time'];
    

    $query = "INSERT INTO destinations (destination_name, country, description, best_time_to_visit) 
              VALUES ('$destination_name', '$country', '$description', '$best_time')";

    if (mysqli_query($conn, $query)) {
        echo "New destination added successfully!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Destination</title>
</head>
<body>
    <h2>Add New Destination</h2>
    <form action="add_destination.php" method="POST">
        <label>Destination Name: </label><input type="text" name="destination_name" required><br>
        <label>Country: </label><input type="text" name="country" required><br>
        <label>Description: </label><textarea name="description" required></textarea><br>
        <label>Best Time to Visit: </label><input type="text" name="best_time" required><br>
        
        <button type="submit">Add Destination</button>
    </form>
</body>
</html>
