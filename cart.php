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
<html>
<head>
    <title>Your Cart</title>
</head>
<body>
    <h1>🛍️ Your Shopping Cart</h1>
    <p><a href="books/view.php">Continue Browsing</a> | <a href="logout.php">Logout</a></p>

    <?php
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        $ids = implode(',', array_map('intval', $_SESSION['cart']));
        $sql = "SELECT * FROM books WHERE id IN ($ids)";
        $result = mysqli_query($conn, $sql);

        $total = 0;
        echo "<ul>";
        while ($book = mysqli_fetch_assoc($result)) {
            $total += $book['price'];
            echo "<li>
                    <strong>{$book['title']}</strong> - \${$book['price']}<br>
                    <a href='cart.php?action=remove&id={$book['id']}'>Remove</a>
                  </li><br>";
        }
        echo "</ul>";
        echo "<p><strong>Total:</strong> \$" . number_format($total, 2) . "</p>";
        echo "<p><a href='checkout.php'>Proceed to Checkout</a></p>";
    }
    ?>
</body>
</html>
