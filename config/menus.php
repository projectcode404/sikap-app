<?php

return [
    [
        'label' => 'messages.master_data',
        'icon' => 'fa-user-group',
        'children' => [
            [
                'label' => 'messages.employees',
                'url' => '/master/employees',
                'icon' => 'fa-id-card',
                'permission' => 'employee_view',
            ],
            [
                'label' => 'messages.divisions',
                'url' => '/master/divisions',
                'icon' => 'fa-layer-group',
                'permission' => 'division_view',
            ],
            [
                'label' => 'messages.positions',
                'url' => '/master/positions',
                'icon' => 'fa-briefcase',
                'permission' => 'position_view',
            ],
            [
                'label' => 'messages.areas',
                'url' => '/master/areas',
                'icon' => 'fa-location-dot',
                'permission' => 'area_view',
            ],
            [
                'label' => 'messages.work_units',
                'url' => '/master/work-units',
                'icon' => 'fa-building-user',
                'permission' => 'work_unit_view',
            ],
            [
                'label' => 'messages.suppliers',
                'url' => '/master/suppliers',
                'icon' => 'fa-building-columns',
                'permission' => 'supplier_view',
            ],
        ]
    ],
    [
        'label' => 'messages.atk',
        'icon' => 'fa-boxes-stacked',
        'children' => [
            [
                'label' => 'messages.atk_items',
                'url' => '/atk/items',
                'icon' => 'fa-warehouse',
                'permission' => 'atk_item_view',
            ],
            [
                'label' => 'messages.atk_purchase_orders',
                'url' => '/atk/purchase-orders',
                'icon' => 'fa-file-signature',
                'permission' => 'atk_purchase_order_view',
            ],
            [
                'label' => 'messages.atk_receives',
                'url' => '/atk/receives',
                'icon' => 'fa-truck-ramp-box',
                'permission' => 'atk_receive_view',
            ],
            [
                'label' => 'messages.atk_out_requests',
                'url' => '/atk/out-requests',
                'icon' => 'fa-cart-plus',
                'permission' => 'atk_out_request_view',
            ],
            [
                'label' => 'messages.atk_returns',
                'url' => '/atk/returns',
                'icon' => 'fa-box-open',
                'permission' => 'atk_return_view',
            ],
            [
                'label' => 'messages.atk_adjustments',
                'url' => '/atk/adjustments',
                'icon' => 'fa-scale-balanced',
                'permission' => 'atk_stock_view',
            ],
        ]
    ],
    [
        'label' => 'messages.users',
        'icon' => 'fa-users',
        'children' => [
            [
                'label' => 'messages.users',
                'url' => '/user/users',
                'icon' => 'fa-user-check',
                'permission' => 'user_view',
            ],
            [
                'label' => 'messages.roles',
                'url' => '/user/roles',
                'icon' => 'fa-user-shield',
                'permission' => 'user_assign_role',
            ],
            [
                'label' => 'messages.permissions',
                'url' => '/user/permissions',
                'icon' => 'fa-user-lock',
                'permission' => 'user_assign_role',
            ],
        ]
    ]
];