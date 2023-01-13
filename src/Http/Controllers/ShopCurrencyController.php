<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopCurrencyController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $currencies = ShopCurrency::all();
        if ($request->ajax()) {
            $currencyData = $currencies->toArray();
            if ($byId) {
                $newData = [];
                foreach ($currencyData as $currency) {
                    $newData[$currency['id']] = $currency;
                }
                $currencyData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $currencyData
            ];
        }
        else {
            return view(
                'shop::admin.shop.currency.content.list',
                [
                    'currencies' => $currencies,
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
            abort(404);
        }
        $emptyUnit = [
            'id' => 'newcurrency',
            'code' => null,
            'name' => null,
            'symbol' => null,
            'symbol_code' => null,
            'symbol_html' => null,
            'default' => 0
        ];
        return view(
            'shop::admin.shop.currency.content.item',
            [
                'currency' => (object)$emptyUnit,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
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
        $currencyDelete = [];

        if (!empty($request->currency)) {
            foreach($request->currency as $id => $currency) {
                if (!empty($currency['delete'])) {
                    $currencyDelete[] = $id;
                    $except[] = 'currency.'.$id;
                }
            }
        }

        if (!empty($request->newcurrency)) {
            foreach($request->newcurrency as $id => $newcurrency) {
                if (!empty($newcurrency['delete']) || (empty($newcurrency['name']) && empty($newcurrency['abbr']))) {
                    $except[] = 'newcurrency.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*currency.*.name' => 'required',
            '*currency.*.code' => 'required',
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

        if (!empty($currencyDelete)) {
            $deleted = ShopCurrency::destroy($currencyDelete);
            if (!$deleted) {
                $result['message'] = __('shop::elf.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['currency'])) {
            foreach ($data['currency'] as $id => $currency) {
                $currencyItem = ShopCurrency::find($id);
                if ($currencyItem) {
                    $currencyItem->name = $currency['name'];
                    $currencyItem->code = $currency['code'];
                    $currencyItem->symbol = $currency['symbol'];
                    $currencyItem->symbol_code = $currency['symbol_code'];
                    $currencyItem->symbol_html = $currency['symbol_html'];
                    $currencyItem->default = empty($currency['default']) ? 0 : 1;
                    $saved = $currencyItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::elf.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newcurrency'])) {
            foreach ($data['newcurrency'] as $newcurrency) {
                if (empty($newcurrency['default'])) {
                    $newcurrency['default'] = 0;
                }
                else {
                    $newcurrency['default'] = 1;
                }
                $created = ShopCurrency::create($newcurrency);
                if (!$created) {
                    $result['message'] = __('shop::elf.error_of_creating_element_with_name',['name'=>$newcurrency['name']]);

                    return $result;
                }
            }
        }

        $currencies = ShopCurrency::all();
        $view = view(
            'shop::admin.shop.currency.content.list',
            [
                'currencies' => $currencies,
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
