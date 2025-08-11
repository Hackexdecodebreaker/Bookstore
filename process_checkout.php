<?php
session_start();
include 'includes/db.php';

// Step 1: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Step 2: Check if cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

// Step 3: Validate shipping address
if (!isset($_POST['address']) || empty(trim($_POST['address']))) {
    echo "<p>Shipping address is required.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];
$address = mysqli_real_escape_string($conn, $_POST['address']);
$cart = $_SESSION['cart'];

// Step 4: Insert order into `orders` table
$order_sql = "INSERT INTO orders (user_id, address, order_date) VALUES ($user_id, '$address', NOW())";
if (mysqli_query($conn, $order_sql)) {
    $order_id = mysqli_insert_id($conn);

    // Step 5: Insert each item into `order_items` table
    foreach ($cart as $book_id => $quantity) {
        $book_id = (int)$book_id;
        $quantity = (int)$quantity;

        $item_sql = "INSERT INTO order_items (order_id, book_id, quantity) VALUES ($order_id, $book_id, $quantity)";
        mysqli_query($conn, $item_sql);
    }

    // Step 6: Clear cart and redirect
    unset($_SESSION['cart']);
    header("Location: order_success.php?order_id=$order_id");
    exit();
} else {
    echo "<p>Failed to place order. Please try again.</p>";
}
?>
