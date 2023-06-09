<?php

require_once('conn.php');
require_once('helper.php');
check_is_login();
$conn = get_db_connection();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// Order ID
	// Customer Name
	// Order Date
	// Total Amount
	if (isset($_GET['staffID']) && isset($_GET['month'])) {
		$staffID = $_GET['staffID'];
		$month = $_GET['month'];
		$sql = "SELECT `orderID`,`customerName`, `dateTime` AS `orderDate`, `orderAmount` AS `totalAmount`
						FROM `orders`,`customer` 
						WHERE `staffID` = '{$staffID}' 
						AND orders.customerEmail = customer.customerEmail 
						AND `dateTime` LIKE '{$month}-%'";
		$result = mysqli_query($conn, $sql);
		if(mysqli_num_rows($result) > 0) {
			$orders = array();
			while ($row = mysqli_fetch_assoc($result)) {
				$orders[] = $row;
			}
			echo json_encode($orders);
		} else {
			echo "No record found";
		}
	} else if (isset($_GET['month'])) {
		// Staff ID
		// Staff Name
		// Orders
		// Total Amount

		$month = $_GET['month'];
		$sql = "SELECT staff.staffID, staffName, 
	COUNT(orderID) AS 'noOfOrders', SUM(orderAmount) 
	AS 'totalAmount' FROM staff, orders
	WHERE orders.staffID = staff.staffID 
	AND dateTime LIKE '{$month}-%' GROUP BY staffID";
		$result = mysqli_query($conn, $sql);
		$rec = array();
		if (mysqli_num_rows($result) == 0) {
			echo "Record not found";
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				$rec[] = $row;
			}
			echo json_encode($rec);
		};
		mysqli_free_result($result);
		mysqli_close($conn);
	}
}
?>
