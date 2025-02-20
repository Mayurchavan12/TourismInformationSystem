<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $query = "INSERT INTO users (first_name, last_name, email, password, phone, address, user_type) 
              VALUES ('$first_name', '$last_name', '$email', '$password', '$phone', '$address', 'customer')";

    if (mysqli_query($conn, $query)) {
        header('Location: login.php');
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
    <title>User Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <label>First Name: </label><input type="text" name="first_name" required><br>
        <label>Last Name: </label><input type="text" name="last_name" required><br>
        <label>Email: </label><input type="email" name="email" required><br>
        <label>Password: </label><input type="password" name="password" required><br>
        <label>Phone: </label><input type="text" name="phone" required><br>
        <label>Address: </label><textarea name="address" required></textarea><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
