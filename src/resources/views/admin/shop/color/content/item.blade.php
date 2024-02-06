<tr data-id="{{ $color->id }}">
    <td>
        <span>{{ $color->id }}</span>
        <input type="hidden" name="color[{{$color->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="color[{{$color->id}}][name]" id="color_{{$color->id}}_name" value="{{ $color->name }}" data-name="name">
    </td>
    <td>
        <div class="color-pointer-wrapper">
            <input type="text" name="color[{{$color->id}}][code_text]" id="color_{{$color->id}}_code_text" data-name="code_text" value="{{ $color->code }}"">
            <input type="color" name="color[{{$color->id}}][code]" id="color_{{$color->id}}_code" value="{{ $color->code ?? '#ffffff' }}" data-name="code">
        </div>
    </td>
    <td>
        <div class="input-wrapper color-picture-wrapper">
            <input type="hidden" name="color[{{$color->id}}][picture_path]" id="color_{{$color->id}}_picture_path" value="{{$color->picture}}">
            <div class="image-button">
                <div class="delete-image @if (empty($color->picture)) hidden @endif">&#215;</div>
                <div class="image-button-img">
                @if (!empty($color->picture))
                    <img src="{{ asset($color->picture) }}" alt="Image">
                @else
                    <img src="{{ asset('/elfcms/admin/modules/shop//images/icons/upload.png') }}" alt="Upload file">
                @endif
                </div>
                <div class="image-button-text">
                @if (!empty($color->picture))
                    {{ __('elfcms::default.change_file') }}
                @else
                    {{ __('elfcms::default.choose_file') }}
                @endif
                </div>
                <input type="file" name="color[{{$color->id}}][picture]" id="color_{{$color->id}}_picture" data-name="picture">
            </div>
        </div>
    </td>
    {{-- <td>
        <div class="checkbox-switch">
            <input type="checkbox" name="color[{{$color->id}}][default]" id="color_{{$color->id}}_default" value="1" data-name="default" @if($color->default) checked @endif>
            <i></i>
        </div>
    </td> --}}
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="color[{{$color->id}}][delete]" id="color_{{$color->id}}_delete" value="1" data-id="{{ $color->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
