<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Basic\Models\FileCatalog;
use Elfcms\Shop\Models\ShopBrand;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopColor;
use Elfcms\Shop\Models\ShopGoods;
use Elfcms\Shop\Models\ShopGoodsParameterValue;
use Elfcms\Shop\Models\ShopGoodsPropertyValue;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopUnit;
use Elfcms\Shop\Models\ShopVendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Str;

class ShopGoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $goodsObject = new ShopGoods();
        $fillable = $goodsObject->getFillable();

        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order) && in_array($request->order, $fillable)) {
            $order = $request->order;
        }
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }
        $search = $request->search ?? '';

        /* $params = [
            'param-2' => [5,2],
            8 => 1,
            1 => ['asd']
        ]; */
        //dd($request->params);
        $params = [];
        if ($request->params) {
            $params = $request->params;
        }
        $props = [];
        if ($request->props) {
            $props = $request->props;
        }
        if (empty($search) && empty($props) && empty($params)) {
            $goodsCollection = ShopGoods::with('items')->get();
        }
        else {
            $goodsCollection = ShopGoods::with('items.parameters')
            ->with('items.properties')
            ->with('parameters')
            ->with('properties')
            ->where(function(Builder $query) use ($search){
                if (!empty($search)) {
                    $query->where('title','like',"%{$search}%");
                }
            })
            ->where(function(Builder $query) use ($params,$props){
                $query->whereHas('parameters',function(Builder $query) use ($params) {
                    $query->where(function(Builder $query) use ($params){
                        foreach ($params as $pid => $pval) {
                            $paramField = 'id';
                            if (!is_numeric($pid)) {
                                $paramField = 'code';
                            }
                            if (!is_array($pval)) {
                                $pval = [$pval];
                            }
                            $parameterData = ShopItemParameter::with('data_type')->where($paramField,$pid)->first();
                            if (!$parameterData) {
                                continue;
                            }
                            $pid = $parameterData->id;
                            $paramType = $parameterData->data_type->code;
                            $query->orWhere(function(Builder $query) use ($pid, $pval, $paramType){
                                $query->where('parameter_id',$pid)->whereIn($paramType.'_value',$pval);
                            });
                        }
                    });
                })
                ->orWhereHas('items',function(Builder $query) use ($params){
                    $query->whereHas('parameters',function(Builder $query) use ($params) {
                        $query->where(function(Builder $query) use ($params){
                            foreach ($params as $pid => $pval) {
                                $paramField = 'id';
                                if (!is_numeric($pid)) {
                                    $paramField = 'code';
                                }
                                if (!is_array($pval)) {
                                    $pval = [$pval];
                                }
                                $parameterData = ShopItemParameter::with('data_type')->where($paramField,$pid)->first();
                                if (!$parameterData) {
                                    continue;
                                }
                                $pid = $parameterData->id;
                                $paramType = $parameterData->data_type->code;
                                $query->orWhere(function(Builder $query) use ($pid, $pval, $paramType){
                                    $query->where('parameter_id',$pid)->whereIn($paramType.'_value',$pval);
                                });
                            }
                        });
                    });
                })
                ->orWhereHas('properties',function(Builder $query) use ($props) {
                    $query->where(function(Builder $query) use ($props){
                        foreach ($props as $pid => $pval) {
                            $paramField = 'id';
                            if (!is_numeric($pid)) {
                                $paramField = 'code';
                            }
                            if (!is_array($pval)) {
                                $pval = [$pval];
                            }
                            $parameterData = ShopItemProperty::with('data_type')->where($paramField,$pid)->first();
                            if (!$parameterData) {
                                continue;
                            }
                            $pid = $parameterData->id;
                            $paramType = $parameterData->data_type->code;
                            $query->orWhere(function(Builder $query) use ($pid, $pval, $paramType){
                                $query->where('property_id',$pid)->whereIn($paramType.'_value',$pval);
                            });
                        }
                    });
                })
                ->orWhereHas('items',function(Builder $query) use ($props){
                    $query->whereHas('properties',function(Builder $query) use ($props) {
                        $query->where(function(Builder $query) use ($props){
                            foreach ($props as $pid => $pval) {
                                $paramField = 'id';
                                if (!is_numeric($pid)) {
                                    $paramField = 'code';
                                }
                                if (!is_array($pval)) {
                                    $pval = [$pval];
                                }
                                $parameterData = ShopItemProperty::with('data_type')->where($paramField,$pid)->first();
                                if (!$parameterData) {
                                    continue;
                                }
                                $pid = $parameterData->id;
                                $paramType = $parameterData->data_type->code;
                                $query->orWhere(function(Builder $query) use ($pid, $pval, $paramType){
                                    $query->where('property_id',$pid)->whereIn($paramType.'_value',$pval);
                                });
                            }
                        });
                    });
                });
            })
            ->orderBy($order,$trend)
            ->get();
        }


            //->toSql();

        //dd($goodsCollection);


        //$goodsCollection = ShopGoods::all();
        return view('shop::admin.shop.goods.index',[
            'page' => [
                'title' => __('shop::elf.goods_pl'),
                'current' => url()->current(),
            ],
            'goodsCollection' => $goodsCollection,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ShopCategory::active()->get();
        $units = ShopUnit::all();
        $brands = ShopBrand::all();
        $vendors = ShopVendor::all();
        //dd($vendors);
        return view('shop::admin.shop.goods.create',[
            'page' => [
                'title' => __('shop::elf.create_goods'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'units' => $units,
            'brands' => $brands,
            'vendors' => $vendors,
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
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:Elfcms\Shop\Models\ShopGoods,slug',
            'category_id' => 'required',
        ]);

        //dd($request->unit_id);
        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;
        //$validated['parent_id'] = $request->parent_id;
        $validated['unit_id'] = (int)$request->unit_id;
        $validated['brand_id'] = $request->brand_id;
        $validated['vendor_id'] = $request->vendor_id;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        //dd($validated);

        $goods = ShopGoods::create($validated);

        if (!empty($request->category_id)) {
            $goods->categories()->attach($request->category_id);
        }

        return redirect(route('admin.shop.goods.edit',$goods))->with('action_result',__('shop::elf.goods_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopGoods $shopGoods)
    {
        if ($request->ajax()) {
            return $shopGoods;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopGoods $shopGoods)
    {
        //dd($shopGoods->categories()->select('category_id')->get()->toArray());
        //dd([time(),(int)(microtime(true)*10000)]);
        $categories = ShopCategory::active()->get();
        $units = ShopUnit::all();
        $brands = ShopBrand::all();
        $vendors = ShopVendor::all();
        //$goodsCategories = $shopGoods->categories()->select('category_id')->get();
        $goodsCategories = [];
        foreach ($shopGoods->categories as $category) {
            $goodsCategories[] = $category->id;
        }

        $parameters = ShopItemParameter::all();
        $properties = ShopItemProperty::all();
        $colors = ShopColor::all();

        //dd($colors);
        /* $p = ShopItemParameter::find(9);
        dd($p->options); */
        foreach ($parameters as $parameter) {
            //if ($parameter->id == 9) dd($parameter->goods_values($shopGoods->id));
            $parameter->value = $parameter->goods_values($shopGoods->id);
            if ($parameter->data_type->code == 'color') {
                $parameter->colorData = ShopColor::find($parameter->value);
            }
            //if ($parameter->id == 9) dd($parameter->value);
        }
        foreach ($properties as $property) {
            //if ($parameter->id == 9) dd($parameter->goods_values($shopGoods->id));
            $property->value = $property->goods_values($shopGoods->id);
            if ($property->data_type->code == 'color') {
                //dd($property->value);
                $property->colorData = ShopColor::find($property->value);
            }
            //if ($parameter->id == 9) dd($parameter->value);
        }

        return view('shop::admin.shop.goods.edit',[
            'page' => [
                'title' => __('shop::elf.edit_goods'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'units' => $units,
            'brands' => $brands,
            'vendors' => $vendors,
            'goodsCategories' => $goodsCategories,
            'goods' => $shopGoods,
            'parameters' => $parameters,
            'properties' => $properties,
            'colors' => $colors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopGoods $shopGoods)
    {
        //dd($request->all());
        //dd($request->file()['parameter'][10]['file']->getClientOriginalName());
        if ($request->notedit && $request->notedit == 1) {
            $shopGoods->active = empty($request->active) ? 0 : 1;

            $shopGoods->save();

            return redirect(route('admin.shop.goods'))->with('action_result',__('shop::elf.goods_edited_successfully'));
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
            $validated = $request->validate([
                'title' => 'required',
                'slug' => 'required',
                'category_id' => 'required',
            ]);
            if (ShopGoods::where('slug',$request->slug)->where('id','<>',$shopGoods->id)->first()) {
                return redirect(route('admin.shop.goods.edit'))->withErrors([
                    'slug' => 'Goods already exists'
                ]);
            }

            $shopGoods->title = $validated['title'];
            $shopGoods->slug = $validated['slug'];
            $shopGoods->unit_id = $request->unit_id;
            $shopGoods->brand_id = $request->brand_id;
            $shopGoods->vendor_id = $request->vendor_id;
            $shopGoods->description = $request->description;
            $shopGoods->active = empty($request->active) ? 0 : 1;
            $shopGoods->meta_keywords = $request->meta_keywords;
            $shopGoods->meta_description = $request->meta_description;

            $existCategories = $shopGoods->categories->toArray();

            $newCategories = $request->category_id ? $request->category_id : [];

            if (!empty($existCategories)) {
                foreach ($existCategories as $existCategory) {
                    if (!in_array($existCategory['id'],$newCategories)) {
                        $shopGoods->categories()->detach($existCategory['id']);
                    }
                    else {
                        $key = array_search($existCategory['id'],$newCategories);
                        unset($newCategories[$key]);
                    }
                }
            }
            if (!empty($newCategories)) {
                foreach ($newCategories as $categoryId) {
                    $shopGoods->categories()->attach($categoryId);
                }
            }

            $shopGoods->save();

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
                    //if ($parameter->data_type->code == 'file') {
                        //dd($request->file()['parameter']);
                        /* $image_path = $request->image_path;
                        if (!empty($request->file()['image'])) {
                            $image = $request->file()['image']->store('public/shop/categories/image');
                            $image_path = str_ireplace('public/','/storage/',$image);
                        } */
                    //}
                    /* $parameterValue = ShopGoodsParameterValue::where('goods_id',$shopGoods->id)->where('parameter_id',$id)->first();
                    if ($parameterValue) {
                        $parameterValue[$parameter->data_type->code . '_value'] = $paramValue;
                        $parameterValue->save();
                    } */
                    $parameterValue = ShopGoodsParameterValue::updateOrCreate(
                        ['goods_id' => $shopGoods->id, 'parameter_id' => $id],
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
                    $file = $paramValue[$parameter->data_type->code]->store('public/shop/parameters/goods/' . $parameter->data_type->code . 's');
                    $file_path = str_ireplace('public/','/storage/',$file);
                    //$fileCatalog = new FileCatalog();
                    //dd($file_path);
                    FileCatalog::set($file_path,$originalName);
                    $parameterValue = ShopGoodsParameterValue::updateOrCreate(
                        ['goods_id' => $shopGoods->id, 'parameter_id' => $id],
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

                    $propertyValue = ShopGoodsPropertyValue::updateOrCreate(
                        ['goods_id' => $shopGoods->id, 'property_id' => $id],
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
                    $file = $paramValue[$property->data_type->code]->store('public/shop/properties/goods/' . $property->data_type->code . 's');
                    $file_path = str_ireplace('public/','/storage/',$file);
                    FileCatalog::set($file_path,$originalName);
                    $propertyValue = ShopGoodsPropertyValue::updateOrCreate(
                        ['goods_id' => $shopGoods->id, 'property_id' => $id],
                        [$property->data_type->code . '_value' => $file_path]
                    );
                }
            }
            /* /Properties */

            return redirect(route('admin.shop.goods.edit',$shopGoods))->with('action_result',__('shop::elf.goods_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopGoods $shopGoods)
    {
        if (!$shopGoods->delete()) {
            return redirect(route('admin.shop.goods'))->withErrors(['action_result'=>__('shop::elf.error_of_goods_deleting')]);
        }

        return redirect(route('admin.shop.goods'))->with('action_result',__('shop::elf.goods_deleted_successfully'));
    }
}
