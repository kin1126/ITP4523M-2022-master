<?php
/*
    File: itemController.php
    Purpose: restful api for item
    Author: Lee Kai Pui, Pan Rubin
    Date: 05/07/2022
*/

require_once('conn.php');
require_once('helper.php');
check_is_login();
$conn = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	// get item by item id
	if (isset($_GET["itemID"])) {
		$itemID = $_GET["itemID"];
		$sql = "SELECT * FROM item WHERE itemID = '{$itemID}'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) {
			echo "Item not found";
		} else {
			$row = mysqli_fetch_assoc($result);
			$item = $row;
			echo json_encode($item);
		};
	} else if (isset($_GET["name"])) {
		$para = $_GET["name"];
		$sql = "SELECT * FROM item WHERE itemName LIKE '%$para%' ORDER BY itemName;";
		$result = mysqli_query($conn, $sql);
		$items = array();
		if (mysqli_num_rows($result) == 0) {
			echo "Items not found";
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($row["stockQuantity"] <= 0)
				{
					continue;
				}
				$items[] = $row;
			}
			echo json_encode($items);
		};
	} else {
		// get all items
		$sql = "SELECT * FROM item";
		$result = mysqli_query($conn, $sql);
		$items = array();
		if (mysqli_num_rows($result) == 0) {
			echo "Items not found";
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				$items[] = $row;
			}
			echo json_encode($items);
		};
	}
	mysqli_free_result($result);
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (strlen($_POST['itemName']) > 0 && strlen($_POST['price']) > 0 && strlen($_POST['stockQuantity']) > 0) {
		// create item
		extract($_POST);
		$sql = "SELECT max(itemID)+1 AS 'nextid' FROM item;";
		$result = mysqli_query($conn, $sql)
			or die('<div class="error">SQL command fails :<br>' . mysqli_error($conn)) . "</div>";
		$num = mysqli_num_rows($result);
		if ($num == 0) {
			$sql = "SELECT IFNULL(max(itemID), 0)+1 AS `nextid` FROM item;";
			$result = mysqli_query($conn, $sql)
				or die('<div class="error">SQL command fails :<br>' . mysqli_error($conn)) . "</div>";
		}
		$rc = mysqli_fetch_assoc($result);
		$nextid = $rc['nextid'];
		$sql = "INSERT INTO item VALUES ('{$nextid}', '{$itemName}', '{$itemDescription}', '{$stockQuantity}', '{$price}')";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			echo $nextid;
		} else {
			echo "Error";
		};
	} else {
		echo "Error";
	}
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	//find order by item id
	if (isset($_GET["itemID"])) {
		$itemID = $_GET["itemID"];
		$sql = "SELECT orderID FROM itemorders WHERE itemID = '{$itemID}'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) {
			$sql = "DELETE FROM item WHERE itemID = '{$_GET['itemID']}'";
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				$sql = "SELECT itemID FROM itemorders WHERE orderID = '{$row['orderID']}'";
				$res = mysqli_query($conn, $sql);
				$sql = "DELETE FROM itemorders WHERE itemID = '{$_GET['itemID']}'";
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
				$sql = "DELETE FROM item WHERE itemID = '{$_GET['itemID']}'";
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
				if (mysqli_num_rows($res) <= 1) {
					$sql = "DELETE FROM orders WHERE orderID = '{$row['orderID']}'";
					mysqli_query($conn, $sql) or die(mysqli_error($conn));
				}
			}
		};
		mysqli_free_result($result);
		echo "Success";
	} else {
		echo "Error";
	}
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	// update item
	parse_str(file_get_contents('php://input'), $_PUT);
	$sql = "UPDATE item 
					SET itemName='{$_PUT['itemName']}',itemDescription='{$_PUT['itemDescription']}',
							stockQuantity='{$_PUT['stockQuantity']}',price='{$_PUT['price']}' 
					WHERE itemID = '{$_PUT['itemID']}'";
	try {
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo json_encode(
			array(
				"statuscode" => 200,
				"msg" => "Update item successfully."
			)
		);
	} catch (mysqli_sql_exception $e) {
		echo "Update item failed.";
	}
}
mysqli_close($conn);
?>
