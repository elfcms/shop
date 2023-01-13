<tr data-id="{{ $parameter->id }}">
    <td>
        <span>{{ $parameter->id }}</span>
        <input type="hidden" name="parameter[{{$parameter->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="parameter[{{$parameter->id}}][name]" id="parameter_{{$parameter->id}}_name" value="{{ $parameter->name }}" data-name="name">
    </td>
    <td>
        <div class="input-wrapper">
            <div class="autoslug-wrapper">
                <input type="checkbox" data-text-id="parameter_{{$parameter->id}}_name" data-slug-id="parameter_{{$parameter->id}}_code" class="autoslug" checked>
                <div class="autoslug-button"></div>
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="parameter[{{$parameter->id}}][code]" id="parameter_{{$parameter->id}}_code" value="{{ $parameter->code }}" data-name="code">
    </td>
    <td>
        <select name="parameter[{{$parameter->id}}][data_type_id]" id="parameter_{{$parameter->id}}_data_type_id" data-name="data_type_id">
            @foreach ($data_types as $data_type)
            <option value="{{ $data_type->id }}" @if ($parameter->data_type_id==$data_type->id) selected @endif>{{ $data_type->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="parameter[{{$parameter->id}}][description]" id="parameter_{{$parameter->id}}_description" value="{{ $parameter->description }}" data-name="description">
    </td>
    <td>
        <div class="checkbox-switch">
            <input type="checkbox" name="parameter[{{$parameter->id}}][is_filter]" id="parameter_{{$parameter->id}}_is_filter" value="1" data-name="is_filter" @if($parameter->is_filter) checked @endif>
            <i></i>
        </div>
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="parameter[{{$parameter->id}}][delete]" id="parameter_{{$parameter->id}}_delete" value="1" data-id="{{ $parameter->id }}" title="{{ __('basic::elf.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
