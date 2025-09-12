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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Online Bookstore</title>
    <style>
        /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap');

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
            color: #ffffff;
            line-height: 1.6;
            padding: 40px 20px;
        }

        /* Main container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(64, 224, 208, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: fadeIn 0.8s ease-out;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #40E0D0, #48D1CC, #20B2AA);
            border-radius: 20px 20px 0 0;
        }

        /* Welcome heading */
        h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            color: #40E0D0;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 10px rgba(64, 224, 208, 0.3);
        }

        /* User info paragraphs */
        p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            padding: 15px 20px;
            background: rgba(64, 224, 208, 0.1);
            border-radius: 12px;
            border-left: 4px solid #40E0D0;
            transition: all 0.3s ease;
        }

        p:hover {
            background: rgba(64, 224, 208, 0.15);
            transform: translateX(5px);
        }

        /* Strong text styling */
        strong {
            color: #40E0D0;
            font-weight: 600;
        }

        /* Quick Navigation heading */
        h2 {
            font-family: 'Poppins', sans-serif;
            color: #40E0D0;
            font-size: 1.5rem;
            margin: 30px 0 20px 0;
            text-align: center;
        }

        /* Links styling */
        a {
            display: block;
            width: 100%;
            padding: 15px 20px;
            background: rgba(64, 224, 208, 0.1);
            color: #ffffff;
            text-decoration: none;
            border-radius: 12px;
            border: 2px solid rgba(64, 224, 208, 0.2);
            font-weight: 500;
            font-size: 1.05rem;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(64, 224, 208, 0.1), transparent);
            transition: left 0.5s;
        }

        a:hover::before {
            left: 100%;
        }

        a:hover {
            background: rgba(64, 224, 208, 0.2);
            border-color: #40E0D0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(64, 224, 208, 0.3);
            color: #40E0D0;
        }

        /* Logout link special styling */
        a[href*="logout"] {
            background: rgba(255, 107, 107, 0.1);
            border-color: rgba(255, 107, 107, 0.3);
            margin-top: 20px;
        }

        a[href*="logout"]:hover {
            background: rgba(255, 107, 107, 0.2);
            border-color: #ff6b6b;
            color: #ff6b6b;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        /* Role badge */
        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #40E0D0, #48D1CC);
            color: #0f2027;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 10px;
        }

        /* Admin role special color */
        .admin-role {
            background: linear-gradient(135deg, #FFD700, #FFA500) !important;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }
            
            .container {
                padding: 30px 20px;
                border-radius: 15px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            p, a {
                padding: 12px 16px;
                font-size: 1rem;
            }
        }

        /* Animation for page load */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 Hello, <?= htmlspecialchars($user['name']) ?>!</h1>
        
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p>
            <strong>Role:</strong> 
            <?= htmlspecialchars($user['role']) ?>
            <span class="role-badge <?= $user['role'] === 'admin' ? 'admin-role' : '' ?>">
                <?= ucfirst($user['role']) ?>
            </span>
        </p>

        <h2>Quick Navigation</h2>
        
        <?php if ($user['role'] === 'customer'): ?>
            <p><a href="../books/view.php">📚 Browse Books</a></p>
            <p><a href="../cart.php">🛒 View Cart</a></p>
            <p><a href="../orders.php">📦 Order History</a></p>
        <?php elseif ($user['role'] === 'admin'): ?>
            <p><a href="../admin/dashboard.php">🛠️ Admin Dashboard</a></p>
            <p><a href="../books/manage.php">📖 Manage Books</a></p>
            <p><a href="../admin/users.php">👥 Manage Users</a></p>
            <p><a href="../admin/orders.php">📋 View All Orders</a></p>
        <?php endif; ?>

        <p><a href="../logout.php">🚪 Logout</a></p>
    </div>
</body>
</html>