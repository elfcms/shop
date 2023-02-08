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

<div class="shop-goods-container">

    <div class="item-form">
        <h3>{{ __('shop::elf.edit_goods') }}</h3>
        <form action="{{ route('admin.shop.goods.update',$goods) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="tab-head">
                <div class="tab-title selected" data-tab="general">{{ __('shop::elf.general') }}</div>
                <div class="tab-title" data-tab="parameters">{{ __('shop::elf.parameters') }}</div>
                <div class="tab-title" data-tab="properties">{{ __('shop::elf.properties') }}</div>
            </div>
            <div class="colored-rows-box tab-box showed" id="general">
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input
                                type="checkbox"
                                name="active"
                                id="active"
                                @if ($goods->active == 1)
                                checked
                                @endif
                            >
                            <i></i>
                        </div>
                        <label for="active">
                            {{ __('basic::elf.active') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="category_id">{{ __('shop::elf.categories') }}</label>
                    <div class="input-wrapper">
                        <select name="category_id[]" id="category_id" multiple required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (in_array($category->id,$goodsCategories)) selected @endif>{{ $category->title }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('basic::elf.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" required value="{{ $goods->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('basic::elf.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" required value="{{ $goods->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="title" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('basic::elf.description') }}</label>
                    <div class="input-wrapper wide-editor-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $goods->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="unit_id">{{ __('shop::elf.unit_of_measurement') }}</label>
                    <div class="input-wrapper">
                        <select name="unit_id" id="unit_id">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @if(!empty($goods->unit->id) && $unit->id == $goods->unit->id) selected @endif>{{ $unit->title }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="brand_id">{{ __('shop::elf.brand') }}</label>
                    <div class="input-wrapper">
                        <select name="brand_id" id="brand_id">
                            <option value="">{{ __('basic::elf.none') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @if(!empty($goods->brand->id) && $brand->id == $goods->brand->id) selected @endif>{{ $brand->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="vendor_id">{{ __('shop::elf.vendor') }}</label>
                    <div class="input-wrapper">
                        <select name="vendor_id" id="vendor_id">
                            <option value="">{{ __('basic::elf.none') }}</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @if(!empty($goods->vendor->id) && $vendor->id == $goods->vendor->id) selected @endif>{{ $vendor->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('basic::elf.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $goods->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('basic::elf.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $goods->meta_description }}</textarea>
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
                        <input type="text" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->goods_values($goods->id) }}">
                        @elseif ($property->data_type->code == 'int')
                        <input type="number" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->goods_values($goods->id) }}" step="1"> --}}
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

    <div class="item-form">
        <h3>{{ __('shop::elf.items') }}</h3>
        <div class="top-button-box">
            <form action="{{ route('admin.shop.item.create') }}" method="GET">
                <input type="hidden" name="goods_id" value="{{$goods->id}}">
                <button type="submit" class="default-btn submit-button">{{ __('shop::elf.add_item') }}</button>
            </form>
        </div>
        <div class="colored-rows-box shop-item-table">
            <div class="input-box shop-item-table-head">
                <div class="shop-item-column shop-item-column-id">
                    ID
                </div>
                <div class="shop-item-column shop-item-column-number">
                    {{ __('shop::elf.item_number') }}
                </div>
                <div class="shop-item-column shop-item-column-note">
                    {{ __('shop::elf.note') }}/{{ __('shop::elf.additional_name') }}
                </div>
                <div class="shop-item-column shop-item-column-buttons"></div>
            </div>
        @foreach ($goods->items as $item)
            <div class="input-box colored">
                <div class="shop-item-column shop-item-column-id">
                    {{ $item->id }}
                </div>
                <div class="shop-item-column shop-item-column-number">
                    {{ $item->item_number }}
                </div>
                <div class="shop-item-column shop-item-column-note">
                    {{ $item->note ?? $item->additional_name ?? $item->alternate_name }}
                </div>
                <div class="shop-item-column shop-item-column-buttons">
                    <div class="shop-item-column-button">
                        <a href="{{ route('admin.shop.item.edit',$item) }}" class="item-button item-button-edit" title="{{ __('shop::elf.edit_item') }}"></a>
                    </div>
                    <div class="shop-item-column-button">
                        <form action="{{ route('admin.shop.item.create') }}" method="GET">
                            <input type="hidden" name="copy_id" value="{{$item->id}}">
                            <button type="submit" class="item-button item-button-copy" title="{{ __('shop::elf.copy') }}"></button>
                        </form>
                    </div>
                    <div class="shop-item-column-button">
                        <form action="{{ route('admin.shop.item.destroy',$item) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="name" value="{{ $item->item_number }}">
                            <input type="hidden" name="is_item" value="1">
                            <button type="submit" class="item-button item-button-delete" title="{{ __('basic::elf.delete') }}"></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>

</div>

    <script>
    tabsInit();
    const imageInputs = document.querySelectorAll('.image-button input[type="file"]')
    if (imageInputs) {
        imageInputs.forEach(imageInput => {
            inputFileImg(imageInput);
        });
    }
    const checkForms = document.querySelectorAll('form[data-submit="check"]');
    function setConfirmDelete(forms) {
        if (forms) {
            forms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let categoryId = this.querySelector('[name="id"]').value,
                        categoryName = this.querySelector('[name="name"]').value,
                        isItem = this.querySelector('[name="is_item"]'),
                        self = this,
                            text = "{{ __('shop::elf.are_you_sure_to_deleting_goods') }}"

                        if (isItem) {
                            text = "{{ __('shop::elf.are_you_sure_to_deleting_item') }}";
                        }
                    popup({
                        title:'{{ __('basic::elf.deleting_of_element') }}' + categoryId,
                        content: text,
                        buttons:[
                            {
                                title:'{{ __('basic::elf.delete') }}',
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('basic::elf.cancel') }}',
                                class:'default-btn cancel-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        }
    }

    setConfirmDelete(checkForms);
    autoSlug('.autoslug');
    //add editor
    runEditor('#description');
    </script>

@endsection
