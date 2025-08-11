<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['order_id'])) {
    echo "<p>Invalid order reference.</p>";
    exit();
}

$order_id = (int)$_GET['order_id'];

// Fetch order details
$order_sql = "SELECT address, order_date FROM orders WHERE id = $order_id";
$order_result = mysqli_query($conn, $order_sql);

if (mysqli_num_rows($order_result) !== 1) {
    echo "<p>Order not found.</p>";
    exit();
}

$order = mysqli_fetch_assoc($order_result);

// Fetch ordered items
$items_sql = "SELECT books.title, order_items.quantity 
              FROM order_items 
              JOIN books ON order_items.book_id = books.id 
              WHERE order_items.order_id = $order_id";
$items_result = mysqli_query($conn, $items_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>✅ Order Placed Successfully!</h1>
    <p><strong>Order ID:</strong> <?= $order_id ?></p>
    <p><strong>Shipping Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>

    <h2>📦 Items Ordered:</h2>
    <ul>
        <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
            <li><?= htmlspecialchars($item['title']) ?> — Quantity: <?= $item['quantity'] ?></li>
        <?php endwhile; ?>
    </ul>

    <p><a href="books/view.php">📚 Continue Browsing</a></p>
    <p><a href="user/profile.php">👤 Go to Profile</a></p>
</body>
</html>
