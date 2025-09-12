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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Bookstore</title>
    <link rel="stylesheet" href="assets/order.css">
</head>
<body>
    <div class="container">
        <h1 class="page-title">📚 Your Order History</h1>
        
        <div class="nav-links">
            <a href="books/view.php" class="back-btn">← Back to Books</a>
            <a href="cart.php">View Cart</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="orders-container">
            <?php
            if ($order_result->num_rows == 0) {
                echo "<div class='empty-orders'>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start browsing our collection!</p>
                      </div>";
            } else {
                while ($order = $order_result->fetch_assoc()) {
                    echo "<div class='order-card'>";
                    
                    // Order Header
                    echo "<div class='order-header'>";
                    echo "<div class='order-info'>
                            <div class='order-label'>Order ID</div>
                            <div class='order-value order-id'>#" . $order['order_id'] . "</div>
                          </div>";
                    echo "<div class='order-info'>
                            <div class='order-label'>Order Date</div>
                            <div class='order-value order-date'>" . date('M d, Y', strtotime($order['order_date'])) . "</div>
                          </div>";
                    echo "<div class='order-info'>
                            <div class='order-label'>Total Amount</div>
                            <div class='order-value order-total'>$" . number_format($order['total_amount'], 2) . "</div>
                          </div>";
                    echo "</div>";

                    // Fetch items for this order
                    $item_query = "SELECT oi.quantity, b.title, b.price 
                                   FROM order_items oi 
                                   JOIN books b ON oi.book_id = b.id 
                                   WHERE oi.order_id = ?";
                    $item_stmt = $conn->prepare($item_query);
                    $item_stmt->bind_param("i", $order['order_id']);
                    $item_stmt->execute();
                    $item_result = $item_stmt->get_result();

                    echo "<div class='order-items-title'>Order Items:</div>";
                    echo "<ul class='order-items'>";
                    while ($item = $item_result->fetch_assoc()) {
                        echo "<li class='order-item'>
                                <div class='item-details'>
                                    <div class='item-title'>" . htmlspecialchars($item['title']) . "</div>
                                    <div class='item-quantity'>Quantity: " . $item['quantity'] . "</div>
                                </div>
                                <div class='item-price'>$" . number_format($item['price'], 2) . "</div>
                              </li>";
                    }
                    echo "</ul>";
                    echo "</div>"; // Close order-card
                }
            }
            ?>
        </div>
    </div>
</body>
</html>