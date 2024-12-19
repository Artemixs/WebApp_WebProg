<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the current user
$query = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
        }

        main {
            padding: 20px;
            margin: auto;
            max-width: 900px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 30px;
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        /* Back to Homepage Button */
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #702C2B;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fafafa;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #702C2B;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f0f8ff;
            cursor: pointer;
        }

        p {
            text-align: center;
            font-size: 1.1rem;
            color: #666;
            margin-top: 20px;
        }

        /* Status Badge */
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
        }

        .status.On-Process {
            background-color: #ffc107;
            color: #fff;
        }

        .status.Completed {
            background-color: #28a745;
            color: #fff;
        }

        .status.Cancelled {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <main>
        <h2>Your Orders</h2>

        <!-- Back to Homepage Button -->
        <a href="index.php" class="back-button">← Back to Homepage</a>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['items']); ?></td>
                            <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td>
                                <span class="status <?php echo htmlspecialchars($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
