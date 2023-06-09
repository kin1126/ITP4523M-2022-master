<div class="w-100">
    <nav class="navbar navbar-expand-lg py-4" style="background-color:#e4e4e4">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">
                <img src="../assert/main.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
                The Better Limited
            </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav  me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active text-black" aria-current="page" href="./<?php echo $_SESSION['position']?>/items.php">
                    Items
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-black" href="./placeorder.php">
                    Place Order
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-black" href="./account.php">
                    Customers
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-black" href="./order.php">
                    Orders
                </a>
              </li>

              <?php
                if ($_SESSION["position"] == "Manager") {
                  echo '<li class="nav-item">
                  <a class="nav-link text-black" href="./Manager/SalesReport.php">
                      Reports
                  </a>
                </li>';
                }
              ?>
              
            </ul>
            <span>
                <span class="text-black fs-5 mx-3"><?php echo $_SESSION['position'] ?> - <?php echo $_SESSION['staff_name'] ?></span>
                <a href="../login.php" class="nav-link d-inline">
                    <span class="text-black">Logout</span>
                </a>
            </span>
          </div>
        </div>
    </nav>
</div>