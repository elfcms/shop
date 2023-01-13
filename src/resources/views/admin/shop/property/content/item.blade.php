<tr data-id="{{ $property->id }}">
    <td>
        <span>{{ $property->id }}</span>
        <input type="hidden" name="property[{{$property->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][name]" id="property_{{$property->id}}_name" value="{{ $property->name }}" data-name="name">
    </td>
    <td>
        <div class="input-wrapper">
            <div class="autoslug-wrapper">
                <input type="checkbox" data-text-id="property_{{$property->id}}_name" data-slug-id="property_{{$property->id}}_code" class="autoslug" checked>
                <div class="autoslug-button"></div>
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][code]" id="property_{{$property->id}}_code" value="{{ $property->code }}" data-name="code">
    </td>
    <td>
        <select name="property[{{$property->id}}][data_type_id]" id="property_{{$property->id}}_data_type_id" data-name="data_type_id">
            @foreach ($data_types as $data_type)
            <option value="{{ $data_type->id }}" @if ($property->data_type_id==$data_type->id) selected @endif>{{ $data_type->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][description]" id="property_{{$property->id}}_description" value="{{ $property->description }}" data-name="description">
    </td>
    <td>
        <input type="checkbox" name="property[{{$property->id}}][is_filter]" id="property_{{$property->id}}_is_filter" value="1" data-name="is_filter" @if($property->is_filter) checked @endif>
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="property[{{$property->id}}][delete]" id="property_{{$property->id}}_delete" value="1" data-id="{{ $property->id }}" title="{{ __('basic::elf.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
