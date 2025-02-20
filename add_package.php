<?php
include('db_connect.php');
session_start();

// Check if the user is an admin
if ($_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destination_id = $_POST['destination_id'];
    $package_name = $_POST['package_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    

    $query = "INSERT INTO packages (destination_id, package_name, description, price, duration) 
              VALUES ('$destination_id', '$package_name', '$description', '$price', '$duration')";

    if (mysqli_query($conn, $query)) {
        echo "New package added successfully!";
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
    <title>Add Package</title>
</head>
<body>
    <h2>Add New Package</h2>
    <form action="add_package.php" method="POST">
        <label>Destination: </label>
        <select name="destination_id" required>
            <?php
            $query = "SELECT * FROM destinations";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['destination_id'] . "'>" . $row['destination_name'] . "</option>";
            }
            ?>
        </select><br>
        <label>Package Name: </label><input type="text" name="package_name" required><br>
        <label>Description: </label><textarea name="description" required></textarea><br>
        <label>Price: </label><input type="number" name="price" required><br>
        <label>Duration (Days): </label><input type="number" name="duration" required><br>
        
        <button type="submit">Add Package</button>
    </form>
</body>
</html>
