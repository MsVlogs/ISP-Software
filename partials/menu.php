<aside class="sidebar" id="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="?page=dashboard" class="sidebar-logo">
            <img src="assets/images/bsd/logo.png" alt="site logo" class="light-logo">
            <img src="assets/images/bsd/logo.png" alt="site logo" class="dark-logo">
            <img src="assets/images/bsd/logo.png" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="sidebar-menu-group-title">
                <a href="?page=dashboard">
                    <i class="mdi mdi-home" class="menu-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="?page=business_target">
                    <i class="mdi mdi-map-outline" class="menu-icon"></i>
                    <span>Business Target</span>
                </a>
            </li>

           
            <?php if ($obj->userMenuePermission('user_view') || $obj->userMenuePermission('user_create')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="mdi mdi-account-group-outline" class="menu-icon"></i>
                        <span>Admin Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('user_view')) { ?>
                            <li>
                                <a href="?page=user_view"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('user_create')) { ?>
                            <li>
                                <a href="?page=user_create"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Create User</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($obj->userMenuePermission('zone_view') || $obj->userMenuePermission('subzone_view') || $obj->userMenuePermission('destination_view') || $obj->userMenuePermission('package_view')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="fe:vector" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-vector-square" class="menu-icon"></i>
                        <span>Others</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('package_view')) { ?>
                            <li>
                                <a href="?page=package_view"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Package Info</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('zone_view')) { ?>
                            <li>
                                <a href="?page=zone_view"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Zone/Area Info</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('subzone_view')) { ?>
                            <li>
                                <a href="?page=subzone_view"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> SubZone Info</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('destination_view')) { ?>
                            <li>
                                <a href="?page=destination_view"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Destination Info</a>
                            </li>
                        <?php } ?>

                    </ul>
                </li>
            <?php } ?>

            <?php if ($obj->userMenuePermission('customer_view') || $obj->userMenuePermission('customer_create')) { ?>
                <li class="dropdown" id="menu-customer">
                    <a href="javascript:void(0)">

                        <i class="mdi mdi-account-group-outline" class="menu-icon"></i>
                        <span>Customers</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('customer_view')) { ?>
                            <li>
                                <a href="?page=customer_view"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Customer View</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('customer_create')) { ?>
                            <li id="menu-customer-create">
                                <a href="?page=customer_create" id="link-customer-create"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Create Customer</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            
            <?php if ($obj->userMenuePermission('billcollection_view') || $obj->userMenuePermission('all_paid') || $obj->userMenuePermission('previous_due')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:cash-multiple" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-cash-multiple" class="menu-icon"></i>
                        <span>Bill Collection</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('billcollection_view')) { ?>
                            <li>
                                <a href="?page=billcollection_view"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Bill Collection</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('all_paid')) { ?>
                            <li>
                                <a href="?page=all_paid"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> All Paid</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('previous_due')) { ?>
                            <li>
                                <a href="?page=previous_due"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Previous Due</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            
            <?php if ($obj->userMenuePermission('mikrotik_connection') || $obj->userMenuePermission('mikrotik_online_secret') || $obj->userMenuePermission('mikrotik_all_secret') || $obj->userMenuePermission('mikrotik_unmatching_secret')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:router-wireless" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-router-wireless" class="menu-icon"></i>
                        <span>Mikrotik</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('mikrotik_connection')) { ?>
                            <li>
                                <a href="?page=mikrotik_connection"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Mikrotik List</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('mikrotik_all_secret')) { ?>
                            <li>
                                <a href="?page=mikrotik_all_secret"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Mikrotik Secret List</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('mikrotik_online_secret')) { ?>
                            <li>
                                <a href="?page=mikrotik_online_secret"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Mikrotik Online List</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('mikrotik_unmatching_secret')) { ?>
                            <li>
                                <a href="?page=mikrotik_unmatching_secret"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Mikrotik unmatching List</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            
            <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:chart-line" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-router-wireless menu-icon"></i>

                        <span>OLT</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('olt_management')) { ?>
                            <li>
                                <a href="?page=olt_management"><i class="ri-circle-fill circle-icon text-success-main w-auto"></i>OLT Management</a>
                            </li>
                        <?php } ?>
                    </ul>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('device_info')) { ?>
                            <li>
                                <a href="?page=device_info"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Device Info</a>
                            </li>
                        <?php } ?>
                       
                    </ul>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('interface_state')) { ?>
                            <li>
                                <a href="?page=interface_state"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>ONU Interface State</a>
                            </li>
                        <?php } ?>
                       
                    </ul>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('device_condition')) { ?>
                            <li>
                                <a href="?page=device_condition"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>Device Condition</a>
                            </li>
                        <?php } ?>
                       
                    </ul>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('olt_diagram')) { ?>
                            <li>
                                <a href="?page=olt_diagram"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Diagram</a>
                            </li>
                        <?php } ?>
                       
                    </ul>
                </li>

            <?php if ($obj->userMenuePermission('view_all_income') || $obj->userMenuePermission('connection_charge')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:chart-line" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-chart-line" class="menu-icon"></i>
                        <span>Income</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('view_all_income')) { ?>
                            <li>
                                <a href="?page=view_all_income"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> View Other Income</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('connection_charge')) { ?>
                            <li>
                                <a href="?page=connection_charge"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Connection Charge</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($obj->userMenuePermission('expence_report') || $obj->userMenuePermission('view_expence') || $obj->userMenuePermission('account_head_view') || $obj->userMenuePermission('account_sub_head_view')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:currency-usd-off" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-currency-usd-off" class="menu-icon"></i>
                        <span>Expense</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('expence_report')) { ?>
                            <li>
                                <a href="?page=expence_report"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Expense Report</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('view_expence')) { ?>
                            <li>
                                <a href="?page=view_expence"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Expense</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('account_head_view')) { ?>
                            <li>
                                <a href="?page=account_head_view"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Acounts Head Info</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('account_sub_head_view')) { ?>
                            <li>
                                <a href="?page=account_sub_head_view"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Acounts Sub-head Info</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($obj->userMenuePermission('view_bonous')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:sale" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-sale" class="menu-icon"></i>
                        <span>Discount</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('view_bonous')) { ?>
                            <li>
                                <a href="?page=view_bonous"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> View Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            
            <?php if ($obj->userMenuePermission('accounts_statement')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon> -->
                        <!-- <i class="mdi mdi-file-invoice" class="menu-icon"></i> -->
                        <i class="fa-solid fa-file-invoice-dollar" class="menu-icon"></i>
                        <span>Statement</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('accounts_statement')) { ?>
                            <li>
                                <a href="?page=accounts_statement"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Accounts Statement</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('brtcreport')) { ?>
                            <li>
                                <a href="?page=brtcreport"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> BTRC Report</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($obj->userMenuePermission('monthly_new') || $obj->userMenuePermission('yearly') || $obj->userMenuePermission('customer_billing_history')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:finance" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-finance" class="menu-icon"></i>
                        <span>Balance Sheet</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('monthly_new')) { ?>
                            <li>
                                <a href="?page=monthly_new"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Monthly Balance Report</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('yearly')) { ?>
                            <li>
                                <a href="?page=yearly"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Yearly Balance Report</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('customer_billing_history')) { ?>
                            <li>
                                <a href="?page=customer_billing_history"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>Billing History</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($obj->userMenuePermission('view_all_employee')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:account-group" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-account-group" class="menu-icon"></i>
                        <span>Employee</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('employee_view')) { ?>
                            <li>
                                <a href="?page=employee_view"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> View Employee</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            
            <?php if ($obj->userMenuePermission('add_complain') || $obj->userMenuePermission('view_complain') || $obj->userMenuePermission('complain_template')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:comment-question" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-comment-question" class="menu-icon"></i>
                        <span>Complain</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('add_complain')) { ?>
                            <li>
                                <a href="?page=add_complain"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Complain</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('view_complain')) { ?>
                            <li>
                                <a href="?page=view_complain"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> View All Complain</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('complain_template')) { ?>
                            <li>
                                <a href="?page=complain_template"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add Complain Template</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($obj->userMenuePermission('view_diagram')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="bx-network-chart" class="menu-icon"></iconify-icon> -->
                        <i class="fa-solid fa-network-wired" class="menu-icon"></i>
                        <span>Diagram</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('view_diagram')) { ?>
                            <li>
                                <a href="?page=view_diagram"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Network Diagram</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($obj->userMenuePermission('add_customize_sms') || $obj->userMenuePermission('due_sms') || $obj->userMenuePermission('sms')) { ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <!-- <iconify-icon icon="mdi:message-text" class="menu-icon"></iconify-icon> -->
                        <i class="mdi mdi-message-text" class="menu-icon"></i>
                        <span>SMS</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <?php if ($obj->userMenuePermission('add_customize_sms')) { ?>
                            <li>
                                <a href="?page=add_customize_sms"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Custom SMS</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('due_sms')) { ?>
                            <li>
                                <a href="?page=due_sms"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Send Due SMS</a>
                            </li>
                        <?php } ?>
                        <?php if ($obj->userMenuePermission('sms')) { ?>
                            <li>
                                <a href="?page=sms"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> SMS</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($obj->userMenuePermission('stock') || $obj->userMenuePermission('product') || $obj->userMenuePermission('supplier') || $obj->userMenuePermission('purchase') || $obj->userMenuePermission('sales') || $obj->userMenuePermission('customer_return') || $obj->userMenuePermission('supplier_return') || $obj->userMenuePermission('product_category')) { ?>
                <li class="sidebar-menu-group-title">Inventory</li>
                <?php if ($obj->userMenuePermission('stock')) { ?>
                    <li>
                        <a href="?page=stock">
                            <!-- <iconify-icon icon="bx-shopping-bag" class="menu-icon"></iconify-icon> -->
                            <i class="fa-solid fa-shopping-bag" class="menu-icon"></i>
                            <span>Stock</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($obj->userMenuePermission('product') || $obj->userMenuePermission('product')) { ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <!-- <iconify-icon icon="mdi:storefront-outline" class="menu-icon"></iconify-icon> -->
                            <i class="mdi mdi-storefront-outline" class="menu-icon"></i>
                            <span>Product</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if ($obj->userMenuePermission('product_category')) { ?>
                                <li>
                                    <a href="?page=product_category"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Product Category</a>
                                </li>
                            <?php } ?>
                            <?php if ($obj->userMenuePermission('product')) { ?>
                                <li>
                                    <a href="?page=product"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Product</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($obj->userMenuePermission('supplier')) { ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <!-- <iconify-icon icon="bx-user" class="menu-icon"></iconify-icon> -->
                            <i class="fa-solid fa-user" class="menu-icon"></i>
                            <span>Supplier</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if ($obj->userMenuePermission('supplier')) { ?>
                                <li>
                                    <a href="?page=supplier"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Supplier</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($obj->userMenuePermission('purchase')) { ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <!-- <iconify-icon icon="mdi:credit-card" class="menu-icon"></iconify-icon> -->
                            <i class="mdi mdi-credit-card" class="menu-icon"></i>
                            <span>Purchase</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if ($obj->userMenuePermission('purchase')) { ?>
                                <li>
                                    <a href="?page=purchase"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Purchase</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($obj->userMenuePermission('sales')) { ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <!-- <iconify-icon icon="mdi:cart" class="menu-icon"></iconify-icon> -->
                            <i class="mdi mdi-cart" class="menu-icon"></i>
                            <span>Sales</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if ($obj->userMenuePermission('sales')) { ?>
                                <li>
                                    <a href="?page=sales"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Sales</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($obj->userMenuePermission('customer_return') || $obj->userMenuePermission('supplier_return')) { ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <!-- <iconify-icon icon="bx:recycle" class="menu-icon"></iconify-icon> -->
                            <i class="fa-solid fa-recycle" class="menu-icon"></i>
                            <span>Product Returns</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if ($obj->userMenuePermission('customer_return')) { ?>
                                <li>
                                    <a href="?page=customer_return"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Customer Return</a>
                                </li>
                            <?php } ?>
                            <?php if ($obj->userMenuePermission('supplier_return')) { ?>
                                <li>
                                    <a href="?page=supplier_return"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Supplier Return</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                 <?php if ($obj->userMenuePermission('setting') || $obj->userMenuePermission('setting')) { ?>
                <li>
                    <a href="?page=setting">
                        <i class="mdi mdi-account-group-outline" class="menu-icon"></i>
                        <span>Setting</span>
                    </a>
                </li>
            <?php } ?>
            <?php } ?>
        </ul>
    </div>
</aside>
<?php $obj->start_script(); ?>
<script>
    window.onload = function() {
        // Function to set the active-page class based on URL
        function setActivePage() {
            const urlParams = new URLSearchParams(window.location.search);

            // Remove 'active-page' class from all menu items
            const allMenuItems = document.querySelectorAll('.sidebar-menu li');
            allMenuItems.forEach(item => {
                item.classList.remove('active-page');
            });

            // Check if the 'mikrotik' parameter exists in the URL
            const pageValue = urlParams.get('page');
            if (urlParams.has('mikrotik') || pageValue === 'customer_edit') {
                // const mikrotikValue = urlParams.get('mikrotik'); // Get the value of 'mikrotik'

                // Find the "Create User" menu item and add the 'active-page' class
                const createUserMenuItem = document.getElementById('menu-customer-create');
                const createUserLink = document.getElementById('link-customer-create');
                const createMenu = document.getElementById('menu-customer');

                if (createUserMenuItem) createUserMenuItem.classList.add('active-page');
                if (createUserLink) createUserLink.classList.add('active-page');
                if (createMenu) createMenu.classList.add('open');
            }
        }

        // Set the active page on page load
        setActivePage();

        // Event listener for link clicks to update active page when navigating
        const links = document.querySelectorAll('.sidebar-menu a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                setActivePage(); // Reset active page class based on new URL
            });
        });
    }
</script>
<?php $obj->end_script(); ?>