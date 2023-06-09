<?php 
/*
    File: account_CURD.php
    Purpose: CURD operation for accounts
    Author: Lee Kai Pui
    Date: 05/07/2022
*/

    include_once("conn.php");
    include_once("helper.php");

    check_is_login();
    $conn = get_db_connection();
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        
        $email = $_GET['email'];

        // delete the record related to the customer 
        $sql = "SELECT * FROM `Orders` WHERE `customerEmail` = '$email'";
        $order_res = mysqli_query($conn, $sql);

        while ($order = mysqli_fetch_assoc($order_res))
        {
            $orderID = $order["orderID"];

            // delete the item in the order
            $sql = "SELECT * FROM `ItemOrders` WHERE `orderID` = '$orderID'";
            $order_item_res = mysqli_query($conn, $sql);
            while ($item = mysqli_fetch_assoc($order_item_res))
            {
                $itemID = $item["itemID"];
                $sql = "DELETE FROM `ItemOrders` WHERE `itemID` = '$itemID'";
                mysqli_query($conn, $sql);
            }
            
            // delete the order after deleting the item in the order
            $sql = "DELETE FROM `Orders` WHERE `orderID` = $orderID";
            mysqli_query($conn, $sql);
        }

        // delete customer info after all related orders are deleted
        $sql = "DELETE FROM `Customer` WHERE customerEmail = '$email'";
        $result = mysqli_query($conn, $sql);
    }


    mysqli_free_result($result);
    mysqli_close($conn);
?>