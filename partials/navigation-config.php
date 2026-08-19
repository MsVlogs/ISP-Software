<?php
/**
 * Central navigation configuration.
 *
 * Keep menu structure, labels, routes and ordering here so the UI can be
 * reorganized without coupling navigation to individual page implementations.
 */

return [
    [
        'key' => 'dashboard',
        'icon' => 'mdi mdi-home',
        'route' => '?page=dashboard',
        'permission' => null,
        'order' => 10,
        'label' => ['en' => 'Dashboard', 'bn' => 'ড্যাশবোর্ড'],
    ],
    [
        'key' => 'customers',
        'icon' => 'mdi mdi-account-group-outline',
        'route' => null,
        'permission' => 'customer_view',
        'order' => 20,
        'label' => ['en' => 'Customers', 'bn' => 'গ্রাহক'],
        'children' => [
            [
                'key' => 'customer_view',
                'route' => '?page=customer_view',
                'permission' => 'customer_view',
                'label' => ['en' => 'Customer View', 'bn' => 'গ্রাহক দেখুন'],
            ],
            [
                'key' => 'customer_create',
                'route' => '?page=customer_create',
                'permission' => 'customer_create',
                'label' => ['en' => 'Create Customer', 'bn' => 'গ্রাহক তৈরি'],
            ],
        ],
    ],
    [
        'key' => 'network',
        'icon' => 'mdi mdi-lan-connect',
        'route' => null,
        'permission' => 'device_condition',
        'order' => 30,
        'label' => ['en' => 'Network', 'bn' => 'নেটওয়ার্ক'],
        'children' => [
            [
                'key' => 'network_map',
                'route' => '?page=network_monitoring&view=map',
                'permission' => 'device_condition',
                'label' => ['en' => 'Network Map', 'bn' => 'নেটওয়ার্ক ম্যাপ'],
            ],
            [
                'key' => 'traffic_monitor',
                'route' => '?page=network_monitoring&view=traffic',
                'permission' => 'device_condition',
                'label' => ['en' => 'Traffic Monitor', 'bn' => 'ট্রাফিক মনিটর'],
            ],
            [
                'key' => 'high_usage',
                'route' => '?page=network_monitoring&view=usage',
                'permission' => 'device_condition',
                'label' => ['en' => 'High Usage', 'bn' => 'উচ্চ ব্যবহার'],
            ],
            [
                'key' => 'device_watcher',
                'route' => '?page=network_monitoring&view=devices',
                'permission' => 'device_condition',
                'label' => ['en' => 'Device Watcher', 'bn' => 'ডিভাইস ওয়াচার'],
            ],
            [
                'key' => 'network_alerts',
                'route' => '?page=network_monitoring&view=alerts',
                'permission' => 'device_condition',
                'label' => ['en' => 'Logs & Alerts', 'bn' => 'লগ ও সতর্কতা'],
            ],
        ],
    ],
    [
        'key' => 'olt',
        'icon' => 'mdi mdi-router-wireless',
        'route' => null,
        'permission' => 'olt_management',
        'order' => 40,
        'label' => ['en' => 'OLT', 'bn' => 'OLT'],
        'children' => [
            [
                'key' => 'olt_management',
                'route' => '?page=olt_management',
                'permission' => 'olt_management',
                'label' => ['en' => 'OLT Management', 'bn' => 'OLT ব্যবস্থাপনা'],
            ],
            [
                'key' => 'olt_active',
                'route' => '?page=olt_management&status=active',
                'permission' => 'olt_management',
                'label' => ['en' => 'Active', 'bn' => 'সক্রিয়'],
            ],
            [
                'key' => 'olt_inactive',
                'route' => '?page=olt_management&status=inactive',
                'permission' => 'olt_management',
                'label' => ['en' => 'Inactive', 'bn' => 'নিষ্ক্রিয়'],
            ],
            [
                'key' => 'olt_deleted',
                'route' => '?page=olt_management&status=deleted',
                'permission' => 'olt_management',
                'label' => ['en' => 'Deleted', 'bn' => 'ডিলিটেড'],
            ],
            [
                'key' => 'device_condition',
                'route' => '?page=device_condition',
                'permission' => 'device_condition',
                'label' => ['en' => 'Device Condition', 'bn' => 'ডিভাইস কন্ডিশন'],
            ],
            [
                'key' => 'olt_diagram',
                'route' => '?page=olt_diagram',
                'permission' => 'olt_diagram',
                'label' => ['en' => 'Diagram', 'bn' => 'ডায়াগ্রাম'],
            ],
        ],
    ],
];
