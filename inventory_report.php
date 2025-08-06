<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';

if (!empty($_REQUEST['edit_purchase_id'])) {
    # code...
    $fetchPurchase = fetchRecord($dbc, "purchase", "purchase_id", base64_decode($_REQUEST['edit_purchase_id']));
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
                            <b class="text-center card-text">Inventory Report</b>
                            <!-- <a href="credit_purchase.php" class="btn btn-admin float-right btn-sm">Add New</a> -->
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <form action="php_action/custom_action.php" id="inventory_repport" class="mb-5" method="post">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="warehouse_id" class="control-label">Warehouse </label>

                                <select class="form-control searchableSelect text-capitalize" name="warehouse_report" id="warehouse_id" required>
                                    <option selected disabled>Select Warehouse</option>
                                    <?php $warehouse = mysqli_query($dbc, "SELECT * FROM warehouse WHERE warehouse_status = 1");
                                    while ($row = mysqli_fetch_array($warehouse)) { ?>
                                        ?>
                                        <option class="text-capitalize" <?= @($fetchusers['warehouse_id'] == $row['warehouse_id']) ? "selected" : "" ?> value="<?= $row['warehouse_id'] ?>"><?= $row['warehouse_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="rack_id" class="control-label">Rack </label>

                                <select class="form-control searchableSelect text-capitalize" name="rack_report" id="rack_id" required>
                                    <option selected disabled>Select Rack</option>
                                    <!-- <?php $warehouse = mysqli_query($dbc, "SELECT * FROM racks WHERE status = 1");
                                    while ($row = mysqli_fetch_array($warehouse)) { ?>
                                        ?>
                                        <option class="text-capitalize" <?= @($fetchusers['rack_id'] == $row['rack_id']) ? "selected" : "" ?> value="<?= $row['rack_id'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?> -->
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="product_id" class="control-label">Product </label>

                                <select class="form-control searchableSelect text-capitalize" name="product_report" id="product_id" required>
                                    <option selected disabled>Select Product</option>
                                    <?php $warehouse = mysqli_query($dbc, "SELECT * FROM product WHERE status = 1");
                                    while ($row = mysqli_fetch_array($warehouse)) { ?>
                                        ?>
                                        <option class="text-capitalize" <?= @($fetchusers['product_id'] == $row['product_id']) ? "selected" : "" ?> value="<?= $row['product_id'] ?>"><?= $row['product_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success " name="submit">Get Report</button>
                            </div>
                        </div>
                    </form>


                    <table class="table table-bordered table-striped table-hover mt-3 dataTable mt-5" id="inventory_report_table">
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Warehouse Name</th>
                                <th>Rack Number</th>
                                <th>Rack Name</th>
                                <th>Rack Zone</th>
                                <th>Product Name</th>
                                <th>Quantity In Stock</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->


    </div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>



<script>
    $("#inventory_repport").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'php_action/custom_action.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#inventory_report_table tbody').html(response.data);
                } else {
                    $('#inventory_report_table tbody').html(
                        '<tr><td colspan="7" class="text-center text-danger">' + response.message + '</td></tr>'
                    );
                }
            },
            error: function(xhr, status, error) {
                $('#inventory_report_table tbody').html(
                    '<tr><td colspan="7" class="text-center text-danger">An error occurred while processing your request.</td></tr>'
                );
            }
        });
    });
</script>