<a href="{{ route('admin.shop.items.pictures.edit',['item'=>$item,'picture'=>$picture]) }}" class="shop-item-picture-tile shop-item-picture-element" title="{{ __('elfcms::default.edit') . ' ' . $picture->name }}" style="order:{{$picture->position}};" data-id="{{ $picture->id }}" data-slug="{{ $picture->slug }}">
    <img src="
        @if (!empty($picture->thumbnail))
            {{asset(file_path($picture->thumbnail))}}
        @elseif (!empty($picture->preview))
            {{asset(file_path($picture->preview))}}
        @else
            {{asset(file_path($picture->image))}}
        @endif
    " alt="">
    {{-- <h5>{{ $picture->name }}</h5> --}}
    <div class="delete-item-box" title="{{ __('elfcms::default.delete') }}">
        <input type="checkbox" name="item[{{$picture->id}}][delete]" id="item_{{$picture->id}}_delete" data-field="delete" onclick="event.stopPropagation()">
        <i></i>
    </div>
    <input type="hidden" name="item[{{$picture->id}}][position]" value="{{$picture->position}}" data-field="position">
</a>
