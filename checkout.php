<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>🧾 Checkout</h1>
    <form method="POST" action="process_checkout.php">
        <label>Shipping Address:</label>
        <input type="text" name="address" required>
        <button type="submit">Confirm Order</button>
    </form>
</body>
</html>
