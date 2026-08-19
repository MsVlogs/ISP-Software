<?php include('user.php') ?>
<div class="row">
    <div class="col-12">
        <br>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Software Login User </h6>

                <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate id="addUser">
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name*</label>
                                <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter your fullname" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">User Name*</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password*</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirm Password*</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirm-password" class="form-control" placeholder="Enter your confirm password" required>
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="astatus" class="form-label">Status*</label>
                                <select class="form-control" id="astatus" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type*</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="SA">Super Admin</option>
                                    <option value="EO">Entry Operator</option>
                                </select>
                            </div>

                        </div> <!-- end col -->

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address*</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Mobile</label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Mobile number" required>
                            </div>

                            <div class="mb-3">
                                <label for="nid" class="form-label">NID</label>
                                <input type="text" id="nid" name="nid" class="form-control" placeholder="NID number">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="pimage" class="form-label">Profile Image</label>
                                <input type="file" id="pimage" name="pimage" class="form-control">
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-center">Action Permission</h6>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="view" id="view" checked>
                                        <label class="form-check-label" for="view">View</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="add" id="add">
                                        <label class="form-check-label" for="add">Add</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="edit" id="edit">
                                        <label class="form-check-label" for="edit">Edit</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="delete" id="delete">
                                        <label class="form-check-label" for="delete">Delete</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" id="select_all_menu_permission">
                                        </b><label class="form-check-label" for="select_all_menu_permission">Select All</label>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <h6 class="text-center">Menu Permission</h6>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Admin User </label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="user_view" id="user_view">
                                    <label class="form-check-label" for="user_view">User List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="user_create" id="user_create">
                                    <label class="form-check-label" for="user_create">Add User</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="user_edit" id="user_edit">
                                    <label class="form-check-label" for="user_edit">Update User</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>OLT </label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="olt_management" id="olt_management">
                                    <label class="form-check-label" for="olt_management">OLT Management (Add/Active/Inactive/Delete)</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="device_condition" id="device_condition">
                                    <label class="form-check-label" for="device_condition">Device Condition</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="interface_state" id="interface_state">
                                    <label class="form-check-label" for="interface_state">ONU Interface State</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="device_info" id="device_info">
                                    <label class="form-check-label" for="device_info">Device Info</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="olt_diagram" id="olt_diagram">
                                    <label class="form-check-label" for="olt_diagram">Diagram</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Others</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="package_view" id="package_view">
                                    <label class="form-check-label" for="package_view">Package/Profile</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="package_view" id="package_view">
                                    <label class="form-check-label" for="package_view">Package/Profile</label>

                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="zone_view" id="zone_view">
                                    <label class="form-check-label" for="zone_view">Zone/Area</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="subzone_view" id="subzone_view">
                                    <label class="form-check-label" for="subzone_view">SubZone/SubArea</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="destination_view" id="destination_view">
                                    <label class="form-check-label" for="destination_view">Destination</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Customer</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_view" id="customer_view" checked>
                                    <label class="form-check-label" for="customer_view">Customer List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_create" id="customer_create" checked>
                                    <label class="form-check-label" for="customer_create">Add Customer</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_edit" id="customer_edit">
                                    <label class="form-check-label" for="customer_edit">Update Customer</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_ledger" id="customer_ledger">
                                    <label class="form-check-label" for="customer_ledger">Customer Ledger</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mikrotik</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_connection" id="mikrotik_connection">
                                    <label class="form-check-label" for="mikrotik_connection">Mikrotik List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_online_secret" id="mikrotik_online_secret">
                                    <label class="form-check-label" for="mikrotik_online_secret">Mikrotik Online Secret </label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_all_secret" id="mikrotik_all_secret">
                                    <label class="form-check-label" for="mikrotik_all_secret">Mikrotik All Secret</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_unmatching_secret" id="mikrotik_unmatching_secret">
                                    <label class="form-check-label" for="mikrotik_unmatching_secret">Mikrotik Unmatching Secret</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bill Collection</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="billcollection_view" id="billcollection_view">
                                    <label class="form-check-label" for="billcollection_view">Bill Collection List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="all_paid" id="all_paid">
                                    <label class="form-check-label" for="all_paid">All paid </label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="previous_due" id="previous_due">
                                    <label class="form-check-label" for="previous_due">Previous Due</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Income</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_all_income" id="view_all_income">
                                    <label class="form-check-label" for="view_all_income">Others Income</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="connection_charge" id="connection_charge">
                                    <label class="form-check-label" for="connection_charge">Connection Charge </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Discount</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_bonous" id="view_bonous">
                                    <label class="form-check-label" for="view_bonous">Discount List</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Expense</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="expence_report" id="expence_report">
                                    <label class="form-check-label" for="expence_report">View Expense</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Account Head</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="account_head_view" id="account_head_view">
                                    <label class="form-check-label" for="account_head_view">Account Head</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="account_sub_head_view" id="account_sub_head_view">
                                    <label class="form-check-label" for="account_sub_head_view">Account Sub Head</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Employee</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="employee_view" id="employee_view">
                                    <label class="form-check-label" for="employee_view">Employee List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="employee_edit" id="employee_edit">
                                    <label class="form-check-label" for="employee_edit">Employee Update</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Balance Sheet</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="monthly_new" id="monthly_new">
                                    <label class="form-check-label" for="monthly_new">Monthly Balance Report</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="yearly" id="yearly">
                                    <label class="form-check-label" for="yearly">yearly Balance Report</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_billing_history" id="customer_billing_history">
                                    <label class="form-check-label" for="customer_billing_history">Customer Billing History</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Statement</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="accounts_statement" id="accounts_statement">
                                    <label class="form-check-label" for="accounts_statement">Accounts Statement</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Complain</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_complain" id="view_complain">
                                    <label class="form-check-label" for="view_complain">Complain List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="add_complain" id="add_complain">
                                    <label class="form-check-label" for="add_complain">Create Complain</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="edit_complain" id="edit_complain">
                                    <label class="form-check-label" for="edit_complain">Update Complain</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="complain_template" id="complain_template">
                                    <label class="form-check-label" for="complain_template">Complain Template List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="edit_complain_template" id="edit_complain_template">
                                    <label class="form-check-label" for="edit_complain_template">Update Complain Template </label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="individual_complain_details" id="individual_complain_details">
                                    <label class="form-check-label" for="individual_complain_details">Individual Complain Template Details</label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diagram</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_diagram" id="view_diagram" checked>
                                    <label class="form-check-label" for="view_diagram">View Diagram</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Inventory</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="product_category" id="product_category">
                                    <label class="form-check-label" for="product_category">Product Category</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="product" id="product">
                                    <label class="form-check-label" for="product">Product</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="supplier" id="supplier">
                                    <label class="form-check-label" for="supplier">Supplier</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="purchase" id="purchase">
                                    <label class="form-check-label" for="purchase">Purchase</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="sales" id="sales">
                                    <label class="form-check-label" for="sales">Sales</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="stock" id="stock">
                                    <label class="form-check-label" for="stock">Stock</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_return" id="customer_return">
                                    <label class="form-check-label" for="customer_return">Customer Return</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="supplier_return" id="supplier_return">
                                    <label class="form-check-label" for="supplier_return">Supplier Return</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success-600" name="submit">Save</button>
                            </div>
                        </div>

                </form>

            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div>
<?php $obj->start_script(); ?>
<script>
    document.getElementById('addUser').addEventListener('submit', function(event) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm-password').value;
        if (password !== confirmPassword) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong! Confrim Passwords does not match",
            });
            // alert('Confrim Passwords do not match. Please write Correct Password again.');
            document.getElementById('confirm-password').value = "";
            event.preventDefault();
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('form#addUser').on('change', 'input#select_all_menu_permission', function(event) {

            var checked = $(this).prop('checked');

            $('form#addUser').find('input[name="menu_permission[]"]').prop('checked', checked);

        })
    });
</script>
<?php $obj->end_script(); ?>