<tr data-id="{{ $color->id }}">
    <td>
        <span>{{ $color->id }}</span>
        <input type="hidden" name="color[{{$color->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="color[{{$color->id}}][name]" id="color_{{$color->id}}_name" value="{{ $color->name }}" data-name="name">
    </td>
    <td>
        <input type="color" name="color[{{$color->id}}][code]" id="color_{{$color->id}}_code" value="{{ $color->code }}" data-name="code">
    </td>
    <td>
        {{-- <div class="checkbox-switch">
            <input type="checkbox" name="color[{{$color->id}}][default]" id="color_{{$color->id}}_default" value="1" data-name="default" @if($color->default) checked @endif>
            <i></i>
        </div> --}}
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="color[{{$color->id}}][delete]" id="color_{{$color->id}}_delete" value="1" data-id="{{ $color->id }}" title="{{ __('basic::elf.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
