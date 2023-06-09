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
  <title>Orders</title>
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/w3.js"></script>
  <link rel="shortcut icon" href="../main.ico" type="image/x-icon">
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <?php
  include_once("../php/conn.php");
  $conn = get_db_connection();
  ?>

  <script>
    function perform_search() {
      $.ajax({
        url: `../php/order_CURD.php?email=${$("input[name=email]").val()}`,
        type: "GET",
        success: function(data) {
          $("#result").html(data);
        }
      }).send();
    }
  </script>
</head>

<body onload="w3.includeHTML();">
  <?php include_once "./header.php"; ?>
  <div class="mt-5 ">
    <div class="search w-75 mx-auto">
      <div class="mb-3 text-center text-primary h1">
        Order
      </div>
      <form class="d-flex g-3 flex-row justify-content-center align-content-center" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="Get">
        <div class="w-75">
          <!-- insert value into placeholder if ?email={} is set -->
          <input list="mydatalist" type="text" class="form-control p-3 rounded-5" placeholder="Enter some keyword here..." name="email" oninput="perform_search()"
                  value="<?php if (isset($_GET["email"])) echo $_GET["email"] ?>">
        </div>
        <div class="mt-2 mx-5">
          <button type="submit" class="btn btn-primary mb-3 text-white">Search</button>
        </div>
      </form>
    </div>
  </div>

  <div class="border my-5"></div>

  <div class="container my-5 pb-5">
    <div class="border p-3 rounded border-primary">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col"><a class="text-black" href="<?php echo $_SERVER["PHP_SELF"] ?>?orderby=orderId<?php if(isset($_GET['email'])) echo "&email=".$_GET['email'];  ?>">Order ID</a></th>
            <th scope="col"><a class="text-black" href="<?php echo $_SERVER["PHP_SELF"] ?>?orderby=customerName<?php if(isset($_GET['email'])) echo "&email=".$_GET['email'] ;  ?>">Customer's Name</a></th>
            <th scope="col">Order Date & Time</th>
          </tr>
        </thead>
        <tbody id="result">
          <?php 
          $sql = "";
          // if email and orderby is set, search by email and order by $_GET['orderby']
          if (isset($_GET["email"]) && isset($_GET['orderby']))
          {
            $orderby = $_GET['orderby'];
            $sql =  "SELECT `Customer`.`customerName`, `Orders`.* FROM `Customer` INNER JOIN `Orders` ON `Orders`.`customerEmail` = `Customer`.`customerEmail` WHERE `Customer`.`customerEmail` = '" . $_GET["email"] . "' ORDER BY  `" . $orderby . "` ASC";
          }
          // search by email
          else if (isset($_GET["email"])) {
            $sql = "SELECT `Customer`.`customerName`, `Orders`.* FROM `Customer` INNER JOIN `Orders` ON `Orders`.`customerEmail` = `Customer`.`customerEmail` WHERE `Customer`.`customerEmail` = '" . $_GET["email"] . "' ORDER BY  `Customer`.`customerName` ASC";
          }
          // order by
          else if (isset($_GET['orderby']))
          {
            $orderby = $_GET['orderby'];
            $sql = "SELECT `Customer`.`customerName`, `Orders`.* FROM `Customer` INNER JOIN `Orders` ON `Orders`.`customerEmail` = `Customer`.`customerEmail` ORDER BY `" . $orderby . "` ASC";
          } 
          // show all
          else {
            $sql = "SELECT `Customer`.`customerName`, `Orders`.* FROM `Customer` INNER JOIN `Orders` ON `Orders`.`customerEmail` = `Customer`.`customerEmail` ORDER BY `Customer`.`customerName` ASC;";
          }
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $id = $row["orderID"];
              echo "<tr>";
              echo "<th scope='row'>" . $id . "</td>";
              echo "<td>" . $row["customerName"] . "</td>";
              echo "<td>" . $row["dateTime"] . "</td>";
              echo "<td><a href='./order_detail.php?id=$id' class='link-info'>Detail</a></td>";
              echo "</tr>";
            }
          }

          mysqli_free_result($result);
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <div w3-include-html="./footer.html"></div>
</body>

</html>