<?php
include('db_connect.php');
session_start();

if (isset($_GET['destination_id'])) {
    $destination_id = $_GET['destination_id'];
    
    $destination_query = "SELECT * FROM destinations WHERE destination_id = '$destination_id'";
    $destination_result = mysqli_query($conn, $destination_query);
    $destination = mysqli_fetch_assoc($destination_result);
} else {
    
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination Details</title>
</head>
<body>
    <h2><?php echo $destination['destination_name']; ?></h2>
    <p><strong>Country:</strong> <?php echo $destination['country']; ?></p>
    <p><strong>Best Time to Visit:</strong> <?php echo $destination['best_time_to_visit']; ?></p>
    <p><strong>Description:</strong> <?php echo $destination['description']; ?></p>

    <h3>Available Packages</h3>
    <?php
    $package_query = "SELECT * FROM packages WHERE destination_id = '$destination_id'";
    $package_result = mysqli_query($conn, $package_query);
    
    if (mysqli_num_rows($package_result) > 0) {
        while ($package = mysqli_fetch_assoc($package_result)) {
            echo "<div>";
            echo "<h4>" . $package['package_name'] . "</h4>";
            echo "<p>" . $package['description'] . "</p>";
            echo "<p><strong>Price:</strong> ₹" . $package['price'] . "</p>";
            echo "<p><strong>Duration:</strong> " . $package['duration'] . " days</p>";

           
            echo "<form action='booking.php' method='GET'>";
            echo "<input type='hidden' name='destination_id' value='" . $destination_id . "'>";
            echo "<input type='hidden' name='package_id' value='" . $package['package_id'] . "'>";
            echo "<button type='submit'>Book Now</button>";
            echo "</form>";

            echo "</div><br>";
        }
    } else {
        echo "<p>No packages available for this destination.</p>";
    }
    ?>
</body>
</html>
