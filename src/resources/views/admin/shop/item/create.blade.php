@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ !empty($product) ? route('admin.shop.product.edit',$product) : route('admin.shop.product') }}" class="button round-button theme-button" style="color:var(--default-color);">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.back') }}
        </span>
    </a>
</div>

    <div class="item-form">
        <h2>{{ __('shop::default.create_item') }}</h2>
        <form action="{{ route('admin.shop.item.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="true" />
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
                {{-- <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        {{-- <input type="hidden" name="image_path" id="image_path">
                        <div class="image-button">
                            <div class="delete-image hidden">&#215;</div>
                            <div class="image-button-img">
                                <img src="{{ asset('/elfcms/admin/modules/shop//images/icons/upload.png') }}" alt="Upload file">
                            </div>
                            <div class="image-button-text">
                                {{ __('elfcms::default.choose_file') }}
                            </div>
                            <input type="file" name="image" id="image">
                        </div> --
                        <x-elfcms-input-image code="image" />
                    </div>
                </div> --}}
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
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ !empty($product) ? route('admin.shop.product.edit',$product) : route('admin.shop.product') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
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
