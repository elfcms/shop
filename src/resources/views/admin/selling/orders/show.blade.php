@extends('elfcms::admin.layouts.main')

@section('pagecontent')
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
                    <form class="inline-form" action="{{ route('admin.selling.orders.update', $order) }}" method="POST"
                        enctype="multipart/form-data" name="orderform" id="orderform">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="change_status" value="1">
                        <input type="hidden" name="start_status" id="start_status" value="{{ $order->status->id }}">
                        <select name="status_id" id="status_id" class="form-control">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ $status->id == $order->order_status_id ? 'selected' : '' }}>{{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- <button type="submit" id="status_submit" class="default-btn submit-button"
                            disabled>{{ __('elfcms::default.submit') }}</button> --}}
                    </form>
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.customer') }}</label>
                <div class="input-wrapper">
                    <a href="{{ route('admin.selling.customers.show', $order->customer) }}">
                        {{ implode(' ', ['#' . $order->customer->id, $order->customer->firstname, $order->customer->lastname, '<' . $order->customer->user->email . '>']) }}
                    </a>
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.payment_method') }}</label>
                <div class="input-wrapper">
                    <a
                        href="{{ route('admin.shop.payment.edit', $order->payment_method) }}">{{ $order->payment_method->name }}</a>
                </div>
            </div>
            <div class="input-box colored">
                <label>{{ __('shop::default.delivery') }}</label>
                <div class="input-wrapper">
                    <a href="{{ route('admin.shop.delivery.edit', $order->delivery) }}">{{ $order->delivery->name }}</a>
                </div>
            </div>
            <div class="input-box colored">
                <label for="tracking_code">{{ __('shop::default.tracking_code') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="tracking_code" id="tracking_code" autocomplete="off"
                        value="{{ $order->tracking_code }}" data-value="{{ $order->tracking_code }}" form="orderform">
                </div>
            </div>
            <div class="input-box colored">
                <label for="tracking_link">{{ __('shop::default.tracking_link') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="tracking_link" id="tracking_link" autocomplete="off"
                        value="{{ $order->tracking_link }}" data-value="{{ $order->tracking_link }}" form="orderform">
                </div>
            </div>
            <div class="input-box colored">
                <div class="input-wrapper">
                    {{-- <form class="inline-form" action="{{ route('admin.selling.orders.update', $order) }}" method="POST"
                        enctype="multipart/form-data" name="trackingform" id="trackingform">
                        @csrf
                        @method('PUT') --}}
                    <button type="submit" id="tracking_submit" class="button color-text-button success-button" disabled
                        form="orderform">{{ __('elfcms::default.save') }}</button>
                    {{-- </form> --}}
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
        <div class="grid-table-wrapper">
            <table class="grid-table table-cols" style="--first-col:65px; --minw:800px; --cols-count:10;">
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
                                <a
                                    href="{{ route('admin.shop.product.edit', $item->product) }}">{{ $item->product->title }}</a>
                            </td>
                            <td>
                                <a href="{{ route('admin.shop.item.edit', $item->id) }}">{{ $item->name }}</a>
                            </td>
                            <td class="right-text">
                                {{ $item->net_price }}
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
                            {{ number_format($result->start_sum, 2, ',', '.') }}
                        </td>
                        <td class="right-text">
                            {{ number_format($result->discount_sum, 2, ',', '.') }}
                        </td>
                        <td class="right-text">
                            {{ number_format($result->result_sum, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            {{ __('shop::default.order_data') }}
                        </td>
                        <td @class([
                            'right-text',
                            'red-text' => $order->total_quantity != $result->quantity,
                        ])>
                            {{ $order->total_quantity }}
                        </td>
                        <td @class([
                            'right-text',
                            'red-text' => $order->start_sum != $result->start_sum,
                        ])>
                            {{ number_format($order->start_sum, 2, ',', '.') }}
                        </td>
                        <td @class([
                            'right-text',
                            'red-text' => $order->discount_sum != $result->discount_sum,
                        ])>
                            {{ number_format($order->discount_sum, 2, ',', '.') }}
                        </td>
                        <td @class([
                            'right-text',
                            'red-text' => $order->clear_sum != round($result->result_sum, 2),
                        ])>
                            {{ number_format($order->clear_sum, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            {{ __('shop::default.delivery') }}
                        </td>
                        <td class="right-text">
                            {{ number_format($order->delivery_sum, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="table-total-line">
                        <td colspan="9">
                            {{ __('shop::default.in_total') }}
                        </td>
                        <td class="right-text">
                            {{ number_format($order->result_sum, 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4>{{ __('shop::default.invoices') }}</h4>
        @if (empty($order->invoices))
            {{ __('elfcms::default.nothing_was_found') }}
        @else
            <div class="grid-table-wrapper">
                <table class="grid-table table-cols" style="--cols-count:5;">
                    <thead>
                        <tr>
                            <th>
                                {{ __('shop::default._number') }}
                            </th>
                            <th>
                                {{ __('elfcms::default.created_at') }}
                            </th>
                            <th>
                                {{ __('shop::default.status') }}
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->invoices as $invoice)
                            <tr>
                                <td>
                                    {{ $invoice->number }}
                                </td>
                                <td>
                                    {{ $invoice->created_at }}
                                </td>
                                <td>
                                    {{ $invoice->status->name }}
                                </td>
                                <td>
                                    @if ($invoice->status->code == 'N')
                                        <form action="{{ route('admin.selling.pay-invoice', $invoice) }}" method="post">
                                            @csrf
                                            @method('POST')
                                            <button type="submit"
                                                class="button color-text-button success-button">{{ __('shop::default.invoice_paid') }}</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->status->code == 'N')
                                        <form action="{{ route('admin.selling.cancel-invoice', $invoice) }}"
                                            method="post">
                                            @csrf
                                            @method('POST')
                                            <button type="submit"
                                                class="button color-text-button danger-button">{{ __('shop::default.cancel_invoice') }}</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if ($can)
            <div class="button-box single-box">
                <form action="{{ route('admin.selling.set-invoice', $order) }}" method="post">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        class="default-btn success-button">{{ __('shop::default.create_invoice') }}</button>
                </form>
            </div>
        @endif

    </div>
    <script>
        const statusSelect = document.querySelector('#status_id');
        /* const statusSubmit = document.querySelector('#status_submit'); */
        const startStatus = document.querySelector('#start_status');

        const trackingSubmit = document.querySelector('#tracking_submit');
        const trackingCode = document.querySelector('#tracking_code');
        const trackingLink = document.querySelector('#tracking_link');

        statusSelect.addEventListener('change', () => {
            if (!startStatus || !startStatus.value || startStatus.value != statusSelect.value) {
                trackingSubmit.disabled = false;
            } else {
                trackingSubmit.disabled = true;
            }
        });

        trackingCode.addEventListener('change', () => {
            if (trackingCode.value != trackingCode.dataset.value || trackingLink.value != trackingLink.dataset
                .value) {
                trackingSubmit.disabled = false;
            } else {
                trackingSubmit.disabled = true;
            }
        });

        trackingLink.addEventListener('change', () => {
            if (trackingLink.value != trackingLink.dataset.value || trackingLink.value != trackingLink.dataset
                .value) {
                trackingSubmit.disabled = false;
            } else {
                trackingSubmit.disabled = true;
            }
        });
    </script>
@endsection
