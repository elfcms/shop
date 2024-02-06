<div class="shop-item-pricelist-row" data-inputrow="{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}">
    <div class="input-wrapper">
        {{ empty($exists) ? '' : $price->id }}
    </div>
    <div class="input-wrapper">
        <select name="price[{{!empty($exists) ? 'exists' : 'new'}}][{{$price->id}}][price_type_id]" id="price_{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}_price_type_id" required>
        @foreach ($price_types as $price_type)
            <option value="{{ $price_type->id }}" @if($price->price_type_id == $price_type->id) selected @endif>{{ $price_type->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="input-wrapper">
        <input type="text" name="price[{{!empty($exists) ? 'exists' : 'new'}}][{{$price->id}}][value]" id="price_{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}_value" value="{{ $price->value }}" data-inputname="value">
    </div>
    <div class="input-wrapper">
        <select name="price[{{!empty($exists) ? 'exists' : 'new'}}][{{$price->id}}][currency_id]" id="price_{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}_currency_id" required>
        @foreach ($currencies as $currency)
            <option value="{{ $currency->id }}" @if($price->price_type_id == $currency->id) selected @endif>{{ $currency->name }} {{ $currency->symbol ?? $currency->code }}</option>
        @endforeach
        </select>
    </div>
    <div class="input-wrapper checkbox-switch-wrapper">
        <div class="checkbox-switch blue">
            <input
                type="checkbox"
                name="price[{{!empty($exists) ? 'exists' : 'new'}}][{{$price->id}}][current]"
                id="price_{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}_current"
                @if($price->current) checked @endif
                data-inputname="current"
                data-inputrow="{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}"
            >
            <i></i>
        </div>
    </div>
    <div class="input-wrapper checkbox-switch-wrapper">
        <div class="checkbox-switch blue">
            <input
                type="checkbox"
                name="price[{{!empty($exists) ? 'exists' : 'new'}}][{{$price->id}}][old]"
                id="price_{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}_old"
                @if($price->old) checked @endif
                data-inputname="old"
                data-inputrow="{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}"
            >
            <i></i>
        </div>
    </div>
    <div class="input-wrapper">
        <div class="check-delete-wrapper">
            <input
                type="checkbox"
                name="price[{{!empty($exists) ? 'exists' : 'new'}}][{{$price->id}}][delete]"
                id="price_{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}_delete"
                value="1"
                data-id="{{ $price->id }}"
                title="{{ __('elfcms::default.delete') }}"
                data-inputname="delete"
                data-inputrow="{{!empty($exists) ? 'exists' : 'new'}}_{{$price->id}}"
            >
            <i></i>
        </div>
    </div>
</div>
