<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopGoods;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemProperty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
            //->toSql();

        //dd($goodsCollection);


        //$goodsCollection = ShopGoods::all();
        return view('shop::admin.shop.goods.index',[
            'page' => [
                'title' => __('shop::elf.goods'),
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function show(ShopGoods $shopGoods)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopGoods $shopGoods)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopGoods  $shopGoods
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopGoods $shopGoods)
    {
        //
    }
}
