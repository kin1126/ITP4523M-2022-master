<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="../../css/bootstrap.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="shortcut icon" href="../..//main.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../js/w3.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        <?php
        session_start();
        if (empty($_SESSION["username"])) {
            header("Location: ../401.html");
            exit;
        }
        ?>
        //add itme to database
        function addItem() {
            if ($("#name").val() == "") {
                alert("Please enter the item name.");
                $("#name").focus();
                return;
            } else if ($("#qty").val() == "") {
                alert("Please enter the item quantity.");
                $("#qty").focus();
                return;
            } else if ($("#price").val() == "") {
                alert("Please enter the item price.");
                $("#price").focus();
                return;
            }
            $.ajax({
                type: "POST",
                url: "../../php/itemController.php",
                data: {
                    itemName: $("#name").val(),
                    itemDescription: $("#desc").val(),
                    stockQuantity: $("#qty").val(),
                    price: $("#price").val()
                },
                success: function(data) {
                    if (data == "Error") {
                        alert("Add item failed.");
                    } else {
                        alert("Add item successfully.");
                        window.location.href = "additem-result.php?itemID=" + data;
                    }
                }
            });
        }
    </script>
</head>

<body onload="w3.includeHTML();">
    <div class="w-100">
        <nav class="navbar navbar-expand-lg py-4" style="background-color:#e4e4e4">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">
                    <img src="../../assert/main.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
                    The Better Limited
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav  me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active text-black" aria-current="page" href="./items.php">
                                Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-black" href="../placeorder.php">
                                Place Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-black" href="../account.php">
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-black" href="../order.php">
                                Orders
                            </a>
                        </li>
                        <?php
                            echo '<li class="nav-item">
                            <a class="nav-link text-black" href="./SalesReport.php">
                                Reports
                            </a>
                            </li>';
                        ?>
                    </ul>
                    <span>
                        <span class="text-black fs-5 mx-3"><?php echo $_SESSION['position'] ?> - <?php echo $_SESSION['staff_name'] ?></span>
                        <a href="../../login.php" class="nav-link d-inline">
                            <span class="text-black">Logout</span>
                        </a>
                    </span>
                </div>
            </div>
        </nav>
    </div>
    <div class="d-flex text-center justify-content-center align-content-center flex-column mb-5 pb-5">
        <div class="mt-5">
            <h1 class="text-center">Add Items</h1>
        </div>
        <div class="mx-auto mt-5 border rounded p-3 border-3" style="width:40%">
            <div class="form-group mb-3">
                <label for="name" class="mb-2">Item Name*</label>
                <textarea type="text" class="form-control" id="name" name="itemName" style="resize:none" rows="2" required placeholder="Item Name"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="desc" class="mb-2">Item Description</label>
                <textarea class="form-control" placeholder="Description of the product ... " id="desc" name="itemDescription" style="resize:none" rows="5"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="qty" class="mb-2">Stock Quantity*</label>
                <input type="number" class="form-control" id="qty" name="stockQuantity" required placeholder="Item Quantity">
            </div>
            <div class="form-group">
                <label for="price" class="mb-2 block">Price*</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="price" name="price" placeholder="price" required>
                </div>
            </div>
            <div>
                <button onclick="addItem();" class="btn btn-primary text-white">Add Item</button>
            </div>
        </div>
    </div>

    <div w3-include-html="../footer.html"></div>
</body>

</html>