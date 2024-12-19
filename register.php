<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($username && $email && $password) {
        $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($query->execute([$username, $email, $password])) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Username or Email might already exist.";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Register</title>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" required>
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
