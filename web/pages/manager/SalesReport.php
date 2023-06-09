<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="../../css/bootstrap.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="shortcut icon" href="../..//main.ico" type="image/x-icon">
    <script src="../../js/w3.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
    </script>
    <script type="text/javascript">
        <?php
        session_start();
        if (empty($_SESSION["username"])) {
            header("Location: ../401.html");
            exit;
        }
        ?>
        let staff = [];
        let orders = [];
        let dummyData = [];
        let selectedMonth = "";
        let myChart = null;

        $(document).ready(
            function() {
                curMonth = new Date().getMonth() + 1;
                if (curMonth < 10) {
                    curMonth = "0" + curMonth;
                }
                selectedMonth = new Date().getFullYear() + "-" + curMonth;
                getByMonth(selectedMonth);
                $('#datepicker').find("input").val(selectedMonth);
                $('#datepicker').datepicker({
                    format: "yyyy-mm",
                    viewMode: "months",
                    minViewMode: "months",
                    endDate: "0",
                    autoclose: true,
                    todayHighlight: true,
                    orientation: "bottom auto",
                    todayBtn: "linked",
                    defaultDate: new Date()
                }).on('changeDate', function(e) {
                    getByMonth(e.format());
                    selectedMonth = e.format();
                    $("#orderRecord").css("display", "none");
                    $(".staff-info").val("");
                })
            }
        );

        function initChart() {
            let barColors = ["#00bcd4", "#ff9800", "#2196f3", "#ffeb3b", "#e91e63", "#673ab7", "#009688",
                "#795548", "#607d8b", "#9e9e9e"
            ];

            myChart = new Chart("sales", {
                type: "doughnut",
                data: {
                    labels: staff,
                    datasets: [{
                        backgroundColor: barColors,
                        data: orders
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        position: "bottom",
                    }
                }
            });
            var ctx = document.getElementById("sales").getContext("2d");
            $("#sales").click(
                function(evt) {
                    var activePoints = myChart.getElementsAtEvent(evt);
                    // set the information on the form
                    if (activePoints[0]) {
                        var chartData = activePoints[0]["_chart"].config.data;
                        var idx = activePoints[0]["_index"];
                        var label = chartData.labels[idx];
                        var value = chartData.datasets[0].data[idx];
                        var infos = $(".staff-info");
                        infos[0].value = dummyData[idx].staffID;
                        infos[1].value = dummyData[idx].staffName;
                        infos[2].value = dummyData[idx].noOfOrders;
                        infos[3].value = dummyData[idx].totalAmount;
                        getOrders();
                    }
                }
            );
        }

        function showOrder() {
            var x = document.getElementsByClassName("order")[0];
            if (x.style.display === "none") {
                x.style.display = "block";
                getOrders();
            } else {
                x.style.display = "none";
            }
        }

        function getByMonth(month) {
            selectedMonth = month;
            $.ajax({
                url: "../../php/MonthlyReport.php",
                type: "GET",
                dataType: "json",
                data: {
                    month: month
                },
                success: function(data) {
                    staff = [];
                    orders = [];
                    for (let i = 0; i < data.length; i++) {
                        let staffID = data[i].staffID;
                        let staffName = data[i].staffName;
                        let noOfOrders = data[i].noOfOrders;
                        let totalAmount = data[i].totalAmount;
                        dummyData = data;
                        staff[i] = staffID;
                        orders[i] = noOfOrders;
                    }
                    if (myChart != null) {
                        myChart.destroy();
                    }
                    initChart();
                },
                error: function(err) {
                    if (myChart != null) {
                        myChart.destroy();
                    }
                }
            });
        }

        function getOrders() {
            $.ajax({
                url: "../../php/MonthlyReport.php",
                type: "GET",
                dataType: "json",
                data: {
                    month: selectedMonth,
                    staffID: $("#staffID").val()
                },
                success: function(data) {
                    let code = "";
                    for (let i = 0; i < data.length; i++) {
                        let orderID = data[i].orderID;
                        let cusName = data[i].customerName;
                        let orderDate = data[i].orderDate;
                        let totalAmount = data[i].totalAmount;
                        code +=
                            `<tr>
                            <th scope="row">${orderID}</th>
                            <td>${cusName}</td>
                            <td>${orderDate}</td>
                            <td>$${totalAmount}</td>
                            <td><a href="../order_detail.php?id=${orderID}" class="link-info">Detail</a></td>
                            </tr>`;
                    }
                    $("tbody").html(code);
                }
            });
        }
    </script>
</head>

<body onload="w3.includeHTML();" style="overflow-x:hidden">
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
                            <a class="nav-link text-black" href="#">
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
    <div class="row mb-5 pb-3">
        <div class="col-4">
            <div class="my-5 mx-5">
                <div class="h1 text-center">Sales Chart</div>
                <canvas id="sales"></canvas>
            </div>
        </div>
        <div class="col-7" style="margin-left:50px">
            <!--
                    1.	Staff ID
                    2.	Staff Name
                    3.	Number of order records from each staff in that month
                    4.	Total sales amount from each staff in that month
                 -->
            <div class="w-100 my-5">
                <div class="h1 text-center my-5">Sales Records</div>
                <div class="row form-group">
                    <label for="date" class="col-sm-2 col-form-label mb-5">Month</label>
                    <div class="col-sm-10">
                        <div class="input-group date" id="datepicker">
                            <input type="text" class="form-control">
                            <span class="input-group-append row">
                                <span class="input-group-text bg-white">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-2 col-form-label">Staff ID</label>
                    <div class="col-sm-10">
                        <input class="form-control staff-info" id="staffID" type="text" value="" readonly>
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-2 col-form-label">Staff Name</label>
                    <div class="col-sm-10">
                        <input class="form-control staff-info" type="text" value="" readonly>
                    </div>

                </div>
                <div class="mb-5 row">
                    <label class="col-sm-2 col-form-label">Number of Order Records</label>
                    <div class="col-sm-8">
                        <input class="form-control staff-info" type="text" value="" readonly>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary text-white form-control" onclick="showOrder()">
                            view order
                        </button>
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-2 col-form-label">Total Sales Amount</label>
                    <div class="col-sm-10">
                        <input class="form-control staff-info" type="text" value="" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div id="orderRecord" class="mx-auto order mx-5" style="width: 90%;display: none;">
            <div class="h2 text-center">
                Sales Orders
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Customer's Name</th>
                            <th scope="col">Order Date & Time</th>
                            <th scope="col">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div w3-include-html="../footer.html"></div>
</body>

</html>