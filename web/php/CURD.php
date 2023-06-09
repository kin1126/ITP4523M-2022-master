<?php 
/*
    File: CURD.php 
    Purpose: CURD operation for all tables
    Author: Lee Kai Pui
    Date: 05/07/2022
*/

    function GETALL($table)
    {
        include_once("../php/conn.php");
        include_once("../php/helper.php");
        
        check_is_login();
    
        $conn = get_db_connection();
        $sql = "SELECT * FROM $table";
        $result = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result))
        {
            $rows[] = $row;
        }

        mysqli_free_result($result);
        mysqli_close($conn);

        return $rows;
    }

    function GET($table, $id)
    {
        include_once("../php/conn.php");
        include_once("../php/helper.php");
        
        check_is_login();
    
        $conn = get_db_connection();
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);


        mysqli_free_result($result);
        mysqli_close($conn);


        return $row;
    }

    function DELETE($table, $id)
    {
        include_once("../php/conn.php");
        include_once("../php/helper.php");
        
        check_is_login();
    
        $conn = get_db_connection();
        $sql = "DELETE FROM $table WHERE id = $id";
        $result = mysqli_query($conn, $sql);


        mysqli_free_result($result);
        mysqli_close($conn);
        
        return $result;
    }


?>