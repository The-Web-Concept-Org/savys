<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';
if (isset($_REQUEST['edit_product_id'])) {
  $fetchproduct = fetchRecord($dbc, "product", "product_id", base64_decode($_REQUEST['edit_product_id']));
}
$btn_name = isset($_REQUEST['edit_product_id']) ? "Update" : "Add";

if (isset($_REQUEST['duplicate_product_id'])) {
  $duplicate_product = fetchRecord($dbc, "product", "product_id", base64_decode($_REQUEST['duplicate_product_id']));
}

?>
<style type="text/css">
  .badge {
    font-size: 15px;
  }

  label {
    font-weight: 600;
  }
</style>

<body class="horizontal light  ">
  <div class="wrapper">
    <?php include_once 'includes/header.php'; ?>
    <main role="main" class="main-content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header card-bg" align="center">

            <div class="row">
              <div class="col-12 mx-auto h4">
                <b class="text-center card-text">Product Management</b>

                <a href="stock.php?type=simple" class="btn btn-admin float-right btn-sm mx-1">Print Stock</a>
                <a href="stock.php?type=amount" class="btn btn-admin float-right btn-sm mx-1">Print Stock With
                  Amount</a>

                <a href="product.php?act=add" class="btn btn-admin float-right btn-sm mx-1">Add New</a>
              </div>
            </div>

          </div>
          <?php if (@$_REQUEST['act'] == "add"): ?>
            <div class="card-body">
              <form action="php_action/custom_action.php" id="add_product_fm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="product_module">
                <input type="hidden" name="product_id" value="<?= @base64_encode($fetchproduct['product_id']) ?>">
                <input type="hidden" id="product_add_from" value="page">

                <div class="form-group row">
                  <div class="col-md-4 mb-3">
                    <label>Product Barcode</label>
                    <input type="text" class="form-control" id="product_code" placeholder="Scan the Product Barcode"
                      name="product_code" autocomplete="off" required autofocus
                      value="<?= @$fetchproduct['product_code'] ?>">
                  </div>

                  <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="button" id="generate_code" class="btn btn-admin2 w-100">Generate Barcode</button>
                  </div>

                  <div class="col-md-3 mb-3">
                    <label>Product Name</label>
                    <input type="text" class="form-control" id="product_name" placeholder="Product Name"
                      name="product_name" autocomplete="off" required value="<?= @$fetchproduct['product_name'] ?? @$duplicate_product['product_name'] ?>">
                  </div>

                  <div class="col-md-3 mb-3">
                    <label>Product Name Urdu</label>
                    <input type="text" class="form-control" id="product_name_urdu" placeholder="Product Name Urdu"
                      name="product_name_urdu" autocomplete="off"
                      value="<?= !empty($fetchproduct['product_name_urdu'])
                                ? $fetchproduct['product_name_urdu']
                                : (!empty($duplicate_product['product_name_urdu'])
                                  ? $duplicate_product['product_name_urdu']
                                  : '') ?>">
                  </div>

                </div>

                <div class="form-group row">
                  <div class="col-md-5 mb-3">
                    <label>Product Brand</label>
                    <select class="form-control searchableSelect tableData" required name="brand_id" id="tableData">
                      <option value="">Select Brand</option>
                      <?php
                      $result = mysqli_query($dbc, "SELECT * FROM brands");
                      while ($row = mysqli_fetch_array($result)) {
                      ?>
                        <option <?= @$fetchproduct['brand_id'] == $row["brand_id"] || @$duplicate_product['brand_id'] == $row["brand_id"] ? "selected" : "" ?>
                          value="<?= $row["brand_id"] ?>">
                          <?= ucwords($row["brand_name"]) ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-success btn-sm w-100" data-toggle="modal"
                      data-target="#add_brand_modal">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>

                  <div class="col-md-5 mb-3">
                    <label>Product Category</label>
                    <select class="form-control searchableSelect" required name="category_id" id="tableData1">
                      <option value="">Select Category</option>
                      <?php
                      $result = mysqli_query($dbc, "SELECT * FROM categories ORDER BY categories_name ASC");
                      while ($row = mysqli_fetch_array($result)) {
                      ?>
                        <option data-price="<?= $row["category_price"] ?>"
                          <?= @$fetchproduct['category_id'] == $row["categories_id"] || @$duplicate_product['category_id'] == $row["categories_id"] ? "selected" : "" ?>
                          value="<?= $row["categories_id"] ?>">
                          <?= ucwords($row["categories_name"]) ?>
                        </option>
                      <?php } ?>
                    </select>

                  </div>

                  <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" data-toggle="modal"
                      data-target="#add_category_modal">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 mb-3">
                    <label>SKU</label>
                    <input type="text" min="0" class="form-control" id="sku" placeholder="SKU" name="sku"
                      autocomplete="off" required value="<?= @$fetchproduct['sku'] ?? @$duplicate_product['sku'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>UOM</label>
                    <input type="text" class="form-control" id="uom" placeholder="UOM Here" name="uom" autocomplete="off"
                      required value="<?= @$fetchproduct['uom'] ?? @$duplicate_product['uom'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Length</label>
                    <input type="number" min="0" class="form-control" id="length" placeholder="Length" name="length"
                      autocomplete="off" required value="<?= @$fetchproduct['length'] ?? @$duplicate_product['length'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Width</label>
                    <input type="number" min="0" class="form-control" id="width" placeholder="Width Here" name="width"
                      autocomplete="off" required value="<?= @$fetchproduct['width'] ?? @$duplicate_product['width'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Height</label>
                    <input type="number" min="0" class="form-control" id="height" placeholder="Height Here" name="height"
                      autocomplete="off" required value="<?= @$fetchproduct['height'] ?? @$duplicate_product['height'] ?>">
                  </div>
                  <div class="col-md-1 mb-3">
                    <label>Color</label>
                    <input type="text" class="form-control" id="color" placeholder="Color Here..." name="color"
                      autocomplete="off" required value="<?= @$fetchproduct['color'] ?? @$duplicate_product['color'] ?>">
                  </div>
                  <div class="col-md-1 mb-3">
                    <label>Size</label>
                    <input type="text" class="form-control" id="size" placeholder="Size Here..." name="size"
                      autocomplete="off" required value="<?= @$fetchproduct['size'] ?? @$duplicate_product['size'] ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-2 mb-3">
                    <label>Purchase Rate</label>
                    <input type="number" min="0" step="0.0001" class="form-control" id="purchase_rate" placeholder="Purchase Rate"
                      name="purchase_rate" autocomplete="off" required value="<?= @$fetchproduct['pur_rate'] ?? @$duplicate_product['pur_rate'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Purchase Tax (%)</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="purchase_tax" placeholder="Tax %"
                      name="purchase_tax" autocomplete="off" required value="<?= @$fetchproduct['purchase_tax'] ?? @$duplicate_product['purchase_tax'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Sale Rate</label>
                    <input type="number" min="0" step="0.0001" class="form-control" id="current_rate" placeholder="Rate"
                      name="current_rate" autocomplete="off" required value="<?= @$fetchproduct['current_rate'] ?? @$duplicate_product['current_rate'] ?>">
                  </div>

                  <div class="col-md-2 mb-3">
                    <label>Wholesale Rate (Calculated)</label>
                    <input type="number" min="0" step="0.0001" class="form-control" id="wholesale_rate" placeholder="Wholesale Rate"
                      name="wholesale_rate" autocomplete="off" readonly
                      value="<?= @$fetchproduct['wholsale_rate'] ?? @$duplicate_product['wholsale_rate'] ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Product Alert on Quantity</label>
                    <input type="number" min="1" required class="form-control"
                      value="<?= empty($fetchproduct) ? 10 : $fetchproduct['alert_at'] ?? @$duplicate_product['alert_at'] ?>" id="alert_at"
                      placeholder="Product Stock Alert" name="alert_at" autocomplete="off">
                  </div>

                  <div class="col-md-2 mb-3">
                    <label>Product Image</label>
                    <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*" value="<?= @$fetchproduct['product_image'] ?? @$duplicate_product['product_image'] ?>">
                  </div>

                </div>


                <div class="form-group row">
                  <div class="col-md-6 mb-3">
                    <label>Product Description</label>
                    <input type="text" class="form-control" name="product_description" placeholder="Product Description"
                      autocomplete="off" value="<?= @$fetchproduct['product_description'] ?? @$duplicate_product['product_description'] ?>">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select class="form-control" required name="availability" id="availability">
                      <option value="1" <?= @$fetchproduct['availability'] == 1 || @$duplicate_product['availability'] == 1 ? "selected" : "" ?>>Available</option>
                      <option value="0" <?= @$fetchproduct['availability'] == "0" || @$duplicate_product['availability'] == "0" ? "selected" : "" ?>>Not Available</option>
                    </select>
                  </div>
                </div>

                <div class="text-right">
                  <button class="btn btn-admin" type="submit" id="add_product_btn">Save</button>
                </div>
              </form>
            </div>
          <?php else: ?>
            <div class="card-body">
              <table class="table dataTable col-12" style="width: 100%" id="product_tb">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Barcode</th>
                    <th>Product Name</th>
                    <!-- <th>Name Urdu</th> -->
                    <th>Brand/Category</th>
                    <?php if ($get_company['stock_manage'] == 1): ?>
                      <th>Quantity In Stock</th>
                    <?php endif; ?>
                    <th class="d-print-none">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $q = mysqli_query($dbc, "SELECT * FROM product WHERE status=1 ");
                  $c = 0;
                  while ($r = mysqli_fetch_assoc($q)) {
                    @$brandFetched = fetchRecord($dbc, "brands", "brand_id", $r['brand_id']);
                    @$categoryFetched = fetchRecord($dbc, "categories", "categories_id", $r['category_id']);
                    $c++;
                  ?>
                    <tr>
                      <td><?= $c ?></td>
                      <td>
                        <img
                          src="./img/uploads/<?= !empty($r['product_image']) ? $r['product_image'] : 'SAVYS_Logo_Final.png' ?>"
                          width="100" height="100" alt="">
                      </td>
                      <td><?= $r['product_code'] ?></td>
                      <td>
                        <?= ucwords($r['product_name']) ?>
                        <?= (!empty($r['color']) || !empty($r['size']))
                          ? " - (" . ucwords($r['color']) . " | " . ucwords($r['size']) . ")"
                          : "" ?>
                      </td>
                      <!-- <td><?= $r['product_name_urdu'] ?></td> -->
                      <td><?= $brandFetched['brand_name'] ?>/<?= $categoryFetched['categories_name'] ?></td>


                      <?php
                      $fetchProductInventory = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(quantity_instock) as quantity_instock FROM inventory WHERE product_id = '{$r['product_id']}'"));
                      ?>

                      <?php if (!empty($fetchProductInventory) && isset($fetchProductInventory['quantity_instock'])): ?>
                        <?php if ($fetchProductInventory['quantity_instock'] > $r['alert_at']): ?>
                          <td>
                            <span class="badge p-1 badge-success d-print-none">
                              <?= $fetchProductInventory['quantity_instock'] ?>
                            </span>
                          </td>
                        <?php else: ?>
                          <td>
                            <span class="badge p-1 badge-danger">
                              <?= $fetchProductInventory['quantity_instock'] ?>
                            </span>
                          </td>
                        <?php endif; ?>
                      <?php else: ?>
                        <td>
                          <span class="badge p-1 badge-secondary">N/A</span>
                        </td>
                      <?php endif; ?>


                      <td class="d-print-none">
                        <?php if (@$userPrivileges['nav_edit'] == 1 || $fetchedUserRole == "admin"): ?>
                          <form action="product.php?act=add" method="POST" class="d-inline-block">
                            <input type="hidden" name="edit_product_id" value="<?= base64_encode($r['product_id']) ?>">
                            <button type="submit" class="btn btn-admin btn-sm m-1">Edit</button>
                          </form>
                        <?php endif; ?>

                        <?php if (@$userPrivileges['nav_delete'] == 1 || $fetchedUserRole == "admin"): ?>
                          <button type="button"
                            onclick="deleteAlert('<?= $r['product_id'] ?>','product','product_id','product_tb')"
                            class="btn btn-admin2 btn-sm m-1 d-inline-block">Delete</button>
                        <?php endif; ?>

                        <a href="print_barcode.php?id=<?= base64_encode($r['product_id']) ?>"
                          class="btn btn-primary btn-sm m-1 d-inline-block">Barcode</a>

                        <form action="product.php?act=add" method="POST" class="d-inline-block">
                          <input type="hidden" name="duplicate_product_id" value="<?= base64_encode($r['product_id']) ?>">
                          <button type="submit" class="btn btn-admin btn-sm m-1">Duplicate</button>
                        </form>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php endif ?>
        </div> <!-- .row -->
      </div> <!-- .container-fluid -->

    </main> <!-- main -->
  </div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>
<script type="text/javascript">
  <?php if ($_REQUEST['act'] == "add" && !isset($_REQUEST['edit_product_id'])) { ?>
    Swal.fire({
      icon: 'info',
      title: 'Note',
      text: 'Before Scanning Barcode, first focus on Product Barcode Input then scan or generate it manually',
      timer: 2500,
      showConfirmButton: false
    });
  <?php } ?>

  var typingTimer; // Global timer identifier
  var doneTypingInterval = 1000; // 1 second

  document.addEventListener("keypress", function(e) {
    var activeId = document.activeElement.id;
    var input = document.querySelector("#product_code");
    var product_name = document.querySelector("#product_name");

    if (product_name !== document.activeElement && input.value === '') {
      if (input !== document.activeElement && e.target.id !== "product_code") {
        return; // do nothing
      } else {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
      }
    }
  });

  function doneTyping() {
    document.querySelector("#product_name").focus();
  }

  // Function to calculate wholesale rate
  function calculateWholesaleRate() {
    const purchaseRate = parseFloat(document.getElementById('purchase_rate').value) || 0;
    const purchaseTax = parseFloat(document.getElementById('purchase_tax').value) || 0;

    const taxAmount = (purchaseRate * purchaseTax) / 100;
    const wholesaleRate = purchaseRate + taxAmount;

    document.getElementById('wholesale_rate').value = wholesaleRate.toFixed(4);
  }

  // Add event listeners for automatic calculation
  document.addEventListener('DOMContentLoaded', function() {
    const purchaseRateInput = document.getElementById('purchase_rate');
    const purchaseTaxInput = document.getElementById('purchase_tax');

    if (purchaseRateInput) {
      purchaseRateInput.addEventListener('input', calculateWholesaleRate);
      purchaseRateInput.addEventListener('change', calculateWholesaleRate);
    }

    if (purchaseTaxInput) {
      purchaseTaxInput.addEventListener('input', calculateWholesaleRate);
      purchaseTaxInput.addEventListener('change', calculateWholesaleRate);
    }

    // Calculate initial wholesale rate on page load
    calculateWholesaleRate();
  });
</script>