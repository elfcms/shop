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

    'version' => '0.1',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'basic_package' => '0.12',

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
            "parent_route" => "admin.shop.index",
            "icon" => "/vendor/elfcms/shop/admin/images/icons/shop.png",
            "submenu" => []
        ],
    ],
];
