<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plz',
        'country',
        'region',
        'city',
        'street',
        'house',
        'flat',
        'address',
        'additional',
        'geodata_zip_id',
        'geodata_country_id',
        'geodata_region_id',
        'geodata_city_id',
        'geodata_street_id'
    ];
}
