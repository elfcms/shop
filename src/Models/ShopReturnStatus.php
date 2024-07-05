<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReturnStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_complited',
        'order',
    ];

    public function returns()
    {
        return $this->hasMany(ShopReturn::class, 'return_status_id');
    }

    public static function byCode($code = null): object|array {
        $result = [];
        if (empty($code)) {
            $data = self::all();
            foreach ($data as $item) {
                $result[$item->code] = $item;
            }
        }
        else {
            $result = self::where('code', $code)->first();
        }
        return $result;
    }
}
