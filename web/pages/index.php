<?php 
    include_once("../php/helper.php");
    check_is_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../main.ico" type="image/x-icon">
    <script src="../js/w3.js"></script>
    <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body onload="w3.includeHTML();">

    <?php include_once "./header.php"; ?>

    <!-- Welcome Back -->
    <div class="d-flex align-items-center flex-column justify-content-center" style="height:80vh">
        <h2>Welcome Back! <?php echo $_SESSION['staff_name'] ?> </h2>
        <div class="list-group w-25 mt-5 mb-5">
            <a class="list-group-item list-group-item-action" href="./<?php echo $_SESSION['position'] ?>/items.php">
                Items
            </a>
            <a class="list-group-item list-group-item-action" href="./placeorder.php">
                Place Order
            </a>
            <a class="list-group-item list-group-item-action" href="./account.php">
                Customers
            </a>
            <a  class="list-group-item list-group-item-action" href="./order.php">
                Orders
            </a>
            <?php 
            if ($_SESSION['position'] == "Manager")
            {
                echo '<a class="list-group-item list-group-item-action" href="./Manager/SalesReport.php">'
                .'Reports'
                .'</a>';
            }
            ?>
        </div>
    </div>
    <div w3-include-html="footer.html"></div>
</body>
</html>