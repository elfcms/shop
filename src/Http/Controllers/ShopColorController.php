<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = ShopColor::all();
        return view('shop::admin.shop.color.index',[
            'page' => [
                'title' => __('shop::elf.colors'),
                'current' => url()->current(),
            ],
            'colors' => $colors,
        ]);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $colors = ShopColor::all();
        if ($request->ajax()) {
            $colorData = $colors->toArray();
            if ($byId) {
                $newData = [];
                foreach ($colorData as $color) {
                    $newData[$color['id']] = $color;
                }
                $colorData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $colorData
            ];
        }
        else {
            return view(
                'shop::admin.shop.color.content.list',
                [
                    'colors' => $colors,
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
        $emptyColor = [
            'id' => 'newcolor',
            'code' => null,
            'name' => null,
            'symbol' => null,
            'symbol_code' => null,
            'symbol_html' => null,
            'default' => 0
        ];
        return view(
            'shop::admin.shop.color.content.item',
            [
                'color' => (object)$emptyColor,
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
        $colorDelete = [];

        if (!empty($request->color)) {
            foreach($request->color as $id => $color) {
                if (!empty($color['delete'])) {
                    $colorDelete[] = $id;
                    $except[] = 'color.'.$id;
                }
            }
        }

        if (!empty($request->newcolor)) {
            foreach($request->newcolor as $id => $newcolor) {
                if (!empty($newcolor['delete']) || (empty($newcolor['name']) && empty($newcolor['abbr']))) {
                    $except[] = 'newcolor.'.$id;
                }
            }
        }
        $data = $request->except($except);
        $validator = Validator::make($data, [
            '*color.*.name' => 'required',
            '*color.*.code' => 'required',
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

        if (!empty($colorDelete)) {
            $deleted = ShopColor::destroy($colorDelete);
            if (!$deleted) {
                $result['message'] = __('shop::elf.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['color'])) {
            foreach ($data['color'] as $id => $color) {
                $colorItem = ShopColor::find($id);
                if ($colorItem) {
                    $colorItem->name = $color['name'];
                    $colorItem->code = $color['code'];
                    $colorItem->default = empty($color['default']) ? 0 : 1;
                    $saved = $colorItem->save();
                    if (!$saved) {
                        $result['message'] =  __('shop::elf.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newcolor'])) {
            foreach ($data['newcolor'] as $newcolor) {
                if (empty($newcolor['default'])) {
                    $newcolor['default'] = 0;
                }
                else {
                    $newcolor['default'] = 1;
                }
                $created = ShopColor::create($newcolor);
                if (!$created) {
                    $result['message'] = __('shop::elf.error_of_creating_element_with_name',['name'=>$newcolor['name']]);

                    return $result;
                }
            }
        }

        $colors = ShopColor::all();
        $view = view(
            'shop::admin.shop.color.content.list',
            [
                'colors' => $colors,
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
