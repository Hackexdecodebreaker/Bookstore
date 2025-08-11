<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore</title>
    <link rel="stylesheet" href="assets/style.css"> <!-- Optional -->
</head>
<body>
    <h1>📚 Welcome to the Online Bookstore</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Logged in as <strong><?php echo $_SESSION['role']; ?></strong></p>

        <?php
        // Redirect admin to dashboard
        if ($_SESSION['role'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit();
        }
        ?>

        <!-- Show books to customers -->
        <h2>Available Books</h2>
        <?php
        $sql = "SELECT * FROM books";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<ul>";
            while ($book = mysqli_fetch_assoc($result)) {
                echo "<li><strong>" . $book['title'] . "</strong> by " . $book['author'] . " - $" . $book['price'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No books available at the moment.</p>";
        }
        ?>

        <p><a href="user/logout.php">Logout</a></p>

    <?php else: ?>
        <p><a href="user/login.php">Login</a> | <a href="user/register.php">Register</a></p>
    <?php endif; ?>
</body>
</html>
