<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Menu data
    |--------------------------------------------------------------------------
    |
    | Menu data of this package for admin panel
    |
    */

    "menu" => [
        [
            "title" => "Selling",
            "lang_title" => "shop::default.selling",
            "route" => "admin.selling.customers",
            "parent_route" => "admin.selling",
            "icon" => "/elfcms/admin/modules/shop/images/icons/bag.svg",
            "color" => "#0064ff",
            "second_color" => "#008dfd",
            "position" => 600,
            "submenu" => [
                [
                    "title" => "Customers",
                    "lang_title" => "shop::default.customers",
                    "route" => "admin.selling.customers"
                ],
                [
                    "title" => "Orders",
                    "lang_title" => "shop::default.orders",
                    "route" => "admin.selling.orders"
                ],
                /* [
                    "title" => "Carts",
                    "lang_title" => "shop::default.carts",
                    "route" => "admin.selling.carts"
                ], */
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Access control
    |--------------------------------------------------------------------------
    |
    | Define access rules for admin panel pages.
    |
    */

    "access_routes" => [
        [
            "title" => "Orders",
            "lang_title" => "shop::default.orders",
            "route" => "admin.selling.orders",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Customers",
            "lang_title" => "shop::default.customers",
            "route" => "admin.selling.category",
            "actions" => ["read", "write"],
        ],
    ],
];
