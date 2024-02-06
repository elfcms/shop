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
        <h3>{{ __('shop::default.create_item') }}</h3>
        <form action="{{ route('admin.shop.item.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    {{-- <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input
                                type="checkbox"
                                name="active"
                                id="active"
                                checked
                            >
                            <i></i>
                        </div>
                        <label for="active">
                            {{ __('elfcms::default.active') }}
                        </label>
                    </div> --}}
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" style="blue" :checked="1" />
                </div>
                <div class="input-box colored">
                    <label for="product_id">{{ __('shop::default.product') }}</label>
                    <div class="input-wrapper">
                        <select name="product_id" id="product_id" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if(!empty($product_id) && $product_id == $product->id) selected @endif>{{ $product->title }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="item_number">{{ __('shop::default.item_number') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="item_number" id="item_number" required>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="vendor_item_number">{{ __('shop::default.vendor_item_number') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="vendor_item_number" id="vendor_item_number">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="additional_name">{{ __('shop::default.additional_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="additional_name" id="additional_name">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="alternate_name">{{ __('shop::default.alternate_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="alternate_name" id="alternate_name">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="note">{{ __('shop::default.note') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="note" id="note">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="balance">{{ __('shop::default.balance') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="balance" id="balance">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        {{-- <input type="hidden" name="image_path" id="image_path">
                        <div class="image-button">
                            <div class="delete-image hidden">&#215;</div>
                            <div class="image-button-img">
                                <img src="{{ asset('/vendor/elfcms/shop/admin/images/icons/upload.png') }}" alt="Upload file">
                            </div>
                            <div class="image-button-text">
                                {{ __('elfcms::default.choose_file') }}
                            </div>
                            <input type="file" name="image" id="image">
                        </div> --}}
                        <x-elfcms-input-image code="image" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="vendor_id">{{ __('shop::default.vendor') }}</label>
                    <div class="input-wrapper">
                        <select name="vendor_id" id="vendor_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3"></textarea>
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
    //autoSlug('.autoslug')
    //add editor
    runEditor('#description')
    </script>

@endsection
