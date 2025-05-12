@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <form action="{{ route('admin.selling.orders') }}" method="get">
        <div class="round-input-wrapper">
            <button type="submit" class="button round-button theme-button inner-button default-highlight-button">
                {!! iconHtmlLocal('elfcms/admin/images/icons/search.svg', width: 18, height: 18, svg: true) !!}
            </button>
            <input type="search" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
        </div>
    </form>
    @if (!empty($search))
        <div class="table-search-result-title">
            {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a
                href="{{ route('admin.selling.orders') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
        </div>
    @endif
</div>

<div class="grid-table-wrapper">
    <table class="grid-table table-cols" style="--first-col:4rem; --minw:50rem; --cols-count:9;">
        <thead>
            <tr>
                <th>
                    ID
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default._number') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'number','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['number'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default.customers') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'customer_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['customer_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default.payment_method') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'payment_method_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['payment_method_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default.delivery_service_service') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'delivery_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['delivery_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default.order_sum') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'result_sum','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['result_sum'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default.status') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'order_status_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['order_status_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.created') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.updated_at') }}
                    <a href="{{ route('admin.selling.orders',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                {{-- <th></th> --}}
            </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr data-id="{{ $order->id }}">
                <td>
                    <a href="{{ route('admin.selling.orders.show',$order) }}">{{ $order->id }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.selling.orders.show',$order) }}">{{ $order->number }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.selling.customers.show',$order->customer) }}" title="{{ __('shop::default.customer') . ' #' . $order->customer->id }}">
                        {{ implode(' ',['#'.$order->customer->id,$order->customer->firstname,$order->customer->lastname]) }}
                    </a>
                </td>
                <td>
                    {{ $order->payment_method->name }}
                </td>
                <td>
                    {{ $order->delivery->name }}
                </td>
                <td>
                    {{ number_format($order->result_sum, 2, ',', '.') }}
                </td>
                {{-- <td>
                    <a href="{{ route('admin.selling.carts.show',$order->cart) }}" title="{{ __('shop::default.cart_of_user') }}">
                        {{ $order->cart->items->count() }}
                    </a>
                </td> --}}
                <td>{{ $order->status->name }}</td>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->updated_at }}</td>
                {{-- <td class="table-button-column">
                    <a href="{{ route('admin.selling.orders.show',$order) }}" class="default-btn edit-button" title="{{ __('elfcms::default.show') }}"></a>
                </td> --}}
            </tr>
        @endforeach
        </tbody>
    </table>
    @if (empty(count($orders)))
        <div class="no-results-box">
            {{ __('elfcms::default.nothing_was_found') }}
        </div>
    @endif
</div>
{{$orders->links('elfcms::admin.layouts.pagination')}}


@endsection
