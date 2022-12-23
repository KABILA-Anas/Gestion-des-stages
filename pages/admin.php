<?php
    ob_start();
    session_start();
    if( empty($_SESSION['user_id']) )
    {
      $_SESSION['page'] = $_SERVER['REQUEST_URI'];
      header('location: login.php');
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
</head>
<body>
    <h1>WELCOME TO ADMIN PAGE</h1>
    <a href="back_end/logout.php">Log out</a>
</body>
</html>