<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version of package
    |
    */

    'version' => '1.0.2',
    'developer' => 'Maxim Klassen',
    'license' => 'MIT',
    'author' => 'Maxim Klassen',
    'title' => 'Gallery',
    'description' => '',
    'url' => '',
    'github' => 'https://github.com/elfcms/shop',
    'release_status' => 'stable',
    'release_date' => '2025',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'basic_package' => '3.0.11',

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
            "title" => "Shop",
            "lang_title" => "shop::default.shop",
            "route" => "admin.shop.product",
            "parent_route" => "admin.shop",
            "icon" => "/elfcms/admin/modules/shop/images/icons/shop.svg",
            "color" => "#0064ff",
            "second_color" => "#008dfd",
            "position" => 500,
            "submenu" => [
                [
                    "title" => "Categories",
                    "lang_title" => "elfcms::default.categories",
                    "route" => "admin.shop.category"
                ],
                [
                    "title" => "Goods",
                    "lang_title" => "shop::default.products",
                    "route" => "admin.shop.product"
                ],
                [
                    "title" => "Vendors",
                    "lang_title" => "shop::default.vendors",
                    "route" => "admin.shop.vendor"
                ],
                [
                    "title" => "Brands",
                    "lang_title" => "shop::default.brands",
                    "route" => "admin.shop.brand"
                ],
                [
                    "title" => "Delivery services",
                    "lang_title" => "shop::default.delivery_services",
                    "route" => "admin.shop.delivery"
                ],
                [
                    "title" => "Units of measurement",
                    "lang_title" => "shop::default.units_of_measurement",
                    "route" => "admin.shop.unit"
                ],
                [
                    "title" => "Parameters",
                    "lang_title" => "shop::default.parameters",
                    "route" => "admin.shop.parameter"
                ],
                [
                    "title" => "Properties",
                    "lang_title" => "shop::default.properties",
                    "route" => "admin.shop.property"
                ],
                [
                    "title" => "Colors",
                    "lang_title" => "shop::default.colors",
                    "route" => "admin.shop.color"
                ],
                [
                    "title" => "Currencies",
                    "lang_title" => "shop::default.currencies",
                    "route" => "admin.shop.currency"
                ],
                [
                    "title" => "Taxes",
                    "lang_title" => "shop::default.taxes",
                    "route" => "admin.shop.tax"
                ],
                [
                    "title" => "Price types",
                    "lang_title" => "shop::default.price_types",
                    "route" => "admin.shop.price-type"
                ],
                [
                    "title" => "Payment methods",
                    "lang_title" => "shop::default.payment_methods",
                    "route" => "admin.shop.payment"
                ],
                [
                    "title" => "Return reasons",
                    "lang_title" => "shop::default.reasons_for_return",
                    "route" => "admin.shop.return-reason.index"
                ],
                [
                    "title" => "Coupons",
                    "lang_title" => "shop::default.coupons",
                    "route" => "admin.shop.coupon"
                ],
                [
                    "title" => "Discounts",
                    "lang_title" => "shop::default.discounts",
                    "route" => "admin.shop.discount"
                ],
                [
                    "title" => "Image settings",
                    "lang_title" => "shop::default.image-settings",
                    "route" => "admin.shop.image-settings.show"
                ],
                /* [
                    "title" => "Receipts",
                    "lang_title" => "elfcms::default.receipts",
                    "route" => "admin.shop.receipts"
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
            "title" => "Categories",
            "lang_title" => "elfcms::default.categories",
            "route" => "admin.shop.category",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Goods",
            "lang_title" => "shop::default.products",
            "route" => "admin.shop.product",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Vendors",
            "lang_title" => "shop::default.vendors",
            "route" => "admin.shop.vendor",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Brands",
            "lang_title" => "shop::default.brands",
            "route" => "admin.shop.brand",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Units of measurement",
            "lang_title" => "shop::default.units_of_measurement",
            "route" => "admin.shop.unit",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Parameters",
            "lang_title" => "shop::default.parameters",
            "route" => "admin.shop.parameter",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Properties",
            "lang_title" => "shop::default.properties",
            "route" => "admin.shop.property",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Colors",
            "lang_title" => "shop::default.colors",
            "route" => "admin.shop.color",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Currencies",
            "lang_title" => "shop::default.currencies",
            "route" => "admin.shop.currency"
        ],
        [
            "title" => "Taxes",
            "lang_title" => "shop::default.taxes",
            "route" => "admin.shop.tax",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Price types",
            "lang_title" => "shop::default.price_types",
            "route" => "admin.shop.price-type",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Image settings",
            "lang_title" => "shop::default.image-settings",
            "route" => "admin.shop.image-settings.show",
            "actions" => ["read", "write"],
        ],
    ],
];
