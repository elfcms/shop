@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.shop.delivery') }}" class="button round-button theme-button"
            style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>

    <div class="item-form">
        <h2>{{ __('shop::default.edit_delivery_service') }} "{{ $delivery->name }}"</h2>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $delivery->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $delivery->updated }}
        </div>
        <form action="{{ route('admin.shop.delivery.update', $delivery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $delivery->id }}">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off"
                            value="{{ $delivery->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="code">{{ __('elfcms::default.code') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="code" id="code" autocomplete="off"
                            value="{{ $delivery->code }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <x-elfcms::ui.checkbox.autoslug textid="name" slugid="code" checked="true" />
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="icon">{{ __('shop::default.icon') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $delivery->icon }}" :params="['name' => 'icon', 'code' => 'icon']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="price">{{ __('shop::default.price') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="price" id="price" min="0" value="{{ $delivery->price }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.shop.delivery') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        
        autoSlug('.autoslug')
        //add editor
        //runEditor('#description')
        const priceInput = document.querySelector('[name="price"]');
        if (priceInput) {
            priceInput.addEventListener('input', function() {
                this.value = this.value.replace(',', '.').replace(/^\.|[^\d\.]|\.(?=.*\.)|^0+(?=\d)/g, '');
            });
        }
    </script>
@endsection
