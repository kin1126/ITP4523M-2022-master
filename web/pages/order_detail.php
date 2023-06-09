<?php 
    include_once("../php/helper.php");
    include_once("../php/conn.php");
    check_is_login();
?>
<?php 
    if (!isset($_GET["id"]))
    {
        include_once("../php/http_helper.php");
        redirect("./order.php");
        exit();
    }
?>

<?php
    $id = $_GET["id"];
    $sql = "
    SELECT `Customer`.*, `ItemOrders`.*, `Orders`.*, `Staff`.*, `Item`.*
        FROM `Customer`, `ItemOrders` 
        INNER JOIN `Orders` ON `ItemOrders`.`orderID` = `Orders`.`orderID` 
        INNER JOIN `Staff` ON `Orders`.`staffID` = `Staff`.`staffID` 
        INNER JOIN `Item` ON `ItemOrders`.`itemID` = `Item`.`itemID`
        WHERE `Orders`.`orderID` =  $id AND 
            `Customer`.`customerEmail` = `Orders`.`customerEmail`
        ORDER BY `Item`.`itemName` ASC;
    ";
    
    $conn = get_db_connection();
    // get all the necessary data from the database
    $result = $conn->query($sql);
    $rows = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $result->free();
    $conn->close();


    /*
        1.	Order ID
        2.	Customer’s Email
        3.	Customer’s Name
        4.	Customer’s Phone Number
        5.	Staff ID
        6.	Staff Name
        7.	Order Date & Time
        8.	Delivery Address
        9.	Delivery Date
        10.	Item ID
        11.	Item Name
        12.	Order Quantity
        13.	Total Amount
    */

    $total = $rows[0]["orderAmount"];
    $orderId = $rows[0]['orderID'];
    $email = $rows[0]['customerEmail'];
    $name = $rows[0]['customerName'];
    $phone = $rows[0]['phoneNumber'];
    $staffId = $rows[0]['staffID'];
    $staffName = $rows[0]['staffName'];
    $orderDate = $rows[0]['dateTime'];
    $deliveryAddress = $rows[0]['deliveryAddress'];
    $deliveryDate = $rows[0]['deliveryDate'];
    $total = $rows[0]['orderAmount'];
    $need_delivery = $deliveryDate != null && $deliveryAddress != null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../main.ico" type="image/x-icon">
    <script src="../js/w3.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
        .list-group-item {
            padding:30px;
        }
        .placeorder {
            position:absolute;
            top:90%;
            height:100px;
        }

        .pointer {
            cursor: pointer;
        }

        .itemid {
            display:none;
        }
    </style>
    <script>
        function printReceipt() {
            // make the print button disappear
            // make the header and footer disappear
            var disappearElements = document.getElementsByClassName("disappear-print");
            for (var i = 0; i < disappearElements.length; i++) {
                disappearElements[i].style.display = "none";
            }
            // expansion the accordion if it is collapsed
            var accordion = document.getElementsByClassName("accordion-button")[0];
            if (accordion.classList.contains("collapsed")) {
                accordion.click();
            }
            window.print();

            // wait for the print to finish
            for (var i = 0; i < disappearElements.length; i++) {
                disappearElements[i].style.display = "block";
            }
        }

        <?php 
        // show the function if the order needs delivery
        if ($need_delivery) {
            echo <<<EOF
                let isEdit = false;
            
                function allowEdit() {
                    var editElements = document.getElementsByClassName("edit");

                    if (!isEdit)
                    {
                        for (var i = 0; i < editElements.length; i++) {
                            editElements[i].disabled = false;
                            editElements[i].removeAttribute("readonly");
                            // set focus to the first input
                            if (i == 0) {
                                editElements[i].focus();
                            }
                        }
                        document.getElementById("edit_prompt").innerHTML = "Save";
                        isEdit = true;
                    }
                    else 
                    {
                        $.ajax(
                        {
                            url: "../php/order_CURD.php",
                            type: "PUT",
                            dataTpye: "json",
                            data: JSON.stringify({
                                orderId:  "$id",
                                deliveryDate: $.trim($("input[name=delivery_date]").val()),
                                deliveryAddress: $.trim($("input[name=delivery_address]").val())
                            }),
                            success: function(data) {
                                location.reload();
                            },
                            error: function(data) {
                                alert(data);
                            }
                        }).send();


                        for (var i = 0; i < editElements.length; i++) {
                            editElements[i].disabled = true;
                            editElements[i].removeAttribute("readonly");
                        }
                        document.getElementById("edit_prompt").innerHTML = "Edit";
                        isEdit = false;
                    }
                }
            EOF;
        }
        ?>

        function sendDELETE()
        {
            // send the delete request to the server
            // return to previous page
            $.ajax(
            {
                url: "../php/order_CURD.php?id=<?php echo $id ?>",
                type: "DELETE",
                dataTpye: "json",
                success: function(data) {
                    $('.modal').modal('toggle');
                    location.href = "./order.php";
                },
                error: function(data) {
                    alert(data);
                }
            }).send();
        }
    </script>
</head>
<body onload="w3.includeHTML();">
    <?php include_once "./header.php"; ?>

    <div class="m-5 py-3 border-bottom disappear-print">
        <!-- Go to previous page (url) by using php. Therefore, no resend form data -->
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>" class="btn btn-secondary px-3 mx-1 mb-2 p-2 rounded shadow">
            Back
        </a>
        <button class="h2 btn btn-primary text-white px-3 mx-1 p-2 rounded shadow" onclick="printReceipt()">
            Print
        </button>
        <?php 
        if ($need_delivery)
        {
            echo <<<EOF
                <button class="h2 btn btn-dark text-white px-3 p-2 mx-1 rounded shadow" onclick="allowEdit()">
                    <span id="edit_prompt">Edit</span>
                </button>
            EOF;
        }
        ?>
        <button class="h2 btn btn-danger text-white px-3 mx-1 p-2 rounded shadow" data-bs-toggle="modal" data-bs-target="#modal">
            Delete
        </button>
        
        <span class="h2 float-end mx-3">
            $ <?php echo $total ?>
        </span>
        <span class="h2 float-end mx-3">
            Total: 
        </span>
    </div>

    <div class="w-75 mx-auto my-5">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h3 class="accordion-header" id="panels-h1">
                    <button class="accordion-button fs-3" type="button" data-bs-toggle="collapse" data-bs-target="#panels-open" aria-expanded="true" aria-controls="panels-open">
                        Sales Order Items
                    </button>
                </h3>
                <div id="panels-open" class="accordion-collapse collapse show" aria-labelledby="panels-h1">
                  <div class="accordion-body">
                    <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Qty</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php 
                                for($i = 1 ; $i <= count($rows) ; $i++)
                                {
                                    echo <<<EOF
                                        <tr>
                                            <th scope="row">$i</th>
                                            <td>{$rows[$i-1]['itemName']}</td>
                                            <td>{$rows[$i-1]['soldPrice']}</td>
                                            <td>{$rows[$i-1]['orderQuantity']}</td>
                                        </tr>
                                    EOF;
                                };
                            ?>
                        </tbody>
                      </table>
                  </div>
                </div>
              </div>
        </div>

        <div class="sub-form border rounded mt-5">
                <h3 class="mx-4 mt-5">Sales Order Details</h3>
                <div class="order-form m-4">
                    <div class="mb-3">
                        <label for="id" class="form-label">Order ID</label>
                        <input readonly disabled type="text" id="id" name="id" class="form-control" value="<?php echo $orderId ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Customer Email</label>
                        <input disabled readonly type="text" class="form-control" id="email" value="<?php echo $email ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Customer Name</label>
                        <input disabled readonly type="text" class="form-control" id="email" value="<?php echo $name ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Customer Phone</label>
                        <input disabled readonly type="number" class="form-control" id="email" value="<?php echo $phone ?>">
                    </div>
                    <div class="mb-3">
                        <label for="staticEmail" class="form-label">Staff ID</label>
                        <input type="text" readonly class="form-control" id="staticEmail" value="<?php echo $staffId ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="staticEmail" class="form-label">Staff Name</label>
                        <input type="text" readonly class="form-control" id="staticEmail" value="<?php echo $staffName ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="staticEmail" class="form-label">Order Date</label>
                        <input type="text" readonly class="form-control" id="staticEmail" value="<?php echo $orderDate ?>" disabled>
                    </div>
                    <?php
                    $string = <<<EOF
                        <div class="mb-3 delivery" id="delivery-picker">
                            <label for="delivery-date" class="form-label">Delivery Date</label>
                            <input readonly disabled type="date" class="form-control edit" id="delivery-date" name="delivery_date" value="$deliveryDate">
                        </div>
                        <div class="mb-3 delivery">
                            <label for="delivery-address" class="form-label" >Delivery Address</label>
                            <input readonly disabled type="text" class="form-control edit" id="delivery-address" name="delivery_address" value="$deliveryAddress">
                        </div>
                    EOF;

                    if ($need_delivery)
                    {
                        echo $string;
                    }
                    ?>
                </div>
            </div>
            <div style="margin-bottom:100px;">

            </div>

        </div>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    Are you sure?<br>All related record will be deleted.
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="sendDELETE()">Ok</button>
            </div>
        </div>
    </div>
    <div w3-include-html="footer.html" class="disappear-print"></div>
</body>
</html>