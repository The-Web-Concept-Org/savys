<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';

if (!empty($_REQUEST['edit_order_id'])) {
  # code...
  $fetchOrder = fetchRecord($dbc, "orders", "order_id", base64_decode($_REQUEST['edit_order_id']));
}
?>

<body class="horizontal light  ">
  <div class="wrapper">
    <?php include_once 'includes/header.php'; ?>

    <div class="container-fluid">
      <div class="card">
        <div class="card-header card-bg" align="center">

          <div class="row">
            <div class="col-12 mx-auto h4">
              <b class="text-center card-text">Dispatch</b>
              <a href="cash_salebarcode.php" class="btn btn-admin float-right btn-sm">Add New</a>
            </div>
          </div>

        </div>
        <div class="card-body">
          <form action="php_action/custom_action.php" method="POST" id="sale_order_fm">
            <input type="hidden" name="product_order_id" value="<?= @empty($_REQUEST['edit_order_id']) ? "" : base64_decode($_REQUEST['edit_order_id']) ?>">
            <input type="hidden" name="payment_type" id="payment_type" value="cash_in_hand">

            <div class="row form-group">
              <div class="col-md-3">
                <label>Order Date</label>
                <input type="text"
                  name="order_date"
                  id="order_date"
                  value="<?= @empty($_REQUEST['edit_order_id']) ? date('Y-m-d') : $fetchOrder['order_date'] ?>"
                  readonly
                  class="form-control"
                  placeholder="Order Date">
              </div>

              <div class="col-sm-3">
                <label>Customer Number</label>
                <input type="number"
                  onchange="getCustomer_name(this.value)"
                  value="<?= @$fetchOrder['client_contact'] ?>"
                  autocomplete="off"
                  min="0"
                  class="form-control"
                  name="client_contact"
                  list="phone"
                  placeholder="Enter Customer Number">
                <datalist id="phone">
                  <?php
                  $q = mysqli_query($dbc, "SELECT DISTINCT client_contact from orders");
                  while ($r = mysqli_fetch_assoc($q)) {
                  ?>
                    <option value="<?= $r['client_contact'] ?>"><?= $r['client_contact'] ?></option>
                  <?php } ?>
                </datalist>
              </div>

              <div class="col-sm-3">
                <label>Customer Name</label>
                <input type="text"
                  id="sale_order_client_name"
                  value="<?= @$fetchOrder['client_name'] ?>"
                  class="form-control"
                  autocomplete="off"
                  name="sale_order_client_name"
                  list="client_name"
                  placeholder="Enter Customer Name">
                <datalist id="client_name">
                  <?php
                  $q = mysqli_query($dbc, "SELECT DISTINCT client_name FROM orders");
                  while ($r = mysqli_fetch_assoc($q)) {
                  ?>
                    <option value="<?= $r['client_name'] ?>"><?= $r['client_name'] ?></option>
                  <?php } ?>
                </datalist>
              </div>

              <div class="col-md-3">
                <label>Order Type</label>
                <select required class="form-control ratetype" name="ratetype" id="ratetype">
                  <option value="">-- Select Order Type --</option>
                  <option selected value="retail">Retail</option>
                  <option value="wholesale">Wholesale</option>
                </select>
              </div>
            </div> <!-- end of form-group -->
            <!-- custom product -->
            <div class="form-group row">
              <div class="col-4 col-md-3">
                <label class="">Product Barcode</label>
                <input type="text"
                  placeholder="Focus here while scanning product barcode"
                  name="barcode_product"
                  autocomplete="off"
                  id="barcode_product"
                  class="form-control">
              </div>

            </div>
            <!-- custom product -->
            <div class="row">
              <div class="col-12">
                <table class="table saleTable" id="myDiv">
                  <thead class="table-bordered">
                    <tr>
                      <th style="font-weight:bold;">Code</th>
                      <th style="font-weight:bold;">Product Name</th>
                      <th style="font-weight:bold;">Unit Price</th>
                      <th style="font-weight:bold;">Quantity</th>
                      <th style="font-weight:bold;">Profit</th>
                      <th style="font-weight:bold;">Total Price</th>
                      <th style="font-weight:bold;">Action</th>
                    </tr>
                  </thead>

                  <tbody class="table table-bordered" id="purchase_product_tb">
                    <?php
                    $total_profit = 0; // Initialize total profit

                    if (isset($_REQUEST['edit_order_id'])):
                      $q = mysqli_query(
                        $dbc,
                        "
        SELECT product.*, brands.*, order_item.* 
        FROM order_item 
        INNER JOIN product ON product.product_id = order_item.product_id 
        INNER JOIN brands ON product.brand_id = brands.brand_id 
        WHERE order_item.order_id = '" . base64_decode($_REQUEST['edit_order_id']) . "'
        "
                      );

                      while ($r = mysqli_fetch_assoc($q)):
                        $profit = ((float)$r['rate'] - (float)$r['purchase_rate']) * (float)$r['quantity'];
                        $total_profit += $profit;
                    ?>
                        <tr id="product_idN_<?= $r['product_id'] ?>">
                          <input type="hidden" data-price="<?= $r['rate'] ?>" data-quantity="<?= $r['quantity'] ?>"
                            id="product_ids_<?= $r['product_id'] ?>" class="product_ids" name="product_ids[]"
                            value="<?= $r['product_id'] ?>">

                          <input type="hidden" id="product_quantites_<?= $r['product_id'] ?>" name="product_quantites[]"
                            value="<?= $r['quantity'] ?>">

                          <input type="hidden" id="product_rate_<?= $r['product_id'] ?>" name="product_rates[]"
                            value="<?= $r['rate'] ?>">

                          <input type="hidden" id="product_totalrate_<?= $r['product_id'] ?>" name="product_totalrates[]"
                            value="<?= $r['rate'] ?>">
                          <input type="hidden" id="get_rack_number<?= $r['product_id'] ?>" name="get_rack_number[]"
                            value="<?= $r['product_code'] ?>">

                  

                          <td style="text-transform: uppercase;"><?= ucwords($r['product_code']) ?></td>

                          <td><?= ucwords($r['product_name']) ?> (<span class="text-success"><?= ucwords($r['brand_name']) ?></span>)</td>

                          <td><?= $r['rate'] ?></td>

                          <td><?= $r['quantity'] ?></td>

                          <td><?= number_format($profit, 2) ?></td>

                          <td><?= number_format((float)$r['rate'] * (float)$r['quantity'], 2) ?></td>

                          <td>
                            <button type="button" onclick="addbarcode_product('<?= $r['product_code'] ?>', 'plus')"
                              class="btn btn-sm btn-success" title="Increase quantity">
                              + Add
                            </button>

                            <button type="button" onclick="addbarcode_product('<?= $r['product_code'] ?>', 'minus')"
                              class="btn btn-sm btn-warning" title="Decrease quantity">
                              - Remove
                            </button>

                            <button type="button" onclick="removeByid('#product_idN_<?= $r['product_id'] ?>')"
                              class="btn btn-sm btn-danger" title="Remove product">
                             🗑 Delete
                            </button>
                          </td>
                        </tr>
                    <?php endwhile;
                    endif; ?>
                  </tbody>

                  <tfoot>
                    <!-- Total Profit: Separate Row -->
                    <tr>
                      <td colspan="6" class="text-right table-bordered font-weight-bold">Total Profit:</td>
                      <td class="table-bordered font-weight-bold" id="total_profit_amount"><?= number_format($total_profit, 2) ?></td>
                    </tr>

                    <!-- Sub Total and Discount -->
                    <tr>
                      <td colspan="3"></td>
                      <td class="table-bordered font-weight-bold">Sub Total:</td>
                      <td class="table-bordered" id="product_total_amount"><?= @$fetchOrder['total_amount'] ?></td>
                      <td class="table-bordered font-weight-bold">Discount / Extra:</td>
                      <td class="table-bordered" id="getDiscount">
                        <div class="row">
                          <div class="col-sm-6 pr-0">
                            <input onkeyup="getOrderTotal()" type="number" id="ordered_discount" class="form-control form-control-sm"
                              value="<?= @empty($_REQUEST['edit_order_id']) ? "0" : $fetchOrder['discount'] ?>" min="0"
                              name="ordered_discount" placeholder="Enter Discount">
                          </div>
                          <div class="col-sm-6 pl-2">
                            <input onkeyup="getOrderTotal()" type="number" id="freight" class="form-control form-control-sm"
                              placeholder="Extra Charges" value="<?= @$fetchOrder['freight'] ?>" min="0" name="freight">
                          </div>
                        </div>
                      </td>
                    </tr>

                    <!-- Grand Total and Paid -->
                    <tr>
                      <td colspan="3"></td>
                      <td class="table-bordered font-weight-bold">Grand Total:</td>
                      <td class="table-bordered" id="product_grand_total_amount"><?= @$fetchOrder['grand_total'] ?></td>
                      <td class="table-bordered font-weight-bold">Paid:</td>
                      <td class="table-bordered">
                        <input type="number" class="form-control form-control-sm" id="paid_ammount" required
                          onkeyup="getRemaingAmount()" name="paid_ammount" value="<?= @$fetchOrder['paid'] ?>"
                          placeholder="Enter amount paid">
                      </td>
                    </tr>

                    <!-- Remaining and Account -->
                    <tr>
                      <td colspan="3"></td>
                      <td class="table-bordered font-weight-bold">Remaining Amount:</td>
                      <td class="table-bordered">
                        <input type="number" class="form-control form-control-sm" id="remaining_ammount" readonly
                          name="remaining_ammount" value="<?= @$fetchOrder['due'] ?>" placeholder="Auto calculated">
                      </td>
                      <td class="table-bordered font-weight-bold">Account:</td>
                      <td class="table-bordered">
                        <select class="form-control" name="payment_account" required>
                          <option value="">-- Select Account --</option>
                          <?php
                          $q = mysqli_query($dbc, "SELECT * FROM customers WHERE customer_status = 1 AND customer_type = 'bank'");
                          while ($r = mysqli_fetch_assoc($q)): ?>
                            <option <?= @($fetchOrder['payment_account'] == $r['customer_id']) ? "selected" : "" ?>
                              value="<?= $r['customer_id'] ?>">
                              <?= ucwords($r['customer_name']) ?>
                            </option>
                          <?php endwhile; ?>
                        </select>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6 offset-6">

                <button class="btn btn-admin float-right " name="sale_order_btn" value="print" type="submit" id="">Save and Print</button>

              </div>
            </div>
          </form>
        </div>
      </div> <!-- .row -->
    </div> <!-- .container-fluid -->


  </div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>
<script type="text/javascript">
  $(document).ready(function() {
    $(window).keydown(function(event) {
      if (event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
  });

  document.addEventListener('keydown', function(e) {
    // Convert key to lowercase to avoid shift issues
    const key = e.key.toLowerCase();

    // Check if Alt+B or Ctrl+B is pressed
    if ((e.altKey || e.ctrlKey) && key === 'b') {
      e.preventDefault(); // Stop default behavior (like bold in editors)
      const barcodeInput = document.getElementById('barcode_product');
      if (barcodeInput) {
        barcodeInput.focus();
        barcodeInput.select(); // Optional: select existing value
      }
    }
    if (e.altKey && e.key.toLowerCase() === 'r') {
      e.preventDefault(); // prevent default browser behavior
      const input = document.getElementById('paid_ammount');
      if (input) input.focus();
    }
  });
</script>