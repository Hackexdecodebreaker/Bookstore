<?php
session_start();
include '../includes/db.php';

// Restrict access to logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, role FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "<p>User not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
    <h1>👤 Hello, <?= htmlspecialchars($user['name']) ?>!</h1>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>

    <?php if ($user['role'] === 'customer'): ?>
        <p><a href="../books/view.php">📚 Browse Books</a></p>
        <p><a href="../cart.php">🛒 View Cart</a></p>
        <p><a href="../orders.php">📦 Order History</a></p>
    <?php elseif ($user['role'] === 'admin'): ?>
        <p><a href="../admin/dashboard.php">🛠️ Admin Dashboard</a></p>
    <?php endif; ?>

    <p><a href="../logout.php">🚪 Logout</a></p>
</body>
</html>
