<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopDeliveryController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $deliveries = ShopDelivery::all();
        if ($request->ajax()) {
            $deliveryData = $deliveries->toArray();
            if ($byId) {
                $newData = [];
                foreach ($deliveryData as $delivery) {
                    $newData[$delivery['id']] = $delivery;
                }
                $deliveryData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $deliveryData
            ];
        }
        else {
            return view(
                'elfcms::admin.shop.delivery.content.list',
                [
                    'deliveries' => $deliveries,
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
            'id' => 'newdelivery',
            'code' => null,
            'name' => null,
            'number' => null,
        ];
        return view(
            'elfcms::admin.shop.delivery.content.item',
            [
                'delivery' => (object)$emptyUnit,
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
        $deliveryDelete = [];

        if (!empty($request->delivery)) {
            foreach($request->delivery as $id => $delivery) {
                if (!empty($delivery['delete'])) {
                    $deliveryDelete[] = $id;
                    $except[] = 'delivery.'.$id;
                }
            }
        }

        if (!empty($request->newdelivery)) {
            foreach($request->newdelivery as $id => $newdelivery) {
                if (!empty($newdelivery['delete']) || (empty($newdelivery['name']) && empty($newdelivery['abbr']))) {
                    $except[] = 'newdelivery.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*delivery.*.name' => 'required',
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

        if (!empty($deliveryDelete)) {
            $deleted = ShopDelivery::destroy($deliveryDelete);
            if (!$deleted) {
                $result['message'] = __('shop::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['delivery'])) {
            foreach ($data['delivery'] as $id => $delivery) {
                $deliveryItem = ShopDelivery::find($id);
                if ($deliveryItem) {
                    $deliveryItem->name = $delivery['name'];
                    $deliveryItem->code = $delivery['code'];
                    $deliveryItem->number = $delivery['number'];
                    $saved = $deliveryItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newdelivery'])) {
            foreach ($data['newdelivery'] as $newdelivery) {
                if (empty($newdelivery['default'])) {
                    $newdelivery['default'] = 0;
                }
                else {
                    $newdelivery['default'] = 1;
                }
                $created = ShopDelivery::create($newdelivery);
                if (!$created) {
                    $result['message'] = __('shop::default.error_of_creating_element_with_name',['name'=>$newdelivery['name']]);

                    return $result;
                }
            }
        }

        $deliveries = ShopDelivery::all();
        $view = view(
            'elfcms::admin.shop.delivery.content.list',
            [
                'deliveries' => $deliveries,
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
