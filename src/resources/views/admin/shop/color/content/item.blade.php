<tr data-id="{{ $color->id }}">
    <td>
        <span>{{ $color->id }}</span>
        <input type="hidden" name="color[{{ $color->id }}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="color[{{ $color->id }}][name]" id="color_{{ $color->id }}_name"
            value="{{ $color->name }}" data-name="name">
    </td>
    <td>
        <div class="color-pointer-wrapper">
            <input type="text" name="color[{{ $color->id }}][code_text]" id="color_{{ $color->id }}_code_text"
                data-name="code_text" value="{{ $color->code }}">
            <input type="color" name="color[{{ $color->id }}][code]" id="color_{{ $color->id }}_code"
                value="{{ $color->code ?? '#ffffff' }}" data-name="code">
        </div>
    </td>
    <td>
        <div class="inputfile small-pic-file" id="inputfile_{{ $color->boxId }}">
            <div @class(['inputfile-buttons', 'hidden' => empty($color->picture)])>
                <div class="inputfile-delete"></div>
            </div>
            <input type="hidden" name="color[{{ $color->id }}][picture_path]" value="{{ $color->picture }}">
            <div class="inputfile-icon default-icon">
                @if (empty($color->picture))
                    {!! iconHtmlLocal('/elfcms/admin/images/icons/upload.svg', svg: true) !!}
                @else
                    <img src="{{ file_path($color->picture) }}" alt="">
                @endif
            </div>
            {{-- <div class="inputfile-title">
                @if (empty($color->picture))
                    {{ __('elfcms::default.choose_file') }}
                @else
                @endif
            </div> --}}
            <input type="file" name="color[{{ $color->id }}][picture]" accept="jpg,jpeg,png,webp">
        </div>
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="color[{{$color->id}}][delete]" id="color_{{$color->id}}_delete" dataid="{{ $color->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>
</tr>
