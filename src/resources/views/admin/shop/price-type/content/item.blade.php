<tr data-id="{{ $price_type->id }}">
    <td>
        <span>{{ $price_type->id }}</span>
        <input type="hidden" name="price_type[{{$price_type->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="price_type[{{$price_type->id}}][name]" id="price_type_{{$price_type->id}}_name" value="{{ $price_type->name }}" data-name="name">
    </td>
    <td>
        <select name="price_type[{{$price_type->id}}][tax_id]" id="price_type_{{$price_type->id}}_tax_id" data-name="tax_id">
            <option value="">{{ __('shop::default.tax_free') }}</option>
            @foreach ($taxes as $tax)
            <option value="{{ $tax->id }}" @if ($tax->id == $price_type->tax_id) selected @endif>{{ $tax->title ?? $tax->name }} {{ $tax->value }}%</option>
            @endforeach
        </select>
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="price_type[{{$price_type->id}}][delete]" id="price_type_{{$price_type->id}}_delete" dataid="{{ $price_type->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>
</tr>
