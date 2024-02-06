<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopPriceType;
use Elfcms\Shop\Models\ShopTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopPriceTypeController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $priceTypes = ShopPriceType::all();
        $taxes = ShopTax::all();
        if ($request->ajax()) {
            $priceTypeData = $priceTypes->toArray();
            if ($byId) {
                $newData = [];
                foreach ($priceTypeData as $priceType) {
                    $newData[$priceType['id']] = $priceType;
                }
                $priceTypeData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $priceTypeData
            ];
        }
        else {
            return view(
                'elfcms::admin.shop.price-type.content.list',
                [
                    'price_types' => $priceTypes,
                    'taxes' => $taxes,
                ]
            );
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyItem(Request  $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $emptyItem = [
            'id' => 'newpricetype',
            'name' => null,
            'tax_id' => null,
        ];
        $taxes = ShopTax::all();
        return view(
            'elfcms::admin.shop.price-type.content.item',
            [
                'price_type' => (object)$emptyItem,
                'taxes' => $taxes
            ]
        );
    }

    /**
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
        $priceTypeDelete = [];

        if (!empty($request->price_type)) {
            foreach($request->price_type as $id => $priceType) {
                if (!empty($priceType['delete'])) {
                    $priceTypeDelete[] = $id;
                    $except[] = 'price_type.'.$id;
                }
            }
        }

        if (!empty($request->newpricetype)) {
            foreach($request->newpricetype as $id => $newpricetype) {
                if (!empty($newpricetype['delete']) || (empty($newpricetype['name']) && empty($newpricetype['abbr']))) {
                    $except[] = 'newpricetype.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*price_type.*.name' => 'required',
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

        if (!empty($priceTypeDelete)) {
            $deleted = ShopPriceType::destroy($priceTypeDelete);
            if (!$deleted) {
                $result['message'] = __('shop::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['price_type'])) {
            foreach ($data['price_type'] as $id => $priceType) {
                $priceTypeItem = ShopPriceType::find($id);
                if ($priceTypeItem) {
                    $priceTypeItem->name = $priceType['name'];
                    $priceTypeItem->tax_id = empty($priceType['tax_id']) ? null : $priceType['tax_id'];
                    $saved = $priceTypeItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newpricetype'])) {
            foreach ($data['newpricetype'] as $newpricetype) {
                $created = ShopPriceType::create($newpricetype);
                if (!$created) {
                    $result['message'] = __('shop::default.error_of_creating_element_with_name',['name'=>$newpricetype['name']]);

                    return $result;
                }
            }
        }

        $priceTypes = ShopPriceType::all();
        $taxes = ShopTax::all();
        $view = view(
            'elfcms::admin.shop.price-type.content.list',
            [
                'price_types' => $priceTypes,
                'taxes' => $taxes,
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
