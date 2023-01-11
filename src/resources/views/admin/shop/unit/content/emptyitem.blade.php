<tr data-id="{{ $unit->id }}">
    <td>
        {{ $unit->id }}
        <input type="hidden" name="newunit[{{$unit->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="newunit[{{$unit->id}}][name]" id="newunit_{{$unit->id}}_name" value="{{ $unit->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="newunit[{{$unit->id}}][abbr]" id="newunit_{{$unit->id}}_abbr" value="{{ $unit->abbr }}" data-name="abbr">
    </td>
    <td>
        <input type="number" name="newunit[{{$unit->id}}][step]" id="newunit_{{$unit->id}}_step" value="{{ $unit->step }}" data-name="step" step="any" min="0.001">
    </td>
    <td>
        <input type="number" name="newunit[{{$unit->id}}][min]" id="newunit_{{$unit->id}}_min" value="{{ $unit->min }}" data-name="min" step="any">
    </td>
    <td>
        <input type="number" name="newunit[{{$unit->id}}][max]" id="newunit_{{$unit->id}}_max" value="{{ $unit->max }}" data-name="max" step="any">
    </td>
    <td>
        <input type="text" name="newunit[{{$unit->id}}][name_plural]" id="newunit_{{$unit->id}}_name_plural" value="{{ $unit->name_plural }}" data-name="name_plural">
    </td>
    <td>
        <input type="text" name="newunit[{{$unit->id}}][lang_name]" id="newunit_{{$unit->id}}_lang_name" value="{{ $unit->lang_name }}" data-name="lang_name">
    </td>
    <td>
        <input type="text" name="newunit[{{$unit->id}}][lang_name_plural]" id="newunit_{{$unit->id}}_lang_name_plural" value="{{ $unit->lang_name_plural }}" data-name="lang_name_plural">
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="newunit[{{$unit->id}}][delete]" id="newunit_{{$unit->id}}_delete" value="1" data-id="{{ $unit->id }}" title="{{ __('basic::elf.delete') }}" onclick="setRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
