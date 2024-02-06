<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopUnitController extends Controller
{

    /**
     *
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
                'elfcms::admin.shop.unit.content.list',
                [
                    'units' => $units
                ]
            );
        }
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyItem(Request  $request)
    {
        if (!$request->ajax()) {
            abort(404);
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
            'elfcms::admin.shop.unit.content.item',
            [
                'unit' => (object)$emptyUnit
            ]
        );
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request  $request)
    {
        $result = [
            'result' => 'error',
            'message' => '',
            'data' => null
        ];
        if (!$request->isMethod('POST')) {
            $result['message'] = __('shop::default.method_must_be',['method'=>'POST']);
            return $result;
        }

        $except = [];
        $unitDelete = [];

        if (!empty($request->unit)) {
            foreach($request->unit as $id => $unit) {
                if (!empty($unit['delete'])) {
                    $unitDelete[] = $id;
                    $except[] = 'unit.'.$id;
                }
            }
        }

        if (!empty($request->newunit)) {
            foreach($request->newunit as $id => $newunit) {
                if (!empty($newunit['delete']) || (empty($newunit['name']) && empty($newunit['abbr']))) {
                    $except[] = 'newunit.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*unit.*.name' => 'required',
            '*unit.*.abbr' => 'required',
            '*unit.*.step' => 'numeric|required',
            '*unit.*.min' => 'numeric|nullable',
            '*unit.*.max' => 'numeric|nullable'
        ]);

        if ($validator->fails()) {
            $errorsData = $validator->errors()->messages();
            $errorsMessages = [];
            foreach ($errorsData as $message) {
                $errorsMessages[] = $message[0];
            }
            $result['message'] = implode('. ', $errorsMessages);

            return $result;
        }

        if (!empty($unitDelete)) {
            $deleted = ShopUnit::destroy($unitDelete);
            if (!$deleted) {
                $result['message'] = __('shop::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['unit'])) {
            foreach ($data['unit'] as $id => $unit) {
                $unitItem = ShopUnit::find($id);
                if ($unitItem) {
                    $unitItem->name = $unit['name'];
                    $unitItem->abbr = $unit['abbr'];
                    $unitItem->step = $unit['step'];
                    $unitItem->min = $unit['min'];
                    $unitItem->max = $unit['max'];
                    $unitItem->name_plural = $unit['name_plural'];
                    $unitItem->lang_name = $unit['lang_name'];
                    $unitItem->lang_name_plural = $unit['lang_name_plural'];
                    $saved = $unitItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newunit'])) {
            foreach ($data['newunit'] as $newunit) {
                $created = ShopUnit::create($newunit);
                if (!$created) {
                    $result['message'] = __('shop::default.error_of_creating_element_with_name',['name'=>$newunit['name']]);

                    return $result;
                }
            }
        }

        $units = ShopUnit::all();
        $view = view(
            'elfcms::admin.shop.unit.content.list',
            [
                'units' => $units
            ]
        )->render();
        if (!$view) {
            $result['message'] = __('shop::default.view_not_found');
            return $result;
        }
        $result['result'] = 'success';
        $result['message'] = __('shop::default.data_saved_successfully');
        $result['data'] = $view;

        return $result;
    }
}
