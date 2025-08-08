<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';
//include_once 'includes/code.php';

$getCustomer = @$_REQUEST['rack_edit_id'];
if (@$getCustomer) {
    $fetchusers = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM racks WHERE rack_id = '$getCustomer'"));
}

?>

<body class="horizontal light  ">
    <div class="wrapper">
        <?php include_once 'includes/header.php'; ?>
        <main role="main" class="main-content">
            <div class="container">
                <div class="row ">
                    <div class="col-12">

                        <div class="card ">
                            <div class="card-header card-bg " align="center">
                                <h4 class="card-text"> Manage Racks</h4>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" method="POST" action="includes/code.php" id="myForm">
                                    <input type="hidden" name="action" value="add_new_rack">
                                    <input type="hidden" name="new_rack_id" value="<?= @$_REQUEST['rack_edit_id'] ?>">

                                    <div class="form-group row">
                                        <label for="clientContact" class="col-sm-2 control-label">Rack Name</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="rack_name" name="rack_name" placeholder="Name Here..." autocomplete="off" required value="<?= @$fetchusers['name'] ?>" />
                                        </div>
                                        <label for="clientContact" class="col-sm-2 control-label">Rack Zone</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="rack_zone" name="rack_zone" placeholder="Zone Here..." autocomplete="off" required value="<?= @$fetchusers['zone'] ?>" />
                                        </div>

                                    </div> <!--/form-group-->
                                    <div class="form-group row">
                                        <label for="clientContact" class="col-sm-2 control-label">Rack Capacity</label>
                                        <div class="col-sm-4">
                                            <input type="text"  class="form-control" id="rack_capacity" name="rack_capacity" placeholder="Capacity Here..." autocomplete="off" required value="<?= @$fetchusers['capacity'] ?>" />
                                        </div>

                                        <label for="warehouse_id" class="col-sm-2 control-label">Warehouse </label>

										<div class="col-sm-4">
											<select class="form-control searchableSelect text-capitalize" name="warehouse_id" id="warehouse_id" required>
												<option selected disabled>Select Warehouse</option>
												<?php $warehouse = mysqli_query($dbc, "SELECT * FROM warehouse WHERE warehouse_status = 1");
												while ($row = mysqli_fetch_array($warehouse)) { ?>
													?>
													<option  class="text-capitalize" <?= @($fetchusers['warehouse_id'] == $row['warehouse_id']) ? "selected" : "" ?> value="<?= $row['warehouse_id'] ?>"><?= $row['warehouse_name'] ?></option>
												<?php } ?>
											</select>
										</div>
                                        <label for="clientContact" class="col-sm-2 mt-3 control-label">Status </label>

                                        <div class="col-sm-4 mt-3">
                                            <select class="form-control" name="rack_status">
                                                <option <?= @($fetchusers['status'] == "1") ? "seleted" : "" ?> value="1">Active</option>
                                                <option <?= @($fetchusers['status'] == "0") ? "seleted" : "" ?> value="0">Not Active</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mt-3 ml-auto">
                                            <?= $users_button; ?>
                                        </div>


                                    </div> <!--/form-group-->

                                


                                </form>
                                <br><br>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12">
                        <div class="card mt-2">
                            <div class="card-header cyan-bgcolor" align="center">
                                <h4>Rack List</h4>
                            </div>
                            <div class="card-body">
                                <table class="table example1" id="myTable">
                                    <thead>
                                        <tr>
                                            <th class="text-dark">Sr</th>
                                            <th class="text-dark">Rack Name</th>
                                            <th class="text-dark">Rack Zone</th>
                                            <th class="text-dark">Rack Capacity</th>
                                            <th class="text-dark">Rack Status</th>
                                            <th class="text-dark">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php



                                        $sql = "SELECT * FROM `racks` WHERE status = '1' ";

                                        $result = mysqli_query($dbc, $sql);

                                        if (mysqli_num_rows($result) > 0) {
                                            $a = 0;
                                            while ($row = mysqli_fetch_array($result)) {
                                                $a++;
                                        ?>
                                                <tr>
                                                    <td><?= $a ?></td>
                                                    <td class="text-capitalize"><?= $row['name']; ?></td>
                                                    <td class=""><?= $row['zone']; ?></td>
                                                    <!-- <td>Encrypted </td> -->
                                                    <td><?= $row['capacity'] ?></td>

                                                    <td>
                                                        <?php
                                                        if ($row['status'] == '1') {
                                                        ?>
                                                            <span class="label label-lg label-info">Active</span>
                                                        <?php
                                                            # code...
                                                        } else {
                                                        ?>
                                                            <span class="label label-lg label-danger"> Not Active</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <!-- <td><?= date('D, d-M-Y', strtotime($row['adddatetime'])); ?> -->


                                                    </td>
                                                    <td class="d-flex">
                                                        <?php if (@$userPrivileges['nav_delete'] == 1 || $fetchedUserRole == "admin"): ?>
                                                            <form action="rack.php" method="POST">
                                                                <input type="hidden" name="rack_del_id" value="<?= $row['rack_id'] ?>">
                                                                <button type="submit" class="btn btn-admin2 btn-sm m-1" onclick="deleteData('racks', 'rack_id', <?= $row['rack_id'] ?>, 'rack.php')">Delete</button>
                                                            </form>
                                                        <?php endif ?>

                                                        <?php if (@$userPrivileges['nav_edit'] == 1 || $fetchedUserRole == "admin"): ?>
                                                            <form action="rack.php" method="POST">
                                                                <input type="hidden" name="rack_edit_id" value="<?= $row['rack_id'] ?>">
                                                                <button type="submit" class="btn btn-admin btn-sm m-1">Edit</button>
                                                            </form>
                                                        <?php endif ?>
                                                    </td>


                                                </tr>
                                    </tbody>
                            <?php
                                            }
                                        }
                            ?>


                                </table>

                            </div>
                        </div>
                    </div>
                </div> <!-- .row -->
            </div> <!-- .container-fluid -->

        </main> <!-- main -->
    </div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>