<?php
session_start();
include '../includes/db.php';

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $author      = mysqli_real_escape_string($conn, $_POST['author']);
    $price       = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle image upload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp  = $_FILES['cover_image']['tmp_name'];
        $image_name = basename($_FILES['cover_image']['name']);
        $target_dir = '../assets/images/';
        $target_file = $target_dir . $image_name;



        
        // Move uploaded file
        if (move_uploaded_file($image_tmp, $target_file)) {
            $image_path = 'assets/images/' . $image_name;

            // Insert into database
            $sql = "INSERT INTO books (title, author, price, description, cover_image) 
                    VALUES ('$title', '$author', $price, '$description', '$image_path')";

            if (mysqli_query($conn, $sql)) {
                $message = "✅ Book added successfully!";
            } else {
                $message = "❌ Error: " . mysqli_error($conn);
            }
        } else {
            $message = "❌ Failed to upload image.";
        }
    } else {
        $message = "❌ Please select a valid image file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Book</title>
    <link rel="stylesheet" href="../assets/admin_form.css">
</head>
<body>
    <div class="form-container">
        <h1>➕ Add New Book</h1>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required>

            <label>Author:</label>
            <input type="text" name="author" required>

            <label>Price ($):</label>
            <input type="number" step="0.01" name="price" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Cover Image:</label>
            <input type="file" name="cover_image" accept="image/*" required>

            <button type="submit">Add Book</button>
        </form>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
