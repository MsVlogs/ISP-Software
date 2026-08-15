<?php include('user.php');

if (isset($_GET['token'])) {
    $user = $obj->getSingleData('vw_user_info', ['where' => ['UserId', '=', $_GET['token']]]);
    if (!$user) {
        $obj->notificationStore("user not found", 'danger');
        echo ' <script>window.location="?page=user_view"; </script>';
        exit;
    }
} else {
    $obj->notificationStore("user not found", 'danger');
    echo ' <script>window.location="?page=user_view"; </script>';
    exit;
}

?>
<div class="row">
    <div class="col-12">
        <br>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Software Login User(EDIT) </h4>

                <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate id="addUser">
                    <input type="hidden" name="updateId" value="<?php echo $user['UserId'] ?>">
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name*</label>
                                <input type="text" id="fullname" name="fullname" value="<?php echo $user['FullName'] ?>" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">User Name*</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php echo $user['UserName'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="astatus" class="form-label">Status*</label>
                                <select class="form-control" id="astatus" name="status" required>
                                    <option <?php echo ($user['Status'] == 1) ? 'selected' : '' ?> value="1">Active</option>
                                    <option <?php echo ($user['Status'] == 0) ? 'selected' : '' ?> value="0">InActive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type*</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option <?php echo ($user['UserType'] == "SA") ? 'selected' : '' ?> value="SA">Super Admin</option>
                                    <option <?php echo ($user['UserType'] == "EO") ? 'selected' : '' ?> value="EO">Entry Operator</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo $user['Address'] ?></textarea>
                            </div>

                        </div> <!-- end col -->

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo $user['Email'] ?>" class="form-control" placeholder="Email">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Mobile</label>
                                <input type="text" id="phone" name="phone" value="<?php echo $user['MobileNo'] ?>" class="form-control" placeholder="Mobile number">
                            </div>

                            <div class="mb-3">
                                <label for="nid" class="form-label">NID</label>
                                <input type="text" id="nid" name="nid" value="<?php echo $user['NationalId'] ?>" class="form-control" placeholder="NID number">
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
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="view" id="view" <?php echo (in_array('view', @unserialize($user['WorkPermission']))) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="view">View</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="add" id="add" <?php echo (in_array('add', @unserialize($user['WorkPermission']))) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="add">Add</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="edit" id="edit" <?php echo (in_array('edit', @unserialize($user['WorkPermission']))) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="edit">Edit</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="workPermission[]" value="delete" id="delete" <?php echo (in_array('delete', @unserialize($user['WorkPermission']))) ? 'checked' : ''; ?>>
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
                                <label class="font-weight-bold">Admin User </label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="user_view" id="user_view" <?php echo (in_array('user_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="user_view">User List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="user_create" id="user_create" <?php echo (in_array('user_create', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="user_create">Add User</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="user_edit" id="user_edit" <?php echo (in_array('user_edit', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="user_edit">Update User</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Others</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="package_view" id="package_view" <?php echo (in_array('package_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="package_view">Package/Profile</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="zone_view" id="zone_view" id="zone_view" <?php echo (in_array('zone_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="zone_view">Zone/Area</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="subzone_view" id="subzone_view" <?php echo (in_array('zone_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="subzone_view">SubZone/SubArea</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="destination_view" id="destination_view" <?php echo (in_array('zone_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="destination_view">Destination</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Customer</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_view" id="customer_view" <?php echo (in_array('customer_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="customer_view">Customer List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_create" id="customer_create" <?php echo (in_array('customer_create', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="customer_create">Add Customer</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_edit" id="customer_edit" <?php echo (in_array('customer_edit', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="customer_edit">Update Customer</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_ledger" id="customer_ledger" <?php echo (in_array('customer_ledger', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="customer_ledger">Customer Ledger</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Mikrotik</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_connection" id="mikrotik_connection" <?php echo (in_array('mikrotik_connection', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mikrotik_connection">Mikrotik List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_online_secret" id="mikrotik_online_secret" <?php echo (in_array('mikrotik_online_secret', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mikrotik_online_secret">Mikrotik Online Secret </label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_all_secret" id="mikrotik_all_secret" <?php echo (in_array('mikrotik_all_secret', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mikrotik_all_secret">Mikrotik All Secret</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="mikrotik_unmatching_secret" id="mikrotik_unmatching_secret" <?php echo (in_array('mikrotik_unmatching_secret', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mikrotik_unmatching_secret">Mikrotik Unmatching Secret</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Bill Collection</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="billcollection_view" id="billcollection_view" <?php echo (in_array('billcollection_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="billcollection_view">Bill Collection List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="all_paid" id="all_paid" <?php echo (in_array('all_paid', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="all_paid">All paid </label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="previous_due" id="previous_due" <?php echo (in_array('previous_due', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="previous_due">Previous Due</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Income</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_all_income" id="view_all_income" <?php echo (in_array('view_all_income', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="view_all_income">Others Income</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="connection_charge" id="connection_charge" <?php echo (in_array('connection_charge', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="connection_charge">Connection Charge </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Discount</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_bonous" id="view_bonous" <?php echo (in_array('view_bonous', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="view_bonous">Discount List</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Expense</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_expence" id="view_expence" <?php echo (in_array('view_expence', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="view_expence">View Expense</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Account Head</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="account_head_view" id="account_head_view" <?php echo (in_array('account_head_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="account_head_view">Account Head</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="account_sub_head_view" id="account_sub_head_view" <?php echo (in_array('account_sub_head_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="account_sub_head_view">Account Sub Head</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Employee</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="employee_view" id="employee_view" <?php echo (in_array('employee_view', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="employee_view">Employee List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="employee_edit" id="employee_edit" <?php echo (in_array('employee_edit', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="employee_edit">Employee Update</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Balance Sheet</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="monthly_new" id="monthly_new" <?php echo (in_array('monthly_new', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="monthly_new">Monthly Balance Report</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="yearly" id="yearly" <?php echo (in_array('yearly', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="yearly">yearly Balance Report</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_billing_history" id="customer_billing_history" <?php echo (in_array('customer_billing_history', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="customer_billing_history">Customer Billing History</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Statement</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="accounts_statement" id="accounts_statement" <?php echo (in_array('accounts_statement', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="accounts_statement">Accounts Statement</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Complain</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_complain" id="view_complain" <?php echo (in_array('view_complain', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="view_complain">Complain List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="add_complain" id="add_complain" <?php echo (in_array('add_complain', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="add_complain">Create Complain</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="edit_complain" id="edit_complain" <?php echo (in_array('edit_complain', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="edit_complain">Update Complain</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="complain_template" id="complain_template" <?php echo (in_array('complain_template', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="complain_template">Complain Template List</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="edit_complain_template" id="edit_complain_template" <?php echo (in_array('edit_complain_template', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="edit_complain_template">Update Complain Template </label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="individual_complain_details" id="individual_complain_details" <?php echo (in_array('individual_complain_details', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="individual_complain_details">Individual Complain Template Details</label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Diagram</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="view_diagram" id="view_diagram" <?php echo (in_array('view_diagram', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="view_diagram">View Diagram</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Inventory</label>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="product_category" id="product_category" <?php echo (in_array('product_category', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="product_category">Product Category</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="product" id="product" <?php echo (in_array('product', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="product">Product</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="supplier" id="supplier" <?php echo (in_array('supplier', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="supplier">Supplier</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="purchase" id="purchase" <?php echo (in_array('purchase', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="purchase">Purchase</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="sales" id="sales" <?php echo (in_array('sales', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sales">Sales</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="stock" id="stock" <?php echo (in_array('stock', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="stock">Stock</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="customer_return" id="customer_return" <?php echo (in_array('customer_return', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="customer_return">Customer Return</label>
                                </div>
                                <div class="form-check checked-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="menu_permission[]" value="supplier_return" id="supplier_return" <?php echo (in_array('supplier_return', @unserialize($user['MenuPermission']))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="supplier_return">Supplier Return</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success waves-effect waves-light btn-lg" name="update">Save</button>
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
            alert('Confrim Passwords do not match. Please write Correct Password again.');
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