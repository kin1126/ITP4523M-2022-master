<?php
/*
    File: helper.php
    Purpose: helper functions for backend
    Author: Lee Kai Pui
    Date: 05/07/2022
*/
function check_is_login()
{
    // if the user is not logged in, redirect to 401.html (unauthorized)
    session_start();
    if (empty($_SESSION["username"]))
    {
        header("Location: ./401.html");
        exit;
    }
    
}
?>