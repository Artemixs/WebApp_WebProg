<?php
session_start();
require 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // User must be logged in
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    // Check for existing cart item
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND food_id = ?");
    $stmt->execute([$user_id, $food_id]);

    if ($stmt->num_rows() > 0) {
        // Update quantity if item already exists
        $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND food_id = ?")
             ->execute([$quantity, $user_id, $food_id]);
    } else {
        // Insert a new cart item
        $conn->prepare("INSERT INTO cart (user_id, food_id, food_name, quantity, price) 
                        VALUES (?, ?, ?, ?, ?)")
             ->execute([$user_id, $food_id, $food_name, $quantity, $price]);
    }
    header("Location: cart.php"); // Redirect to cart
    exit();
}
?>
