<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Books</title>
    <link rel="stylesheet" href="../assets/view.css">
</head>
<body>
    <header>
        📚 Browse Books
    </header>

    <div class="container">
        <p><a href="../user/logout.php" class="action-link">🚪 Logout</a></p>

        <div class="book-grid">
        <?php
        $sql = "SELECT * FROM books";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($book = mysqli_fetch_assoc($result)) {
                echo "
                <div class='book-card'>
                    <div class='book-image'>
                        <img src='../{$book['cover_image']}' alt='Cover of {$book['title']}'>
                    </div>
                    <div class='book-info'>
                        <h3>{$book['title']}</h3>
                        <p>by {$book['author']}</p>
                        <p>\${$book['price']}</p>
                        <a href='details.php?id={$book['id']}' class='details-btn'>View Details</a>
                        <a href='../cart.php?action=add&id={$book['id']}' class='cart-btn'>Add to Cart</a>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No books available.</p>";
        }
        ?>
        </div>
    </div>
</body>
</html>
