<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopUnit;
use Illuminate\Http\Request;

class ShopUnitController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $units = ShopUnit::all();
        if ($request->ajax()) {
            $unitData = $units->toArray();
            if ($byId) {
                $newData = [];
                foreach ($unitData as $unit) {
                    $newData[$unit['id']] = $unit;
                }
                $unitData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $unitData
            ];
        }
        else {
            return view(
                'shop::admin.shop.unit.content.list',
                [
                    'units' => $units
                ]
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyItem(Request  $request)
    {
        if (!$request->ajax()) {
            //abort(404);
        }
        $emptyUnit = [
            'id' => 'newunit',
            'name' => null,
            'abbr' => null,
            'step' => 1,
            'min' => null,
            'max' => null,
            'name_plural' => null,
            'lang_name' => null,
            'lang_name_plural' => null,
        ];
        return view(
            'shop::admin.shop.unit.content.item',
            [
                'unit' => (object)$emptyUnit
            ]
        );
    }
}
