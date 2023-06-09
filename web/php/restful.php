
<?php
/*
    File: restful.php
    Purpose: restful api
    Author: Lee Kai Pui
    Date: 08/07/2022
*/

    include_once("CURD.php");
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        // get the id and table name from the url
        $id = $_GET['id'];
        $table = $_GET['table'];
        // delete the record
        DELETE($table, $id);
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        // get the body of the request
        // decode the body into a php object
        $body = json_decode(file_get_contents('php://input'));

        // return the record
    }
    else
    {
        echo "Invalid request method";
    }
?>