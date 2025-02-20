<?php
include('db_connect.php');
$destination_id = $_GET['id'];
$query = "SELECT * FROM destinations WHERE destination_id = $destination_id";
$result = mysqli_query($conn, $query);
$destination = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $destination['destination_name']; ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <header>
    <h1>Explore <?php echo $destination['destination_name']; ?></h1>
  </header>

  <section id="destination-details">
    <h2>About</h2>
    <p><?php echo $destination['description']; ?></p>
    <h3>Best Time to Visit</h3>
    <p><?php echo $destination['best_time_to_visit']; ?></p>
  </section>

  <!-- Hotel Options -->
  <section id="hotel-options">
    <h2>Hotels</h2>
    <ul>
      <?php
      $hotel_query = "SELECT * FROM hotels WHERE destination_id = $destination_id";
      $hotel_result = mysqli_query($conn, $hotel_query);
      while ($hotel = mysqli_fetch_assoc($hotel_result)) {
        echo "<li>
                <p>" . $hotel['hotel_name'] . " - " . $hotel['rating'] . " Stars</p>
                <p>Price per night: ₹" . $hotel['price_per_night'] . "</p>
                <a href='booking.php?hotel_id=" . $hotel['hotel_id'] . "&destination_id=" . $destination_id . "'>Book Now</a>
              </li>";
      }
      ?>
    </ul>
  </section>

  <!-- Travel Options -->
  <section id="travel-options">
    <h2>Travel Options</h2>
    <ul>
      <?php
      $travel_query = "SELECT * FROM travel_options WHERE destination_id = $destination_id";
      $travel_result = mysqli_query($conn, $travel_query);
      while ($travel = mysqli_fetch_assoc($travel_result)) {
        echo "<li>
                <p>" . $travel['travel_type'] . " - ₹" . $travel['price'] . "</p>
                <a href='travel_booking.php?travel_id=" . $travel['travel_id'] . "&destination_id=" . $destination_id . "'>Book Travel</a>
              </li>";
      }
      ?>
    </ul>
  </section>

</body>
</html>
