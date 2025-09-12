<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="2;url=login.php">
    <title>Logging Out...</title>
    <link rel="stylesheet" href="../assets/logout.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
            background-color: #f4f4f4;
        }
        .message {
            background-color: #fff;
            display: inline-block;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>👋 You have been logged out.</h2>
        <p>Redirecting to login page..., <a href="user/login.php">click here</a> if you are not redirected.</p>
        <p>If you're not redirected, <a href="user/login.php">click here</a> to login again.</p>
    </div>
</body>
</html>