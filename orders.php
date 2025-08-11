<?php
session_start();
include 'includes/db.php'; // your DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$order_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

echo "<h2>Your Order History</h2>";

while ($order = $order_result->fetch_assoc()) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
    echo "<strong>Order ID:</strong> " . $order['order_id'] . "<br>";
    echo "<strong>Date:</strong> " . $order['order_date'] . "<br>";
    echo "<strong>Total:</strong> $" . $order['total_amount'] . "<br>";

    // Fetch items for this order
    $item_query = "SELECT oi.quantity, b.title, b.price 
                   FROM order_items oi 
                   JOIN books b ON oi.book_id = b.book_id 
                   WHERE oi.order_id = ?";
    $item_stmt = $conn->prepare($item_query);
    $item_stmt->bind_param("i", $order['order_id']);
    $item_stmt->execute();
    $item_result = $item_stmt->get_result();

    echo "<ul>";
    while ($item = $item_result->fetch_assoc()) {
        echo "<li>" . $item['title'] . " - Qty: " . $item['quantity'] . " - $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "</div>";
}
?>
