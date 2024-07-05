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
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="reason[{{$reason->id}}][delete]" id="reason_{{$reason->id}}_delete" value="1" data-id="{{ $reason->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
