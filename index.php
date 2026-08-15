<?php include 'partials/main.php'; ?>
<!DOCTYPE html>
<html lang="en" data-topbar-color="brand">

<head>
    <?php
    $sub_title = "Dashboard";
   $page = $_GET['page'] ?? '?page=signup';
    if ($page == 'user_create') {
        $title = "Create User";
    } elseif ($page == 'user_view') {
        $title = "View User";
    } elseif ($page == 'user_edit') {
        $title = "Edit User";
    } elseif ($page == 'zone_view') {
        $title = "Zone/Area";
    } elseif ($page == 'subzone_view') {
        $title = "SubZone/SubArea";
    } elseif ($page == 'destination_view') {
        $title = "Destination";
    } elseif ($page == 'package_view') {
        $title = "Package/Profile";
    } elseif ($page == 'customer_create') {
        $title = "Create Customer";
    } elseif ($page == 'customer_view') {
        $title = "View Customer";
    } elseif ($page == 'customer_edit') {
        $title = "Edit Customer";
    } elseif ($page == 'customer_ledger') {
        $title = "Customer Ledger";
    } elseif ($page == 'billcollection_view') {
        $title = "Bill Collection";
    } elseif ($page == 'all_paid') {
        $title = "All Paid";
    } elseif ($page == 'previous_due') {
        $title = "Previous Due";
    } elseif ($page == 'accounts_statement') {
        $title = "Accounts Statement";
    } elseif ($page == 'mikrotik_connection') {
        $title = "Mikrotik List";
    } elseif ($page == 'mikrotik_online_secret') {
        $title = "Mikrotik Online Secret";
    } elseif ($page == 'mikrotik_all_secret') {
        $title = "Mikrotik All Secret";
    } elseif ($page == 'mikrotik_unmatching_secret') {
        $title = "Mikrotik Unmatching Secret";
    } elseif ($page == 'account_head_view') {
        $title = "Account Head";
    } elseif ($page == 'account_sub_head_view') {
        $title = "Account Sub Head";
    } elseif ($page == 'view_expence') {
        $title = "View Expense";
    } elseif ($page == 'expence_report') {
        $title = "Expense Report";
    } elseif ($page == 'employee_view') {
        $title = "Employee List";
    } elseif ($page == 'employee_create') {
        $title = "Create Employee";
    } elseif ($page == 'employee_edit') {
        $title = "Edit Employee";
    } elseif ($page == 'attedance') {
        $title = "Attendance";
    } elseif ($page == 'attendance_report') {
        $title = "Attendance Report";
    } elseif ($page == 'view_all_income') {
        $title = "Others Income";
    } elseif ($page == 'connection_charge') {
        $title = "Connection Charge";
    } elseif ($page == 'view_bonous') {
        $title = "Discount List";
    } elseif ($page == 'same_bonus') {
        $title = "Same Bonus";
    } elseif ($page == 'view_diagram') {
        $title = "View Diagram";
    } elseif ($page == 'edit_complain') {
        $title = "Edit Customer Complain";
    } elseif ($page == 'add_complain') {
        $title = "Create Customer Complain";
    } elseif ($page == 'view_complain') {
        $title = "View Customer Complain";
    } elseif ($page == 'complain_template') {
        $title = "Complain Template";
    } elseif ($page == 'edit_complain_template') {
        $title = "Edit Complain Template";
    } elseif ($page == 'individual_complain_details') {
        $title = "Individual Complain Template Details";
    } elseif ($page == 'monthly_new') {
        $title = "Monthly Balance Report";
    } elseif ($page == 'yearly') {
        $title = "Yearly Balance Report";
    } elseif ($page == 'customer_billing_history') {
        $title = "Customer Billing History";
    } elseif ($page == 'add_customize_sms') {
        $title = "Add Customize SMS";
    } elseif ($page == 'due_sms') {
        $title = "Due SMS";
    } elseif ($page == 'send_due_sms') {
        $title = "Send Due SMS";
    } elseif ($page == 'sms') {
        $title = "SMS";
    } elseif ($page == 'product_category') {
        $title = "Product Category";
    } elseif ($page == 'product') {
        $title = "Product";
    } elseif ($page == 'supplier') {
        $title = "Supplier";
    } elseif ($page == 'purchase') {
        $title = "Purchase";
    } elseif ($page == 'stock') {
        $title = "Stock";
    } elseif ($page == 'sales') {
        $title = "Sales";
    } elseif ($page == 'customer_return') {
        $title = "Customer Return";
    } elseif ($page == 'supplier_return') {
        $title = "Supplier Return";
    } elseif ($page == 'business_target') {
        $title = "Business Target";
    }elseif ($page == 'brtcreport') {
        $title = "BTRC Report";
    } elseif ($page == 'olt_management') {
        $title = "OLT Management";
    } elseif ($page == 'device_condition') {
        $title = "Device Condition";
    } elseif ($page == 'device_info') {
        $title = "Device Info";
    } elseif ($page == 'interface_state') {
        $title = "Interface State";
    } elseif ($page == 'olt_diagram') {
        $title = "OLT Diagram";
    } elseif ($page == 'fetch_onu_data') {
        $title = "Fetch ONU Data";
    } elseif ($page == 'fetch_interface_data') {
        $title = "Fetch Interface Data";
    } else {
        $title = "Dashboard";
    }


    include 'partials/title-meta.php'; ?>
    <!-- third party css end -->
    <?php include 'partials/head-css.php'; ?>
    <style>
        /* Prevent scrolling on body */
        body {
            overflow: hidden;
            height: 100%;
        }

        /* Enable scrolling only for the main content */
        .dashboard-main-body{
            overflow-y: auto;
            height: calc(100vh - 143px);
            /* Adjust height as needed, for example, subtracting header/footer height */
        }
        @media (min-width: 1400px) {
   .dashboard-main {
     margin-inline-start: 15.1875rem;
   }
 }
    </style>

</head>

<body>

 <?php
    if (!$login) {
        if ($page === 'login') {
            include 'pages/auth/login.php';
        } else {
            include 'pages/auth/signup.php';
        }
        return; // Prevent loading rest of the layout for unauthenticated users
    }?>

    <!-- Begin page -->

    <?php if ($login) include 'partials/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
    <?php if($login){?>
    <main class="dashboard-main">
        <?php if ($login)  include 'partials/topbar.php'; ?>

        <div class="dashboard-main-body">
            <?php if ($login) include 'partials/page-title.php'; ?>
            <div class="row gy-4">
                <?php if ($login) include 'partials/route.php'; ?>
            </div>
        </div>

        <?php if ($login) include 'partials/footer.php'; ?>
    </main>
    <?php }
    ?>
    <!-- END wrapper -->



    <?php if ($login) include 'partials/footer-scripts.php'; ?>

    <!-- Plugins js-->
    <!-- <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/libs/selectize/js/standalone/selectize.min.js"></script> -->


    <!-- Datatables init -->
    <!-- <script src="assets/js/pages/datatables.init.js"></script> -->

    <!-- Dashboar 1 init js-->
    <!-- <script src="assets/js/pages/dashboard-1.init.js"></script> -->

    <script>
        function confirmDelete() {
            return confirm('This will be delete,\n Are You sure? ');
        }
    </script>

    <!-- Dynamic Scripts -->
    <?php $obj->add_dynamic_scripts(); ?>

</body>

</html>
