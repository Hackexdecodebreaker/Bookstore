<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="2;url=index.php">
    <title>Logging Out...</title>
</head>
<body>
    <p>👋 You have been logged out. Redirecting to homepage...</p>
    

    <p>If you are not redirected, <a href="login.php">click here</a> to login again.</p>
    <?php
session_start();
session_unset();
session_destroy();
sleep(2);
?>
    <?php header("location:login.php"); 
    exit();
    ?>
</body>
</html>
