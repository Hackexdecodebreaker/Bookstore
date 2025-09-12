<?php
session_start();
include 'includes/db.php';

// Restrict access to customers only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    if (!in_array($book_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $book_id;
    }
    header("Location: cart.php");
    exit();
}

// Handle remove from cart
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    $_SESSION['cart'] = array_diff($_SESSION['cart'], [$book_id]);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Bookstore</title>
    <link rel="stylesheet" href="assets/cart.css">
</head>
<body>
    <div class="container">
        <h1>🛍️ Your Shopping Cart</h1>
        
        <div class="nav-links">
            <a href="books/view.php">Continue Browsing</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="cart-container">
            <?php
            if (empty($_SESSION['cart'])) {
                echo "<div class='empty-cart'>
                        <p>Your cart is empty.</p>
                        <p>Start browsing our collection to add books!</p>
                      </div>";
            } else {
                $ids = implode(',', array_map('intval', $_SESSION['cart']));
                $sql = "SELECT * FROM books WHERE id IN ($ids)";
                $result = mysqli_query($conn, $sql);

                $total = 0;
                echo "<ul class='cart-items'>";
                while ($book = mysqli_fetch_assoc($result)) {
                    $total += $book['price'];
                    echo "<li class='cart-item'>
                            <div class='item-info'>
                                <div class='item-title'>{$book['title']}</div>
                                <div class='item-price'>$" . number_format($book['price'], 2) . "</div>
                                <div class='item-actions'>
                                    <a href='cart.php?action=remove&id={$book['id']}' class='remove-btn'>Remove from Cart</a>
                                </div>
                            </div>
                          </li>";
                }
                echo "</ul>";
                
                echo "<div class='cart-total'>
                        <div class='total-amount'>Total: $" . number_format($total, 2) . "</div>
                        <a href='checkout.php' class='checkout-btn'>Proceed to Checkout</a>
                      </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>