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
        <h3>{{ __('elfcms::default.edit_brand') }}{{ $brand->id }}</h3>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $brand->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $brand->updated }}
        </div>
        <form action="{{ route('admin.shop.brand.update',$brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $brand->id }}">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $brand->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off" value="{{ $brand->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="name" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $brand->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="logo">{{ __('shop::default.logo') }}</label>
                    <div class="input-wrapper">
                        <x-elfcms-input-image code="logo" value="{{$brand->logo}}" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elfcms-input-image code="image" value="{{$brand->image}}" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="company">{{ __('shop::default.company') }}</label>
                    <div class="input-wrapper">
                        <textarea name="company" id="company" cols="30" rows="3">{{ $brand->company }}</textarea>
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
    runEditor('#description')
    </script>

@endsection
