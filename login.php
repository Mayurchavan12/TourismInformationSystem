<?php
include('db_connect.php');
session_start();


if (isset($_SESSION['user_id'])) {
    
    if ($_SESSION['user_type'] == 'admin') {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);  
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    
    if ($user = mysqli_fetch_assoc($result)) {
        
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_type'] = $user['user_type']; 

            
            if ($user['user_type'] == 'admin') {
                header('Location: admin_panel.php');  
            } else {
                header('Location: index.php');  
            }
            exit();
        } else {
            
            $error_message = "Invalid password!";
        }
    } else {
        
        $error_message = "No user found with this email!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php
    
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
    <form action="login.php" method="POST">
        <label>Email: </label><input type="email" name="email" required><br>
        <label>Password: </label><input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>
