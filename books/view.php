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
        $sql = "SELECT * FROM books ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($book = mysqli_fetch_assoc($result)) {
                // Escape output to prevent XSS
                $id = htmlspecialchars($book['id']);
                $title = htmlspecialchars($book['title']);
                $author = htmlspecialchars($book['author']);
                $price = htmlspecialchars($book['price']);
                $cover = !empty($book['cover_image']) ? "../" . htmlspecialchars($book['cover_image']) : "../assets/default_cover.jpg";
                $created = date("F j, Y", strtotime($book['created_at']));

                echo "
                <div class='book-card'>
                    <div class='book-image'>
                        <img src='$cover' alt='Cover of $title'>
                    </div>
                    <div class='book-info'>
                        <h3>$title</h3>
                        <p>by $author</p>
                        <p>\$$price</p>
                        <p><em>Added on $created</em></p>
                        <a href='details.php?id=$id' class='details-btn'>View Details</a>
                        <a href='../cart.php?action=add&id=$id' class='cart-btn'>Add to Cart</a>
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
