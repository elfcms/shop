<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopReturnReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopReturnReasonController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $reasons = ShopReturnReason::all();
        if ($request->ajax()) {
            $reasonData = $reasons->toArray();
            if ($byId) {
                $newData = [];
                foreach ($reasonData as $reason) {
                    $newData[$reason['id']] = $reason;
                }
                $reasonData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $reasonData
            ];
        }
        else {
            return view(
                'elfcms::admin.shop.return-reason.content.list',
                [
                    'reasons' => $reasons,
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
            'id' => 'newreason',
            'description' => null,
            'name' => null,
        ];
        return view(
            'elfcms::admin.shop.return-reason.content.item',
            [
                'reason' => (object)$emptyUnit,
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
        $reasonDelete = [];

        if (!empty($request->reason)) {
            foreach($request->reason as $id => $reason) {
                if (!empty($reason['delete'])) {
                    $reasonDelete[] = $id;
                    $except[] = 'reason.'.$id;
                }
            }
        }

        if (!empty($request->newreason)) {
            foreach($request->newreason as $id => $newreason) {
                if (!empty($newreason['delete']) || (empty($newreason['name']) && empty($newreason['abbr']))) {
                    $except[] = 'newreason.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*reason.*.name' => 'required',
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

        if (!empty($reasonDelete)) {
            $deleted = ShopReturnReason::destroy($reasonDelete);
            if (!$deleted) {
                $result['message'] = __('shop::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['reason'])) {
            foreach ($data['reason'] as $id => $reason) {
                $reasonItem = ShopReturnReason::find($id);
                if ($reasonItem) {
                    $reasonItem->name = $reason['name'];
                    $reasonItem->description = $reason['description'];
                    $saved = $reasonItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newreason'])) {
            foreach ($data['newreason'] as $newreason) {
                $created = ShopReturnReason::create($newreason);
                if (!$created) {
                    $result['message'] = __('shop::default.error_of_creating_element_with_name',['name'=>$newreason['name']]);

                    return $result;
                }
            }
        }

        $reasons = ShopReturnReason::all();
        $view = view(
            'elfcms::admin.shop.return-reason.content.list',
            [
                'reasons' => $reasons,
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
