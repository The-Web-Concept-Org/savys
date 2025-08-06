<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';
//include_once 'includes/code.php';
?>

<body class="horizontal light  ">
	<div class="wrapper">
		<?php include_once 'includes/header.php'; ?>
		<main role="main" class="main-content">
			<div class="container">
				<div class="row ">
					<div class="col-12">
						<div class="card">
							<div class="card-header card-bg text-center">
								<h4 class="card-text">Create Users</h4>
							</div>
							<div class="card-body">
								<?= getMessage(@$msg, @$sts); ?>
								<form class="form-horizontal" method="POST" action="includes/code.php" id="myForm">
									<input type="hidden" name="action" value="add_new_user">
									<input type="hidden" name="new_user_id" value="<?= @$_REQUEST['user_edit_id'] ?>">

									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Full Name</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="fullname" placeholder="Enter full name" autocomplete="off" required value="<?= ucwords(@$fetchusers['fullname']) ?>" />
										</div>

										<label class="col-sm-2 col-form-label">Username</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" pattern="^[a-zA-Z0-9]*$" id="check_userName" name="username" placeholder="Enter username" autocomplete="off" required value="<?= @$fetchusers['username'] ?>" />
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Phone Number</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="phone" placeholder="Enter phone number" autocomplete="off" required value="<?= @$fetchusers['phone'] ?>" />
										</div>

										<label class="col-sm-2 col-form-label">Email</label>
										<div class="col-sm-4">
											<input type="email" class="form-control" name="email" placeholder="Enter email" autocomplete="off" required value="<?= @$fetchusers['email'] ?>" />
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Password</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="password" placeholder="Enter password" autocomplete="off" />
											<small class="text-danger">Password will be encrypted for security.</small>
											<input type="hidden" name="old_password" value="<?= isset($_REQUEST['user_edit_id']) ? $fetchusers['password'] : '123456' ?>" />
										</div>

										<label class="col-sm-2 col-form-label">User Role</label>
										<div class="col-sm-4">
											<select class="form-control" name="user_role" required>
												<option value="">Select Role</option>
												<option value="subadmin" <?= @$fetchusers['user_role'] == 'subadmin' ? 'selected' : '' ?>>Sub Admin</option>
												<option value="manager" <?= @$fetchusers['user_role'] == 'manager' ? 'selected' : '' ?>>Manager</option>
												<option value="cashier" <?= @$fetchusers['user_role'] == 'cashier' ? 'selected' : '' ?>>Cashier</option>
												<option value="localusers" <?= @$fetchusers['user_role'] == 'localusers' ? 'selected' : '' ?>>Local User</option>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Warehouse</label>
										<div class="col-sm-4">
											<select class="form-control searchableSelect text-capitalize" name="warehouse_id" id="warehouse_id" required>
												<option selected disabled>Select Warehouse</option>
												<?php
												$warehouse = mysqli_query($dbc, "SELECT * FROM warehouse WHERE warehouse_status = 1");
												while ($row = mysqli_fetch_array($warehouse)) { ?>
													<option class="text-capitalize" <?= @($fetchusers['warehouse_id'] == $row['warehouse_id']) ? "selected" : "" ?> value="<?= $row['warehouse_id'] ?>">
														<?= ucwords($row['warehouse_name']) ?>
													</option>
												<?php } ?>
											</select>
										</div>

										<label class="col-sm-2 col-form-label">Status</label>
										<div class="col-sm-4">
											<select class="form-control" name="status" required>
												<option value="1" <?= @($fetchusers['status'] == "1") ? "selected" : "" ?>>Active</option>
												<option value="0" <?= @($fetchusers['status'] == "0") ? "selected" : "" ?>>Not Active</option>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Address</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="address" placeholder="Enter address" autocomplete="off" required value="<?= ucwords(@$fetchusers['address']) ?>" />
										</div>
									</div>

									<div class="form-group row">
										<div class="col-sm-2 offset-sm-10 text-right">
											<?= $users_button; ?>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<div class="card mt-2">
							<div class="card-header cyan-bgcolor text-center">
								<h4 class="text-white">Users List</h4>
							</div>
							<div class="card-body">
								<?= getMessage(@$msg, @$sts); ?>
								<table class="table example1" id="myTable">
									<thead class="thead-light">
										<tr>
											<th>User ID</th>
											<th>Username</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Address</th>
											<th>User Role</th>
											<th>Status</th>
											<th>Action</th>
											<th>Set Privileges</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT * FROM users";
										$result = mysqli_query($dbc, $sql);

										if (mysqli_num_rows($result) > 0):
											while ($row = mysqli_fetch_array($result)):
										?>
												<tr>
													<td><?= $row['user_id']; ?></td>
													<td><?= ucwords($row['username']); ?></td>
													<td><?= $row['email']; ?></td>
													<td><?= $row['phone']; ?></td>
													<td><?= ucwords($row['address']); ?></td>
													<td><?= ucwords($row['user_role']); ?></td>
													<td>
														<?php if ($row['status'] == '1'): ?>
															<span class="badge badge-success">Available</span>
														<?php else: ?>
															<span class="badge badge-danger">Not Available</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if (@$userPrivileges['nav_delete'] == 1 || $fetchedUserRole == "admin"): ?>
															<form action="users.php" method="POST">
																<input type="hidden" name="user_del_id" value="<?= $row['user_id'] ?>">
																<button type="submit" class="btn btn-admin2 btn-sm m-1">Delete</button>
															</form>
														<?php endif ?>

														<?php if (@$userPrivileges['nav_edit'] == 1 || $fetchedUserRole == "admin"): ?>
															<form action="users.php" method="POST">
																<input type="hidden" name="user_edit_id" value="<?= $row['user_id'] ?>">
																<button type="submit" class="btn btn-admin btn-sm m-1">Edit</button>
															</form>
														<?php endif ?>
													</td>
													<td>
														<a href="privileges.php?new_user_id=<?= base64_encode($row['user_id']) ?>" target="_blank" class="btn btn-secondary btn-sm" title="Set Privileges">
															<i class="fa fa-user text-white"></i>
														</a>
													</td>
												</tr>
										<?php endwhile;
										endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>
</body>

</html>
<?php include_once 'includes/foot.php'; ?>