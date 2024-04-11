<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FileCatalog;
use Elfcms\Elfcms\Models\Setting;
use Elfcms\Shop\Models\ShopColor;
use Elfcms\Shop\Models\ShopCurrency;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemParameterValue;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopItemPropertyValue;
use Elfcms\Shop\Models\ShopPrice;
use Elfcms\Shop\Models\ShopPriceType;
use Elfcms\Shop\Models\ShopVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $products = ShopProduct::active()->get();
        $vendors = ShopVendor::all();
        //dd($vendors);
        $product_id = null;
        if (!empty($request->product_id)) {
            $product_id = $request->product_id;
        }
        $copy_id = null;
        if (!empty($request->copy_id)) {
            $copy_id = $request->copy_id;
        }

        if (!empty($copy_id)) {
            $copyItem = ShopItem::find($copy_id);

            if ($copyItem) {

                $parameters = ShopItemParameter::all();
                $properties = ShopItemProperty::all();
                $colors = ShopColor::all();
                foreach ($parameters as $parameter) {
                    $parameter->value = $parameter->item_values($copyItem->id);
                    if ($parameter->data_type->code == 'color') {
                        $parameter->colorData = ShopColor::find($parameter->value);
                    }
                }
                foreach ($properties as $property) {
                    $property->value = $property->item_values($copyItem->id);
                    if ($property->data_type->code == 'color') {
                        $property->colorData = ShopColor::find($property->value);
                    }
                }
                $price_types = ShopPriceType::all();
                $currencies = ShopCurrency::all();
                return view('elfcms::admin.shop.item.edit',[
                    'page' => [
                        'title' => __('shop::default.create_item'),
                        'current' => url()->current(),
                    ],
                    'products' => $products,
                    'vendors' => $vendors,
                    'item' => $copyItem,
                    'parameters' => $parameters,
                    'properties' => $properties,
                    'colors' => $colors,
                    'isCopy' => true,
                    'price_types' => $price_types,
                    'currencies' => $currencies,
                ]);
            }
        }

        return view('elfcms::admin.shop.item.create',[
            'page' => [
                'title' => __('shop::default.create_item'),
                'current' => url()->current(),
            ],
            'products' => $products,
            'vendors' => $vendors,
            'product_id' => $product_id,
            'copy_id' => $copy_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'item_number' => 'required|unique:Elfcms\Shop\Models\ShopItem,item_number',
            'product_id' => 'required',
            'image' => 'nullable|file|max:1024',
        ]);

        //dd($request->unit_id);

        $path = Setting::value('shop_image_path') ?? '/shop/item/image';

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store($path);
        }

        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;
        $validated['product_id'] = $request->product_id;
        $validated['unit_id'] = (int)$request->unit_id;
        $validated['brand_id'] = $request->brand_id;
        $validated['vendor_id'] = $request->vendor_id;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        $validated['item_number'] = $validated['item_number'];
        $validated['vendor_item_number'] = $request->vendor_item_number;
        $validated['additional_name'] = $request->additional_name;
        $validated['alternate_name'] = $request->alternate_name;
        $validated['note'] = $request->note;
        $validated['balance'] = $request->balance;
        $validated['image'] = $image_path;
        //dd($validated);

        $shopItem = ShopItem::create($validated);

        if ($shopItem) {
            /* Parameters */
            if (!empty($request->parameter)) {
                foreach($request->parameter as $id => $paramValue) {
                    $parameter = ShopItemParameter::find($id);
                    if (is_array($paramValue)) {
                        //dd($paramValue);
                        if (!empty($request->file()['parameter'][$id]['file'])) {
                            continue;
                        }
                        if ($parameter->data_type->code != 'file' && $parameter->data_type->code != 'image') {
                            continue;
                        }
                        $paramValue = $paramValue['path'];
                    }
                    if ($parameter->data_type->code == 'list') {
                        if (empty($paramValue)) {
                            $paramValue = [];
                        }
                        if (!is_array($paramValue)) {
                            $paramValue = [$paramValue];
                        }
                        $paramValue = json_encode($paramValue);
                        //dd([$parameter->data_type->code,$paramValue]);
                    }
                    if ($parameter->data_type->code == 'color') {
                        if ($paramValue == 0) {
                            $paramValue = null;
                        }
                    }
                    $parameterValue = ShopItemParameterValue::updateOrCreate(
                        ['item_id' => $shopItem->id, 'parameter_id' => $id],
                        [$parameter->data_type->code . '_value' => $paramValue]
                    );

                }
            }
            if (!empty($request->file()['parameter'])) {
                foreach($request->file()['parameter'] as $id => $paramValue) {
                    $parameter = ShopItemParameter::find($id);
                    if ($parameter->data_type->code != 'file' && $parameter->data_type->code != 'image') {
                        continue;
                    }
                    $originalName = $paramValue[$parameter->data_type->code]->getClientOriginalName();
                    //dd($paramValue[$parameter->data_type->code]);
                    $file_path = $request->parameter[$id]['path'];
                    $file_path = $paramValue[$parameter->data_type->code]->store('shop/parameters/item/' . $parameter->data_type->code . 's');
                    //$fileCatalog = new FileCatalog();
                    //dd($file_path);
                    FileCatalog::set($file_path,$originalName);
                    $parameterValue = ShopItemParameterValue::updateOrCreate(
                        ['item_id' => $shopItem->id, 'parameter_id' => $id],
                        [$parameter->data_type->code . '_value' => $file_path]
                    );
                }
            }
            /* /Parameters */

            /* Properties */
            if (!empty($request->property)) {
                foreach($request->property as $id => $paramValue) {
                    $property = ShopItemProperty::find($id);
                    if (is_array($paramValue)) {
                        if (!empty($request->file()['property'][$id]['file'])) {
                            continue;
                        }
                        if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                            continue;
                        }
                        $paramValue = $paramValue['path'];
                    }
                    if ($property->data_type->code == 'list') {
                        if (empty($paramValue)) {
                            $paramValue = [];
                        }
                        if (!is_array($paramValue)) {
                            $paramValue = [$paramValue];
                        }
                        $paramValue = json_encode($paramValue);
                    }
                    if ($property->data_type->code == 'color') {
                        if ($paramValue == 0) {
                            $paramValue = null;
                        }
                    }

                    $propertyValue = ShopItemPropertyValue::updateOrCreate(
                        ['item_id' => $shopItem->id, 'property_id' => $id],
                        [$property->data_type->code . '_value' => $paramValue]
                    );

                }
            }
            if (!empty($request->file()['property'])) {
                foreach($request->file()['property'] as $id => $paramValue) {
                    $property = ShopItemProperty::find($id);
                    if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                        continue;
                    }
                    $originalName = $paramValue[$property->data_type->code]->getClientOriginalName();
                    $file_path = $request->property[$id]['path'];
                    $file_path = $paramValue[$property->data_type->code]->store('shop/properties/item/' . $property->data_type->code . 's');
                    FileCatalog::set($file_path,$originalName);
                    $propertyValue = ShopItemPropertyValue::updateOrCreate(
                        ['item_id' => $shopItem->id, 'property_id' => $id],
                        [$property->data_type->code . '_value' => $file_path]
                    );
                }
            }
            /* /Properties */
        }

        return redirect(route('admin.shop.product.edit',$shopItem->product))->with('action_result',__('shop::default.item_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopItem  $shopItem
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopItem $shopItem)
    {
        if ($request->ajax()) {
            return $shopItem;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopItem  $shopItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopItem $shopItem)
    {
        $products = ShopProduct::active()->get();
        $vendors = ShopVendor::all();
        $parameters = ShopItemParameter::all();
        $properties = ShopItemProperty::all();
        $colors = ShopColor::all();

        //dd($colors);
        //dd($shopItem->publicPrices);
        /* $p = ShopItemParameter::find(9);
        dd($p->options); */
        foreach ($parameters as $parameter) {
            //if ($parameter->id == 9) dd($parameter->product_values($shopItem->id));
            $parameter->value = $parameter->item_values($shopItem->id);
            if ($parameter->data_type->code == 'color') {
                $parameter->colorData = ShopColor::find($parameter->value);
            }
            //if ($parameter->id == 9) dd($parameter->value);
        }
        foreach ($properties as $property) {
            //if ($parameter->id == 9) dd($parameter->product_values($shopItem->id));
            $property->value = $property->item_values($shopItem->id);
            if ($property->data_type->code == 'color') {
                //dd($property->value);
                $property->colorData = ShopColor::find($property->value);
            }
            //if ($parameter->id == 9) dd($parameter->value);
        }

        $price_types = ShopPriceType::all();
        $currencies = ShopCurrency::all();

        //dd($shopItem->prices);

        return view('elfcms::admin.shop.item.edit',[
            'page' => [
                'title' => __('shop::default.edit_item'),
                'current' => url()->current(),
            ],
            'products' => $products,
            'vendors' => $vendors,
            'item' => $shopItem,
            'parameters' => $parameters,
            'properties' => $properties,
            'colors' => $colors,
            'price_types' => $price_types,
            'currencies' => $currencies,
            'exists' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopItem  $shopItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopItem $shopItem)
    {
        //dd($request->price['exists']);
        if ($request->notedit && $request->notedit == 1) {
            $shopItem->active = empty($request->active) ? 0 : 1;

            $shopItem->save();

            return redirect(route('admin.shop.product'))->with('action_result',__('shop::default.product_edited_successfully'));
        }
        else {
            /* $request->merge([
                'slug' => Str::slug($request->slug),
            ]); */
            $validated = $request->validate([
                'item_number' => 'required',
                'product_id' => 'required',
                'image' => 'nullable|file|max:1024',
            ]);
            if (ShopItem::where('item_number',$request->item_number)->where('id','<>',$shopItem->id)->first()) {
                return redirect()->back()->withErrors([
                    'item_number' => 'Goods already exists'
                ]);
            }

            $path = Setting::value('shop_image_path') ?? '/shop/item/image';

            $image_path = $request->image_path;
            if (!empty($request->file()['image'])) {
                $image_path = $request->file()['image']->store($path);
            }

            $shopItem->item_number = $validated['item_number'];
            $shopItem->product_id = $request->product_id;
            $shopItem->vendor_item_number = $request->vendor_item_number;
            $shopItem->vendor_id = $request->vendor_id;
            $shopItem->description = $request->description;
            $shopItem->additional_name = $request->additional_name;
            $shopItem->alternate_name = $request->alternate_name;
            $shopItem->note = $request->note;
            $shopItem->balance = $request->balance;
            $shopItem->image = $image_path;
            $shopItem->active = empty($request->active) ? 0 : 1;
            $shopItem->meta_keywords = $request->meta_keywords;
            $shopItem->meta_description = $request->meta_description;


            $shopItem->save();

            if ($shopItem) {
                /* Prices */

                if (!empty($request->price)) {
                    if (!empty($request->price['exists'])) {
                        foreach($request->price['exists'] as $id => $price) {
                            $priceItem = ShopPrice::where('id',$id)->where('item_id',$shopItem->id)->first();
                            if ($priceItem) {
                                if (!empty($price['delete'])) {
                                    $priceItem->delete();
                                    continue;
                                }
                                if (empty($price['current'])) {
                                    $priceItem->current = 0;
                                }
                                else {
                                    $priceItem->current = 1;
                                }
                                if (empty($price['old'])) {
                                    $priceItem->old = 0;
                                }
                                else {
                                    $priceItem->old = 1;
                                }
                                $priceItem->price_type_id = $price['price_type_id'];
                                $priceItem->currency_id = $price['currency_id'];
                                $priceItem->value = $price['value'] * 100;

                                $priceItem->save();
                            }
                        }
                    }

                    if (!empty($request->price['new'])) {
                        foreach($request->price['new'] as $price) {
                            if (!empty($price['delete'])) {
                                continue;
                            }
                            if (empty($price['current'])) {
                                $price['current'] = 0;
                            }
                            else {
                                $price['current'] = 1;
                            }
                            if (empty($price['old'])) {
                                $price['old'] = 0;
                            }
                            else {
                                $price['old'] = 1;
                            }
                            $price['value'] = $price['value'] * 100;

                            $priceItem = new ShopPrice($price);
                            $shopItem->prices()->save($priceItem);
                        }
                    }
                }

                /* /Prices */

                /* Parameters */
                if (!empty($request->parameter)) {
                    foreach($request->parameter as $id => $paramValue) {
                        $parameter = ShopItemParameter::find($id);
                        if (is_array($paramValue)) {
                            //dd($paramValue);
                            if (!empty($request->file()['parameter'][$id]['file'])) {
                                continue;
                            }
                            if ($parameter->data_type->code != 'file' && $parameter->data_type->code != 'image') {
                                continue;
                            }
                            $paramValue = $paramValue['path'];
                        }
                        if ($parameter->data_type->code == 'list') {
                            if (empty($paramValue)) {
                                $paramValue = [];
                            }
                            if (!is_array($paramValue)) {
                                $paramValue = [$paramValue];
                            }
                            $paramValue = json_encode($paramValue);
                            //dd([$parameter->data_type->code,$paramValue]);
                        }
                        if ($parameter->data_type->code == 'color') {
                            if ($paramValue == 0) {
                                $paramValue = null;
                            }
                        }
                        $parameterValue = ShopItemParameterValue::updateOrCreate(
                            ['item_id' => $shopItem->id, 'parameter_id' => $id],
                            [$parameter->data_type->code . '_value' => $paramValue]
                        );

                    }
                }
                if (!empty($request->file()['parameter'])) {
                    foreach($request->file()['parameter'] as $id => $paramValue) {
                        $parameter = ShopItemParameter::find($id);
                        if ($parameter->data_type->code != 'file' && $parameter->data_type->code != 'image') {
                            continue;
                        }
                        $originalName = $paramValue[$parameter->data_type->code]->getClientOriginalName();
                        //dd($paramValue[$parameter->data_type->code]);
                        $file_path = $request->parameter[$id]['path'];
                        $file_path = $paramValue[$parameter->data_type->code]->store('shop/parameters/item/' . $parameter->data_type->code . 's');
                        //$fileCatalog = new FileCatalog();
                        //dd($file_path);
                        FileCatalog::set($file_path,$originalName);
                        $parameterValue = ShopItemParameterValue::updateOrCreate(
                            ['item_id' => $shopItem->id, 'parameter_id' => $id],
                            [$parameter->data_type->code . '_value' => $file_path]
                        );
                    }
                }
                /* /Parameters */

                /* Properties */
                if (!empty($request->property)) {
                    foreach($request->property as $id => $paramValue) {
                        $property = ShopItemProperty::find($id);
                        if (is_array($paramValue)) {
                            if (!empty($request->file()['property'][$id]['file'])) {
                                continue;
                            }
                            if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                                continue;
                            }
                            $paramValue = $paramValue['path'];
                        }
                        if ($property->data_type->code == 'list') {
                            if (empty($paramValue)) {
                                $paramValue = [];
                            }
                            if (!is_array($paramValue)) {
                                $paramValue = [$paramValue];
                            }
                            $paramValue = json_encode($paramValue);
                        }
                        if ($property->data_type->code == 'color') {
                            if ($paramValue == 0) {
                                $paramValue = null;
                            }
                        }

                        $propertyValue = ShopItemPropertyValue::updateOrCreate(
                            ['item_id' => $shopItem->id, 'property_id' => $id],
                            [$property->data_type->code . '_value' => $paramValue]
                        );

                    }
                }
                if (!empty($request->file()['property'])) {
                    foreach($request->file()['property'] as $id => $paramValue) {
                        $property = ShopItemProperty::find($id);
                        if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                            continue;
                        }
                        $originalName = $paramValue[$property->data_type->code]->getClientOriginalName();
                        $file_path = $request->property[$id]['path'];
                        $file_path = $paramValue[$property->data_type->code]->store('shop/properties/item/' . $property->data_type->code . 's');
                        FileCatalog::set($file_path,$originalName);
                        $propertyValue = ShopItemPropertyValue::updateOrCreate(
                            ['item_id' => $shopItem->id, 'property_id' => $id],
                            [$property->data_type->code . '_value' => $file_path]
                        );
                    }
                }
                /* /Properties */

                return redirect(route('admin.shop.product.edit',$shopItem->product))->with('action_result',__('shop::default.item_edited_successfully'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopItem  $shopItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopItem $shopItem)
    {
        if (!$shopItem->delete()) {
            return redirect()->back()->withErrors(['action_result'=>__('shop::default.error_of_item_deleting')]);
        }

        return redirect()->back()->with('action_result',__('shop::default.item_deleted_successfully'));
    }
}
