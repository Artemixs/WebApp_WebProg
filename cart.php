<?php
session_start();

// Initialize the cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];

    // Add food to cart array
    $item = [
        'food_id' => $food_id,
        'food_name' => $food_name,
        'food_price' => $food_price,
        'quantity' => 1 // Default quantity
    ];

    // Check if the item already exists in the cart
    $item_found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['food_id'] == $food_id) {
            $cart_item['quantity'] += 1; // Increase quantity if already in cart
            $item_found = true;
            break;
        }
    }

    // If item is not in the cart, add it
    if (!$item_found) {
        $_SESSION['cart'][] = $item;
    }

    // Redirect to cart page to avoid form resubmission
    header("Location: cart.php");
    exit();
}

// Handle Remove Item action
if (isset($_GET['remove_item'])) {
    $remove_food_id = $_GET['remove_item'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['food_id'] == $remove_food_id) {
            unset($_SESSION['cart'][$key]); // Remove the item
            break;
        }
    }
    // Reindex the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css"/>
    <title>Cart - FoodieBayan</title>
    <style>
        /* Basic Table Styling */
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .cart-table th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 5px;
        }

        .btn-remove {
            background-color: #FF4C4C;
        }
    </style>
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

    <h2>Your Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_amount = 0;
                foreach ($_SESSION['cart'] as $item): 
                    $item_total = $item['food_price'] * $item['quantity'];
                    $total_amount += $item_total;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['food_name']); ?></td>
                    <td>$<?php echo number_format($item['food_price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item_total, 2); ?></td>
                    <td>
                        <a href="?remove_item=<?php echo $item['food_id']; ?>" class="btn btn-remove">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total Amount: $<?php echo number_format($total_amount, 2); ?></h3>

        <form action="payment.php" method="POST">
            <button type="submit" class="btn">Proceed to Checkout</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</main>
</body>
</html>
