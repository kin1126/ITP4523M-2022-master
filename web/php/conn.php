<?php 
/*
    File: conn.php 
    Purpose: connect to database
    Author: Lee Kai Pui
    Date: 19/06/2022
*/

    $host_name = "127.0.0.1";
    $database = "projectdb";
    $user_name = "root";
    $password = "";

    function get_db_connection() {
        global $host_name, $database, $user_name, $password;
        return mysqli_connect($host_name, $user_name, $password, $database) ;
    };
?>