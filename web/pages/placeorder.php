<?php 
    include_once("../php/helper.php");
    check_is_login();
?>
<?php 
    include_once("../php/conn.php");
    $conn = get_db_connection();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Place Order</title>
        <link rel="stylesheet" href="../css/bootstrap.css" />
        <link rel="stylesheet" href="../css/style.css" />
        <script src="../js/w3.js"></script>
        <!-- JavaScript Bundle with Popper -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
            crossorigin="anonymous"
        ></script>
        <!-- cdn of jquery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <link rel="shortcut icon" href="../main.ico" type="image/x-icon">
        <style>
            .list-group-item {
                padding: 30px;
            }
            .placeorder {
                position: absolute;
                top: 90%;
                height: 100px;
            }

            .pointer {
                cursor: pointer;
            }

            .itemid {
                display: none;
            }
        </style>
        <script>

            cart = [];          // the ids of the items in the cart
            total = 0;
            total_price = 0;
            
            function check_enough_stock(id, qty)
            {
                // get the stock quantity from hidden input
                const stock = $(`input[name=${id}_stock]`).val();
                // default
                quantity = 1;
                
                // if the item is in the cart, get the quantity from the cart card
                // and determine the quantity to be ordered
                if ($("#"+id+"_qty").text() != "")
                {
                    quantity = parseInt($(`#${id}_qty`).text()) + qty;
                }


                if(stock - quantity < 0)
                {
                    alert("Not enough stock");
                    return false;
                }
                else
                {
                    return true;
                }
            }

            function changeQty(id , qty , price)
            {
                if (!check_enough_stock(id, qty)) return;

                // check if the quantity will be 0 after change
                curr_qty = parseInt($("#" + id + "_qty").text());
                // the cart could not have qty with <= 0
                if (curr_qty + qty < 0) return; 
                
                // remove the element if the quantity is 0
                if (curr_qty + qty == 0)
                {
                    $("#"+id+"_card").remove();
                    cart = cart.filter(item => item !== id.toString())
                    // remove the item from hidden form data
                    $("#form_data input[name="+id+"]").remove();
                }

                // add the quantity
                real_qty = curr_qty  + qty;
                // and display to the user and update the hidden input
                $("#" + id + "_qty").text(real_qty ); 
                $("#cart_form input[name=" + id + "]").val(real_qty);

                
                // update the total price
                if (qty < 0)
                {
                    total_price -= parseInt(price);
                }
                else
                {
                    total_price += parseInt(price);
                }
                // get the discount from api
                $.ajax({
                    url:"http://127.0.0.1:8080/api/discountCalculator?discount="+(total_price),
                    type:"GET",
                    dataTpye : "json",
                    crossDomin: true,
                    success:function(data)
                    {
                        new_price = (total_price) * (1 - data.discount);
                        new_price = Math.floor(new_price * 100) / 100;
                        
                        if (data.discount != 0)
                        {
                            // disable the new_price to be some many float
                            $("#price").html(new_price+`<span class='fs-6 mx-3'>discount: ${data.discount*100}%<span>`);
                        }
                        else 
                        {
                            $("#price").html(new_price);
                        }
                        $("#price_modal").text(new_price);
                    }
                })

            }


            function addToCart(itemid , item_name , price ) {
                if (!check_enough_stock(itemid,1)) return;
                
                // check if the item is already in the cart
                var isInCart = false;
                for (var i = 0; i < cart.length; i++) {
                    if (cart[i] == itemid) {
                        isInCart = true;
                        break;
                    }
                }
                if (isInCart)
                {
                    changeQty(itemid, 1, price);
                    return;
                }
                
                // add the cart
                cart.push(itemid);

                changeQty(itemid, 1, price);
                
                // create the card and put it in shopping cart
                var listGroup = document.getElementsByClassName("list-group");
                // add item to list-group
                var item = document.getElementById(itemid);
                var z = document.createElement("div"); // is a node
                z.innerHTML = `
                <li class="list-group-item" id="${itemid}_card">
                    <h5 class="card-title">${item_name}</h5>
                    <div class="itemid">1000</div>
                    <div class="float-end">
                        <svg onclick="changeQty(${itemid} , -1 , ${price});" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-circle mx-3 pointer text-primary" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/> 
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        <p class="card-text d-inline qty"  id="${itemid}_qty">1</p>
                        <svg onclick="changeQty(${itemid} , 1 , ${price});" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle mx-3 pointer text-primary" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </div>
                </li>
            `;
                listGroup[0].appendChild(z);
                // add item to the form cart
                $("#form_data").append(`<input type='hidden' name='${itemid}'  value='${1}'>`)
               
            }
            

            function search()
            {
                // get the searching result from server and display it
                $.ajax({
                    accepts: "application/json",
                    method: "GET",
                    dataType:"json",
                    url: "../php/itemController.php?name="+$("input[name=name]").val(),
                    success: (data) => {
                        // the server return "Items not found" if no item found
                        str = "";
                        // reset the item in the list
                        $("#items_list").html("")
                        
                        for(i = 0 ; i < data.length ; i++)
                        {
                            name = data[i].itemName;
                            // replace all the '"' to ' ' to avoid the error of the html
                            name = name.replace(/\"/g, " ");
                            str += `
                                <div class="card col-3 mx-2 my-2" style="width: 18rem">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                ${name}
                                            </h5>
                                            <input type="hidden" value="${data[i].stockQuantity}" name="${data[i].itemID}_stock">
                                            <a
                                                href="#"
                                                class="btn btn-primary text-white"
                                                onclick="addToCart('${data[i].itemID}' , '${name}' , '${data[i].price}')"
                                                >Add to cart</a
                                            >
                                        </div>
                                    </div>
                            `;
                        }
                        $("#items_list").html(str)
                    },
                    error: (err) => {
                        $("#items_list").html("")
                    }
                });
            }

            function clearCart() {
                var cart_ = document.getElementById("cart");
                cart_.innerHTML = "";
                cart = [];
                $("#price").text("0");
                $("#price_modal").text("0");
                // delete the form data except the submitted button
                $("#form_data").find("input:not([type=button]):not([type=submit]):not([type=reset])").remove();
                total_price = 0;
                total = 0;
            }


            function check_cart_isempty()
            {
                if (cart.length == 0)
                {
                    alert("Your cart is empty");
                    // prevent the form from submitting
                }
                else 
                {
                    $("#confirm_modal"). modal("toggle");
                }
            }
        </script>
    </head>
    <body onload="w3.includeHTML();">
        
        <?php include "header.php"; ?>
        <div class="m-5 py-3 border-bottom">
            <span class="h2">Place Order</span>
            <a href="#">
                <button
                    class="btn btn-primary float-end"
                    onclick="check_cart_isempty()"
                >
                    <span class="text-white">Next</span>
                </button>
            </a>

            <span class="h2 float-end mx-3"> $ <span id="price">0</span> </span>
            <span class="h2 float-end mx-3"> Total: </span>
        </div>
        <div class="row w-100 mb-5 pb-5" style="height: 75vh" style="position: relative">
            <div class="col-3 h-100 mx-5" style="margin-left: 25px;">
                <div
                    class="d-flex align-content-center justify-content-between mb-3"
                >
                    <h3>Shopping cart</h3>
                    <button class="btn btn-secondary" onclick="clearCart();">
                        <a
                            href="#"
                            class="text-white text-decoration-none"
                            >Clear</a
                        >
                    </button>
                </div>
                <div class="border border-1" style="height:90%; overflow-y:auto">
                    <ul class="list-group w-100 " id="cart">
                    </ul>
                </div>
            </div>
            <div class="col h-100">
                <div class="d-flex justify-content-between">
                    <h3>Items</h3>
                    <div class="d-flex" role="search">
                        <input
                            class="form-control me-2"
                            type="text"
                            placeholder="Search"
                            name="name"
                            aria-label="Search"
                            oninput="search()"
                        />
                    </div>
                </div>

                <div class="row mt-4" id="items_list" style="overflow-y:auto">
                <?php 
                    $sql = "SELECT * FROM `Item` ORDER BY itemName ASC";
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res))
                    {
                        // do not displace the item if the stock is 0
                        if ($row["stockQuantity"] <= 0)
                        {
                            continue;
                        }
                        $item_name = $row['itemName'];
                        $item_name =  str_replace("\"", " ", $item_name);
                        echo <<<EOF
                            <div class="card col-3 mx-2 my-2" style="width: 18rem">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {$row['itemName']}
                                    </h5>
                                    <input type="hidden" value="{$row['stockQuantity']}" name="{$row['itemID']}_stock">
                                    <a
                                        href="#"
                                        class="btn btn-primary text-white"
                                        onclick="addToCart('{$row['itemID']}' , '$item_name' , '{$row['price']}')"
                                        >Add to cart</a
                                    >
                                </div>
                            </div>
                        EOF;
                    }
                ?>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="confirm_modal"
            tabindex="-1"
            aria-labelledby="confirm_modal_label"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirm_modal_label">
                            Are you sure?
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="fs-5">Total Price : $ <span id="price_modal">0</span> </div>
                    </div>
                    <div class="modal-footer">
                        <!-- the hidden form that send to next pages -->
                        <form action="./placeorder-2.php" method="POST" id="cart_form">
                            <div class="form-check" id="form_data">
                                <input type="submit" value="OK" type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div w3-include-html="footer.html"></div>
    </body>
</html>
