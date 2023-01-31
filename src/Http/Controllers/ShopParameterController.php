<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Basic\Models\DataType;
use Elfcms\Shop\Models\ShopItemParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopParameterController extends Controller
{

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $dataTypes = DataType::all();
        $parameters = ShopItemParameter::all();
        if ($request->ajax()) {
            $parameterData = $parameters->toArray();
            if ($byId) {
                $newData = [];
                foreach ($parameterData as $parameter) {
                    $newData[$parameter['id']] = $parameter;
                }
                $parameterData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $parameterData
            ];
        }
        else {
            return view(
                'shop::admin.shop.parameter.content.list',
                [
                    'parameters' => $parameters,
                    'data_types' => $dataTypes,
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
        $dataTypes = DataType::all();
        $emptyUnit = [
            'id' => 'newparameter',
            'data_type_id' => null,
            'code' => null,
            'name' => null,
            'description' => null,
            'is_filter' => 0,
            'multiple' => 0,
            'data_type' => (object)['code'=>null],
        ];
        return view(
            'shop::admin.shop.parameter.content.item',
            [
                'parameter' => (object)$emptyUnit,
                'data_types' => $dataTypes,
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
            $result['message'] = __('shop::elf.method_must_be',['method'=>'POST']);
            return $result;
        }

        $except = [];
        $parameterDelete = [];

        if (!empty($request->parameter)) {
            foreach($request->parameter as $id => $parameter) {
                if (!empty($parameter['delete'])) {
                    $parameterDelete[] = $id;
                    $except[] = 'parameter.'.$id;
                }
            }
        }

        if (!empty($request->newparameter)) {
            foreach($request->newparameter as $id => $newparameter) {
                if (!empty($newparameter['delete']) || (empty($newparameter['name']) && empty($newparameter['abbr']))) {
                    $except[] = 'newparameter.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*parameter.*.name' => 'required',
            '*parameter.*.code' => 'required',
            '*parameter.*.data_type_id' => 'integer|required',
            '*parameter.*.is_filter' => 'integer|required',
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

        if (!empty($parameterDelete)) {
            $deleted = ShopItemParameter::destroy($parameterDelete);
            if (!$deleted) {
                $result['message'] = __('shop::elf.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['parameter'])) {
            foreach ($data['parameter'] as $id => $parameter) {
                $parameterItem = ShopItemParameter::find($id);
                if ($parameterItem) {
                    $options = [];
                    if (!empty($parameter['options']) && is_array($parameter['options'])) {
                        foreach ($parameter['options'] as $option) {
                            if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                                continue;
                            }
                            $options[$option['key']] = $option['value'];
                        }
                    }
                    $parameterItem->name = $parameter['name'];
                    $parameterItem->code = $parameter['code'];
                    $parameterItem->data_type_id = $parameter['data_type_id'];
                    $parameterItem->description = $parameter['description'];
                    $parameterItem->is_filter = empty($parameter['is_filter']) ? 0 : 1;
                    $parameterItem->multiple = empty($parameter['multiple']) ? 0 : 1;
                    $parameterItem->options = json_encode($options);
                    $saved = $parameterItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::elf.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newparameter'])) {
            foreach ($data['newparameter'] as $newparameter) {
                if (empty($newparameter['multiple'])) {
                    $newparameter['multiple'] = 0;
                }
                else {
                    $newparameter['multiple'] = 1;
                }
                if (empty($newparameter['is_filter'])) {
                    $newparameter['is_filter'] = 0;
                }
                else {
                    $newparameter['is_filter'] = 1;
                }
                $options = [];
                if (!empty($newparameter['options']) && is_array($newparameter['options'])) {
                    foreach ($newparameter['options'] as $option) {
                        if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                            continue;
                        }
                        $options[$option['key']] = $option['value'];
                    }
                    $newparameter['options'] = json_encode($options);
                }
                $created = ShopItemParameter::create($newparameter);
                if (!$created) {
                    $result['message'] = __('shop::elf.error_of_creating_element_with_name',['name'=>$newparameter['name']]);

                    return $result;
                }
            }
        }

        $parameters = ShopItemParameter::all();
        $dataTypes = DataType::all();
        $view = view(
            'shop::admin.shop.parameter.content.list',
            [
                'parameters' => $parameters,
                'data_types' => $dataTypes,
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
