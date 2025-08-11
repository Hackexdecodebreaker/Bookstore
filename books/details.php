<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid book ID.</p>";
    exit();
}

$book_id = intval($_GET['id']);
$sql = "SELECT * FROM books WHERE id = $book_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $book = mysqli_fetch_assoc($result);
} else {
    echo "<p>Book not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="stylesheet" href="../assets/details.css">
</head>
<body>
    <div class="container">
        <div class="book-details-card">
            <div class="book-cover">
                <img src="../<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover of <?= htmlspecialchars($book['title']) ?>">
            </div>
            <div class="book-info">
                <h1><?= htmlspecialchars($book['title']) ?></h1>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p><strong>Price:</strong> $<?= number_format($book['price'], 2) ?></p>
                <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($book['description'])) ?></p>

                <div class="actions">
                    <a href="../cart.php?action=add&id=<?= $book['id'] ?>" class="btn">🛒 Add to Cart</a>
                    <a href="view.php" class="btn alt">🔙 Back to Book List</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
