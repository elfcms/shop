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
            "icon" => "/elfcms/admin/modules/shop/images/icons/bag.png",
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
            "title" => "Customers",
            "lang_title" => "shop::default.customers",
            "route" => "admin.shop.category",
            "actions" => ["read", "write"],
        ],
    ],
];
