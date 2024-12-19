<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$address = $_POST['address'] ?? '';

// Move items to "orders" table
$conn->autocommit(false);
try {
    // Insert orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, food_name, quantity, price, address)
                            SELECT user_id, food_name, quantity, price, ? 
                            FROM cart WHERE user_id = ?");
    $stmt->execute([$address, $user_id]);

    // Clear the cart
    $conn->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

    $conn->commit();
    header("Location: thank_you.php");
} catch (Exception $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css"/>
    <title>Checkout - FoodieBayan</title>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
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
                <li class="map">
                    
                <li class="profile">
                    <a class="profile" href="profile.php">
                        <img src="images/account_circle.png" alt="Profile">
                    </a>
                </li>
            </ul>
</nav>
        <!-- Navbar END -->

        <div class="container mt-5">
            <h2 class="text-center">Checkout</h2>
            <!-- Checkout Form -->
            <form action="payment.php" method="POST">
                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit">Credit Card</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Proceed to Payment</button>
            </form>
            <div class="container mt-5">
            <h2 class="text-center">Checkout</h2>
            <div class="row">
                <div class="col-md-8">
                    <div class="card cart-card">
                        <div class="card-body">
                            <h5>Your Order</h5>
                            <table class="table cart-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample cart items -->
                                    <tr>
                                        <td>Kare-kare</td>
                                        <td>$10.00</td>
                                        <td>1</td>
                                        <td>$10.00</td>
                                    </tr>
                                    <tr>
                                        <td>Pork Sisig</td>
                                        <td>$12.00</td>
                                        <td>2</td>
                                        <td>$24.00</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="total-price">Total: <strong>$34.00</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

</body>
</html>