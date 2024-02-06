@props(['list', 'default'=>null, 'name'=>'color', 'id' => uniqid()])
@if (!empty($list))

<div class="shop-color-picker-box" id="{{ $id }}">
    <div class="shop-color-picker-button" onclick="openPickerColor(this)">
        <div class="shop-color-picker-item" data-id="{{ $default->id ?? 0 }}">
            <div class="shop-color-picker-image empty" style="color: {{ $default->code ?? 'transparent' }};@if(!empty($default->picture)) background-image:url('{{$default->picture}}') @endif"></div>
            <label>{{ $default->name ?? __('shop::default.none') }}</label>
        </div>
    </div>
    <div class="shop-color-picker-list closed">
        <div class="shop-color-picker-head">
            <div class="shop-color-picker-close" onclick="closePickerColor(this)">&#215;</div>
        </div>
        <div class="shop-color-picker-item" data-id="0">
            <div class="shop-color-picker-image empty" style="color: transparent;"></div>
            <input type="radio" name="{{ $name }}" id="color_item_0"  @empty($default) checked @endempty value="0" data-id="0" onclick="setPickerColor(this)">
            <label>{{ __('shop::default.none') }}</label>
        </div>
    @foreach ($list as $item)
        <div class="shop-color-picker-item" data-id="{{ $item->id }}">
            <div class="shop-color-picker-image" style="color: {{ $item->code }};">
            @if (!empty($item->picture))
                <img src="{{ asset($item->picture) }}" alt="{{ $item->name }}">
            @endif
            </div>
            <input type="radio" name="{{ $name }}" id="color_item_{{ $id }}_{{ $item->id }}" @if($default && $default->id == $item->id) checked @endif value="{{ $item->id }}" data-id="{{ $item->id }}" onclick="setPickerColor(this)">
            <label {{-- for="color_item_{{ $id }}_{{ $item->id }}" --}}>{{ $item->name }}</label>
        </div>
    @endforeach
    </div>
</div>
<script>
const colorPickerDefaults_{{$id}} = document.querySelectorAll('.shop-color-picker-item input[type="radio"]:checked');

if (colorPickerDefaults_{{$id}}) {
    window.onload = () => {
        colorPickerDefaults_{{$id}}.forEach(element => {
            setPickerColor(element);
        });
    }
}

</script>
@endif
