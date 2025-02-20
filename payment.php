<?php
include('db_connect.php');
session_start();

$booking_id = $_GET['booking_id'];
$query = "SELECT * FROM bookings WHERE booking_id = $booking_id";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);


$total_amount = $booking['total_price'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $query = "INSERT INTO payments (booking_id, amount, payment_status) 
              VALUES ('$booking_id', '$total_amount', 'Completed')";
    mysqli_query($conn, $query);

    echo "Payment Successful!";
    header("Location: booking_history.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
    <h2>Payment</h2>
    <p>Total Amount: ₹<?php echo $total_amount; ?></p>
    <form action="payment.php?booking_id=<?php echo $booking_id; ?>" method="POST">
        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
