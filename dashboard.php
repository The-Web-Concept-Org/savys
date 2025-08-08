<!doctype html>
<html lang="en">
<?php include_once 'includes/head.php';
// $current_date = date('Y-m-d');
// $d = strtotime("last Day");

// $yesterday_date = date("Y-m-d", $d);
// $previous_week = strtotime("-1 week +1 day");
// $start_week = strtotime("last sunday midnight", $previous_week);
// $end_week = strtotime("next saturday", $start_week);

// $start_week = date("Y-m-d", $start_week);
// $end_week = date("Y-m-d", $end_week);
// $d = strtotime("today");
// $last_start_week = strtotime("last sunday midnight", $d);
// $last_end_week = strtotime("next saturday", $d);
// $start = date("Y-m-d", $last_start_week);
// $end = date("Y-m-d", $last_end_week);
// $start_of_month = date('Y-m-01', strtotime(date('Y-m-d')));
// // Last day of the month.
// $end_of_month = date('Y-m-t', strtotime($current_date));
$current_date = date('Y-m-d');
$d = strtotime("last Day");

$yesterday_date = date("Y-m-d", $d);
$previous_week = strtotime("-1 week +1 day");
$start_week = strtotime("last sunday midnight", $previous_week);
$end_week = strtotime("next saturday", $start_week);

$start_week = date("Y-m-d", $start_week);
$end_week = date("Y-m-d", $end_week);
$d = strtotime("today");
$last_start_week = strtotime("last sunday midnight", $d);
$last_end_week = strtotime("next saturday", $d);
$start = date("Y-m-d", $last_start_week);
$end = date("Y-m-d", $last_end_week);
$start_of_month = date('Y-m-01', strtotime(date('Y-m-d')));
// Last day of the month.
$end_of_month = date('Y-m-t', strtotime($current_date));


// 
// 
$date_select = '';
if (isset($_REQUEST['orderdate']) && $_REQUEST['orderdate'] !== '') {
  $selectedOption = $_POST['orderdate'];

  switch ($selectedOption) {
    case 'today':
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '" . date('Y-m-d') . "'";
      break;

    case 'yesterday':
      $yesterday = date('Y-m-d', strtotime('-1 day'));
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '$yesterday'";
      break;

    case 'last7days':
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime('-7 days')) . "'";
      break;

    case 'last30days':
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime('-30 days')) . "'";
      break;

    case 'thismonth':
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m') = '" . date('Y-m') . "'";
      break;

    case 'lastmonth':
      $lastMonth = date('Y-m', strtotime('last month'));
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m') = '$lastMonth'";
      break;

    default:
      // Handle the default case (e.g., when no option is selected)
      $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '" . date('Y-m-d') . "'";
      break;
  }
} elseif (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && empty($_REQUEST['end_date'])) {

  $start_date =  $_REQUEST['start_date'];

  $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '$start_date'";
} elseif (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && isset($_REQUEST['end_date'])) {

  $start_date = $_REQUEST['start_date'];

  $end_date = $_REQUEST['end_date'];

  $date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
} else {

  $date_select = " AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '" . date('Y-m-d') . "'";
}


?>

<body class="horizontal light  ">
  <div class="wrapper">
    <?php include_once 'includes/header.php'; ?>
    <main role="main" class="main-content">
      <div class="container-fluid px-3">
        <div class="row align-items-center mb-2 px-3">
          <div class="col-4 d-flex align-items-center ">
            <button type="button" class="btn btn-primary  filter_btn" data-toggle="modal"
              data-target="#modalCookie1"><i class="fa fa-filter"></i></button>
          </div>
          <div class="col-8 justify-content-end d-flex align-items-center">
            <div class="w-75 justify-content-end d-flex align-items-center">
              <?php
              if (isset($_REQUEST['orderdate']) && $_REQUEST['orderdate'] !== '') {
                $selectedOption = $_POST['orderdate'];

                if ($selectedOption == 'today') {
                  // Handle the case for Today
                  $selectedOption = 'Today';
                  echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $selectedOption</h5>";
                } elseif ($selectedOption == 'yesterday') {
                  // Handle the case for Yesterday
                  $selectedOption = 'Yesterday';
                  echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $selectedOption</h5>";
                } elseif ($selectedOption == 'last7days') {
                  // Handle the case for Last 7 Days
                  $selectedOption = 'Last 7 Days';
                  echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $selectedOption</h5>";
                } elseif ($selectedOption == 'last30days') {
                  // Handle the case for Last 30 Days
                  $selectedOption = 'Last 30 Days';
                  echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $selectedOption</h5>";
                } elseif ($selectedOption == 'thismonth') {
                  // Handle the case for This Month
                  $selectedOption = 'This Month';
                  echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $selectedOption</h5>";
                } elseif ($selectedOption == 'lastmonth') {
                  // Handle the case for Last Month
                  $selectedOption = 'Last Month';
                  echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $selectedOption</h5>";
                }
              } elseif (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && empty($_REQUEST['end_date'])) {
                $start_date = $_REQUEST['start_date'];
                echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'> $start_date</h5>";
              } elseif (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && isset($_REQUEST['end_date'])) {
                $start_date = $_REQUEST['start_date'];
                $end_date = $_REQUEST['end_date'];
                echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'>$start_date <h4 class='my-0 font-weight-bold mx-2'>To</h4> $end_date</h5>";
              } else {
                $start_date = date('Y-m-d');
                echo "<h4 class='my-0 font-weight-bold mx-2'>From</h4> <h5 class='my-0  font-weight-bold'>$start_date</h5>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="container-fluid">

          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center mb-2">


                <!-- <?php echo  $date_select; ?> -->
              </div>
              <div class="row">

                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow bg-primary text-white border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-white">
                            <i class="fe fe-16 fe-shopping-bag text-default mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-white mb-0">Total Sales</p>
                          <span class="h3 mb-0 text-white">
                            <?php
                            $current_date = date('Y-m-d'); // make sure this is defined

                            $query = "SELECT SUM(grand_total) AS total_sales FROM orders WHERE order_date = '$current_date'";
                            $result = mysqli_query($dbc, $query);

                            if ($result) {
                              $row = mysqli_fetch_assoc($result);
                              $total_sales = isset($row['total_sales']) ? $row['total_sales'] : 0;
                              echo number_format($total_sales);
                            } else {
                              echo "Query Error: " . mysqli_error($dbc);
                            }
                            ?>

                          </span>
                          <!--   <span class="small text-white">+5.5%</span> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-shopping-cart text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">Total Orders</p>
                          <span class="h3 mb-0">
                            <?php
                            // Count today's total orders
                            @$total_orders = mysqli_fetch_assoc(mysqli_query($dbc, "
        SELECT 
            COUNT(*) AS total_orders
        FROM 
            orders
        WHERE 
            1=1 $date_select 
    "))['total_orders'];
                            $total_orders = isset($total_orders) ? $total_orders : 0;

                            echo number_format($total_orders);
                            ?>

                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-dollar-sign text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col">
                          <p class="small text-muted mb-0">Monthly Sales</p>
                          <div class="row align-items-center no-gutters">
                            <div class="col-12">
                              <span class="h3 mr-2 mb-0">
                                <?php
                                $total_sales = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(grand_total) as total_sales FROM orders WHERE  order_date BETWEEN '$start_of_month' AND '$end_of_month' "))['total_sales'];
                                $total = isset($total_sales) ? $total_sales : "0";
                                $total = ($fetchedUserRole == "admin") ? $total : "0";
                                echo number_format($total);
                                ?>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-activity text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col">
                          <p class="small text-muted mb-0">Monthly Orders</p>
                          <span class="h3 mb-0">
                            <?php
                            @$total_orders = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(*) as total_orders FROM orders WHERE order_date BETWEEN '$start_of_month' AND '$end_of_month'"))['total_orders'];

                            $total = isset($total_orders) ? $total_orders : "0";
                            echo  $total = ($fetchedUserRole == "admin") ? $total : "0";

                            ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div><!-- First row end -->

              <div class="row">

                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow bg-primary text-white border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-white">
                            <i class="fe fe-16 fe-shopping-bag text-default mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-white mb-0">Total Purchase</p>
                          <span class="h3 mb-0 text-white">
                            <?php
                            $query = "SELECT SUM(grand_total) AS total_sales FROM purchase WHERE 1=1 $date_select";
                            $result = mysqli_query($dbc, $query);

                            if ($result) {
                              $row = mysqli_fetch_assoc($result);
                              $total_purchase = isset($row['total_sales']) ? $row['total_sales'] : "0";
                              echo $total_purchase;
                            } else {
                              echo "Query Error: " . mysqli_error($dbc);
                            }
                            ?>
                          </span>
                          <!--   <span class="small text-white">+5.5%</span> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-shopping-cart text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">Total Purchases Count</p>
                          <span class="h3 mb-0">
                            <?php
                            @$total_purchases = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(*) as total_orders FROM purchase where purchase_date='$current_date' "))['total_orders'];
                            $total_purchases2 = isset($total_purchases) ? $total_purchases : "0";
                            echo number_format($total_purchases2);
                            ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-dollar-sign text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col">
                          <p class="small text-muted mb-0">Monthly Purchase</p>
                          <div class="row align-items-center ">
                            <div class="col-12">
                              <span class="h3 mr-2 mb-0">
                                <?php
                                @$total_sales = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(grand_total) as total_sales FROM purchase where purchase_date BETWEEN '$start_of_month' AND '$end_of_month'"))['total_sales'];
                                $total = isset($total_sales) ? $total_sales : "0";
                                $total = ($fetchedUserRole == "admin") ? $total : "0";
                                echo number_format($total);
                                ?>
                              </span>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-activity text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col">
                          <p class="small text-muted mb-0">Monthly Purchase Count</p>
                          <span class="h3 mb-0">
                            <?php
                            @$total_orders = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(*) as total_orders FROM purchase WHERE purchase_date BETWEEN '$start_of_month' AND '$end_of_month' "))['total_orders'];
                            $total = isset($total_orders) ? $total_orders : "0";
                            echo   $total = ($fetchedUserRole == "admin") ? $total : "0";

                            ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div><!-- Second row end  -->
              <!-- <div class="row">
              <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow bg-primary text-white border-0">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-3 text-center">
                        <span class="circle circle-sm bg-white">
                          <i class="fe fe-16 fe-shopping-bag text-default mb-0"></i>
                        </span>
                      </div>
                      <div class="col pr-0">
                        <p class="small text-white mb-0">Today Cash Received</p>
                        <span class="h3 mb-0 text-white">
                          <?php
                          @$total_sales = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(paid) as total_sales FROM orders where order_date='$current_date' AND payment_type='cash_in_hand' "))['total_sales'];
                          $total = isset($total_sales) ? $total_sales : "0";
                          echo number_format($total);
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                          <i class="fe fe-16 fe-shopping-cart text-white mb-0"></i>
                        </span>
                      </div>
                      <div class="col pr-0">
                        <p class="small text-muted mb-0">Today Cash Received Order</p>
                        <span class="h3 mb-0">
                          <?php
                          @$total_orders = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(*) as total_orders FROM orders where order_date='$current_date' AND paid>0  AND payment_type='cash_in_hand'"))['total_orders'];
                          echo $total = isset($total_orders) ? $total_orders : "0";
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                          <i class="fe fe-16 fe-dollar-sign text-white mb-0"></i>
                        </span>
                      </div>
                      <div class="col">
                        <p class="small text-muted mb-0">Total Pending Amount</p>
                        <div class="row align-items-center no-gutters">
                          <div class="col-12">
                            <span class="h3 mr-2 mb-0">
                              <?php
                              $total_sales = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(due) as total_sales FROM orders WHERE  order_date='$current_date' AND payment_type='cash_in_hand' "))['total_sales'];
                              $total = isset($total_sales) ? $total_sales : "0";
                              echo number_format($total);
                              ?>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                          <i class="fe fe-16 fe-activity text-white mb-0"></i>
                        </span>
                      </div>
                      <div class="col">
                        <p class="small text-muted mb-0">Pending Order's Amount</p>
                        <span class="h3 mb-0">
                          <?php
                          @$total_orders = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT count(*) as total_orders FROM orders WHERE order_date='$current_date' AND due>0  AND payment_type='cash_in_hand' "))['total_orders'];
                          echo $total = isset($total_orders) ? $total_orders : "0";
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


            </div> -->

              <!-- row start-->

              <div class="row">
                <!-- <div class="col-md-6">
                <div class="card shadow mb-4">
                  <div class="card-header">
                    <strong>Accounts</strong>
                  </div>
                  <div class="card-body px-4">
                    <?php if ($fetchedUserRole == "admin"): ?>

                      <div class="row border-bottom">
                        <div class="col-4 text-center mb-3">
                          <p class="mb-1 small text-muted">Revenue</p>
                          <span class="h3">
                            <?php
                            @$total_sales = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(grand_total) as total_sales FROM orders where order_date='$current_date' "))['total_sales'];
                            echo $total = isset($total_sales) ? $total_sales : "0";
                            ?>
                          </span><br />
                          <span class="small text-muted">+20%</span>
                          <span class="fe fe-arrow-up text-success fe-12"></span>
                        </div>
                        <div class="col-4 text-center mb-3">
                          <p class="mb-1 small text-muted">Income</p>
                          <span class="h3">
                            <?= getIncome($dbc, "where order_date='$current_date'") ?>
                          </span><br />
                          <span class="small text-muted">+20%</span>
                          <span class="fe fe-arrow-up text-success fe-12"></span>
                        </div>
                        <div class="col-4 text-center mb-3">
                          <p class="mb-1 small text-muted">Expense</p>
                          <span class="h3"><?= getexpense($dbc, "AND voucher_date='$current_date'") ?></span><br />
                          <span class="small text-muted">-2%</span>
                          <span class="fe fe-arrow-down text-danger fe-12"></span>
                        </div>
                      </div>
                    <?php endif; ?>
                    <table class="table table-bordered mt-3 mb-1 mx-n1 table-sm">
                      <thead>
                        <tr>
                          <th class="">Type</th>
                          <th class="text-right">Cash in Hand (no.)</th>
                          <th class="text-right">Cash in Hand (pkr)</th>
                          <th class="text-right">Credit (no.)</th>
                          <th class="text-right">Credit (pkr)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $cash_in_hand_sale = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as cash_in_hand,sum(grand_total) as cash_in_hand_amount FROM orders WHERE order_date = '$current_date' AND payment_type='cash_in_hand' "));
                        $credit_sale = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as credit_sale,sum(grand_total) as credit_sale_amount FROM orders WHERE order_date = '$current_date' AND payment_type='credit_sale' "));

                        $cash_in_hand_pur = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as cash_in_hand,sum(grand_total) as cash_in_hand_amount FROM purchase WHERE purchase_date = '$current_date' AND payment_type='sale_purchase' "));
                        $credit_pur = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as credit_sale,sum(grand_total) as credit_sale_amount FROM purchase WHERE purchase_date = '$current_date' AND payment_type='credit_purchase' "));
                        ?>
                        <tr>
                          <td>Orders</td>
                          <td class="text-center"><?= @(int)$cash_in_hand_sale['cash_in_hand'] ?></td>
                          <td class="text-center"><?= @(int)$cash_in_hand_sale['cash_in_hand_amount'] ?></td>
                          <td class="text-center"><?= @(int)$credit_sale['credit_sale'] ?></td>
                          <td class="text-center"><?= @(int)$credit_sale['credit_sale_amount'] ?></td>
                        </tr>
                        <tr>
                          <td>Purchases</td>
                          <td class="text-center"><?= @(int)$cash_in_hand_pur['cash_in_hand'] ?></td>
                          <td class="text-center"><?= @(int)$cash_in_hand_pur['cash_in_hand_amount'] ?></td>
                          <td class="text-center"><?= @(int)$credit_pur['credit_sale'] ?></td>
                          <td class="text-center"><?= @(int)$credit_pur['credit_sale_amount'] ?></td>

                        </tr>

                      </tbody>
                      <tfoot>
                        <tr>
                          <th>Cash in hand Account</th>
                          <td style="font-size: 22px;text-align: right;" align="right" colspan="4">
                            <?php
                            $qwer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(credit-debit) as TotalCash FROM transactions WHERE customer_id = '2'"));
                            echo number_format(@$qwer['TotalCash']);
                            ?>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div> -->
                <div class="col-md-6">
                  <div class="card shadow mb-4">
                    <div class="card-header">
                      <strong>Accounts</strong>
                    </div>
                    <div class="card-body px-4">
                      <?php if ($fetchedUserRole == "admin"): ?>

                        <div class="row border-bottom">
                          <div class="col-4 text-center mb-3">
                            <p class="mb-1 small text-muted">Revenue</p>
                            <span class="h3">
                              <?php
                              @$total_sales = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(grand_total) as total_sales FROM orders where order_date='$current_date' "))['total_sales'];
                              echo $total = isset($total_sales) ? $total_sales : "0";
                              ?>
                            </span><br />
                            <span class="small text-muted">+20%</span>
                            <span class="fe fe-arrow-up text-success fe-12"></span>
                          </div>
                          <div class="col-4 text-center mb-3">
                            <p class="mb-1 small text-muted">Purchse</p>
                            <span class="h3">
                              <?= getIncome($dbc, "where order_date='$current_date'") ?>
                            </span><br />
                            <span class="small text-muted">+20%</span>
                            <span class="fe fe-arrow-up text-success fe-12"></span>
                          </div>
                          <div class="col-4 text-center mb-3">
                            <p class="mb-1 small text-muted">Profit</p>
                            <span class="h3"><?= getexpense($dbc, "AND voucher_date='$current_date'") ?></span><br />
                            <span class="small text-muted">-2%</span>
                            <span class="fe fe-arrow-down text-danger fe-12"></span>
                          </div>
                        </div>
                      <?php endif; ?>
                      <table class="table table-bordered mt-3 mb-1 mx-n1 table-sm">
                        <thead>
                          <tr>
                            <th class="">Type</th>
                            <th class="text-right">Cash in Hand (no.)</th>
                            <th class="text-right">Cash in Hand (pkr)</th>
                            <th class="text-right">Credit (no.)</th>
                            <th class="text-right">Credit (pkr)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $cash_in_hand_sale = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as cash_in_hand,sum(grand_total) as cash_in_hand_amount FROM orders WHERE order_date = '$current_date' AND payment_type='cash_in_hand' "));
                          $credit_sale = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as credit_sale,sum(grand_total) as credit_sale_amount FROM orders WHERE order_date = '$current_date' AND payment_type='credit_sale' "));

                          $cash_in_hand_pur = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as cash_in_hand,sum(grand_total) as cash_in_hand_amount FROM purchase WHERE purchase_date = '$current_date' AND payment_type='sale_purchase' "));
                          $credit_pur = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) as credit_sale,sum(grand_total) as credit_sale_amount FROM purchase WHERE purchase_date = '$current_date' AND payment_type='credit_purchase' "));
                          ?>
                          <tr>
                            <td>Orders</td>
                            <td class="text-center"><?= @(int)$cash_in_hand_sale['cash_in_hand'] ?></td>
                            <td class="text-center"><?= @(int)$cash_in_hand_sale['cash_in_hand_amount'] ?></td>
                            <td class="text-center"><?= @(int)$credit_sale['credit_sale'] ?></td>
                            <td class="text-center"><?= @(int)$credit_sale['credit_sale_amount'] ?></td>
                          </tr>
                          <tr>
                            <td>Purchases</td>
                            <td class="text-center"><?= @(int)$cash_in_hand_pur['cash_in_hand'] ?></td>
                            <td class="text-center"><?= @(int)$cash_in_hand_pur['cash_in_hand_amount'] ?></td>
                            <td class="text-center"><?= @(int)$credit_pur['credit_sale'] ?></td>
                            <td class="text-center"><?= @(int)$credit_pur['credit_sale_amount'] ?></td>

                          </tr>

                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Cash in hand Account</th>
                            <td style="font-size: 22px;text-align: right;" align="right" colspan="4">
                              <?php
                              $qwer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sum(credit-debit) as TotalCash FROM transactions WHERE customer_id = '2'"));
                              echo number_format(@$qwer['TotalCash']);
                              ?>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card shadow eq-card mb-4">
                    <div class="card-body">
                      <div class="card-title">
                        <strong>Sale</strong>
                        <a class="float-right small text-muted" href="#!">View all</a>
                      </div>
                      <div class="row mt-b">
                        <div class="col-6 text-center mb-3 border-right">
                          <p class="text-muted mb-1">Today</p>
                          <h6 class="mb-1"><?php echo $total = isset($total_sales) ? $total_sales : "0"; ?></h6>
                          <p class="text-muted mb-2"></p>
                        </div>
                        <div class="col-6 text-center mb-3">
                          <p class="text-muted mb-1">Yesterday</p>
                          <h6 class="mb-1"><?= getOrders($dbc, "WHERE order_date='$yesterday_date'", "grand_total") ?></h6>
                          <p class="text-muted"></p>
                        </div>

                        <div class="col-6 text-center border-right">
                          <p class="text-muted mb-1">This Week</p>
                          <h6 class="mb-1"><?= getOrders($dbc, "WHERE order_date BETWEEN '$start' AND '$end' ", "grand_total") ?></h6>
                          <p class="text-muted mb-2"></p>
                        </div>
                        <div class="col-6 text-center">
                          <p class="text-muted mb-1">Last Week</p>
                          <h6 class="mb-1"><?= getOrders($dbc, "WHERE order_date BETWEEN '$start_week' AND '$end_week' ", "grand_total") ?></h6>
                          <p class="text-muted"></p>
                        </div>
                      </div>
                      <div class="chart-widget">
                        <div id="columnChartWidget" width="300" height="200"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="col-md-6">
                <div class="card shadow mb-4">
                  <div class="card-header">
                    <strong class="card-title">Balance</strong>
                    <a class="float-right small text-muted" href="#!">View all</a>
                  </div>
                  <div class="card-body">

                    <div class="list-group list-group-flush my-n3">
                      <?php
                      $getCustomer = get($dbc, "customers where customer_type='customer' OR customer_type='supplier' AND customer_status=1 LIMIT 5");
                      $c = 0;
                      while ($customer = mysqli_fetch_assoc($getCustomer)):
                        $c++;
                        $from_balance = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(credit-debit) AS from_balance FROM transactions WHERE customer_id='" . $customer['customer_id'] . "'"));

                      ?>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-3 col-md-2">
                              <h6><?= $c ?></h6>
                            </div>
                            <div class="col">
                              <strong><?= ucwords($customer['customer_name']) ?></strong>
                              <div class="my-0 text-muted small"><?= $customer['customer_phone'] ?>, <?= $customer['customer_email'] ?></div>
                            </div>
                            <div class="col-auto">
                              <strong><?php if (!empty($from_balance['from_balance'])) {
                                        echo $from_balance['from_balance'];
                                      } else {
                                        echo 0;
                                      } ?></strong>

                            </div>
                          </div>
                        </div>
                      <?php endwhile; ?>
                    </div> 
                  </div> 
                </div> 
              </div> -->
              </div>
              <!-- Row end -->



              <!-- next row  -->
              <?php if ($fetchedUserRole == "admin"): ?>
                <div class="row">
                  <!-- <div class="col-md-8">
                  <div class="card shadow eq-card mb-4">
                    <div class="card-body">
                      <div class="card-title">
                        <strong>Credit Sales</strong>
                        <a class="float-right small text-muted" href="credit_orders.php">View all</a>
                      </div>
                      <div class="row mt-b">
                        <table class="table">
                          <thead>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Remaining Days</th>

                          </thead>
                          <tbody>
                            <?php


                            ?>

                            <?php $q = mysqli_query($dbc, "SELECT * FROM orders WHERE ( credit_sale_type='15days' OR credit_sale_type='30days'  ) AND payment_status=0 ORDER BY order_id DESC LIMIT 5");
                            $c = 0;
                            while (@$r = mysqli_fetch_assoc($q)) {
                              $c++;
                              $Date = date('Y-m-d');
                              $now = strtotime($Date);

                              if ($r['credit_sale_type'] == "15days") {
                                $next_date = date('Y-m-d', strtotime($r['order_date'] . ' + 15 days'));
                              } elseif ($r['credit_sale_type'] == "30days") {
                                $next_date = date('Y-m-d', strtotime($r['order_date'] . ' + 30 days'));
                              }


                              $your_date = strtotime($next_date);
                              $datediff = $your_date - $now;
                              $total_days = round($datediff / (60 * 60 * 24));



                            ?>
                              <tr>

                                <td><?= ucwords($r['client_name']) ?> (<?= $r['client_contact'] ?>)</td>

                                <td><?= $r['order_date'] ?></td>
                                <td><?= $next_date ?></td>
                                <td><?= $r['due'] ?></td>
                                <td>
                                  <?php if ($total_days < 6): ?>
                                    <span class="badge badge-danger"><?= $total_days ?></span>
                                  <?php else: ?>
                                    <span class="badge badge-success"><?= $total_days ?></span>

                                  <?php endif ?>
                                </td>



                              </tr>
                            <?php  } ?>

                          </tbody>
                        </table>
                      </div>
                      <div class="chart-widget">
                        <div id="columnChartWidget" width="300" height="200"></div>
                      </div>
                    </div> 
                  </div>

                </div>  -->

                </div>
              <?php endif ?>



              <div class="card shadow d-none">
                <div class="card-header">
                  <strong class="card-title">Recent Order</strong>
                  <a class="float-right small text-muted" href="view_orders.php">View all</a>
                </div>
                <div class="card-body my-n2">
                  <table class="table table-striped table-hover table-borderless">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th> Name</th>
                        <th> Phone No.</th>
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Order Type</th>


                      </tr>
                    </thead>
                    <tbody>
                      <?php $q = mysqli_query($dbc, "SELECT * FROM orders ORDER BY order_id DESC LIMIT 5");
                      $c = 0;
                      while ($r = mysqli_fetch_assoc($q)) {
                        $c++;



                      ?>
                        <tr>
                          <td><?= $c ?></td>
                          <td><?= ucwords($r['client_name']) ?></td>
                          <td><?= $r['client_contact'] ?></td>
                          <td><?= $r['order_date'] ?></td>
                          <td><?= $r['grand_total'] ?></td>
                          <td><?= $r['payment_type'] ?></td>
                        </tr>
                      <?php  } ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- <div class="row">
              <div class="col-sm-6">
                <div class="card shadow">
                  <div class="card-header">
                    <strong class="card-title">Cash Sale's Pending Orders</strong>
                    <a class="float-right small text-muted" href="pending_bills.php?search_it=all">View all</a>
                  </div>
                  <div class="card-body my-n2">
                    <table class="table table-striped table-hover table-borderless">
                      <thead>
                        <tr>
                          <th>Order No.</th>
                          <th>Customer Details</th>
                          <th>Order Date</th>
                          <th>Piad Amount</th>
                          <th>Remaining Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $q = mysqli_query($dbc, "SELECT * FROM orders WHERE payment_type='cash_in_hand' AND payment_status=0 ORDER BY order_id DESC LIMIT 5");
                        $c = 0;
                        while ($row = mysqli_fetch_assoc($q)) {
                          $c++;
                        ?>
                          <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['client_name'] ?> (<?= $row['client_contact'] ?>)</td>
                            <td><?= $row['order_date'] ?></td>
                            <td><span class="badge badge-success"><?= $row['paid'] ?></span> </td>
                            <td><span class="badge badge-danger"><?= $row['due'] ?></span> </td>
                          </tr>
                        <?php  } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                
                

              </div>
              <div class="col-sm-6">
                <div class="card shadow">
                  <div class="card-header">
                    <strong class="card-title">Today Payments</strong>
                    <a class="float-right small text-muted" href="credit_orders.php">View all</a>
                  </div>
                  <div class="card-body my-n2">
                    <table class="table table-striped table-hover table-borderless">
                      <thead>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Remaining Days</th>

                      </thead>
                      <tbody>
                        <?php


                        ?>

                        <?php $q = mysqli_query($dbc, "SELECT * FROM orders WHERE ( credit_sale_type='15days' OR credit_sale_type='30days')  AND payment_status=0 ORDER BY order_id DESC");
                        $c = 0;
                        while (@$r = mysqli_fetch_assoc($q)) {
                          $c++;
                          $Date = date('Y-m-d');
                          $now = strtotime($Date);

                          if ($r['credit_sale_type'] == "15days") {
                            $next_date = date('Y-m-d', strtotime($r['order_date'] . ' + 15 days'));
                          } elseif ($r['credit_sale_type'] == "30days") {
                            $next_date = date('Y-m-d', strtotime($r['order_date'] . ' + 30 days'));
                          }
                          $your_date = strtotime($next_date);
                          $datediff = $your_date - $now;
                          $total_days = round($datediff / (60 * 60 * 24));
                          if ($next_date == $Date):
                        ?>
                            <tr>
                              <td><?= ucwords($r['client_name']) ?> (<?= $r['client_contact'] ?>)</td>
                              <td><?= $r['order_date'] ?></td>
                              <td><?= $next_date ?></td>
                              <td><?= $r['due'] ?></td>
                              <td>
                                <?php if ($total_days < 6): ?>
                                  <span class="badge badge-danger"><?= $total_days ?></span>
                                <?php else: ?>
                                  <span class="badge badge-success"><?= $total_days ?></span>

                                <?php endif ?>
                              </td>
                            </tr>
                        <?php endif;
                        } ?>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> -->

            </div>
          </div>
        </div>

        <div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="defaultModalLabel">Shortcuts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body px-5">
                <div class="row align-items-center">
                  <div class="col-6 text-center">
                    <div class="squircle bg-success justify-content-center">
                      <i class="fe fe-cpu fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Control area</p>
                  </div>
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-activity fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Activity</p>
                  </div>
                </div>
                <div class="row align-items-center">
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-droplet fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Droplet</p>
                  </div>
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-upload-cloud fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Upload</p>
                  </div>
                </div>
                <div class="row align-items-center">
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-users fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Users</p>
                  </div>
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-settings fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Settings</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </main>
  </div>
  <div class="modal fade top" id="modalCookie1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" data-backdrop="true">
    <div class="modal-dialog modal-frame modal-lg modal-top modal-notify modal-info" role="document">
      <!--Content-->
      <div class="modal-content">
        <!--Body-->
        <div class="modal-body">
          <form class="form-group" action="#" method="post">
            <div class="row my-3">
              <!-- Add date selection input fields or datepicker here -->
              <div class="col-md-12 col-sm-12 col-lg-4 col-xl-4">
                <!-- <input type="hidden" name=""> -->
                <label class="text-dark" for="start_date">Start Date</label>
                <input class="form-control" value="" type="date" id="start_date" name="start_date">
              </div>
              <div class="col-md-12 col-sm-12 col-lg-4 col-xl-4">
                <label class="text-dark" for="end_date">End Date</label>
                <input class="form-control" value="" type="date" id="end_date" name="end_date">
              </div>
              <div class="col-md-12 col-sm-12 col-lg-4 col-xl-4">
                <label class="text-dark" for="end_date">Order Date</label>
                <select name="orderdate" id="orderdate" class="form-control">
                  <option value="">Select</option>
                  <option value="today">Today</option>
                  <option value="yesterday">Yesterday</option>
                  <option value="last7days">Last 7 Days</option>
                  <option value="last30days">Last 30 Days</option>
                  <option value="thismonth">This Month</option>
                  <option value="lastmonth">Last Month</option>
                </select>
              </div>
              <div
                class=" py-3 d-flex align-items-end justify-content-end col-md-12 col-sm-12 col-lg-12 col-xl-12">
                <div>
                  <input class=" btn btn-success text-white waves-effect" type="submit"
                    name="saleByDate" value="Filter Sale">
                </div>
                <div>
                  <a type="button" class="mx-2 btn btn-danger text-white waves-effect"
                    data-dismiss="modal">Cancel</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!--/.Content-->
    </div>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/moment.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/simplebar.min.js"></script>
  <script src='js/daterangepicker.js'></script>
  <script src='js/jquery.stickOnScroll.js'></script>
  <script src="js/tinycolor-min.js"></script>
  <script src="js/config.js"></script>
  <script src="js/d3.min.js"></script>
  <script src="js/topojson.min.js"></script>
  <script src="js/datamaps.all.min.js"></script>
  <script src="js/datamaps-zoomto.js"></script>
  <script src="js/datamaps.custom.js"></script>
  <script src="js/Chart.min.js"></script>
  <script>
    /* defind global options */
    Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
    Chart.defaults.global.defaultFontColor = colors.mutedColor;
  </script>
  <script src="js/gauge.min.js"></script>
  <script src="js/jquery.sparkline.min.js"></script>
  <script src="js/apexcharts.min.js"></script>
  <script src="js/apexcharts.custom.js"></script>
  <script src='js/jquery.mask.min.js'></script>
  <script src='js/select2.min.js'></script>
  <script src='js/jquery.steps.min.js'></script>
  <script src='js/jquery.validate.min.js'></script>
  <script src='js/jquery.timepicker.js'></script>
  <script src='js/dropzone.min.js'></script>
  <script src='js/uppy.min.js'></script>
  <script src='js/quill.min.js'></script>
  <script>
    barChartjs = document.getElementById("barChar");
    barChartjs && new Chart(barChartjs, {
      type: "bar",
      data: ChartData,
      options: ChartOptions
    });
    $('.select2').select2({
      theme: 'bootstrap4',
    });
    $('.select2-multi').select2({
      multiple: true,
      theme: 'bootstrap4',
    });
    $('.drgpicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      showDropdowns: true,
      locale: {
        format: 'MM/DD/YYYY'
      }
    });
    $('.time-input').timepicker({
      'scrollDefault': 'now',
      'zindex': '9999' /* fix modal open */
    });
    /** date range picker */
    if ($('.datetimes').length) {
      $('.datetimes').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'M/DD hh:mm A'
        }
      });
    }
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#reportrange').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
    }, cb);
    cb(start, end);
    $('.input-placeholder').mask("00/00/0000", {
      placeholder: "__/__/____"
    });
    $('.input-zip').mask('00000-000', {
      placeholder: "____-___"
    });
    $('.input-money').mask("#.##0,00", {
      reverse: true
    });
    $('.input-phoneus').mask('(000) 000-0000');
    $('.input-mixed').mask('AAA 000-S0S');
    $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
      translation: {
        'Z': {
          pattern: /[0-9]/,
          optional: true
        }
      },
      placeholder: "___.___.___.___"
    });
    // editor
    var editor = document.getElementById('editor');
    if (editor) {
      var toolbarOptions = [
        [{
          'font': []
        }],
        [{
          'header': [1, 2, 3, 4, 5, 6, false]
        }],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{
            'header': 1
          },
          {
            'header': 2
          }
        ],
        [{
            'list': 'ordered'
          },
          {
            'list': 'bullet'
          }
        ],
        [{
            'script': 'sub'
          },
          {
            'script': 'super'
          }
        ],
        [{
            'indent': '-1'
          },
          {
            'indent': '+1'
          }
        ], // outdent/indent
        [{
          'direction': 'rtl'
        }], // text direction
        [{
            'color': []
          },
          {
            'background': []
          }
        ], // dropdown with defaults from theme
        [{
          'align': []
        }],
        ['clean'] // remove formatting button
      ];
      var quill = new Quill(editor, {
        modules: {
          toolbar: toolbarOptions
        },
        theme: 'snow'
      });
    }
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>
  <script>
    var uptarg = document.getElementById('drag-drop-area');
    if (uptarg) {
      var uppy = Uppy.Core().use(Uppy.Dashboard, {
        inline: true,
        target: uptarg,
        proudlyDisplayPoweredByUppy: false,
        theme: 'dark',
        width: 770,
        height: 210,
        plugins: ['Webcam']
      }).use(Uppy.Tus, {
        endpoint: 'https://master.tus.io/files/'
      });
      uppy.on('complete', (result) => {
        console.log('Upload complete! We’ve uploaded these files:', result.successful)
      });
    }
  </script>
  <script src="js/apps.js"></script>
</body>

</html>