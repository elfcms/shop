<tr data-id="{{ $unit->id }}">
    <td>
        <span>{{ $unit->id }}</span>
        <input type="hidden" name="unit[{{$unit->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="unit[{{$unit->id}}][name]" id="unit_{{$unit->id}}_name" value="{{ $unit->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="unit[{{$unit->id}}][abbr]" id="unit_{{$unit->id}}_abbr" value="{{ $unit->abbr }}" data-name="abbr">
    </td>
    <td>
        <input type="number" name="unit[{{$unit->id}}][step]" id="unit_{{$unit->id}}_step" value="{{ $unit->step }}" data-name="step" step="any" min="0.001">
    </td>
    <td>
        <input type="number" name="unit[{{$unit->id}}][min]" id="unit_{{$unit->id}}_min" value="{{ $unit->min }}" data-name="min" step="any">
    </td>
    <td>
        <input type="number" name="unit[{{$unit->id}}][max]" id="unit_{{$unit->id}}_max" value="{{ $unit->max }}" data-name="max" step="any">
    </td>
    <td>
        <input type="text" name="unit[{{$unit->id}}][name_plural]" id="unit_{{$unit->id}}_name_plural" value="{{ $unit->name_plural }}" data-name="name_plural">
    </td>
    <td>
        <input type="text" name="unit[{{$unit->id}}][lang_name]" id="unit_{{$unit->id}}_lang_name" value="{{ $unit->lang_name }}" data-name="lang_name">
    </td>
    <td>
        <input type="text" name="unit[{{$unit->id}}][lang_name_plural]" id="unit_{{$unit->id}}_lang_name_plural" value="{{ $unit->lang_name_plural }}" data-name="lang_name_plural">
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="unit[{{$unit->id}}][delete]" id="unit_{{$unit->id}}_delete" dataid="{{ $unit->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>
</tr>
