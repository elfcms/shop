<tr data-id="{{ $vendor->id }}">
    <td>
        <span>{{ $vendor->id }}</span>
        <input type="hidden" name="vendor[{{$vendor->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="vendor[{{$vendor->id}}][number]" id="vendor_{{$vendor->id}}_number" value="{{ $vendor->number }}" data-name="number">
    </td>
    <td>
        <input type="text" name="vendor[{{$vendor->id}}][name]" id="vendor_{{$vendor->id}}_name" value="{{ $vendor->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="vendor[{{$vendor->id}}][code]" id="vendor_{{$vendor->id}}_code" value="{{ $vendor->code }}" data-name="code">
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="vendor[{{$vendor->id}}][delete]" id="vendor_{{$vendor->id}}_delete" value="1" data-id="{{ $vendor->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
