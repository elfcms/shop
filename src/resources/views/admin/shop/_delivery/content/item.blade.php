<tr data-id="{{ $delivery->id }}">
    <td>
        <span>{{ $delivery->id }}</span>
        <input type="hidden" name="delivery[{{$delivery->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="delivery[{{$delivery->id}}][number]" id="delivery_{{$delivery->id}}_number" value="{{ $delivery->number }}" data-name="number">
    </td>
    <td>
        <input type="text" name="delivery[{{$delivery->id}}][name]" id="delivery_{{$delivery->id}}_name" value="{{ $delivery->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="delivery[{{$delivery->id}}][code]" id="delivery_{{$delivery->id}}_code" value="{{ $delivery->code }}" data-name="code">
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="delivery[{{$delivery->id}}][delete]" id="delivery_{{$delivery->id}}_delete" value="1" data-id="{{ $delivery->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
