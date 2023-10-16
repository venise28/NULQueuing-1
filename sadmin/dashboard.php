<?php 
@include '../database.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('location: index.php'); 
    exit();
}

?>


<!DOCTYPE html>
<html>
<a href="logout.php">Logout</a>
</html>