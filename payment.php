<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: cart.php");
        exit();
    }


    $user_id = $_SESSION['user_id'];
    $items = [];
    $total_amount = 0;

    // Prepare items and total amount
    foreach ($_SESSION['cart'] as $item) {
        $items[] = $item['food_name'] . " (x" . $item['quantity'] . ")";
        $total_amount += $item['food_price'] * $item['quantity'];
    }

    $items_str = implode(", ", $items);
    $status = 'On Process';

    // Correct bind_param with 4 variables
    $stmt = $conn->prepare("INSERT INTO orders (user_id, items, total_amount, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $items_str, $total_amount, $status);

    if ($stmt->execute()) {

    } else {
        echo "Error placing the order: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css"/>
    <title>Payment - FoodieBayan</title>
</head>
<body>
    <!-- Sidebar -->
    <aside class=" sidebar">
        <div class="icon">
            <a href="index.php">
                <img src="images/home-button.png" alt="Home" class="circle">
                <span class="icon-label"></span>
            </a>
        </div>
        <div class="icon">
            <a href="cart.php">
                <img src="images/Shopping cart.png" alt="Cart">
                <span class="icon-label"></span>
            </a>
        </div>
        <div class="icon">
            <a href="orders.php">
                <img src="images/Shopping bag.png" alt="Orders">
                <span class="icon-label"></span>
            </a>
        </div>

    </aside>
    <!-- Sidebar END -->

    <main class="main" style="padding-left: 80px;">
        <!-- Navbar -->
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
        <!-- Navbar END -->

        <div class="payment">
            <h2 class="text-center">Payment</h2>
            <!-- Payment Form -->
            <form action="thank_you.php" method="POST">
                <div class="mb-3">
                    <label for="cardNumber" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" required>
                </div>
                <div class="mb-3">
                    <label for="expiryDate" class="form-label">Expiry Date</label>
                    <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" required>
                </div>
                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit Payment</button>
            </form>
        </div>
    </main>

</body>
</html>