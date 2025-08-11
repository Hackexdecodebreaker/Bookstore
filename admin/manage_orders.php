<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by title or author">
    <button type="submit">Search</button>
</form>

<?php
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

$stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No books found.</p>";
} else {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>Title</th><th>Author</th><th>Price</th></tr>";
    while ($book = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$book['title']}</td>";
        echo "<td>{$book['author']}</td>";
        echo "<td>${$book['price']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>



<?php
session_start();
include_once __DIR__ . '../includes/db.php';


// Optional: Check admin role
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

echo "<h2>All Customer Orders</h2>";

$query = "SELECT o.order_id, o.order_date, o.total_amount, u.username 
          FROM orders o 
          JOIN users u ON o.user_id = u.user_id 
          ORDER BY o.order_date DESC";

$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "<p>No orders found.</p>";
} else {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>Order ID</th><th>User</th><th>Date</th><th>Total</th></tr>";
    while ($order = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$order['order_id']}</td>";
        echo "<td>{$order['username']}</td>";
        echo "<td>{$order['order_date']}</td>";
        echo "<td>${$order['total_amount']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<p><a href='admin/dashboard.php'>Back to Admin Dashboard</a></p>";
?>


