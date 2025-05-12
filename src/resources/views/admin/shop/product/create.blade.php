@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.shop.product') }}" class="button round-button theme-button" style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>

    <div class="item-form">
        <h2>{{ __('shop::default.create_product') }}</h2>
        <form action="{{ route('admin.shop.product.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="category_id">{{ __('shop::default.categories') }}</label>
                    <div class="input-wrapper">
                        {{-- <select name="category_id[]" id="category_id" multiple required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select> --}}
                        <div class="choosing-box">
                            @foreach ($categories as $category)
                                <div class="choosing-line">
                                    <x-elfcms::ui.checkbox.classic name="category_id[]" id="category_{{ $category->id }}"
                                        value="{{ $category->id }}" color="var(--default-color)" />
                                    <label for="category_{{ $category->id }}">{{ $category->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" required>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" required>
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <x-elfcms::ui.checkbox.autoslug textid="title" slugid="slug" checked="true" />
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="" :params="['name' => 'image']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="unit_id">{{ __('shop::default.unit_of_measurement') }}</label>
                    <div class="input-wrapper">
                        <select name="unit_id" id="unit_id">
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="brand_id">{{ __('shop::default.brand') }}</label>
                    <div class="input-wrapper">
                        <select name="brand_id" id="brand_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
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
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.shop.product') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        const imageInput = document.querySelector('#image')
        if (imageInput) {
            inputFileImg(imageInput)
        }
        autoSlug('.autoslug')
        //add editor
        runEditor('#description')
    </script>
@endsection
