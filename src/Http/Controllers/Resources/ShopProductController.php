<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FileCatalog;
use Elfcms\Shop\Models\ShopBrand;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopColor;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopProductParameterValue;
use Elfcms\Shop\Models\ShopProductPropertyValue;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopUnit;
use Elfcms\Shop\Models\ShopVendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Str;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $productObject = new ShopProduct();
        $fillable = $productObject->getFillable();

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
            $products = ShopProduct::with('items')->get();
        }
        else {
            $products = ShopProduct::with('items.parameters')
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

        //dd($products);


        //$products = ShopProduct::all();
        return view('elfcms::admin.shop.product.index',[
            'page' => [
                'title' => __('shop::default.products'),
                'current' => url()->current(),
            ],
            'products' => $products,
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
        return view('elfcms::admin.shop.product.create',[
            'page' => [
                'title' => __('shop::default.create_product'),
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
            'slug' => 'required|unique:Elfcms\Shop\Models\ShopProduct,slug',
            'category_id' => 'required',
        ]);

        $path = '/shop/product/images';

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store($path);
        }

        //dd($request->unit_id);
        $validated['image'] = $image_path;
        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;
        //$validated['parent_id'] = $request->parent_id;
        $validated['unit_id'] = (int)$request->unit_id;
        $validated['brand_id'] = $request->brand_id;
        $validated['vendor_id'] = $request->vendor_id;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        //dd($validated);

        $product = ShopProduct::create($validated);

        if (!empty($request->category_id)) {
            $product->categories()->attach($request->category_id);
        }

        return redirect(route('admin.shop.product.edit',$product))->with('action_result',__('shop::default.product_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopProduct $shopProduct)
    {
        if ($request->ajax()) {
            return $shopProduct;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopProduct $shopProduct)
    {
        //dd($shopProduct->categories()->select('category_id')->get()->toArray());
        //dd([time(),(int)(microtime(true)*10000)]);
        $categories = ShopCategory::active()->get();
        $units = ShopUnit::all();
        $brands = ShopBrand::all();
        $vendors = ShopVendor::all();
        //$productCategories = $shopProduct->categories()->select('category_id')->get();
        $productCategories = [];
        foreach ($shopProduct->categories as $category) {
            $productCategories[] = $category->id;
        }

        $parameters = ShopItemParameter::all();
        $properties = ShopItemProperty::all();
        $colors = ShopColor::all();

        //dd($colors);
        /* $p = ShopItemParameter::find(9);
        dd($p->options); */
        foreach ($parameters as $parameter) {
            //if ($parameter->id == 9) dd($parameter->product_values($shopProduct->id));
            $parameter->value = $parameter->product_values($shopProduct->id);
            if ($parameter->data_type->code == 'color') {
                $parameter->colorData = ShopColor::find($parameter->value);
            }
            //if ($parameter->id == 9) dd($parameter->value);
        }
        foreach ($properties as $property) {
            //if ($parameter->id == 9) dd($parameter->product_values($shopProduct->id));
            $property->value = $property->product_values($shopProduct->id);
            if ($property->data_type->code == 'color') {
                //dd($property->value);
                $property->colorData = ShopColor::find($property->value);
            }
            //if ($parameter->id == 9) dd($parameter->value);
        }

        return view('elfcms::admin.shop.product.edit',[
            'page' => [
                'title' => __('shop::default.edit_product'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'units' => $units,
            'brands' => $brands,
            'vendors' => $vendors,
            'productCategories' => $productCategories,
            'product' => $shopProduct,
            'parameters' => $parameters,
            'properties' => $properties,
            'colors' => $colors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopProduct $shopProduct)
    {
        //dd($request->all());
        //dd($request->file()['parameter'][10]['file']->getClientOriginalName());
        if ($request->notedit && $request->notedit == 1) {
            $shopProduct->active = empty($request->active) ? 0 : 1;

            $shopProduct->save();

            return redirect(route('admin.shop.product'))->with('action_result',__('shop::default.product_edited_successfully'));
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
            if (ShopProduct::where('slug',$request->slug)->where('id','<>',$shopProduct->id)->first()) {
                return redirect(route('admin.shop.product.edit'))->withErrors([
                    'slug' => 'Product already exists'
                ]);
            }

            //$path = Setting::value('shop_image_path') ?? '/shop/item/image';
            $path = '/shop/product/images';

            $image_path = $request->image_path;
            if (!empty($request->file()['image'])) {
                $image_path = $request->file()['image']->store($path);
            }

            $shopProduct->title = $validated['title'];
            $shopProduct->slug = $validated['slug'];
            $shopProduct->image = $image_path;
            $shopProduct->unit_id = $request->unit_id;
            $shopProduct->brand_id = $request->brand_id;
            $shopProduct->vendor_id = $request->vendor_id;
            $shopProduct->description = $request->description;
            $shopProduct->active = empty($request->active) ? 0 : 1;
            $shopProduct->meta_keywords = $request->meta_keywords;
            $shopProduct->meta_description = $request->meta_description;

            $existCategories = $shopProduct->categories->toArray();

            $newCategories = $request->category_id ? $request->category_id : [];

            if (!empty($existCategories)) {
                foreach ($existCategories as $existCategory) {
                    if (!in_array($existCategory['id'],$newCategories)) {
                        $shopProduct->categories()->detach($existCategory['id']);
                    }
                    else {
                        $key = array_search($existCategory['id'],$newCategories);
                        unset($newCategories[$key]);
                    }
                }
            }
            if (!empty($newCategories)) {
                foreach ($newCategories as $categoryId) {
                    $shopProduct->categories()->attach($categoryId);
                }
            }

            $shopProduct->save();

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
                    $parameterValue = ShopProductParameterValue::updateOrCreate(
                        ['product_id' => $shopProduct->id, 'parameter_id' => $id],
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
                    $file_path = $paramValue[$parameter->data_type->code]->store('shop/parameters/product/' . $parameter->data_type->code . 's');
                    //$fileCatalog = new FileCatalog();
                    //dd($file_path);
                    FileCatalog::set($file_path,$originalName);
                    $parameterValue = ShopProductParameterValue::updateOrCreate(
                        ['product_id' => $shopProduct->id, 'parameter_id' => $id],
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

                    $propertyValue = ShopProductPropertyValue::updateOrCreate(
                        ['product_id' => $shopProduct->id, 'property_id' => $id],
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
                    $file_path = $paramValue[$property->data_type->code]->store('shop/properties/product/' . $property->data_type->code . 's');
                    FileCatalog::set($file_path,$originalName);
                    $propertyValue = ShopProductPropertyValue::updateOrCreate(
                        ['product_id' => $shopProduct->id, 'property_id' => $id],
                        [$property->data_type->code . '_value' => $file_path]
                    );
                }
            }
            /* /Properties */

            return redirect(route('admin.shop.product.edit',$shopProduct))->with('action_result',__('shop::default.product_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopProduct $shopProduct)
    {
        if (!$shopProduct->delete()) {
            return redirect(route('admin.shop.product'))->withErrors(['action_result'=>__('shop::default.error_of_product_deleting')]);
        }

        return redirect(route('admin.shop.product'))->with('action_result',__('shop::default.product_deleted_successfully'));
    }
}
