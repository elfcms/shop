<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCurrency;
use Elfcms\Shop\Models\ShopCustomer;
use Illuminate\Http\Request;

class ShopCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
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
        if ($order == 'email') {
            $customers = ShopCustomer::query()
                ->join('users', function($j){
                    $j->on('users.id', '=', 'shop_customers.user_id');
                })
                ->orderBy('users.email',$trend);
            if (!empty ($search)) {
                $customers = $customers->where('firstname','like',"%{$search}%")->orWhere('lastname','like',"%{$search}%");
            }
            $customers = $customers->paginate($count);
        }
        elseif (!empty ($search)) {
            $customers = ShopCustomer::withCount('orders')->where('firstname','like',"%{$search}%")->orWhere('lastname','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        else {
            $customers = ShopCustomer::withCount('orders')->orderBy($order, $trend)->paginate($count);
        }
        return view('elfcms::admin.selling.customers.index',[
            'page' => [
                'title' => __('shop::default.customers'),
                'current' => url()->current(),
            ],
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopCustomer $customer)
    {
        $customer->created = '';
        $customer->updated = '';
        if (!empty($customer->created_at)) {
            $customer->created = date('d.m.Y H:i:s',strtotime($customer->created_at));
        }
        if (!empty($customer->updated_at)) {
            $customer->updated = date('d.m.Y H:i:s',strtotime($customer->updated_at));
        }
        if (!empty($customer->birthday)) {
            $customer->birthday = date('d.m.Y',strtotime($customer->birthday));
        }
        $currency = ShopCurrency::where('default',1)->first();
        if (!$currency) $currency = ShopCurrency::first();
        $currencySymbol = $currency->symbol ?? '€';
        return view('elfcms::admin.selling.customers.show',[
            'page' => [
                'title' => __('shop::default.customer'),
                'current' => url()->current(),
            ],
            'customer' => $customer,
            'currencySymbol' => $currencySymbol,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopCustomer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopCustomer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopCustomer $customer)
    {
        //
    }
}
