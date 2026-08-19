<?php
$homepage = 'pages/dashbaord/home.php';

if ($page == 'dashboard') {
    include $homepage;
} elseif ($page == 'user_create') {
    ($obj->userMenuePermission('user_create')) ? include 'pages/user/user_create.php' : include $homepage;
} elseif ($page == 'setting') {
    ($obj->userMenuePermission('setting')) ? include 'pages/setting/index.php' : include $homepage;
} elseif ($page == 'user_view') {
    ($obj->userMenuePermission('user_view')) ? include 'pages/user/user_view.php' : include $homepage;
} elseif ($page == 'user_edit') {
    ($obj->userMenuePermission('user_edit')) ? include 'pages/user/user_edit.php' : include $homepage;
} elseif ($page == 'zone_view') {
    ($obj->userMenuePermission('zone_view')) ? include 'pages/others/zone_view.php' : include $homepage;
} elseif ($page == 'subzone_view') {
    ($obj->userMenuePermission('subzone_view')) ? include 'pages/others/subzone_view.php' : include $homepage;
} elseif ($page == 'destination_view') {
    ($obj->userMenuePermission('destination_view')) ? include 'pages/others/destination_view.php' : include $homepage;
} elseif ($page == 'package_view') {
    ($obj->userMenuePermission('package_view')) ? include 'pages/others/package_view.php' : include $homepage;
} elseif ($page == 'customer_create') {
    ($obj->userMenuePermission('customer_create')) ? include 'pages/customer/customer_create.php' : include $homepage;
} elseif ($page == 'customer_view') {
    ($obj->userMenuePermission('customer_view')) ? include 'pages/customer/customer_view.php' : include $homepage;
} elseif ($page == 'customer_edit') {
    ($obj->userMenuePermission('customer_edit')) ? include 'pages/customer/customer_edit.php' : include $homepage;
} elseif ($page == 'customer_ledger') {
    ($obj->userMenuePermission('customer_ledger')) ? include 'pages/customer/customer_ledger.php' : include $homepage;
} elseif ($page == 'billcollection_view') {
    ($obj->userMenuePermission('billcollection_view')) ? include 'pages/billcollection/billcollection_view.php' : include $homepage;
} elseif ($page == 'all_paid') {
    ($obj->userMenuePermission('all_paid')) ? include 'pages/billcollection/bill_paid.php' : include $homepage;
} elseif ($page == 'previous_due') {
    ($obj->userMenuePermission('previous_due')) ? include 'pages/billcollection/previous_due.php' : include $homepage;
} elseif ($page == 'accounts_statement') {
    ($obj->userMenuePermission('accounts_statement')) ? include 'pages/statement/accounts_statement.php' : include $homepage;
} elseif ($page == 'brtcreport') {
    ($obj->userMenuePermission('brtcreport')) ? include 'pages/statement/brtcreport.php' : include $homepage;
} elseif ($page == 'mikrotik_connection') {
    ($obj->userMenuePermission('mikrotik_connection')) ? include 'pages/mikrotik/connect.php' : include $homepage;
} elseif ($page == 'mikrotik_online_secret') {
    ($obj->userMenuePermission('mikrotik_online_secret')) ? include 'pages/mikrotik/online_secret_list.php' : include $homepage;
} elseif ($page == 'mikrotik_all_secret') {
    ($obj->userMenuePermission('mikrotik_all_secret')) ? include 'pages/mikrotik/all_secret_list.php' : include $homepage;
} elseif ($page == 'mikrotik_unmatching_secret') {
    ($obj->userMenuePermission('mikrotik_unmatching_secret')) ? include 'pages/mikrotik/unmatching_secret.php' : include $homepage;
}elseif ($page == 'olt_management') {
    ($obj->userMenuePermission('olt_management')) ? include 'pages/olt/olt_management.php' : include $homepage;
}elseif ($page == 'device_condition') {
    ($obj->userMenuePermission('device_condition')) ? include 'pages/olt/device_condition.php' : include $homepage;
}elseif ($page == 'device_info') {
    ($obj->userMenuePermission('device_info')) ? include 'pages/olt/device_info.php' : include $homepage;
}elseif ($page == 'interface_state') {
    ($obj->userMenuePermission('interface_state')) ? include 'pages/olt/interface_state.php' : include $homepage;
}elseif ($page == 'olt_diagram') {
    ($obj->userMenuePermission('olt_diagram')) ? include 'pages/olt/olt_diagram.php' : include $homepage;
}elseif ($page == 'network_monitoring') {
    ($obj->userMenuePermission('device_condition')) ? include 'pages/network/network_monitoring.php' : include $homepage;

}elseif ($page == 'fetch_onu_data') {
    ($obj->userMenuePermission('fetch_onu_data')) ? include 'pages/olt/fetch_onu_data.php' : include $homepage;
}elseif ($page == 'fetch_interface_data') {
    ($obj->userMenuePermission('fetch_interface_data')) ? include 'pages/olt/fetch_interface_data.php' : include $homepage;
}
elseif ($page == 'account_head_view') {
    ($obj->userMenuePermission('account_head_view')) ? include 'pages/expence/account_head_view.php' : include $homepage;
} elseif ($page == 'account_sub_head_view') {
    ($obj->userMenuePermission('account_sub_head_view')) ? include 'pages/expence/account_sub_head_view.php' : include $homepage;
} elseif ($page == 'view_expence') {
    ($obj->userMenuePermission('view_expence')) ? include 'pages/expence/view_expence.php' : include $homepage;
} elseif ($page == 'expence_report') {
    ($obj->userMenuePermission('expence_report')) ? include 'pages/expence/expence_report.php' : include $homepage;
} elseif ($page == 'employee_view') {
    ($obj->userMenuePermission('employee_view')) ? include 'pages/employee/employee_view.php' : include $homepage;
} elseif ($page == 'employee_create') {
    ($obj->userMenuePermission('employee_create')) ? include 'pages/employee/employee_create.php' : include $homepage;
} elseif ($page == 'employee_edit') {
    ($obj->userMenuePermission('employee_edit')) ? include 'pages/employee/employee_edit.php' : include $homepage;
} elseif ($page == 'attedance') {
    ($obj->userMenuePermission('attedance')) ? include 'pages/employee/attedance.php' : include $homepage;
} elseif ($page == 'attendance_report') {
    ($obj->userMenuePermission('attendance_report')) ? include 'pages/employee/attendance_report.php' : include $homepage;
} elseif ($page == 'view_all_income') {
    ($obj->userMenuePermission('view_all_income')) ? include 'pages/income/view_all_income.php' : include $homepage;
} elseif ($page == 'connection_charge') {
    ($obj->userMenuePermission('connection_charge')) ? include 'pages/income/connection_charge.php' : include $homepage;
} elseif ($page == 'view_bonous') {
    ($obj->userMenuePermission('view_bonous')) ? include 'pages/bonus/view_bonous.php' : include $homepage;
} elseif ($page == 'same_bonus') {
    ($obj->userMenuePermission('same_bonus')) ? include 'pages/bonus/same_bonus.php' : include $homepage;
} elseif ($page == 'view_diagram') {
    ($obj->userMenuePermission('view_diagram')) ? include 'pages/diagram/view_diagram.php' : include $homepage;
} elseif ($page == 'edit_complain') {
    ($obj->userMenuePermission('edit_complain')) ? include 'pages/complain/edit_complain.php' : include $homepage;
} elseif ($page == 'add_complain') {
    ($obj->userMenuePermission('add_complain')) ? include 'pages/complain/add_complain.php' : include $homepage;
} elseif ($page == 'view_complain') {
    ($obj->userMenuePermission('view_complain')) ? include 'pages/complain/view_complain.php' : include $homepage;
} elseif ($page == 'complain_template') {
    ($obj->userMenuePermission('complain_template')) ? include 'pages/complain/complain_template.php' : include $homepage;
} elseif ($page == 'edit_complain_template') {
    ($obj->userMenuePermission('edit_complain_template')) ? include 'pages/complain/edit_complain_template.php' : include $homepage;
} elseif ($page == 'individual_complain_details') {
    ($obj->userMenuePermission('individual_complain_details')) ? include 'pages/complain/individual_complain_details.php' : include $homepage;
} elseif ($page == 'monthly_new') {
    ($obj->userMenuePermission('monthly_new')) ? include 'pages/balance_sheet/monthly_balance_report.php' : include $homepage;
} elseif ($page == 'yearly') {
    ($obj->userMenuePermission('yearly')) ? include 'pages/balance_sheet/yearly_balance_report.php' : include $homepage;
} elseif ($page == 'customer_billing_history') {
    ($obj->userMenuePermission('customer_billing_history')) ? include 'pages/balance_sheet/customer_billing_history.php' : include $homepage;
} elseif ($page == 'add_customize_sms') {
    ($obj->userMenuePermission('add_customize_sms')) ? include 'pages/sms/add_customize_sms.php' : include $homepage;
} elseif ($page == 'due_sms') {
    ($obj->userMenuePermission('due_sms')) ? include 'pages/sms/add_duesms.php' : include $homepage;
} elseif ($page == 'send_due_sms') {
    ($obj->userMenuePermission('send_due_sms')) ? include 'pages/sms/send_due_sms.php' : include $homepage;
} elseif ($page == 'sms') {
    ($obj->userMenuePermission('sms')) ? include 'pages/sms/sms_custom.php' : include $homepage;
} elseif ($page == 'product_category') {
    ($obj->userMenuePermission('product_category')) ? include 'pages/inventory/product/product_category.php' : include $homepage;
} elseif ($page == 'product') {
    ($obj->userMenuePermission('product')) ? include 'pages/inventory/product/product.php' : include $homepage;
} elseif ($page == 'supplier') {
    ($obj->userMenuePermission('supplier')) ? include 'pages/inventory/supplier/supplier.php' : include $homepage;
} elseif ($page == 'purchase') {
    ($obj->userMenuePermission('purchase')) ? include 'pages/inventory/purchase/purchase.php' : include $homepage;
} elseif ($page == 'stock') {
    ($obj->userMenuePermission('stock')) ? include 'pages/inventory/stock.php' : include $homepage;
} elseif ($page == 'sales') {
    ($obj->userMenuePermission('sales')) ? include 'pages/inventory/sales/sales.php' : include $homepage;
} elseif ($page == 'customer_return') {
    ($obj->userMenuePermission('customer_return')) ? include 'pages/inventory/return/customer_return.php' : include $homepage;
} elseif ($page == 'supplier_return') {
    ($obj->userMenuePermission('supplier_return')) ? include 'pages/inventory/return/supplier_return.php' : include $homepage;
} elseif ($page == 'business_target') {
    ($obj->userMenuePermission('business_target')) ? include 'pages/target/business_target.php' : include $homepage;
} else {
    include $homepage;
}
