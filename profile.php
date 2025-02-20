<?php
include('db_connect.php');
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error fetching user data: " . mysqli_error($conn);
    exit();
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    header('Location: login.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    
    $update_query = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone' WHERE user_id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        header('Location: profile.php');
        echo "Update successfull";
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
</head>
<body>
    <h2>Your Profile</h2>
    <form method="POST">
        <label>First Name: </label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required><br>
        
        <label>Last Name: </label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required><br>

        <label>Email: </label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label>Phone: </label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>

        <button type="submit">Update Profile</button>
    </form>

    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
