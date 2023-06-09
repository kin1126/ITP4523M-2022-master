<?php
include_once("conn.php");
include_once("helper.php");
check_is_login();
$conn = get_db_connection();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	extract($_POST);
	//create customer or update customer
	$sql = "SELECT * FROM customer WHERE customerEmail = '{$customerEmail}' ";
	$result = mysqli_query($conn, $sql);
	$rc = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) == 0) {
		$sql = "INSERT INTO customer VALUES ('{$customerEmail}', '{$customerName}', NULLIF('{$phoneNumber}',''))";
	} else if ($rc['customerName'] != $customerName || $rc['phoneNumber'] != $phoneNumber) {
		$sql = "UPDATE customer 
							SET customerName = '{$customerName}', phoneNumber = NULLIF('{$phoneNumber}','')
							WHERE customerEmail = '{$customerEmail}'";
	}
	$result = mysqli_query($conn, $sql);

	//create order
	$sql = "SELECT max(CAST(orderID AS SIGNED))+1 AS 'nextid' FROM orders;";
	$result = mysqli_query($conn, $sql)
		or die('<div class="error">SQL command fails :<br>' . mysqli_error($conn)) . "</div>";
	$num = mysqli_num_rows($result);
	if ($num == 0) {
		$sql = "SELECT IFNULL(max(CAST(orderID AS SIGNED)), 0)+1 AS `nextid` FROM orders;";
		$result = mysqli_query($conn, $sql)
			or die('<div class="error">SQL command fails :<br>' . mysqli_error($conn)) . "</div>";
	}
	$rc = mysqli_fetch_assoc($result);
	$nextid = (int)$rc['nextid'];
	if (isset($_POST['deliveryDate']) && isset($_POST['deliveryAddress'])) {
		$sql = "INSERT INTO orders VALUES ('{$nextid}', '{$customerEmail}', '{$_SESSION['username']}', default, NULLIF('{$deliveryAddress}',''), NULLIF('{$deliveryDate}',''), '{$orderAmount}')";
	} else {
		$sql = "INSERT INTO orders VALUES ('{$nextid}', '{$customerEmail}', '{$_SESSION['username']}', default, default, default, '{$orderAmount}')";
	}
	mysqli_query($conn, $sql);

	$ois = json_decode($orderItems, true);
	foreach ($ois as $item) {
		//create order items
		$sql = "INSERT INTO itemorders VALUES ('{$nextid}', '{$item['oiID']}', '{$item['qty']}', '{$item['soldPrice']}')";
		mysqli_query($conn, $sql);
		//update stock
		$sql = "UPDATE item SET stockQuantity = stockQuantity - '{$item['qty']}' WHERE itemID = '{$item['oiID']}'";
		mysqli_query($conn, $sql);
	}

	if ($result) {
		echo $nextid;
	} else {
		echo "Error";
	};
	mysqli_free_result($result);
	mysqli_close($conn);
}
?>