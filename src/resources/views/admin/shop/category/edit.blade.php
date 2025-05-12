@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ route('admin.shop.category') }}" class="button round-button theme-button" style="color:var(--default-color);">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.back') }}
        </span>
    </a>
</div>

    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_category') }}{{ $category->id }}</h2>
        <div class="date-info create-info">
            {{ __('elfcms::default.created_at') }}: {{ $category->created }}
        </div>
        <div class="date-info update-info">
            {{ __('elfcms::default.updated_at') }}: {{ $category->updated }}
        </div>
        <form action="{{ route('admin.shop.category.update',$category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active" :checked="$category->active" />
                </div>
                <div class="input-box colored">
                    <label for="parent_id">{{ __('elfcms::default.parent') }}</label>
                    <div class="input-wrapper">
                        <select name="parent_id" id="parent_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($categories as $item)
                            @if ($item->id != $category->id)
                                <option value="{{ $item->id }}" @if ($item->active != 1) class="inactive" @endif @if ($item->id == $category->parent_id) selected @endif>{{ $item->title }}@if ($item->active != 1) [{{ __('elfcms::default.inactive') }}] @endif</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" value="{{ $category->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" value="{{ $category->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <x-elfcms::ui.checkbox.autoslug textid="title" slugid="slug" checked="true" />
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $category->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                            <x-elf-input-file value="{{ $category->image }}" :params="['name' => 'image']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $category->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $category->meta_description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.shop.category') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    const previewInput = document.querySelector('#preview')
    if (previewInput) {
        inputFileImg(previewInput)
    }

    autoSlug('.autoslug')
    //add editor
    runEditor('#description')
    </script>

@endsection
