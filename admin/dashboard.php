<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>
    <header>
         Admin Dashboard
    </header>

    <div class="container">
        
        <p><a href="add_book.php" class="action-link"> Add New Book</a></p>

        <h2> Book List</h2>
        <?php
        $sql = "SELECT * FROM books";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr><th>Title</th><th>Author</th><th>Price</th><th>Actions</th></tr>";
            while ($book = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$book['title']}</td>
                        <td>{$book['author']}</td>
                        <td>\${$book['price']}</td>
                        <td>
                            <a href='edit_book.php?id={$book['id']}' class='action-link'>Edit</a> |
                            <a href='delete_book.php?id={$book['id']}' class='action-link'>Delete</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
            echo "<p><a href=\"../user/logout.php\" class=\"action-link\">🚪 Logout</a></p>";
        } else {
            echo "<p>No books found.</p>";
        }
        ?>
    </div>
</body>
</html>
