<?php
include('db_connect.php');
$query = $_GET['query'];

$sql = "SELECT * FROM destinations WHERE destination_name LIKE '%$query%'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>
    <h2>Search Results for "<?php echo $query; ?>"</h2>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . $row['destination_name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<a href='destination_details.php?destination_id=" . $row['destination_id'] . "'>View Details</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    ?>
</body>
</html>
