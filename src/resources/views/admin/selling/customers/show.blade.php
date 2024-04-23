@extends('elfcms::admin.layouts.shop')

@section('shop-content')

<div class="item-form">
    <h3>
        {{ $customer->firstname . ' ' . $customer->lastname }}
    </h3>
    <div class="date-info create-info">
        {{ __('elfcms::default.created_at') }}: {{ $customer->created }}
    </div>
    <div class="date-info update-info">
        {{ __('elfcms::default.updated_at') }}: {{ $customer->updated }}
    </div>
    <div class="colored-rows-box">
        <div class="input-box colored">
            <label>{{ __('elfcms::default.email') }}</label>
            <div class="input-wrapper">
                <a href="{{ route('admin.user.users.edit',$customer->user) }}">
                    {{ $customer->user->email }}
                </a>
            </div>
        </div>
        <div class="input-box colored">
            <label>{{ __('shop::default.birthday') }}</label>
            <div class="input-wrapper">
                {{ $customer->birthday }}
            </div>
        </div>
        <div class="input-box colored">
            <label>{{ __('elfcms::default.phone_number') }}</label>
            <div class="input-wrapper">
                {{ $customer->phone }}
            </div>
        </div>
        <hr>
        <h4>{{ __('elfcms::default.addresses') }}</h4>
        @foreach ($customer->addresses as $address)
        <div class="input-box colored">
            {{-- <label></label> --}}
            <div class="input-wrapper">
                {{ $address->country .', '. $address->plz .' '. $address->city .', '. ($address->address ?? ($address->street .' '. $address->house . (!empty($address->flat ? ', ' . $address->flat : ''))) . (!mepty($address->additional ? ', ' . $address->additional : ''))) }}
            </div>
        </div>
        @endforeach
        <hr>
        <h4>{{ __('shop::default.cart') }}</h4>
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
            @foreach ($customer->cart->items as $item)
            {{-- @dd($item) --}}
                <tr data-id="{{ $item->id }}">
                    <td>
                        {{ $item->id }}
                    </td>
                    <td>
                        {{ $item->product->title }}
                    </td>
                    <td>
                        {{ $item->additional_name ?? $item->alternate_name }}
                    </td>
                    <td class="right-text">
                        {{  number_format($item->net_price, 2, ',', '.') }}
                    </td>
                    <td class="right-text">
                        {{ number_format($item->discountSum, 2, ',', '.') }}
                    </td>
                    <td class="right-text">
                        {{ number_format($item->price, 2, ',', '.') }}
                    </td>
                    <td class="right-text">
                        {{ $item->pivot->quantity }}
                    </td>
                    <td class="right-text">
                        {{ number_format($item->net_price * $item->pivot->quantity, 2, ',', '.') }}
                    </td>
                    <td class="right-text">
                        {{ number_format($item->discountSum * $item->pivot->quantity, 2, ',', '.') }}
                    </td>
                    <td class="right-text">
                        {{ number_format($item->price * $item->pivot->quantity, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($customer->cart->items)))
            <div class="no-results-box">
                {{ __('elfcms::default.nothing_was_found') }}
            </div>
        @endif
        <hr>
        <h4>{{ __('shop::default.orders') }}</h4>
        <div class="widetable-wrapper">
            <table class="grid-table table-cols-8" style="--first-col:65px; --minw:800px;">
                <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            {{ __('shop::default._number') }}
                        </th>
                        <th>
                            {{ __('shop::default.payment_method') }}
                        </th>
                        <th>
                            {{ __('shop::default.delivery_service_service') }}
                        </th>
                        <th>
                            {{ __('shop::default.order_sum') }}
                        </th>
                        <th>
                            {{ __('shop::default.status') }}
                        </th>
                        <th>
                            {{ __('elfcms::default.created') }}
                        </th>
                        <th>
                            {{ __('elfcms::default.updated_at') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customer->orders as $order)
                    <tr data-id="{{ $order->id }}">
                        <td>
                            <a href="{{ route('admin.selling.orders.show',$order) }}">{{ $order->id }}</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.selling.orders.show',$order) }}">{{ $order->number }}</a>
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
                        <td>{{ $order->status->name }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->updated_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if (empty(count($customer->orders)))
                <div class="no-results-box">
                    {{ __('elfcms::default.nothing_was_found') }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
