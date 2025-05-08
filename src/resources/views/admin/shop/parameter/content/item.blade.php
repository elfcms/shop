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
                <x-elfcms::ui.checkbox.autoslug textid="parameter_{{$parameter->id}}_name" slugid="parameter_{{$parameter->id}}_code" checked="true" />
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="parameter[{{$parameter->id}}][code]" id="parameter_{{$parameter->id}}_code" value="{{ $parameter->code }}" data-name="code">
    </td>
    <td>
        <select name="parameter[{{$parameter->id}}][data_type_id]" id="parameter_{{$parameter->id}}_data_type_id" data-name="data_type_id" data-id="{{$parameter->id}}" onchange="showOptions(this)">
            @foreach ($data_types as $data_type)
            <option value="{{ $data_type->id }}" data-code="{{ $data_type->code }}" @if ($parameter->data_type_id==$data_type->id) selected @endif>{{ $data_type->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="parameter[{{$parameter->id}}][description]" id="parameter_{{$parameter->id}}_description" value="{{ $parameter->description }}" data-name="description">
    </td>
    <td>
        <x-elfcms::ui.checkbox.switch name="parameter[{{$parameter->id}}][is_filter]" id="parameter_{{$parameter->id}}_is_filter" checked="{{ $parameter->is_filter }}" data-name="is_filter" />
        {{-- <div class="checkbox-switch green">
            <input type="checkbox" name="parameter[{{$parameter->id}}][is_filter]" id="parameter_{{$parameter->id}}_is_filter" value="1" data-name="is_filter" @if($parameter->is_filter) checked @endif>
            <i></i>
        </div> --}}
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <x-elfcms::ui.checkbox.delete name="parameter[{{$parameter->id}}][delete]" id="parameter_{{$parameter->id}}_delete" dataid="{{ $parameter->id }}" title="{{ __('elfcms::default.delete') }}" click="setUnitRowDelete(this)" />
        </div>
    </td>
    <td @class(['table-subrow','showed' => $parameter->data_type->code=='list'])>
        <div class="shop-option-box">
            <div class="shop-option-multiple-line">
                <div class="small-checkbox" style="--switch-color:var(--default-color)">
                    <input type="checkbox" name="parameter[{{$parameter->id}}][multiple]" id="parameter_{{$parameter->id}}_multiple" value="1" data-name="multiple" @checked($parameter->multiple)>
                    <i></i>
                </div>
                {{-- 
                <div class="checkbox-switch green" data-column="multiple">
                    <input type="checkbox" name="parameter[{{$parameter->id}}][multiple]" id="parameter_{{$parameter->id}}_multiple" value="1" data-name="multiple" @if($parameter->multiple) checked @endif>
                    <i></i>
                </div> --}}
                <label for="parameter_{{$parameter->id}}_multiple">{{ __('shop::default.multiple') }}</label>
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
                @if (!empty($parameter->options))
                    @foreach ($parameter->options as $key => $value)
                    <div class="shop-option-table-row">
                        <div class="shop-option-table-column">
                            <input type="text" name="parameter[{{$parameter->id}}][options][{{$loop->index}}][key]" value="{{ $key }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="key">
                        </div>
                        <div class="shop-option-table-column">
                            <input type="text" name="parameter[{{$parameter->id}}][options][{{$loop->index}}][value]" value="{{ $value }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="value">
                        </div>
                        <div class="shop-option-table-column">
                            <div class="small-checkbox" style="--switch-color:var(--danger-color)">
                                <input type="checkbox" name="parameter[{{$parameter->id}}][options][{{$loop->index}}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="delete">
                                <i></i>
                            </div>
                            {{-- <div class="checkbox-switch red">
                                <input type="checkbox" name="parameter[{{$parameter->id}}][options][{{$loop->index}}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="delete">
                                <i></i>
                            </div> --}}
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
                <div class="shop-option-table-add">
                    <button class="button color-text-button small-button" data-id="{{$parameter->id}}" onclick="addOption(this{{!$parameter->code ? '' : ',false' }})">{{ __('elfcms::default.add_option') }}</button>
                </div>
            </div>
        </div>
    </td>
</tr>
