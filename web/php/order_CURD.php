<?php 
/*
    File: order_CURD.php
    Purpose: CURD operation for order
    Author: Lee Kai Pui
    Date: 05/07/2022
*/

    include_once("conn.php");
    include_once("helper.php");

    check_is_login();
    $conn = get_db_connection();
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        // get the id and table name from the url
        $id = $_GET['id'];

        // delete the order line
        $sql = "DELETE FROM `ItemOrders` WHERE `orderID` = $id";
        mysqli_query($conn, $sql);

        // delete the orders 
        $sql = "DELETE FROM `Orders` WHERE `orderID` = '$id'";
        mysqli_query($conn, $sql);
        exit();
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        // get the body of the request
        // decode the body into a php object
        $body = json_decode(file_get_contents('php://input'));
        
        // return the record
        $id = $body->orderId;
        $address = $body->deliveryAddress;
        $date = $body->deliveryDate;

        $sql = "UPDATE `Orders` SET `deliveryAddress` = '$address' , `deliveryDate` = '$date' WHERE `orderID` = '$id'";
        mysqli_query($conn, $sql);

    }
    else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["email"]) )
    {
        $email = $_GET["email"];
        $sql = "SELECT `Customer`.`customerName`, `Orders`.* FROM `Customer` INNER JOIN `Orders` ON `Orders`.`customerEmail` = `Customer`.`customerEmail` WHERE `Customer`.`customerEmail` LIKE '%$email%'";
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $id = $row["orderID"];
                echo "<tr>";
                echo "<th scope='row'>".$id."</td>";
                echo "<td>".$row["customerName"]."</td>";
                echo "<td>".$row["dateTime"]."</td>";
                echo "<td><a href='./order_detail.php?id=$id' class='link-info'>Detail</a></td>";
                echo "</tr>";
            }
        }
    }
    else
    {
        echo "Invalid request method";        
    }

    if (isset($result))
    {
        mysqli_free_result($result);
    }
    if (isset($conn))
    {
        mysqli_close($conn);
    }
?>