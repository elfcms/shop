@extends('elfcms::admin.layouts.main')
@inject('image', 'Elfcms\Elfcms\Aux\Image')

@section('pagecontent')

@if (Session::has('elementsuccess'))
<div class="alert alert-alternate">{{ Session::get('elementsuccess') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

    <a href="{{ route('admin.shop.item.edit',$item) }}" class="back-button">{{ __('elfcms::default.back') }}</a>
<div class="gallery-info-box">
    {{-- <div class="gallery-preview-box">
        <img src="{{ file_path($gallery->preview) }}" alt="">
    </div> --}}
    <div class="gallery-data-box">
        <h2>{{ $item->product->title }} - {{ $item->additional_name ?? $item->alternate_name }}</h2>
        {{-- <div class="gallery-description">{{ $item->description }}</div> --}}
        {{-- <div class="gallery-addtitional-text">{{ $item->additional_name ?? $item->alternate_name }}</div> --}}
    </div>
    {{-- <div class="dallery-edit-button-box">
        <a href="{{ route('admin.shop.item.edit',$item) }}" class="default-btn big-square-button edit-button">
            {{__('elfcms::default.edit')}}
        </a>
    </div> --}}
</div>
<div class="gallery-items-box">
    @include('elfcms::admin.shop.item_pictures.content.index')
</div>

@endsection
