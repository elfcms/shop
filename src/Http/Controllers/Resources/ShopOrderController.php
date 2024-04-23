<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopOrder;
use Elfcms\Shop\Models\ShopOrderStatus;
use Illuminate\Http\Request;

class ShopOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trend = 'asc';
        $order = 'id';
        $search = $request->search ?? '';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }
        if (!empty($search)) {
            $orders = ShopOrder::where('firstname', 'like', "%{$search}%")->orWhere('lastname', 'like', "%{$search}%")->orderBy($order, $trend)->paginate($count);
        } else {
            $orders = ShopOrder::orderBy($order, $trend)->paginate($count);
        }
        return view('elfcms::admin.selling.orders.index', [
            'page' => [
                'title' => __('shop::default.orders'),
                'current' => url()->current(),
            ],
            'orders' => $orders,
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
     * @param  \App\Models\ShopOrder  $order
     * @return \Illuminate\Http\Response
     */
    public function show(ShopOrder $order)
    {
        $order->created = '';
        $order->updated = '';
        if (!empty($order->created_at)) {
            $order->created = date('d.m.Y H:i:s',strtotime($order->created_at));
        }
        if (!empty($order->updated_at)) {
            $order->updated = date('d.m.Y H:i:s',strtotime($order->updated_at));
        }
        //dd($order->items[0]->prices);
        $items = [];
        $result = [
            'start_sum' => 0,
            'discount_sum' => 0,
            'result_sum' => 0,
            'quantity' => 0,
        ];
        foreach ($order->items as $item) {
            $items[] = (object)[
                'id' => $item->id,
                'product' => $item->product,
                'name' => $item->additional_name ?? $item->alternate_name,
                'net_price' => number_format($item->prices->net_price / 100, 2, ',' ,'.'),
                'discount_price' => number_format($item->prices->discount_price / 100, 2, ',' ,'.'),
                'price' => number_format($item->prices->price / 100, 2, ',' ,'.'),
                'start_sum' => number_format($item->prices->start_sum / 100, 2, ',' ,'.'),
                'discount_sum' => number_format($item->prices->discount_sum / 100, 2, ',' ,'.'),
                'result_sum' => number_format($item->prices->result_sum / 100, 2, ',' ,'.'),
                'quantity' => $item->prices->quantity,
            ];
            $result['start_sum'] += $item->prices->start_sum / 100;
            $result['discount_sum'] += $item->prices->discount_sum / 100;
            $result['result_sum'] += $item->prices->result_sum / 100;
            $result['quantity'] += $item->prices->quantity;
        }
        $statuses = ShopOrderStatus::all();
        return view('elfcms::admin.selling.orders.show', [
            'page' => [
                'title' => __('shop::default.order') .' '. $order->number,
                'current' => url()->current(),
            ],
            'order' => $order,
            'items' => $items,
            'result' => (object)$result,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopOrder  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopOrder $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopOrder  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopOrder $order)
    {
        if (!empty($request->change_status)) {
            $order->order_status_id = $request->status_id;
            $order->save();
        }
        return redirect(route('admin.selling.orders.show',$order))->with('action_result',__('shop::default.vendor_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopOrder  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopOrder $order)
    {
        //
    }
}
