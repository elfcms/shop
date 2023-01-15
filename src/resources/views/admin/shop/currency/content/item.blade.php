<tr data-id="{{ $currency->id }}">
    <td>
        <span>{{ $currency->id }}</span>
        <input type="hidden" name="currency[{{$currency->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="currency[{{$currency->id}}][name]" id="currency_{{$currency->id}}_name" value="{{ $currency->name }}" data-name="name">
    </td>
    <td>
        <input type="text" name="currency[{{$currency->id}}][code]" id="currency_{{$currency->id}}_code" value="{{ $currency->code }}" data-name="code">
    </td>
    <td>
        <input type="text" name="currency[{{$currency->id}}][symbol]" id="currency_{{$currency->id}}_symbol" value="{{ $currency->symbol }}" data-name="symbol">
    </td>
    <td>
        <input type="text" name="currency[{{$currency->id}}][symbol_code]" id="currency_{{$currency->id}}_symbol_code" value="{{ $currency->symbol_code }}" data-name="symbol_code">
    </td>
    <td>
        <input type="text" name="currency[{{$currency->id}}][symbol_html]" id="currency_{{$currency->id}}_symbol_html" value="{{ $currency->symbol_html }}" data-name="symbol_html">
    </td>
    <td>
        <div class="checkbox-switch green">
            <input type="checkbox" name="currency[{{$currency->id}}][default]" id="currency_{{$currency->id}}_default" value="1" data-name="default" @if($currency->default) checked @endif>
            <i></i>
        </div>
    </td>
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="currency[{{$currency->id}}][delete]" id="currency_{{$currency->id}}_delete" value="1" data-id="{{ $currency->id }}" title="{{ __('basic::elf.delete') }}" onclick="setUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
