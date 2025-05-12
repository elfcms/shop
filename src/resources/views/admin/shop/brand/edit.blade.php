@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.shop.brand') }}" class="button round-button theme-button" style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>

    <div class="item-form">
        <h2>{{ __('shop::default.edit_brand') }}{{ $brand->id }}</h2>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $brand->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $brand->updated }}
        </div>
        <form action="{{ route('admin.shop.brand.update', $brand) }}" method="POST" enctype="multipart/form-data">
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
                            <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
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
                        <x-elf-input-file value="{{ $brand->logo }}" :params="['name' => 'logo', 'code' => 'logo']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $brand->image }}" :params="['name' => 'image', 'code' => 'image']" :download="true" />
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
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.shop.brand') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        /* const imageInput = document.querySelector('#image')
        if (imageInput) {
            inputFileImg(imageInput)
        }
        const logoInput = document.querySelector('#logo')
        if (logoInput) {
            inputFileImg(logoInput)
        } */

        autoSlug('.autoslug')
        //add editor
        runEditor('#description')
    </script>
@endsection
