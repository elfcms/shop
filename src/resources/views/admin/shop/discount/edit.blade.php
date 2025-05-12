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
        <h2>{{ __('shop::default.edit_discount') }} #{{ $discount->id }}</h2>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $discount->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $discount->updated }}
        </div>
        <form action="{{ route('admin.shop.discount.update', $discount) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $discount->id }}">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" value="{{ $discount->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="value">{{ __('elfcms::default.value') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="value" id="value" value="{{ $discount->value }}" min="1"
                            max="99">
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
