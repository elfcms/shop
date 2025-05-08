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
                <x-elfcms::ui.checkbox.autoslug textid="property_{{$property->id}}_name" slugid="property_{{$property->id}}_code" checked="true" />
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][code]" id="property_{{$property->id}}_code" value="{{ $property->code }}" data-name="code">
    </td>
    <td>
        <select name="property[{{$property->id}}][data_type_id]" id="property_{{$property->id}}_data_type_id" data-name="data_type_id" data-id="{{$property->id}}" onchange="showOptions(this)">
            @foreach ($data_types as $data_type)
            <option value="{{ $data_type->id }}" data-code="{{ $data_type->code }}" @if ($property->data_type_id==$data_type->id) selected @endif>{{ $data_type->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="property[{{$property->id}}][description]" id="property_{{$property->id}}_description" value="{{ $property->description }}" data-name="description">
    </td>
    <td>
        <x-elfcms::ui.checkbox.switch name="property[{{$property->id}}][is_filter]" id="property_{{$property->id}}_is_filter" checked="{{ $property->is_filter }}" data-name="is_filter" />
        {{-- <div class="checkbox-switch green">
            <input type="checkbox" name="property[{{$property->id}}][is_filter]" id="property_{{$property->id}}_is_filter" value="1" data-name="is_filter" @if($property->is_filter) checked @endif>
            <i></i>
        </div> --}}
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="property[{{$property->id}}][delete]" id="property_{{$property->id}}_delete" dataid="{{ $property->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>

    <td @class(['table-subrow','showed' => $property->data_type->code=='list'])>
        <div class="shop-option-box">
            <div class="shop-option-multiple-line">
                <div class="small-checkbox" style="--switch-color:var(--default-color)">
                    <input type="checkbox" name="property[{{$property->id}}][multiple]" id="property_{{$property->id}}_multiple" value="1" data-name="multiple" @checked($property->multiple)>
                    <i></i>
                </div>
                {{-- <div class="checkbox-switch green" data-column="multiple">
                    <input type="checkbox" name="property[{{$property->id}}][multiple]" id="property_{{$property->id}}_multiple" value="1" data-name="multiple" @if($property->multiple) checked @endif>
                    <i></i>
                </div> --}}
                <label for="property_{{$property->id}}_multiple">{{ __('shop::default.multiple') }}</label>
            </div>
            <div class="shop-option-box-label">
                {{ __('shop::default.options') }}
            </div>
            <div class="shop-option-table">
                <div class="shop-option-table-head">
                    <div class="shop-option-table-column">
                        {{ __('shop::default.key') }}
                    </div>
                    <div class="shop-option-table-column">
                        {{ __('shop::default.value') }}
                    </div>
                    <div class="shop-option-table-column">
                        {{ __('elfcms::default.delete') }}
                    </div>
                </div>
                <div class="shop-option-table-body">
                @if (!empty($property->options))
                    @foreach ($property->options as $key => $value)
                    <div class="shop-option-table-row">
                        <div class="shop-option-table-column">
                            <input type="text" name="property[{{$property->id}}][options][{{$loop->index}}][key]" value="{{ $key }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="key">
                        </div>
                        <div class="shop-option-table-column">
                            <input type="text" name="property[{{$property->id}}][options][{{$loop->index}}][value]" value="{{ $value }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="value">
                        </div>
                        <div class="shop-option-table-column">
                            <div class="small-checkbox" style="--switch-color:var(--danger-color)">
                                <input type="checkbox" name="property[{{$property->id}}][options][{{$loop->index}}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="delete">
                                <i></i>
                            </div>
                            {{-- <div class="checkbox-switch red">
                                <input type="checkbox" name="property[{{$property->id}}][options][{{$loop->index}}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="delete">
                                <i></i>
                            </div> --}}
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
                <div class="shop-option-table-add">
                    <button class="button color-text-button small-button" data-id="{{$property->id}}" onclick="addOption(this{{!$property->code ? '' : ',false' }})">{{ __('elfcms::default.add_option') }}</button>
                </div>
            </div>
        </div>
    </td>
</tr>
