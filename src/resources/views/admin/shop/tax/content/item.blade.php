<tr data-id="{{ $tax->id }}">
    <td>
        <span>{{ $tax->id }}</span>
        <input type="hidden" name="tax[{{$tax->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="tax[{{$tax->id}}][name]" id="tax_{{$tax->id}}_name" value="{{ $tax->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="tax[{{$tax->id}}][title]" id="tax_{{$tax->id}}_title" value="{{ $tax->title }}" data-name="title">
    </td>
    <td>
        <input type="text" name="tax[{{$tax->id}}][value]" id="tax_{{$tax->id}}_value" value="{{ $tax->value }}" data-name="value">
    </td>
    <td>
        <input type="text" name="tax[{{$tax->id}}][description]" id="tax_{{$tax->id}}_description" value="{{ $tax->description }}" data-name="description">
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="tax[{{$tax->id}}][delete]" id="tax_{{$tax->id}}_delete" dataid="{{ $tax->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>
</tr>
