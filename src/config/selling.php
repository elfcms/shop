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
            "route" => "admin.shop.product",
            "parent_route" => "admin.shop",
            "icon" => "/elfcms/admin/modules/shop/images/icons/shop.png",
            "position" => 600,
            "submenu" => [
                [
                    "title" => "Customers",
                    "lang_title" => "shop::default.customers",
                    "route" => "admin.shop.category"
                ],
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
