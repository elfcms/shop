<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopVendorController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $vendors = ShopVendor::all();
        if ($request->ajax()) {
            $vendorData = $vendors->toArray();
            if ($byId) {
                $newData = [];
                foreach ($vendorData as $vendor) {
                    $newData[$vendor['id']] = $vendor;
                }
                $vendorData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $vendorData
            ];
        }
        else {
            return view(
                'elfcms::admin.shop.vendors.content.list',
                [
                    'vendors' => $vendors,
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
        $emptyUnit = [
            'id' => 'newvendor',
            'code' => null,
            'name' => null,
            'number' => null,
        ];
        return view(
            'elfcms::admin.shop.vendors.content.item',
            [
                'vendor' => (object)$emptyUnit,
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
        $vendorDelete = [];

        if (!empty($request->vendor)) {
            foreach($request->vendor as $id => $vendor) {
                if (!empty($vendor['delete'])) {
                    $vendorDelete[] = $id;
                    $except[] = 'vendor.'.$id;
                }
            }
        }

        if (!empty($request->newvendor)) {
            foreach($request->newvendor as $id => $newvendor) {
                if (!empty($newvendor['delete']) || (empty($newvendor['name']) && empty($newvendor['abbr']))) {
                    $except[] = 'newvendor.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*vendor.*.name' => 'required',
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

        if (!empty($vendorDelete)) {
            $deleted = ShopVendor::destroy($vendorDelete);
            if (!$deleted) {
                $result['message'] = __('shop::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['vendor'])) {
            foreach ($data['vendor'] as $id => $vendor) {
                $vendorItem = ShopVendor::find($id);
                if ($vendorItem) {
                    $vendorItem->name = $vendor['name'];
                    $vendorItem->code = $vendor['code'];
                    $vendorItem->number = $vendor['number'];
                    $saved = $vendorItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newvendor'])) {
            foreach ($data['newvendor'] as $newvendor) {
                if (empty($newvendor['default'])) {
                    $newvendor['default'] = 0;
                }
                else {
                    $newvendor['default'] = 1;
                }
                $created = ShopVendor::create($newvendor);
                if (!$created) {
                    $result['message'] = __('shop::default.error_of_creating_element_with_name',['name'=>$newvendor['name']]);

                    return $result;
                }
            }
        }

        $vendors = ShopVendor::all();
        $view = view(
            'elfcms::admin.shop.vendors.content.list',
            [
                'vendors' => $vendors,
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
