@extends('shop::admin.layouts.shop')

@section('shop-content')

    @if (Session::has('action_result'))
        <div class="alert alert-success">{{ Session::get('action_result') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <nav class="pagenav">
        <div class="abstract-nav-line">
            <a href="{{ route('admin.shop.product.edit', $isCopy->product ?? $item->product ) }}" class="default-btn alternate-button">{{ $isCopy->product->title ?? $item->product->title }}</a>
        </div>
    </nav>

    <div class="item-form">
        <h3>{{ $page['title'] }}</h3>
        <form action="{{ !empty($isCopy) ? route('admin.shop.item.store') : route('admin.shop.item.update',$item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method(!empty($isCopy) ? 'POST' : 'PUT')
            <div class="tab-head">
                <div class="tab-title selected" data-tab="general">{{ __('shop::elf.general') }}</div>
                <div class="tab-title" data-tab="parameters">{{ __('shop::elf.parameters') }}</div>
                <div class="tab-title" data-tab="properties">{{ __('shop::elf.properties') }}</div>
                <div class="tab-title" data-tab="prices">{{ __('shop::elf.prices') }}</div>
            </div>

            <div class="colored-rows-box tab-box" id="prices">
                <div class="shop-item-pricelist" id="pricelist">
                    <div class="shop-item-pricelist-head">
                        <div class="shop-item-column">
                            ID
                        </div>
                        <div class="shop-item-column">
                            {{ __('shop::elf.price_type') }}
                        </div>
                        <div class="shop-item-column">
                            {{ __('shop::elf.value') }}
                        </div>
                        <div class="shop-item-column">
                            {{ __('shop::elf.currency') }}
                        </div>
                        <div class="shop-item-column">
                            {{ __('shop::elf.default') }}
                        </div>
                        <div class="shop-item-column">
                            {{ __('shop::elf.old') }}
                        </div>
                        <div class="shop-item-column"></div>
                    </div>
                    @forelse ($item->prices as $price)
                        @include('shop::admin.shop.item.content.priceitem')
                    @empty
                    <div class="none-price">{{ __('shop::elf.none') }}</div>
                    @endforelse
                </div>
                <div class="button-box single-box">
                    <button type="button" class="default-btn alternate-button" id="priceaddbutton">{{ __('shop::elf.add_price') }}</button>
                </div>
                <script>
                const priceAddButton = document.querySelector('#priceaddbutton');
                const priceList = document.querySelector('#pricelist');
                let newId = 1;
                if (priceList) {
                    const priceExistValues = priceList.querySelectorAll('.shop-item-pricelist-row [data-inputname="value"]');
                    const checkboxExistsList = document.querySelectorAll('.shop-item-pricelist-row input[type="checkbox"]');
                    if (priceExistValues) {
                        priceExistValues.forEach(input => {
                            input.addEventListener('input',function(e){
                                inputMoney(e.target);
                            });
                            input.addEventListener('change',function(e){
                                changeMoney(e.target);
                            });
                        });
                    }
                    if (checkboxExistsList) {
                        checkboxExistsList.forEach(element => {
                            element.addEventListener('click',function(e){
                                checkDefault(e.target);
                            });
                        });
                    }
                    if (priceAddButton) {
                        priceAddButton.addEventListener('click',function(){
                            getShopAjaxText('{{ route("admin.shop.ajax.itemprice.empty-item") }}', {
                                id: newId,
                            }).then((data) => {
                                priceList.insertAdjacentHTML('beforeend',data)

                            }).finally(()=>{
                                const nonePrice = document.querySelector('.none-price');
                                if (nonePrice) {
                                    nonePrice.remove();
                                }
                                const valueInput = document.querySelector('[name="price[new]['+newId+'][value]"]');
                                const checkboxList = document.querySelectorAll('.shop-item-pricelist-row input[type="checkbox"][data-inputrow="new_'+newId+'"]');
                                valueInput.addEventListener('input',function(e){
                                    inputMoney(e.target);
                                });
                                valueInput.addEventListener('change',function(e){
                                    changeMoney(e.target);
                                });
                                if (checkboxList) {
                                    checkboxList.forEach(element => {
                                        element.addEventListener('click',function(e){
                                            checkDefault(e.target);
                                        });
                                    });
                                }
                                newId++;
                            })
                        });
                    }
                }
                function setRowDelete(checkbox, inverse = false) {
                    const row = document.querySelector('.shop-item-pricelist-row[data-inputrow="'+checkbox.dataset.inputrow+'"]');
                    if (row) {
                        if (checkbox.checked && !inverse || !checkbox.checked && inverse) {
                            row.classList.add('deletable');
                        }
                        else {
                            row.classList.remove('deletable');
                        }
                    }
                }
                function checkDefault(checkbox) {
                    if (checkbox.checked) {
                        const checkboxList = document.querySelectorAll('.shop-item-pricelist-row input[type="checkbox"]');
                        if (checkboxList) {
                            checkboxList.forEach(element => {
                                if (element === checkbox) {
                                    return;
                                }
                                if (element.dataset.inputrow == checkbox.dataset.inputrow) {
                                    element.checked = false;
                                }
                                else {
                                    if (checkbox.dataset.inputname != 'delete' && element.dataset.inputname == checkbox.dataset.inputname) {
                                        element.checked = false;
                                    }
                                }
                            });
                        }
                    }
                    setRowDelete(checkbox,checkbox.dataset.inputname != 'delete');
                }
                </script>
            </div>
            <div class="colored-rows-box tab-box showed" id="general">
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input
                                type="checkbox"
                                name="active"
                                id="active"
                                checked
                            >
                            <i></i>
                        </div>
                        <label for="active">
                            {{ __('basic::elf.active') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="product_id">{{ __('shop::elf.product') }}</label>
                    <div class="input-wrapper">
                        <select name="product_id" id="product_id" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if($item->product_id == $product->id) selected @endif>{{ $product->title }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="item_number">{{ __('shop::elf.item_number') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="item_number" id="item_number" required value="{{ $item->item_number }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="vendor_item_number">{{ __('shop::elf.vendor_item_number') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="vendor_item_number" id="vendor_item_number" value="{{ $item->vendor_item_number }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="additional_name">{{ __('shop::elf.additional_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="additional_name" id="additional_name" value="{{ $item->additional_name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="alternate_name">{{ __('shop::elf.alternate_name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="alternate_name" id="alternate_name" value="{{ $item->alternate_name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="note">{{ __('shop::elf.note') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="note" id="note" value="{{ $item->note }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="balance">{{ __('shop::elf.balance') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="balance" id="balance" value="{{ $item->balance }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $item->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('basic::elf.image') }}</label>
                    <div class="input-wrapper">
                        <input type="hidden" name="image_path" id="image_path" value="{{$item->image}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($item->image)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($item->image))
                                <img src="{{ asset($item->image) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/shop/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($item->image))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="vendor_id">{{ __('shop::elf.vendor') }}</label>
                    <div class="input-wrapper">
                        <select name="vendor_id" id="vendor_id">
                            <option value="">{{ __('basic::elf.none') }}</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @if($item->vendor_id == $vendor->id) selected @endif>{{ $vendor->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('basic::elf.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $item->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('basic::elf.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $item->meta_description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="colored-rows-box tab-box" id="parameters">
                @foreach ($parameters as $parameter)
                <div class="input-box colored">
                    {{-- <input type="checkbox" name="used_parameter[{{$parameter->id}}]" id="used_parameter_{{$parameter->id}}"> --}}
                    <label for="parameter_{{$parameter->id}}">{{ $parameter->name }}</label>
                    <div class="input-wrapper">
                        @if ($parameter->data_type->code == 'text' || $parameter->data_type->code == 'json')
                        <textarea name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}">{{ $parameter->value }}</textarea>
                        @elseif ($parameter->data_type->code == 'list')
                        <select name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}" @if($parameter->multiple) multiple @endif>
                            <option value="">{{ __('shop::elf.none') }}</option>
                            @if (!empty($parameter->options))
                            @foreach ($parameter->options as $value => $text)
                            <option value="{{$value}}"
                                @if (is_array($parameter->value) && in_array($value,$parameter->value))
                                selected
                                @endif>{{$text}}</option>
                            @endforeach
                            @endif
                        </select>

                        @elseif ($parameter->data_type->code == 'image')
                        <input type="hidden" name="parameter[{{$parameter->id}}][path]" id="parameter_{{$parameter->id}}_path" value="{{ $parameter->value }}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($parameter->value)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($parameter->value))
                                <img src="{{ asset($parameter->value) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/shop/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($parameter->value))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="parameter[{{$parameter->id}}][image]" id="parameter_{{$parameter->id}}_image">
                        </div>
                        @elseif ($parameter->data_type->code == 'file')
                        <x-basic::anonymous.button.file name="parameter[{{$parameter->id}}]" value="{{ $parameter->value }}" id="parameter_{{$parameter->id}}" />
                        @elseif ($parameter->data_type->code == 'color')
                        <x-shop::anonymous.picker.color :list="$colors" name="parameter[{{$parameter->id}}]" :default="$parameter->colorData" id="parameter_{{$parameter->id}}" />
                        {{-- @elseif ($parameter->data_type->code == 'string')
                        <input type="text" name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}" value="{{ $parameter->value }}">
                        @elseif ($parameter->data_type->code == 'int')
                        <input type="number" name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}" value="{{ $parameter->value }}" step="1"> --}}
                        @elseif ($parameter->data_type->code == 'bool')
                        <input type="hidden" name="parameter[{{$parameter->id}}]" value="0">
                        <input type="checkbox" name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}" @if ($parameter->value) checked @endif value="1">
                        @else
                        <input type="{{ $parameter->data_type->field[0] }}" name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}" value="{{ $parameter->value }}">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="colored-rows-box tab-box" id="properties">
                @foreach ($properties as $property)
                <div class="input-box colored">
                    <label for="property_{{$property->id}}">{{ $property->name }}</label>
                    <div class="input-wrapper">
                        @if ($property->data_type->code == 'text' || $property->data_type->code == 'json')
                        <textarea name="property[{{$property->id}}]" id="property_{{$property->id}}">{{ $property->value }}</textarea>
                        @elseif ($property->data_type->code == 'list')
                        <select name="property[{{$property->id}}]" id="property_{{$property->id}}" @if($property->multiple) multiple @endif>
                            <option value="">{{ __('shop::elf.none') }}</option>
                            @if (!empty($property->options))
                            @foreach ($property->options as $value => $text)
                            <option value="{{$value}}"
                                @if (is_array($property->value) && in_array($value,$property->value))
                                selected
                                @endif>{{$text}}</option>
                            @endforeach
                            @endif
                        </select>
                        @elseif ($property->data_type->code == 'image')
                        <input type="hidden" name="property[{{$property->id}}][path]" id="property_{{$property->id}}_path" value="{{ $property->value }}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($property->value)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($property->value))
                                <img src="{{ asset($property->value) }}" alt="Image">
                            @else
                                <img src="{{ asset('/vendor/elfcms/shop/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($property->value))
                                {{ __('basic::elf.change_file') }}
                            @else
                                {{ __('basic::elf.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="property[{{$property->id}}][image]" id="property_{{$property->id}}_image">
                        </div>
                        @elseif ($property->data_type->code == 'file')
                        <x-basic::anonymous.button.file name="property[{{$property->id}}]" value="{{ $property->value }}" id="property_{{$property->id}}" />
                        @elseif ($property->data_type->code == 'color')
                        <x-shop::anonymous.picker.color :list="$colors" name="property[{{$property->id}}]" :default="$property->colorData" id="property_{{$property->id}}" />

                        {{-- @elseif ($property->data_type->code == 'string')
                        <input type="text" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->product_values($product->id) }}">
                        @elseif ($property->data_type->code == 'int')
                        <input type="number" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->product_values($product->id) }}" step="1"> --}}
                        @elseif ($property->data_type->code == 'bool')
                        <input type="checkbox" name="property[{{$property->id}}]" id="property_{{$property->id}}" @if ($property->value) checked @endif value="1">
                        @else
                        <input type="{{ $property->data_type->field[0] }}" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->value }}">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('basic::elf.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
    tabsInit();
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    //autoSlug('.autoslug')
    //add editor
    runEditor('#description')
    </script>

@endsection
