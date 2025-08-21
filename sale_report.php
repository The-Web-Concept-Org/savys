<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php'; ?>
<style type="text/css">
	thead tr th {
		font-size: 19px !important;
		font-weight: bolder !important;
		color: #000 !important;
	}

	tbody tr th,
	tbody tr th p {
		font-size: 17px !important;
		/* font-weight: bolder !important; */
		color: #000 !important;
	}

	.tolat_text {
		font-size: 18px !important;
		font-weight: bolder !important;
		color: #000 !important;
	}

	@media print {
		.print_hide {
			display: none !important;
		}

		.form_sec {
			display: none !important;
		}
	}
</style>

<body class="horizontal light  ">
	<div class="wrapper">
		<?php include_once 'includes/header.php'; ?>
		<main role="main" class="main-content">
			<div class="container-fluid">
				<div class="card">
					<div class="card-header card-bg print_hide" align="center">

						<div class="row d-print-none">
							<div class="col-12 mx-auto h4">
								<b class="text-center card-text">Dispatch Report</b>


							</div>
						</div>

					</div>
					<div class="card-body">
						<!-- <form action="" method="get" class=" d-print-none" >
							<div class="row">
								<div class="col-sm-3">
										<div class="form-group">
				<label for="">Customer Account</label>
				<select class="form-control" id="clientName" name="customer_id" autofocus="true">
				  <option value="">~~SELECT~~</option>
				  <?php
				  $sql = "SELECT * FROM customers WHERE customer_status = 1 AND customer_type='customer'";
				  $result = $connect->query($sql);

				  while ($row = $result->fetch_array()) {
					  echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
				  } // while
				  
				  ?>
			  </select>	
			</div>

								</div>
								<div  class="col-sm-3">
										<div class="form-group">
				<label for="">From</label>
				<input type="text" class="form-control" autocomplete="off" name="from_date" id="from" placeholder="From Date">
			</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
				<label for="">To</label>
				<input type="text" class="form-control" autocomplete="off" name="to_date" id="to" placeholder="To Date">
			</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label>Type</label>
										<select  class="form-control" name="sale_type">
											<option value="all">Select Type</option>
											<option value="cash_in_hand">Cash Sale</option>
											<option value="credit_sale">Credit Sale</option>
										</select>
									</div>
								</div>
								<div class="col-sm-1">
										<label style="visibility: hidden;">a</label><br>
			<button class="btn btn-admin2" name="search_sale" type="submit">Search</button>
									
								</div>
							</div>
				
			
		</form> -->


						<form class="form-group print_hide" action="#" method="post">
							<div class="row my-3 align-items-end">
								<!-- Start Date -->
								<div class="col-12 col-lg-3 mb-3 mb-lg-0">
									<label class="text-dark" for="start_date">Start Date</label>
									<input class="form-control" type="date" id="start_date" name="start_date">
								</div>
								<!-- End Date -->
								<div class="col-12 col-lg-3 mb-3 mb-lg-0">
									<label class="text-dark" for="end_date">End Date</label>
									<input class="form-control" type="date" id="end_date" name="end_date">
								</div>
								<!-- Order Date -->
								<div class="col-12 col-lg-3 mb-3 mb-lg-0">
									<label class="text-dark" for="orderdate">Order Date</label>
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
								<!-- Buttons -->
								<div class="col-12 col-lg-3 d-flex justify-content-lg-center">
									<div class="d-flex flex-column flex-lg-row gap-5">
										<input class="btn btn-success text-white waves-effect" type="submit"
											name="search_sale" value="Filter Sale">

									</div>
								</div>
							</div>
						</form>
					</div>
				</div> <!-- .card -->

				<?php if (isset($_REQUEST['search_sale'])):

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

						$start_date = $_REQUEST['start_date'];

						$date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '$start_date'";
					} elseif (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && isset($_REQUEST['end_date'])) {

						$start_date = $_REQUEST['start_date'];

						$end_date = $_REQUEST['end_date'];

						$date_select = "AND DATE_FORMAT(timestamp, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
					} else {

						$date_select = " AND DATE_FORMAT(timestamp, '%Y-%m-%d') = '" . date('Y-m-d') . "'";
					}



					?>
					<div class="card">
						<div class="card-header card-bg" align="center">

							<div class="row">
								<div class="col-12 mx-auto h4">
									<b class="text-center card-text">Dispatch Report</b>
									<button onclick="window.print();"
										class="btn btn-admin btn-sm float-right print_btn print_hide"
										style="display: none;">Print Report</button>


								</div>
							</div>

						</div>
						<div class="card-body">

							<table id="salesTable" class="table table-bordered dataTable">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Dated</th>
										<th style="width: 100px">Bill#</th>
										<th>Item</th>
										<th>Sold Qty</th>
										<th>Rate</th>
										<th>Total</th>
										<!-- <th>Payment Detail</th>
										<th>Party Detail</th>
										<th>Sale Type</th> -->
									</tr>
								</thead>
								<tbody>
									<?php $i = 1;

									//echo "sa";
									$q = "SELECT * FROM orders WHERE 1=1 $date_select ";

									?>
									<?php
									//echo $q;
									$query = mysqli_query($dbc, $q);

									$Grandgrandtotal = 0;
									$creditGrand = 0;
									while ($r = mysqli_fetch_assoc($query)):

										// $fetchCustomer = fetchRecord($dbc, "customers", "customer_id", $r['customer_account']);
										$getItem = mysqli_query($dbc, "SELECT * FROM order_item WHERE order_id='$r[order_id]'");

										?>

										<tr>
											<th><?= $i ?></th>
											<th><?= date('D, d-M-Y', strtotime($r['order_date'])) ?></th>
											<th <?php
											if ($r['payment_type'] == 'credit_sale') {
												?>
													style="background-color: black;color: white!important" <?php
												# code...
											}
											?>>
												<?php
												if ($r['payment_type'] == 'cash_in_hand') {
													echo "A.T. ";
												}
												?>
												<?= $r['order_id'] ?>
											</th>
											<th>
												<?php

												while ($fetchItem = mysqli_fetch_assoc($getItem)):
													$fetchProduct = fetchRecord($dbc, "product", 'product_id', $fetchItem['product_id']);
													$fetchCategory = fetchRecord($dbc, "categories", "categories_id", $fetchProduct['category_id']); ?>
													<p><?= $fetchProduct['product_name'] ?>
														<small>(<?= strtoupper($fetchCategory['categories_name']) ?>)</small>
													</p>
												<?php endwhile; ?>
											</th>
											<th>
												<?php
												$getItem = mysqli_query($dbc, "SELECT * FROM order_item WHERE order_id='$r[order_id]'");
												while ($fetchItem = mysqli_fetch_assoc($getItem)):
													$fetchProduct = fetchRecord($dbc, "product", 'product_id', $fetchItem['product_id']);
													$fetchCategory = fetchRecord($dbc, "categories", "categories_id", $fetchProduct['category_id']); ?>
													<p><?= $fetchItem['quantity'] ?> <span class="text-right">x</span></p>
												<?php endwhile; ?>
											</th>
											<th>
												<?php
												$getItem = mysqli_query($dbc, "SELECT * FROM order_item WHERE order_id='$r[order_id]'");
												while ($fetchItem = mysqli_fetch_assoc($getItem)):
													$fetchProduct = fetchRecord($dbc, "product", 'product_id', $fetchItem['product_id']);
													$fetchCategory = fetchRecord($dbc, "categories", "categories_id", $fetchProduct['category_id']); ?>
													<p><?= $fetchItem['rate'] ?></p>
												<?php endwhile; ?>
											</th>
											<th class="tolat_text">
												<?php
												$getItem = mysqli_query($dbc, "SELECT * FROM order_item WHERE order_id='$r[order_id]'");
												while ($fetchItem = mysqli_fetch_assoc($getItem)):
													$fetchProduct = fetchRecord($dbc, "product", 'product_id', $fetchItem['product_id']);
													$fetchCategory = fetchRecord($dbc, "categories", "categories_id", $fetchProduct['category_id']); ?>
													<p><?= $fetchItem['total'] ?></p>
												<?php endwhile; ?>
											</th>
											<!-- <th>
												Grand Total:<?= @$r['grand_total'] ?><br>
												Paid:<?= @$r['paid'] ?><br>

												Due: <?= $r['due'] ?>

												<?php
												if ($r['payment_type'] == 'cash_in_hand') {
													# code...
										
													$Grandgrandtotal += $r['grand_total'];
												} else {
													$creditGrand += $r['grand_total'];
												}
												?>

											</th>
											<th>
												<?= $r['client_name'] ?> <br><?= $r['client_contact'] ?>

											</th>
											<th><?= $r['payment_type'] ?></th> -->
										</tr>
										<?php $i++; endwhile; ?>
								</tbody>
								<tr>
									<td colspan="4"></td>
									<td colspan="2">
										<center>
											<h3>Total Sale</h3>
										</center>
									</td>
									<td>
										<h3><?= $Grandgrandtotal ?></h3>
									</td>
									<!-- <td>
										<h3>Credit Sale </h3>
									</td>
									<td>
										<h3><?= $creditGrand ?></h3>
									</td> -->
								</tr>
							</table>
						</div>
					</div>
					<!-- <?php endif; ?> -->

			</div> <!-- .container-fluid -->

		</main> <!-- main -->
	</div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>
<script>
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
</script>