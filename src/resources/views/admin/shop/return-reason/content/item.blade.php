<tr data-id="{{ $reason->id }}">
    <td>
        <span>{{ $reason->id }}</span>
        <input type="hidden" name="reason[{{$reason->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="reason[{{$reason->id}}][name]" id="reason_{{$reason->id}}_name" value="{{ $reason->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="reason[{{$reason->id}}][description]" id="reason_{{$reason->id}}_description" value="{{ $reason->description }}" data-name="description">
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="reason[{{$reason->id}}][delete]" id="reason_{{$reason->id}}_delete" dataid="{{ $reason->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>
</tr>
