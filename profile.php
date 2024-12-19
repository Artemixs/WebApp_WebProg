<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Profile</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">FoodieBayan</a>
            <ul class="navbar-nav ms-auto">
            
                <li class="profile">
                    <a class="profile" href="profile.php">
                        <img src="images/account_circle.png" alt="Profile">
                    </a>
                </li>
            </ul>
</nav>
    <div class="profile-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>
