<?php
session_start();
include '../includes/db.php';

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";
$book_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$book_id) {
    $message = "❌ No book ID provided.";
} else {
    $sql = "DELETE FROM books WHERE id = $book_id";
    if (mysqli_query($conn, $sql)) {
        $message = "✅ Book deleted successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <link rel="stylesheet" href="../assets/admin_form.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .card h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .message {
            font-size: 18px;
            margin-bottom: 30px;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-block;
        }

        .message.success {
            background-color: #e6f4ea;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .message.error {
            background-color: #fdecea;
            color: #c62828;
            border: 1px solid #f5c6cb;
        }

        a.button-link {
            display: inline-block;
            padding: 12px 24px;
            background-color: #1976d2;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        a.button-link:hover {
            background-color: #1565c0;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🗑️ Delete Book</h1>
        <p class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>
        <a href="dashboard.php" class="button-link">← Back to Dashboard</a>
    </div>
</body>
</html>
