@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.shop.discount') }}" class="button round-button theme-button"
            style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>

    <div class="item-form">
        <h2>{{ __('shop::default.create_discount') }}</h2>
        <form action="{{ route('admin.shop.discount.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="value">{{ __('elfcms::default.value') }} (%)</label>
                    <div class="input-wrapper">
                        <input type="number" name="value" id="value" min="1" max="99">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.shop.discount') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
