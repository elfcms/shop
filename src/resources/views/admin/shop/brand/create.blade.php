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
        <h2>{{ __('shop::default.create_brand') }}</h2>
        <form action="{{ route('admin.shop.brand.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off">
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
                        <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="logo">{{ __('shop::default.logo') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="" :params="['name' => 'logo', 'code' => 'logo']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="" :params="['name' => 'image', 'code' => 'image']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="company">{{ __('shop::default.company') }}</label>
                    <div class="input-wrapper">
                        <textarea name="company" id="company" cols="30" rows="3"></textarea>
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
        const imageInput = document.querySelector('#image')
        /* if (imageInput) {
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
