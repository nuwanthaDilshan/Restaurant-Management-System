<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php
        require_once('partials/_topnav.php');
        ?>
        <!-- Header -->
        <div style="background-image: url(assets/img/theme/hotel-1191718_1920.jpg); background-size: cover; background-position: center center;" class="header  pb-8 pt-5 pt-md-8">
        <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Form to select report type -->
            <div class="row mb-4">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <form method="POST">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="report_type">Select Report Type</label>
                                        <select id="report_type" name="report_type" class="form-control" onchange="toggleDateRange()">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="range">Date Range</option>
                                        </select>
                                    </div>
                                    <div id="date-range-fields" class="form-group col-md-8" style="display: none;">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                        <label for="end_date">End Date</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" name="generate_report" class="btn btn-success">Generate Report</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Payment Reports
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Payment Code</th>
                                        <th scope="col">Payment Method</th>
                                        <th class="text-success" scope="col">Order Code</th>
                                        <th scope="col">Amount Paid</th>
                                        <th class="text-success" scope="col">Date Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;

                                    if (isset($_POST['generate_report'])) {
                                        $report_type = $_POST['report_type'];

                                        if ($report_type == 'daily') {
                                            $query = "SELECT * FROM rpos_payments WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC";
                                        } elseif ($report_type == 'weekly') {
                                            $query = "SELECT * FROM rpos_payments WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) ORDER BY created_at DESC";
                                        } elseif ($report_type == 'range') {
                                            $start_date = $_POST['start_date'];
                                            $end_date = $_POST['end_date'];
                                            $query = "SELECT * FROM rpos_payments WHERE DATE(created_at) BETWEEN ? AND ? ORDER BY created_at DESC";
                                        } else {
                                            $query = "SELECT * FROM rpos_payments ORDER BY created_at DESC";
                                        }

                                        $stmt = $mysqli->prepare($query);

                                        if ($report_type == 'range') {
                                            $stmt->bind_param('ss', $start_date, $end_date);
                                        }

                                        $stmt->execute();
                                        $res = $stmt->get_result();

                                        while ($payment = $res->fetch_object()) {
                                            $total += $payment->pay_amt; // Calculate the total amount paid
                                    ?>
                                            <tr>
                                                <th class="text-success" scope="row">
                                                    <?php echo $payment->pay_code; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $payment->pay_method; ?>
                                                </th>
                                                <td class="text-success">
                                                    <?php echo $payment->order_code; ?>
                                                </td>
                                                <td>
                                                    Rs: <?php echo $payment->pay_amt; ?>
                                                </td>
                                                <td class="text-success">
                                                    <?php echo date('d/M/Y g:i', strtotime($payment->created_at)); ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (isset($_POST['generate_report'])) { ?>
                            <div class="card-footer">
                                <h4>Total Amount Paid: Rs. <?php echo $total; ?></h4>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php
            require_once('partials/_footer.php');
            ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
    <script>
        function toggleDateRange() {
            var reportType = document.getElementById('report_type').value;
            var dateRangeFields = document.getElementById('date-range-fields');
            if (reportType == 'range') {
                dateRangeFields.style.display = 'block';
            } else {
                dateRangeFields.style.display = 'none';
            }
        }
    </script>
</body>
</html>
