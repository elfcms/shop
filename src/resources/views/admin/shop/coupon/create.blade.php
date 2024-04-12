@extends('elfcms::admin.layouts.shop')

@section('shop-content')

    @if (Session::has('action_result'))
        <div class="alert alert-success">{{ Session::get('action_result') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="item-form">
        <h3>{{ __('shop::default.create_coupon') }}</h3>
        <form action="{{ route('admin.shop.coupon.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" readonly value="{{$code}}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="currency_id">{{ __('shop::default.currency') }}</label>
                    <div class="input-wrapper">
                        <select name="currency_id" id="currency_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <x-elfcms-input-checkbox code="combine" label="{{ __('shop::default.combine_coupon') }}" style="blue" />
                </div>
                <div class="input-box colored">
                    <x-elfcms-input-checkbox code="is_percent" label="{{ __('shop::default.is_percent_coupon') }}" style="blue" />
                </div>
                <div class="input-box colored">
                    <label for="value">{{ __('elfcms::default.value') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="value" id="value" min="0">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="min_price">{{ __('shop::default.min_price') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="min_price" id="min_price" min="0">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="max_price">{{ __('shop::default.max_price') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="max_price" id="max_price" min="0">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="min_value">{{ __('shop::default.min_value') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="min_value" id="min_value" min="0">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="max_value">{{ __('shop::default.max_value') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="max_value" id="max_value" min="0">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="uses_number">{{ __('shop::default.uses_number') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="uses_number" id="uses_number" min="1" value="1">
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="valid_from">{{ __('shop::default.valid_from') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="valid_from[]" id="public_time"{{--  autocomplete="off" value="{{ $item->valid_from }}" --}}>
                        <input type="time" name="valid_from[]" id="valid_from_time"{{--  autocomplete="off" value="{{ $item->valid_from_time }}" --}}>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="valid_until">{{ __('shop::default.valid_until') }}</label>
                    <div class="input-wrapper">
                        <input type="date" name="valid_until[]" id="valid_until"{{--  autocomplete="off" value="{{ $item->valid_until }}" --}}>
                        <input type="time" name="valid_until[]" id="valid_until_time"{{--  autocomplete="off" value="{{ $item->valid_until_time }}" --}}>
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="customer_id">{{ __('shop::default.customer') }}</label>
                    <div class="input-wrapper">
                        <select name="customer_id" id="customer_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->firstname .' '. $customer->lastname }} [{{ $customer->user?->email }}]</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    const logoInput = document.querySelector('#logo')
    if (logoInput) {
        inputFileImg(logoInput)
    }
    autoSlug('.autoslug')
    //add editor
    //runEditor('#description')
    </script>

@endsection
