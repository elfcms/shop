@extends('shop::admin.layouts.shop')

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
        <h3>{{ __('basic::elf.edit_brand') }}{{ $brand->id }}</h3>
        <div class="date-info create-info">
            {{ __('basic::elf.created_at') }}: {{ $brand->created }}
        </div>
        <div class="date-info update-info">
            {{ __('basic::elf.updated_at') }}: {{ $brand->updated }}
        </div>
        <form action="{{ route('admin.shop.brand.update',$brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $brand->id }}">
                <div class="input-box colored">
                    <label for="name">{{ __('basic::elf.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $brand->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('basic::elf.slug') }}</label>
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
                    <label for="desctiption">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $brand->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="logo">{{ __('shop::elf.logo') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="logo_path" id="logo_path" value="{{$brand->logo}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($brand->logo)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($brand->logo))
                                <img src="{{ asset($brand->logo) }}" alt="logo">
                            @else
                                <img src="{{ asset('/vendor/elfcms/shop/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($brand->image))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="logo" id="logo">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('basic::elf.image') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="image_path" id="image_path" value="{{$brand->image}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($brand->image)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($brand->image))
                                <img src="{{ asset($brand->image) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/shop/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($brand->image))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="company">{{ __('shop::elf.company') }}</label>
                    <div class="input-wrapper">
                        <textarea name="company" id="company" cols="30" rows="3">{{ $brand->company }}</textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('basic::elf.submit') }}</button>
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
