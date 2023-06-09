<?php 
    session_start();
    if ($_SESSION["position"] != "Manager")
    {
        header("Location: ../401.html");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Items</title>
    <link rel="stylesheet" href="../../css/bootstrap.css" />
    <link rel="stylesheet" href="../../css/style.css" />
    <script src="../../js/w3.js"></script>
    <link rel="shortcut icon" href="../..//main.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        <?php
        if (empty($_SESSION["username"])) {
            header("Location: ../401.html");
            exit;
        }
        ?>

        let onEdit = false;
        let itemID = "";

        function removeItem(id) {
            itemID = id;
        }

        function editItem() {
            // get all textarea and input
            // remove attribute disabled
            if (!onEdit) {
                editableForm();
            } else {
                resetForm();
                updateItem(itemID);
            }
        }

        function editableForm() {
            $("#data").find("textarea").removeAttr("disabled");
            $("#data").find("input").removeAttr("disabled");
            $("#data").find("#edit").text("Save");
            onEdit = true;
        }

        function resetForm() {
            $("#data").find("textarea").attr("disabled", true);
            $("#data").find("input").attr("disabled", true);
            $("#data").find("#edit").text("Edit");
            onEdit = false;
        }

        $('#data').on('hidden.bs.modal', function() {
            resetForm();
        });

        $(document).ready(function() {
            getAll();
        });

        //request get all items from database
        function getAll() {
            $.ajax({
                url: "../../php/itemController.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let body = $("tbody");
                    let code = "";
                    for (let i = 0; i < data.length; i++) {
                        let item = data[i];
                        let id = item.itemID;
                        let name = item.itemName;
                        let desc = item.itemDescription;
                        let qty = item.stockQuantity;
                        let price = item.price;
                        code +=
                            `<tr>
                                <th scope="row">${id}</th>
                                <td>${name}</td>
                                <td>${qty}</td>
                                <td>${price}</td>
                                <td><a href="#" onclick="getByID(${id})" class="link-info" data-bs-toggle="modal" data-bs-target="#data">details</a></td>
                                <td><a href="#" class="link-danger" data-bs-toggle="modal" data-bs-target="#modal" onclick="removeItem(${id})">delete</a></td>
							</tr>`
                    }
                    body.append(code);
                }
            });
        }

        function getByID(id) {
            itemID = id;
            $.ajax({
                url: "../../php/itemController.php",
                type: "GET",
                dataType: "json",
                data: {
                    itemID: id
                },
                success: function(data) {
                    let item = data;
                    let id = item.itemID;
                    let name = item.itemName;
                    let desc = item.itemDescription;
                    let qty = item.stockQuantity;
                    let price = item.price;
                    $("#data #name").val(name);
                    $("#data #desc").val(desc);
                    $("#data #qty").val(qty);
                    $("#data #price").val(price);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function sendDelete(id) {
            //delete item from database
            $.ajax({
                url: `../../php/itemController.php?itemID=${itemID}`,
                type: "DELETE",
                success: function(data) {
                    if (data == "Success") {
                        alert("Item deleted");
                        window.location.reload();
                    } else {
                        alert("Failed to delete item");
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        //request to update item data to database
        function updateItem(id) {
            $.ajax({
                url: "../../php/itemController.php",
                type: "PUT",
                dataType: "json",
                data: {
                    itemID: id,
                    itemName: $("#data #name").val(),
                    itemDescription: $("#data #desc").val(),
                    stockQuantity: $("#data #qty").val(),
                    price: $("#data #price").val()
                },
                success: function(data) {
                    alert("Item updated");
                    window.location.reload();
                },
                error: function(err) {
                    console.log(err);
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
                if ($_SESSION["position"] == "Manager") {
                  echo '<li class="nav-item">
                  <a class="nav-link text-black" href="./SalesReport.php">
                      Reports
                  </a>
                </li>';
                }
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
    <div class="container my-5 pb-5 mg-5">
        <div class="d-flex justify-content-between mb-3">
            <span class="text-primary h1">Goods</span>
            <a href="./additems.php">
                <button class="btn btn-primary text-white fs-6 py-3">
                    Add Item
                </button>
            </a>
        </div>

        <div class="border p-3 rounded border-primary">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Price</th>
                        <th scope="col">Detail</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="item_detail_label">
                        Goods Detail
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="fs-5">Goods Name :</div>
                    <textarea class="form-control" id="name" required disabled></textarea>
                </div>
                <div class="modal-body">
                    <div class="fs-5">Description :</div>
                    <textarea class="form-control" id="desc" disabled></textarea>
                </div>
                <div class="modal-body">
                    <div class="fs-5">Stock :</div>
                    <input type="number" id="qty" class="form-control" required value="" disabled />
                </div>
                <div class="modal-body">
                    <div class="fs-5">Price :</div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">$</span>
                        <input type="number" id="price" class="form-control" required disabled value="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="editItem();" id="edit">
                        Edit
                    </button>
                    <button type="button" id="closeBtn" class="btn btn-secondary" onclick="resetForm();" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form id="updateForm" method="post" action="../../php/itemController.php">
        <input type="hidden" name="itemID" value="">
        <input type="hidden" name="itemName" value="">
        <input type="hidden" name="itemDescription" value="">
        <input type="hidden" name="stockQuantity" value="">
        <input type="hidden" name="price" value="">
    </form>
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        Are you sure to delete this item? <br>
                        All related records will be deleted.
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="sendDelete()">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <div w3-include-html="../footer.html"></div>
</body>