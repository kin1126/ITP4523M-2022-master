<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Items</title>
  <link rel="stylesheet" href="../../css/bootstrap.css">
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="shortcut icon" href="../..//main.ico" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../../js/w3.js"></script>
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
  </script>
</head>
<script type="text/javascript">
  <?php
  session_start();
  if (empty($_SESSION["username"])) {
    header("Location: ../401.html");
    exit;
  }
  ?>
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
							</tr>`
        }
        body.append(code);
      },
      error: function(err) {
        console.log(err);
      }
    });
  }

  function getByID(id) {
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
        $("#data #name").text(name);
        $("#data #desc").text(desc);
        $("#data #qty").text(qty);
        $("#data #price").text(price);
      },
      error: function(err) {
        console.log(err);
      }
    });
  }

  $(document).ready(function() {
    getAll();
  });
</script>

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
  <div class="container my-5 pb-5">
    <div class="d-flex justify-content-between mb-3">
      <span class="text-primary h1">Goods</span>
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
          <h5 class="modal-title" id="exampleModalLabel">
            Goods Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="fs-5">Goods Name :</div>
          <div id="name"></div>
        </div>
        <div class="modal-body">
          <div class="fs-5">Description :</div>
          <div id="desc"></div>
        </div>
        <div class="modal-body">
          <div class="fs-5">Stock :</div>
          <div id="qty"></div>
        </div>
        <div class="modal-body">
          <div class="fs-5">Price :</div>
          <div id="price"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div w3-include-html="../footer.html"></div>
</body>

</html>