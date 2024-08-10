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
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0 text-white">Weekly Report</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-12">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Orders (Last 7 Days)</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th class="text-success" scope="col">Code</th>
                    <th scope="col">Customer</th>
                    <th class="text-success" scope="col">Product</th>
                    <th scope="col">Unit Price</th>
                    <th class="text-success" scope="col">Qty</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th class="text-success" scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_orders WHERE created_at >= NOW() - INTERVAL 7 DAY ORDER BY created_at DESC";
                  $stmt = $mysqli->prepare($ret);
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
                      <td><?php if ($order->order_status == '') {
                            echo "<span class='badge badge-danger'>Not Paid</span>";
                          } else {
                            echo "<span class='badge badge-success'>$order->order_status</span>";
                          } ?></td>
                      <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-xl-12">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Payments (Last 7 Days)</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th class="text-success" scope="col">Code</th>
                    <th scope="col">Amount</th>
                    <th class="text-success" scope="col">Order Code</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM rpos_payments WHERE created_at >= NOW() - INTERVAL 7 DAY ORDER BY created_at DESC";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($payment = $res->fetch_object()) {
                  ?>
                    <tr>
                      <th class="text-success" scope="row"><?php echo $payment->pay_code; ?></th>
                      <td>Rs: <?php echo $payment->pay_amt; ?></td>
                      <td class="text-success"><?php echo $payment->order_code; ?></td>
                      <td><?php echo date('d/M/Y g:i', strtotime($payment->created_at)); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>
</html>
