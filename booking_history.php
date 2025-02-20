<?php
include('db_connect.php');
session_start();
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM bookings WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
</head>
<body>
    <h2>Your Bookings</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Date</th>
            <th>Total Price</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['booking_id']; ?></td>
            <td><?php echo $row['booking_date']; ?></td>
            <td>₹<?php echo $row['total_price']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
