<?php
session_start();
include '../includes/db.php';

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Validate book ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<p>❌ Invalid book ID.</p>");
}

$book_id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM books WHERE id = $book_id");
if (mysqli_num_rows($result) !== 1) {
    die("<p>❌ Book not found.</p>");
}

$book = mysqli_fetch_assoc($result);
$message = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $author      = mysqli_real_escape_string($conn, $_POST['author']);
    $price       = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE books SET 
                title = '$title', 
                author = '$author', 
                price = '$price', 
                description = '$description' 
            WHERE id = $book_id";

    if (mysqli_query($conn, $sql)) {
        $message = "Book updated successfully!";
        $status = "success";
        // Refresh book data
        $book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books WHERE id = $book_id"));
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .editor-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 30px;
            text-align: center;
            color: #00e5ff;
            text-shadow: 0 0 5px #00e5ff;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ccc;
        }

        input, textarea {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 16px;
        }

        input:focus, textarea:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.15);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #00e5ff;
            color: #0f2027;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00bcd4;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #00e5ff;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            font-size: 18px;
            margin-bottom: 20px;
            padding: 16px;
            border-radius: 10px;
            text-align: center;
        }

        .message.success {
            background-color: rgba(0, 255, 0, 0.1);
            color: #00ff99;
            border: 1px solid #00ff99;
        }

        .message.error {
            background-color: rgba(255, 0, 0, 0.1);
            color: #ff6666;
            border: 1px solid #ff6666;
        }
    </style>
</head>
<body>
    <div class="editor-container">
        <h1> Edit Book</h1>

        <?php if ($message): ?>
            <div class="message <?= $status ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

            <label>Author:</label>
            <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($book['price']) ?>" required>

            <label>Description:</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($book['description']) ?></textarea>

            <button type="submit">Update Book</button>
        </form>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>
