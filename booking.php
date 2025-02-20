<?php
include('db_connect.php');
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$destination_id = $_GET['destination_id'] ?? null;


$travel_options_query = "SELECT travel_id, travel_type, price FROM travel_options WHERE destination_id = '$destination_id'";
$travel_options_result = mysqli_query($conn, $travel_options_query);
if (!$travel_options_result) {
    echo "Error fetching travel options: " . mysqli_error($conn);
    exit;
}


$package_id = $_GET['package_id'] ?? null;
$hotel_id = null;
if (isset($package_id)) {
    $query = "SELECT hotel_id FROM packages WHERE package_id = '$package_id' LIMIT 1"; 
    $result = mysqli_query($conn, $query);
    if ($result) {
        $package = mysqli_fetch_assoc($result);
        if (isset($package['hotel_id'])) {
            $hotel_id = $package['hotel_id']; 
        } else {
            echo "Error: No hotel found for the given package.";
            exit;
        }
    } else {
        echo "Error fetching package details: " . mysqli_error($conn);
        exit;
    }
}


if (!$hotel_id) {
    echo "Hotel ID is missing!";
    exit;
}


$hotel_query = "SELECT hotel_name FROM hotels WHERE hotel_id = '$hotel_id' LIMIT 1";
$hotel_result = mysqli_query($conn, $hotel_query);
if ($hotel_result) {
    $hotel = mysqli_fetch_assoc($hotel_result);
    $hotel_name = $hotel['hotel_name'];
} else {
    echo "Error fetching hotel details: " . mysqli_error($conn);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total_price = 0;
    $booking_date = date('Y-m-d H:i:s'); 

    
    $query = "INSERT INTO bookings (user_id, booking_date, total_price, name, email, phone) 
              VALUES ('$user_id', '$booking_date', '$total_price', '$name', '$email', '$phone')";
    if (!mysqli_query($conn, $query)) {
        echo "Error: " . mysqli_error($conn);
        die('Error inserting booking');
    }

    
    $booking_id = mysqli_insert_id($conn);

    
    if ($hotel_id) {
        $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
        $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
        $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);

        
        $hotel_query = "SELECT price_per_night FROM hotels WHERE hotel_id = '$hotel_id'";
        $hotel_result = mysqli_query($conn, $hotel_query);
        if (!$hotel_result) {
            echo "Error: " . mysqli_error($conn); // Debugging error
            die('Error fetching hotel details');
        }

        $hotel = mysqli_fetch_assoc($hotel_result);

        
        $days_stayed = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $hotel_price = $hotel['price_per_night'] * $days_stayed;

        
        $hotel_booking_query = "INSERT INTO hotel_bookings (booking_id, hotel_id, check_in_date, check_out_date, room_type) 
                                VALUES ('$booking_id', '$hotel_id', '$check_in', '$check_out', '$room_type')";
        if (!mysqli_query($conn, $hotel_booking_query)) {
            echo "Error: " . mysqli_error($conn); 
            die('Error inserting hotel booking');
        }

        
        $total_price += $hotel_price;
    }

    
    if (isset($_POST['travel_id'])) {
        $travel_id = mysqli_real_escape_string($conn, $_POST['travel_id']);
        $travel_date = mysqli_real_escape_string($conn, $_POST['travel_date']);
        $travel_class = mysqli_real_escape_string($conn, $_POST['travel_class']);

        
        $travel_query = "SELECT price FROM travel_options WHERE travel_id = '$travel_id'";
        $travel_result = mysqli_query($conn, $travel_query);
        if (!$travel_result) {
            echo "Error: " . mysqli_error($conn); 
            die('Error fetching travel details');
        }

        $travel = mysqli_fetch_assoc($travel_result);

        
        $total_price += $travel['price'];

        
        $travel_booking_query = "INSERT INTO travel_bookings (booking_id, travel_id, travel_date, travel_class) 
                                 VALUES ('$booking_id', '$travel_id', '$travel_date', '$travel_class')";
        if (!mysqli_query($conn, $travel_booking_query)) {
            echo "Error: " . mysqli_error($conn); 
            die('Error inserting travel booking');
        }
    }

    // Step 4: Update total price in bookings table after adding hotel and travel prices
    $update_query = "UPDATE bookings SET total_price = '$total_price' WHERE booking_id = '$booking_id'";
    if (!mysqli_query($conn, $update_query)) {
        echo "Error: " . mysqli_error($conn); 
        die('Error updating total price');
    }

    
    header("Location: payment.php?booking_id=$booking_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
</head>
<body>
    <h2>Booking Details</h2>
    <form action="booking.php?destination_id=<?php echo $destination_id; ?>&package_id=<?php echo $package_id; ?>" method="POST">

        <!-- Personal Information -->
        <h3>Personal Information</h3>
        <label>Name: </label><input type="text" name="name" required><br>
        <label>Email: </label><input type="email" name="email" required><br>
        <label>Phone: </label><input type="text" name="phone" required><br>

        <!-- Hotel Information -->
        <h3>Hotel Information</h3>
        <p>Hotel Name: <?php echo $hotel_name; ?></p>
        <label>Check-in Date: </label><input type="date" name="check_in" required><br>
        <label>Check-out Date: </label><input type="date" name="check_out" required><br>
        <label>Room Type: </label>
        <select name="room_type">
            <option value="Single">Single</option>
            <option value="Double">Double</option>
            <option value="Suite">Suite</option>
        </select><br>

        <!-- Travel Information -->
        <h3>Travel Information</h3>
        <label>Travel Date: </label><input type="date" name="travel_date" required><br>
        <label>Travel Class: </label>
        <select name="travel_class">
            <option value="Economy">Economy</option>
            <option value="Business">Business</option>
            <option value="First Class">First Class</option>
        </select><br>

        <label>Travel Type: </label>
        <select name="travel_id" required>
            <option value="">Select Travel Type</option>
            <?php
            
            while ($travel_option = mysqli_fetch_assoc($travel_options_result)) {
                echo "<option value='" . $travel_option['travel_id'] . "'>" . $travel_option['travel_type'] . " - " . number_format($travel_option['price'], 2) . "</option>";
            }
            ?>
        </select><br>

        <button type="submit">Next</button>
    </form>
</body>
</html>
