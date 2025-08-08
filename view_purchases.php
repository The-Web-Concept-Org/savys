<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php'; ?>

<body class="horizontal light  ">
  <style>
    /* Style for the details control button */
    td.details-control {

      cursor: pointer;
      width: 30px;
      height: 30px;
    }



    /* Style for the details content */
    .details-content {
      padding: 20px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      margin: 10px 0;
    }

    .details-content h5 {
      margin-bottom: 15px;
      color: #495057;
    }

    /* Style for DataTables buttons */
    .dt-buttons {
      margin-bottom: 10px;
    }

    .dt-button {
      margin-right: 5px;
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
                  <th></th> <!-- Details control column -->
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
                    <td class="details-control">
                      <button class=" btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                          height="16" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                          <path fill-rule="evenodd"
                            d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                        </svg>
                      </button>
                    </td>
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
                          <a href="#"
                            onclick="deleteAlert('<?= $r['purchase_id'] ?>','purchase','purchase_id','view_purchase_tb')"
                            class="btn btn-danger btn-sm m-1">Delete</a>
                        <?php endif; ?>
                        <a target="_blank" href="print_order.php?id=<?= $r['purchase_id'] ?>&type=purchase"
                          class="btn btn-admin2  btn-sm m-1">Print</a>
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
  $(document).ready(function () {
    // Initialize DataTable with row details
    var table = $('#view_purchase_tb').DataTable({
      autoWidth: true,
      lengthMenu: [
        [10, 20, 50, -1],
        [10, 20, 50, "All"],
      ],
      order: [
        [1, "desc"] // Order by purchase ID (second column)
      ],
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'excel',
          text: 'Export to Excel',
          className: 'btn btn-success btn-sm',
          exportOptions: {
            columns: ':visible:not(:first-child):not(:last-child)' // Exclude details control and action columns
          }
        }
      ],
      pageLength: 10,
      responsive: true,
      columnDefs: [
        { orderable: false, targets: [0, -1] } // Disable sorting on details control and action columns
      ]
    });

    // Add event listener for opening and closing details
    $('#view_purchase_tb tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      var purchaseId = row.data()[1]; // Get purchase ID from second column

      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
      } else {
        // Open this row
        row.child(formatDetails(purchaseId)).show();
        tr.addClass('shown');
      }
    });
  });

  // Function to format the details row
  function formatDetails(purchaseId) {
    // Create a loading placeholder
    var detailsHtml = '<div class="details-content">' +
      '<h5>Purchase Items for Purchase ID: ' + purchaseId + '</h5>' +
      '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>' +
      '</div>';

    // Fetch purchase items via AJAX
    console.log('Making AJAX request for purchase_id:', purchaseId);
    $.ajax({
      url: 'php_action/fetch_purchase_items.php',
      type: 'POST',
      data: { purchase_id: purchaseId },
      dataType: 'json',
      beforeSend: function () {
        console.log('AJAX request started for purchase_id:', purchaseId);
      },
      success: function (response) {
        console.log('AJAX response received:', response);
        if (response.success) {
          var itemsHtml = '<div class="details-content">' +
            '<h5>Purchase Items for Purchase ID: ' + purchaseId + '</h5>';

          if (response.items.length > 0) {
            itemsHtml += '<table class="table table-bordered table-sm">' +
              '<thead class="table-light">' +
              '<tr>' +
              '<th>Sr</th>' +
              '<th>Image</th>' +
              '<th>Rack Barcode Number</th>' +
              '<th>Name</th>' +
              '<th>Quantity</th>' +
              '<th>Price</th>' +
              '<th>Total</th>' +
              '<th>Action</th>' +
              '</tr>' +
              '</thead>' +
              '<tbody>';

            response.items.forEach(function (item, index) {
              itemsHtml += '<tr>' +
                '<td>' + (index + 1) + '</td>' +
                '<td><img src="' + item.product_image + '" alt="Product Image" style="height:60px;object-fit:cover;border-radius:6px;" /></td>' +
                '<td>' + item.rack_number + '</td>' +
                '<td>' + item.product_name + '</td>' +
                '<td>' + item.quantity + '</td>' +
                '<td>' + item.rate + '</td>' +
                '<td>' + (item.quantity * item.rate) + '</td>' +
                '<td>' +
                '<a href="generate_barcode.php?id=' + btoa(item.purchase_item_id) + '" class="btn btn-primary btn-sm" target="_blank">Generate Barcode</a>' +
                '</td>' +
                '</tr>';
            });

            itemsHtml += '</tbody></table>';
          } else {
            itemsHtml += '<div class="alert alert-info">No items found for this purchase.</div>';
          }

          itemsHtml += '</div>';

          // Update the details content
          var row = $('#view_purchase_tb').DataTable().row(function (idx, data, node) {
            return data[1] == purchaseId;
          });
          row.child(itemsHtml).show();
        } else {
          // Show error message
          var errorHtml = '<div class="details-content">' +
            '<h5>Purchase Items for Purchase ID: ' + purchaseId + '</h5>' +
            '<div class="alert alert-danger">Error loading purchase items: ' + response.message + '</div>' +
            '</div>';

          var row = $('#view_purchase_tb').DataTable().row(function (idx, data, node) {
            return data[1] == purchaseId;
          });
          row.child(errorHtml).show();
        }
      },
      error: function (xhr, status, error) {
        console.log('AJAX error:', { xhr: xhr, status: status, error: error });
        console.log('Response text:', xhr.responseText);

        // Show error message
        var errorHtml = '<div class="details-content">' +
          '<h5>Purchase Items for Purchase ID: ' + purchaseId + '</h5>' +
          '<div class="alert alert-danger">Error loading purchase items. Please try again.<br>Status: ' + status + '<br>Error: ' + error + '</div>' +
          '</div>';

        var row = $('#view_purchase_tb').DataTable().row(function (idx, data, node) {
          return data[1] == purchaseId;
        });
        row.child(errorHtml).show();
      }
    });

    return detailsHtml;
  }
</script>