<?php include('user.php') ?>
<style>
    .table-responsive {
        overflow-x: auto;
    }
</style>
<div class="card basic-data-table">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h4 class="card-title">Software Login List</h4>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table
            class="table bordered-table mb-0"
            id="dataTable"
            data-page-length="10">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Mobile No</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">User Type</th>
                    <th scope="col">Status</th>
                    <th scope="col">Details</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Change Pass</th>
                </tr>
            </thead>
             <tbody>
                <?php $i = 1;
                $userId = $_SESSION['userid'];
                if ($userId != 2) {
                    $Users = $obj->raw_sql("SELECT * FROM _createuser ORDER BY UserId DESC LIMIT 5");
                    foreach ($Users as $value) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo isset($value['FullName']) ? $value['FullName'] : NULL; ?></td>
                            <td><?php echo isset($value['UserName']) ? $value['UserName'] : NULL; ?></td>
                            <td><?php echo isset($value['Email']) ? $value['Email'] : NULL; ?></td>
                            <td><?php echo isset($value['MobileNo']) ? $value['MobileNo'] : NULL; ?></td>
                            <td><?php echo isset($value['companyName']) ? $value['companyName'] : NULL; ?></td>
                            <td><?php echo isset($value['Address']) ? $value['Address'] : NULL; ?></td>
                            <td> <?php $userTypeLabels = ['SA' => 'Super Admin', 'EO' => 'Entry Operator'];
                                    echo isset($value['UserType']) ? ($userTypeLabels[$value['UserType']] ?? '') : NULL; ?>
                            </td>
                            <td> <?php echo ($value['Status'] == '1') ? '<span class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Active <i class="fas fa-check"></i></span>' : '<span class="bg-danger-200 text-danger-600 border border-neutral-400 px-24 py-4 radius-4 fw-medium text-sm">InActive <i class="fas fa-times text-danger"></i></span>' ?> </td>
                            <td>
                                <div id="modal<?php echo $value['UserId'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content p-0">
                                            <div class="card mb-0">
                                                <div class="card-header">
                                                    <h5 class="text-center"> <?php echo isset($value['FullName']) ? $value['FullName'] : NULL; ?> </h5> <img src="<?php echo $value['PhotoPath']; ?>" class="img-thumbnail" style="width: 100px;height: 100px;">
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <!-- Full Name -->
                                                        <div class="col-md-12">
                                                            <strong>Full Name</strong><br>
                                                            <?php echo $value['FullName']; ?>
                                                        </div>

                                                        <!-- User Name -->
                                                        <div class="col-md-4">
                                                            <strong>User Name</strong><br>
                                                            <?php echo $value['UserName']; ?>
                                                        </div>

                                                        <!-- Email -->
                                                        <div class="col-md-4">
                                                            <strong>Email</strong><br>
                                                            <?php echo $value['Email']; ?>
                                                        </div>

                                                        <!-- Mobile No -->
                                                        <div class="col-md-4">
                                                            <strong>Mobile No</strong><br>
                                                            <?php echo $value['MobileNo']; ?>
                                                        </div>

                                                        <!-- National ID -->
                                                        <div class="col-md-4">
                                                            <strong>National ID</strong><br>
                                                            <?php echo $value['NationalId']; ?>
                                                        </div>

                                                        <!-- Address -->
                                                        <div class="col-md-4">
                                                            <strong>Address</strong><br>
                                                            <?php echo $value['Address']; ?>
                                                        </div>

                                                        <!-- Status -->
                                                        <div class="col-md-4">
                                                            <strong>Status</strong><br>
                                                            <?php echo ($value['Status'] == '1') ? '<span class="text-success">Active <i class="fas fa-check"></i></span>' : '<span class="text-danger">InActive <i class="fas fa-times text-danger"></i></span>' ?>
                                                        </div>

                                                        <!-- User Type -->
                                                        <div class="col-md-4">
                                                            <strong>User Type</strong><br>
                                                            <?php echo isset($value['UserType']) ? ($userTypeLabels[$value['UserType']] ?? '') : NULL; ?>
                                                        </div>

                                                        <!-- Menu Permission -->
                                                        <div class="col-md-12 text-wrap">
                                                            <strong>Menu Permission</strong><br>
                                                            <?php
                                                            $menuPermissions = unserialize($value['MenuPermission']);
                                                            foreach ($menuPermissions as $permission) {
                                                                echo str_replace('_', ' ', $permission) . ', ';
                                                            }
                                                            ?>
                                                        </div>

                                                        <!-- Work Permission -->
                                                        <div class="col-md-12">
                                                            <strong>Work Permission</strong><br>
                                                            <?php $workPermissions = unserialize($value['WorkPermission']);
                                                            foreach ($workPermissions as $permission) {
                                                                echo str_replace('_', ' ', $permission) . ', ';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-secondary waves-effect waves-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal<?php echo $value['UserId']; ?>">
                                    <!-- <iconify-icon icon="mdi:eye"></iconify-icon> -->
                                    <i class="fas fa-eye"></i>
                                </button>

                            </td>

                            <td><a class=" bg-info-200 btn btn-blue waves-effect waves-light" <?php if ($ty == 'SA') { ?> href="?page=user_edit&token=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>" <?php   } ?>><span class="fas fa-edit"></span></a></td>
                            <td><button type="button" class="btn btn-warning waves-effect waves-light passwordchange" data-passwordid="<?php echo $value['UserId'] ?>" data-bs-toggle="modal" data-bs-target="#con-close-modal">Change</button> </td>
                        </tr>
                    <?php
                    }
                } else {
                    foreach ($obj->getAllData("vw_user_info") as $value) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo isset($value['FullName']) ? $value['FullName'] : NULL; ?></td>
                            <td><?php echo isset($value['UserName']) ? $value['UserName'] : NULL; ?></td>
                            <td><?php echo isset($value['Email']) ? $value['Email'] : NULL; ?></td>
                            <td><?php echo isset($value['MobileNo']) ? $value['MobileNo'] : NULL; ?></td>
                            <td><?php echo isset($value['companyName']) ? $value['companyName'] : NULL; ?></td>
                            <td><?php echo isset($value['Address']) ? $value['Address'] : NULL; ?></td>
                            
                            <td> <?php $userTypeLabels = ['SA' => 'Super Admin', 'EO' => 'Entry Operator'];
                                    echo isset($value['UserType']) ? ($userTypeLabels[$value['UserType']] ?? '') : NULL; ?>
                            </td>
                            <td> <?php echo ($value['Status'] == '1') ? '<span class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Active <i class="fas fa-check"></i></span>' : '<span class="bg-danger-200 text-danger-600 border border-neutral-400 px-24 py-4 radius-4 fw-medium text-sm">InActive <i class="fas fa-times text-danger"></i></span>' ?> </td>
                            <td>
                                <div id="modal<?php echo $value['UserId'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content p-0">
                                            <div class="card mb-0">
                                                <div class="card-header">
                                                    <h5 class="text-center"> <?php echo isset($value['FullName']) ? $value['FullName'] : NULL; ?> </h5> <img src="<?php echo $value['PhotoPath']; ?>" class="img-thumbnail" style="width: 100px;height: 100px;">
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <!-- Full Name -->
                                                        <div class="col-md-12">
                                                            <strong>Full Name</strong><br>
                                                            <?php echo $value['FullName']; ?>
                                                        </div>

                                                        <!-- User Name -->
                                                        <div class="col-md-4">
                                                            <strong>User Name</strong><br>
                                                            <?php echo $value['UserName']; ?>
                                                        </div>

                                                        <!-- Email -->
                                                        <div class="col-md-4">
                                                            <strong>Email</strong><br>
                                                            <?php echo $value['Email']; ?>
                                                        </div>

                                                        <!-- Mobile No -->
                                                        <div class="col-md-4">
                                                            <strong>Mobile No</strong><br>
                                                            <?php echo $value['MobileNo']; ?>
                                                        </div>

                                                        <!-- National ID -->
                                                        <div class="col-md-4">
                                                            <strong>National ID</strong><br>
                                                            <?php echo $value['NationalId']; ?>
                                                        </div>

                                                        <!-- Address -->
                                                        <div class="col-md-4">
                                                            <strong>Address</strong><br>
                                                            <?php echo $value['Address']; ?>
                                                        </div>

                                                        <!-- Status -->
                                                        <div class="col-md-4">
                                                            <strong>Status</strong><br>
                                                            <?php echo ($value['Status'] == '1') ? '<span class="text-success">Active <i class="fas fa-check"></i></span>' : '<span class="text-danger">InActive <i class="fas fa-times text-danger"></i></span>' ?>
                                                        </div>

                                                        <!-- User Type -->
                                                        <div class="col-md-4">
                                                            <strong>User Type</strong><br>
                                                            <?php echo isset($value['UserType']) ? ($userTypeLabels[$value['UserType']] ?? '') : NULL; ?>
                                                        </div>

                                                        <!-- Menu Permission -->
                                                        <div class="col-md-12 text-wrap">
                                                            <strong>Menu Permission</strong><br>
                                                            <?php
                                                            $menuPermissions = unserialize($value['MenuPermission']);
                                                            foreach ($menuPermissions as $permission) {
                                                                echo str_replace('_', ' ', $permission) . ', ';
                                                            }
                                                            ?>
                                                        </div>

                                                        <!-- Work Permission -->
                                                        <div class="col-md-12">
                                                            <strong>Work Permission</strong><br>
                                                            <?php $workPermissions = unserialize($value['WorkPermission']);
                                                            foreach ($workPermissions as $permission) {
                                                                echo str_replace('_', ' ', $permission) . ', ';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-secondary waves-effect waves-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal<?php echo $value['UserId']; ?>">
                                    <!-- <iconify-icon icon="mdi:eye"></iconify-icon> -->
                                    <i class="fas fa-eye"></i>
                                </button>

                            </td>

                            <td><a class=" bg-info-200 btn btn-blue waves-effect waves-light" <?php if ($ty == 'SA') { ?> href="?page=user_edit&token=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>" <?php   } ?>><span class="fas fa-edit"></span></a></td>
                            <td><button type="button" class="btn btn-warning waves-effect waves-light passwordchange" data-passwordid="<?php echo $value['UserId'] ?>" data-bs-toggle="modal" data-bs-target="#con-close-modal">Change</button> </td>
                        </tr>
                <?php  }
                } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- end row-->
<!-- sample modal content -->
<div class="modal fade" id="con-close-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Password Update</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" class="needs-validation" novalidate id="passwordForm">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="passwordid" id="passwordid">
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" id="newPassword" placeholder="New Password" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" name="confirmpassword" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="passwordupdate" class="btn btn-success waves-effect waves-light">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- /.modal -->
<?php $obj->start_script(); ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "responsive": true,
            "paging": true,
            "searching": true,
            "info": true,
        });
    });
    var elements = document.getElementsByClassName('passwordchange');
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', function() {
            var dataId = this.getAttribute('data-passwordid');
            document.getElementById('passwordid').value = dataId;
        });
    }

    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        var password = document.getElementById('newPassword').value;
        var confirmPassword = document.getElementById('confirmPassword').value;
        if (password !== confirmPassword) {
            alert('Passwords do not match. Please try again.');
            document.getElementById('confirmPassword').value = "";
            event.preventDefault();
        }
    });
</script>
<?php $obj->end_script(); ?>