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
    <title>Customer Accounts</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/w3.js"></script>
    <link rel="shortcut icon" href="../main.ico" type="image/x-icon">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
      <script>
          let targetId = "";
          function sendDELETE()
          {
              // send a DELETE request to the server
              // and then reload the page
              $.ajax({
                  url: `../php/account_CURD.php?email=${targetId}`,
                  type: "DELETE",
                  data: {
                      id: targetId
                  },
                  success: function(data)
                  {
                      window.location.reload();
                  }
              });

              // close the modal
              $('.modal').modal('toggle');
          }

          function setId(id)
          {
              targetId = id;
          }
      </script>
</head>
<body onload="w3.includeHTML();">
    <?php include_once "./header.php"; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
          <span class="text-primary h1">
          Customer Accounts
          </span>
        </div>
        
        <div class="border p-3 rounded border-primary">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <?php 
                  if($_SESSION["position"] == "Manager")
                  {
                    echo "<th>Action</th>";
                  }
                ?>
                <th scope="col">View Order</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once('../php/conn.php');
              $conn = get_db_connection();
              $sql = "SELECT * FROM `Customer`";
              $result = mysqli_query($conn, $sql);
              $id = 1 ;
              while ($row = mysqli_fetch_assoc($result)) {
                extract($row);
                // http://localhost:9999/pages/order_detail.php?id=2
                echo <<<EOD
                <tr>
                  <th scope="row">$id</th>
                  <td>$customerName</td>
                  <td>$customerEmail</td>
                  <td>$phoneNumber</td>
                EOD;
                  if($_SESSION["position"] == "Manager")
                  {
                    echo <<<EOD
                      <td><a href='#' class='link-danger' data-bs-toggle='modal' data-bs-target='#modal' onclick="setId('$customerEmail')">delete</a></td>
                    EOD;
                  };
                echo <<<EOD
                  <td><a href="./order.php?email=$customerEmail" class="link-info">view</a></td>
                  </tr>
                EOD;
                $id++;
              }
              mysqli_free_result($result);
              mysqli_close($conn);
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                       Are you sure? <br>All related order will be deleted.
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="sendDELETE()">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div w3-include-html="footer.html"></div>
</body>
