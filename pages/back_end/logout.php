<?php 
ob_start();
session_start();
$_SESSION['pseudo'] = '';
unset($_SESSION['user_id']);
unset($_SESSION['user_type']);
session_destroy();
//echo $_SESSION['pseudo'];
header('location: ../login.php');
?>