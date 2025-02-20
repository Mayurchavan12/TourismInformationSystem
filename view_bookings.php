<?php
include('db_connect.php');
session_start();

// Check if the user is an admin
if ($_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
}

$query = "SELECT * FROM bookings";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
</head>
<body>
    <h2>All Bookings</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Booking Date</th>
            <th>Total Price</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['booking_id']; ?></td>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['booking_date']; ?></td>
            <td>₹<?php echo $row['total_price']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
