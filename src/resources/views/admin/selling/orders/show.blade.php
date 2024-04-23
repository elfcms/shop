@extends('elfcms::admin.layouts.shop')

@section('shop-content')

@if (Session::has('action_result'))
<div class="alert alert-success">{{ Session::get('action_result') }}</div>
@endif

<div class="item-form">
    <div class="date-info create-info">
        {{ __('elfcms::default.created_at') }}: {{ $order->created }}
    </div>
    <div class="date-info update-info">
        {{ __('elfcms::default.updated_at') }}: {{ $order->updated }}
    </div>
    {{-- <form action="{{ route('admin.shop.order.update',$order) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') --}}
        <div class="colored-rows-box">
            <input type="hidden" name="id" id="id" value="{{ $order->id }}">
            <div class="input-box colored">
                <label>{{ __('shop::default._number') }}</label>
                <div class="input-wrapper">
                    {{ $order->number }}
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.status') }}</label>
                <div class="input-wrapper">
                    <form class="inline-form" action="{{ route('admin.selling.orders.update',$order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="change_status" value="1">
                        <input type="hidden" name="start_status" id="start_status" value="{{ $order->status->id }}">
                        <select name="status_id" id="status_id" class="form-control">
                            @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ $status->id == $order->order_status_id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" id="status_submit" class="default-btn submit-button" disabled>{{ __('elfcms::default.submit') }}</button>
                    </form>
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.customer') }}</label>
                <div class="input-wrapper">
                    <a href="{{ route('admin.selling.customers.show',$order->customer) }}">
                        {{ implode(' ',['#'.$order->customer->id,$order->customer->firstname,$order->customer->lastname,'<'.$order->customer->user->email.'>']) }}
                    </a>
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.payment_method') }}</label>
                <div class="input-wrapper">
                    <a href="{{ route('admin.shop.payment.edit',$order->payment_method) }}">{{ $order->payment_method->name }}</a>
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.delivery') }}</label>
                <div class="input-wrapper">
                    <a href="{{ route('admin.shop.delivery.edit',$order->delivery) }}">{{ $order->delivery->name }}</a>
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="code">{{ __('elfcms::default.code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="code" id="code" autocomplete="off" value="{{ $order->code }}">
                </div>
                <div class="input-wrapper">
                    <div class="autoslug-wrapper">
                        <input type="checkbox" data-text-id="name" data-slug-id="code" class="autoslug" checked>
                        <div class="autoslug-button"></div>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="icon">{{ __('shop::default.icon') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="icon" value="{{$order->icon}}" />
                </div>
            </div>
            <div class="input-box colored">
                <label for="price">{{ __('shop::default.price') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="price" id="price" min="0" value="{{$order->price}}">
                </div>
            </div> --}}
        </div>
        {{-- <div class="button-box single-box">
            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
        </div> --}}
    {{-- </form> --}}
    <table class="grid-table table-cols-10" style="--first-col:65px; --minw:800px;">
        <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    {{ __('shop::default.product') }}
                </th>
                <th>
                    {{ __('shop::default.item') }}
                </th>
                <th>
                    {{ __('shop::default.price') }}
                </th>
                <th>
                    {{ __('shop::default.discount_price') }}
                </th>
                <th>
                    {{ __('shop::default.price_with_discont') }}
                </th>
                <th>
                    {{ __('shop::default.quantity') }}
                </th>
                <th>
                    {{ __('shop::default.start_sum') }}
                </th>
                <th>
                    {{ __('shop::default.discount_sum') }}
                </th>
                <th>
                    {{ __('shop::default.result_sum') }}
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr data-id="{{ $item->id }}">
                <td>
                    {{ $item->id }}
                </td>
                <td>
                    <a href="{{ route('admin.shop.product.edit', $item->product) }}">{{ $item->product->title }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.shop.item.edit',$item->id) }}">{{ $item->name }}</a>
                </td>
                <td class="right-text">
                    {{  $item->net_price }}
                </td>
                <td class="right-text">
                    {{ $item->discount_price }}
                </td>
                <td class="right-text">
                    {{ $item->price }}
                </td>
                <td class="right-text">
                    {{ $item->quantity }}
                </td>
                <td class="right-text">
                    {{ $item->start_sum }}
                </td>
                <td class="right-text">
                    {{ $item->discount_sum }}
                </td>
                <td class="right-text">
                    {{ $item->result_sum }}
                </td>
            </tr>
        @endforeach
        <tr class="table-total-line">
            <td colspan="6">
                {{ __('shop::default.total') }}
            </td>
            <td class="right-text">
                {{ $result->quantity }}
            </td>
            <td class="right-text">
                {{ number_format($result->start_sum, 2, ',' ,'.') }}
            </td>
            <td class="right-text">
                {{ number_format($result->discount_sum, 2, ',' ,'.') }}
            </td>
            <td class="right-text">
                {{ number_format($result->result_sum, 2, ',' ,'.') }}
            </td>
        </tr>
        <tr>
            <td colspan="6">
                {{ __('shop::default.order_data') }}
            </td>
            <td @class(['right-text','red-text' => $order->total_quantity != $result->quantity])>
                {{ $order->total_quantity }}
            </td>
            <td @class(['right-text','red-text' => $order->start_sum != $result->start_sum])>
                {{ number_format($order->start_sum, 2, ',' ,'.') }}
            </td>
            <td @class(['right-text','red-text' => $order->discount_sum != $result->discount_sum])>
                {{ number_format($order->discount_sum, 2, ',' ,'.') }}
            </td>
            <td @class(['right-text','red-text' => $order->clear_sum != round($result->result_sum,2)])>
                {{ number_format($order->clear_sum, 2, ',' ,'.') }}
            </td>
        </tr>
        <tr>
            <td colspan="9">
                {{ __('shop::default.delivery') }}
            </td>
            <td class="right-text">
                {{ number_format($order->delivery_sum, 2, ',' ,'.') }}
            </td>
        </tr>
        <tr class="table-total-line">
            <td colspan="9">
                {{ __('shop::default.in_total') }}
            </td>
            <td class="right-text">
                {{ number_format($order->result_sum, 2, ',' ,'.') }}
            </td>
        </tr>
        </tbody>
    </table>

</div>
<script>
const statusSelect = document.querySelector('#status_id');
const statusSubmit = document.querySelector('#status_submit');
const startStatus = document.querySelector('#start_status');

statusSelect.addEventListener('change', () => {
    console.log(startStatus, startStatus.value, statusSelect.value)
    if (!startStatus || !startStatus.value || startStatus.value != statusSelect.value ) {
        statusSubmit.disabled = false;
    }
    else {
        statusSubmit.disabled = true;
    }
});
</script>
@endsection
