@extends('elfcms::admin.layouts.main')
@inject('image', 'Elfcms\Elfcms\Aux\Image')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ !empty($item) ? route('admin.shop.item.edit',$item) : route('admin.shop.product') }}" class="button round-button theme-button" style="color:var(--default-color);">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.back') }}
        </span>
    </a>
</div>

<div class="shop-pics-info-box">
    {{-- <div class="shop-pics-preview-box">
        <img src="{{ file_path($gallery->preview) }}" alt="">
    </div> --}}
    <div class="shop-pics-data-box">
        <h2>{{ $item->product->title }} - {{ $item->additional_name ?? $item->alternate_name }}</h2>
        {{-- <div class="shop-pics-description">{{ $item->description }}</div> --}}
        {{-- <div class="shop-pics-addtitional-text">{{ $item->additional_name ?? $item->alternate_name }}</div> --}}
    </div>
    {{-- <div class="dallery-edit-button-box">
        <a href="{{ route('admin.shop.item.edit',$item) }}" class="default-btn big-square-button edit-button">
            {{__('elfcms::default.edit')}}
        </a>
    </div> --}}
</div>
<div class="shop-pics-items-box">
    @include('elfcms::admin.shop.item_pictures.content.index')
</div>

@endsection
