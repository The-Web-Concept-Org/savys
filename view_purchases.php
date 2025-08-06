<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php'; ?>

<body class="horizontal light  ">
  <style>
    .purchase-row {
      cursor: pointer;
    }

    .purchase-row:hover {
      background-color: #f1f1f1;
    }

    .dropdown-content {
      background-color: #f9f9f9;
    }

    .dropdown-items {
      padding: 10px;
    }
  </style>
  <div class="wrapper">
    <?php include_once 'includes/header.php'; ?>
    <main role="main" class="main-content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header card-bg" align="center">

            <div class="row">
              <div class="col-12 mx-auto h4">
                <b class="text-center card-text">Purchase List</b>


              </div>
            </div>

          </div>
          <div class="card-body">
            <table class="table dataTable" id="view_purchase_tb">
              <thead>
                <tr>
                  <th><strong>#</strong></th>
                  <th><strong>Customer Name</strong></th>
                  <th><strong>Customer Contact</strong></th>
                  <th><strong>Date</strong></th>
                  <th><strong>Amount</strong></th>
                  <th><strong>Purchase Type</strong></th>
                  <th><strong>Action</strong></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $q = mysqli_query($dbc, "SELECT * FROM purchase ORDER BY purchase_id DESC");
                $c = 0;
                while ($r = mysqli_fetch_assoc($q)) {
                  $c++;
                ?>
                  <tr>
                    <td><?= $r['purchase_id'] ?></td>
                    <td><?= ucfirst($r['client_name']) ?></td>
                    <td><?= $r['client_contact'] ?></td>
                    <td><?= $r['purchase_date'] ?></td>
                    <td><?= $r['grand_total'] ?></td>
                    <td><?= $r['payment_type'] ?></td>
                    <td>
                      <div class="d-flex">

                        <?php if (@$userPrivileges['nav_edit'] == 1 || $fetchedUserRole == "admin" && $r['payment_type'] == "cash_purchase"): ?>
                          <form action="cash_purchase.php" method="POST">
                            <input type="hidden" name="edit_purchase_id" value="<?= base64_encode($r['purchase_id']) ?>">
                            <button type="submit" class="btn btn-admin btn-sm m-1">Edit</button>
                          </form>
                        <?php endif; ?>
                        <?php if (@$userPrivileges['nav_edit'] == 1 || $fetchedUserRole == "admin" && $r['payment_type'] == "credit_purchase"): ?>
                          <form action="credit_purchase.php" method="POST">
                            <input type="hidden" name="edit_purchase_id" value="<?= base64_encode($r['purchase_id']) ?>">
                            <button type="submit" class="btn btn-admin btn-sm m-1">Edit</button>
                          </form>
                        <?php endif; ?>
                        <?php if (@$userPrivileges['nav_delete'] == 1 || $fetchedUserRole == "admin"): ?>
                          <a href="#" onclick="deleteAlert('<?= $r['purchase_id'] ?>','purchase','purchase_id','view_purchase_tb')" class="btn btn-danger btn-sm m-1">Delete</a>
                        <?php endif; ?>
                        <a target="_blank" href="print_order.php?id=<?= $r['purchase_id'] ?>&type=purchase" class="btn btn-admin2  btn-sm m-1">Print</a>
                        <button class="purchase-row btn btn-primary btn-sm m-1 ml-auto" data-toggle="dropdown" data-id="<?= $r['purchase_id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                            <path d="M3.204 5h9.592L8 10.481zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659" />
                          </svg></button>
                      </div>
                    </td>
                  </tr>
                  <!-- Dropdown row for purchase items -->
                  <tr class="dropdown-content" id="dropdown-<?= $r['purchase_id'] ?>" style="display: none;">
                    <td colspan="7">
                      <div class="dropdown-items">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Sr</th>
                              <th>Rack Barcode Number</th>
                              <th>Name</th>
                              <th>Quantity</th>
                              <th>Price</th>
                              <th>Total</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            // Fetch purchase items for this purchase_id
                            $i = 0;
                            $items_query = mysqli_query($dbc, "SELECT * FROM purchase_item WHERE purchase_id = " . $r['purchase_id']);
                            if (mysqli_num_rows($items_query) > 0) {
                              while ($item = mysqli_fetch_assoc($items_query)) {
                                $i++;
                            ?>
                                <tr>
                                  <td><?= $i ?></td>
                                  <td><?= $item['rack_number'] ?></td>
                                  <td>
                                    <?php
                                    // Fetch product details for each item
                                    $getProduct = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM product WHERE product_id = " . $item['product_id']));
                                    if ($getProduct) {
                                      echo ucfirst($getProduct['product_name']);
                                    } else {
                                      echo "Unknown Product";
                                    }
                                    ?>
                                  </td>
                                  <td><?= $item['quantity'] ?></td>
                                  <td><?= $item['rate'] ?></td>
                                  <td><?= $item['rate'] ?></td>
                                  <td>
                                    <a href="generate_barcode.php?id=<?=base64_encode( $item['purchase_item_id']) ?>"><button class=" btn btn-primary btn-sm m-1">Generate Barcode</button></a>
                                  </td>
                                </tr>
                              <?php
                              }
                            } else {
                              ?>
                              <tr>
                                <td colspan="5">No items found for this purchase.</td>
                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div> <!-- .row -->
      </div> <!-- .container-fluid -->

    </main> <!-- main -->
  </div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    const rows = document.querySelectorAll('.purchase-row');
    rows.forEach(row => {
      row.addEventListener('click', function() {
        const purchaseId = this.getAttribute('data-id');
        const dropdown = document.getElementById(`dropdown-${purchaseId}`);
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
          dropdown.style.display = 'table-row';
        } else {
          dropdown.style.display = 'none';
        }
      });
    });
  });
</script>