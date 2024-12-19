<?php
session_start();
require_once "config.php"; // Database connection

// Check if user is logged in

// Fetch user information

$username = $_SESSION['username']; // Example session variable

// Fetch food items from the database
$sql = "SELECT * FROM food_items"; // Assuming food_items table contains food details
$result = $conn->query($sql);

// Handle Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];

    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add item to cart or update quantity
    $item_found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['food_id'] == $food_id) {
            $item['quantity'] += 1; // Increase quantity if already in cart
            $item_found = true;
            break;
        }
    }

    if (!$item_found) {
        $_SESSION['cart'][] = [
            'food_id' => $food_id,
            'food_name' => $food_name,
            'food_price' => $food_price,
            'quantity' => 1
        ];
    }

    // Redirect to prevent form resubmission
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>FoodieBayan - Home</title>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="icon">
            <a href="index.php">
                <img src="images/home-button.png" alt="Home">
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
        <nav class="navbar">
            <a class="navbar-brand" href="#">FoodieBayan</a>
            <ul class="navbar-nav">
                
                    <a href="profile.php"><img src="images/account_circle.png" alt="Profile"></a>
                </li>
            </ul>
        </nav>
        <!-- Navbar END -->

        <!-- Welcome Message -->
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

        <!-- Food Items Section -->
        <div class="container">
            <h2>Available Food Items</h2>
            <div class="food-items">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="food-card">
                            <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="food-img">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                            <!-- Add to Cart Button -->
                            <form method="POST" action="index.php">
                                <input type="hidden" name="food_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="food_name" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="food_price" value="<?php echo $row['price']; ?>">
                                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No food items available.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
