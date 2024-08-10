<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');

// Initialize variables for date range and status filter
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
$filter_status = isset($_POST['filter_status']) ? $_POST['filter_status'] : '';

// Query to get products sold within the selected date range
$reportQuery = "
    SELECT prod_name, SUM(prod_qty) as total_quantity, SUM(prod_price * prod_qty) as total_revenue
    FROM rpos_orders
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY prod_name
    ORDER BY total_quantity DESC";

$stmt = $mysqli->prepare($reportQuery);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$reportResults = $stmt->get_result();
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
            <!-- Date Range and Status Filter Form -->
            <div class="row mb-4">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Select Date Range and Status for Daily Report</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="filter_status">Order Status</label>
                                            <select name="filter_status" class="form-control">
                                                <option value="">All</option>
                                                <option value="Paid" <?php if (isset($filter_status) && $filter_status == 'Paid') echo 'selected'; ?>>Paid</option>
                                                <option value="Not Paid" <?php if (isset($filter_status) && $filter_status == 'Not Paid') echo 'selected'; ?>>Not Paid</option>
                                                <option value="Canceled" <?php if (isset($filter_status) && $filter_status == 'Canceled') echo 'selected'; ?>>Canceled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Results -->
            <div class="row mb-4">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Products Sold from <?php echo date('d/M/Y', strtotime($start_date)); ?> to <?php echo date('d/M/Y', strtotime($end_date)); ?></h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th class="text-success" scope="col">Total Quantity Sold</th>
                                        <th scope="col">Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($product = $reportResults->fetch_object()) { ?>
                                        <tr>
                                            <td><?php echo $product->prod_name; ?></td>
                                            <td class="text-success"><?php echo $product->total_quantity; ?></td>
                                            <td>Rs: <?php echo $product->total_revenue; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Records Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Orders Records
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th class="text-success" scope="col">Product</th>
                                        <th scope="col">Unit Price</th>
                                        <th class="text-success" scope="col">#</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Adjust the query to filter by status if selected
                                    $orderQuery = "SELECT * FROM rpos_orders WHERE DATE(created_at) BETWEEN ? AND ?";

                                    if (!empty($filter_status)) {
                                        if ($filter_status == 'Not Paid') {
                                            $orderQuery .= " AND (order_status IS NULL OR order_status = '')";
                                        } else {
                                            $orderQuery .= " AND order_status = ?";
                                        }
                                    }

                                    $orderQuery .= " ORDER BY created_at DESC";

                                    $stmt = $mysqli->prepare($orderQuery);

                                    if (!empty($filter_status) && $filter_status != 'Not Paid') {
                                        $stmt->bind_param('sss', $start_date, $end_date, $filter_status);
                                    } else {
                                        $stmt->bind_param('ss', $start_date, $end_date);
                                    }

                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $total = ($order->prod_price * $order->prod_qty);
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td class="text-success"><?php echo $order->prod_name; ?></td>
                                            <td>Rs: <?php echo $order->prod_price; ?></td>
                                            <td class="text-success"><?php echo $order->prod_qty; ?></td>
                                            <td>Rs: <?php echo $total; ?></td>
                                            <td><?php 
                                                if ($order->order_status == '') {
                                                    echo "<span class='badge badge-danger'>Not Paid</span>";
                                                } elseif ($order->order_status == 'Canceled') {
                                                    echo "<span class='badge badge-warning'>Canceled</span>"; // Orange color for Canceled
                                                } else {
                                                    echo "<span class='badge badge-success'>$order->order_status</span>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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
</body>
</html>
