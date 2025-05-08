@extends('elfcms::admin.layouts.main')

@section('pagecontent')

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
        <h3>{{ __('shop::default.edit_discount') }} #{{ $discount->id }}</h3>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $discount->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $discount->updated }}
        </div>
        <form action="{{ route('admin.shop.discount.update',$discount) }}" method="POST" enctype="multipart/form-data">
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
                        <input type="number" name="value" id="value" value="{{ $discount->value }}" min="1" max="99">
                    </div>
                </div>
            </div>
            {{-- <div class="colored-rows-box">
                <h4>{{ __('shop::default.set_discount_for_all_products_in_category') }}</h4>
            @foreach ($categories as $category)
                <div class="input-box colored" style="margin-left: {{ 10*(int)$category->level }}px;">
                    <x-elfcms-input-checkbox code="categories[{{$category->id}}]" label="{{ $category->title }}" style="blue" />
                </div>
            @endforeach
            </div> --}}
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>

@endsection
