<?php 
include('db_connect.php');
session_start();


$destinations_query = "SELECT * FROM destinations LIMIT 5"; 
$destinations_result = mysqli_query($conn, $destinations_query);


if (!$destinations_result) {
    echo "Error fetching destinations: " . mysqli_error($conn);
    exit;
}


$packages_query = "SELECT * FROM packages LIMIT 3";
$packages_result = mysqli_query($conn, $packages_query);


if (!$packages_result) {
    echo "Error fetching packages: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tourism Information System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- Header -->
  <header>
    <h1>Welcome to the Tourism Information System</h1>
    <nav>
      <?php
      
      if (isset($_SESSION['user_id'])) {
          
          echo "<a href='logout.php'>Logout</a> | ";
          echo "<a href='profile.php'>Profile</a> | ";

          
          if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
              
              echo "<a href='admin_panel.php'>Admin Panel</a> | ";
          }
      } else {
          
          echo "<a href='login.php'>Login</a> | ";
          echo "<a href='register.php'>Register</a>";
      }
      ?>
    </nav>
  </header>

  <!-- Featured Packages -->
  <section id="featured-packages">
    <h2>Featured Packages</h2>
    <div class="package-container">
      <?php
      
      if (mysqli_num_rows($packages_result) > 0) {
          while ($package = mysqli_fetch_assoc($packages_result)) {
              echo "<div class='package'>
                      <h3>" . $package['package_name'] . "</h3>
                      <p>" . $package['description'] . "</p>
                      <p><strong>Price: ₹" . $package['price'] . "</strong></p>
                      <a href='destination_details.php?destination_id=" . $package['destination_id'] . "'>Book Now</a>
                    </div>";
          }
      } else {
          echo "<p>No featured packages available.</p>";
      }
      ?>
    </div>
  </section>

  <!-- Search Bar -->
  <section id="search-bar">
    <h2>Search for Destinations</h2>
    <form action="search_results.php" method="GET">
      <input type="text" name="query" placeholder="Enter destination name" required>
      <button type="submit">Search</button>
    </form>
  </section>

  <!-- Destinations Display Section -->
  <h3>Popular Destinations</h3>
  <div>
    <?php 
    
    if (mysqli_num_rows($destinations_result) > 0) {
        while ($row = mysqli_fetch_assoc($destinations_result)) : ?>
            <div>
                <h4><?php echo htmlspecialchars($row['destination_name']); ?></h4>
                <p><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...</p>
                <a href="destination_details.php?destination_id=<?php echo $row['destination_id']; ?>">View Details</a>
            </div><br>
        <?php endwhile; 
    } else {
        echo "<p>No popular destinations available.</p>";
    }
    ?>
  </div>

</body>
</html>
