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

    'version' => '0.5',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'basic_package' => '1.4.1',

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
            "lang_title" => "shop::elf.shop",
            "route" => "admin.shop.index",
            "parent_route" => "admin.shop",
            "icon" => "/vendor/elfcms/shop/admin/images/icons/shop.png",
            "position" => 110,
            "submenu" => [
                [
                    "title" => "Categories",
                    "lang_title" => "basic::elf.categories",
                    "route" => "admin.shop.category"
                ],
                [
                    "title" => "Goods",
                    "lang_title" => "shop::elf.products",
                    "route" => "admin.shop.product"
                ],
                [
                    "title" => "Vendors",
                    "lang_title" => "shop::elf.vendors",
                    "route" => "admin.shop.vendor"
                ],
                [
                    "title" => "Brands",
                    "lang_title" => "shop::elf.brands",
                    "route" => "admin.shop.brand"
                ],
                [
                    "title" => "Units of measurement",
                    "lang_title" => "shop::elf.units_of_measurement",
                    "route" => "admin.shop.unit"
                ],
                [
                    "title" => "Parameters",
                    "lang_title" => "shop::elf.parameters",
                    "route" => "admin.shop.parameter"
                ],
                [
                    "title" => "Properties",
                    "lang_title" => "shop::elf.properties",
                    "route" => "admin.shop.property"
                ],
                [
                    "title" => "Colors",
                    "lang_title" => "shop::elf.colors",
                    "route" => "admin.shop.color"
                ],
                [
                    "title" => "Currencies",
                    "lang_title" => "shop::elf.currencies",
                    "route" => "admin.shop.currency"
                ],
            ]
        ],
    ],
];
