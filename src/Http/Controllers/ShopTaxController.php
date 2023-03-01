<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopTaxController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $taxes = ShopTax::all();
        if ($request->ajax()) {
            $taxData = $taxes->toArray();
            if ($byId) {
                $newData = [];
                foreach ($taxData as $tax) {
                    $newData[$tax['id']] = $tax;
                }
                $taxData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $taxData
            ];
        }
        else {
            return view(
                'shop::admin.shop.tax.content.list',
                [
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
            'id' => 'newtax',
            'name' => null,
            'value' => null,
            'title' => null,
            'description' => null,
        ];
        return view(
            'shop::admin.shop.tax.content.item',
            [
                'tax' => (object)$emptyItem,
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
            $result['message'] = __('shop::elf.method_must_be',['method'=>'POST']);
            return $result;
        }

        $except = [];
        $taxDelete = [];

        if (!empty($request->tax)) {
            foreach($request->tax as $id => $tax) {
                if (!empty($tax['delete'])) {
                    $taxDelete[] = $id;
                    $except[] = 'tax.'.$id;
                }
            }
        }

        if (!empty($request->newtax)) {
            foreach($request->newtax as $id => $newtax) {
                if (!empty($newtax['delete']) || (empty($newtax['name']) && empty($newtax['abbr']))) {
                    $except[] = 'newtax.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*tax.*.name' => 'required',
            '*tax.*.value' => 'required',
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

        if (!empty($taxDelete)) {
            $deleted = ShopTax::destroy($taxDelete);
            if (!$deleted) {
                $result['message'] = __('shop::elf.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['tax'])) {
            foreach ($data['tax'] as $id => $tax) {
                $taxItem = ShopTax::find($id);
                if ($taxItem) {
                    $taxItem->name = $tax['name'];
                    $taxItem->value = $tax['value'];
                    $taxItem->title = $tax['title'];
                    $taxItem->description = $tax['description'];
                    $saved = $taxItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::elf.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newtax'])) {
            foreach ($data['newtax'] as $newtax) {
                $created = ShopTax::create($newtax);
                if (!$created) {
                    $result['message'] = __('shop::elf.error_of_creating_element_with_name',['name'=>$newtax['name']]);

                    return $result;
                }
            }
        }

        $taxes = ShopTax::all();
        $view = view(
            'shop::admin.shop.tax.content.list',
            [
                'taxes' => $taxes,
            ]
        )->render();
        if (!$view) {
            $result['message'] = __('shop::elf.view_not_found');
            return $result;
        }
        $result['result'] = 'success';
        $result['message'] = __('shop::elf.data_saved_successfully');
        $result['data'] = $view;

        return $result;
    }
}
