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

    'version' => '0.9',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'basic_package' => '2.0',

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
            "icon" => "/elfcms/admin/modules/shop/images/icons/shop.png",
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
                    "title" => "Image settings",
                    "lang_title" => "shop::default.image-settings",
                    "route" => "admin.shop.image-settings.show"
                ],
                [
                    "title" => "Receipts",
                    "lang_title" => "elfcms::default.receipts",
                    "route" => "admin.shop.receipts"
                ],
            ]
        ]
    ],
];
