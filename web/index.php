<?php 
    // re-direct to the login page if the user is not logged in
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
    }
    else 
    {
        header("Location: ./pages/index.php");
    }
?>