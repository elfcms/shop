@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ route('admin.shop.product') }}" class="button round-button theme-button" style="color:var(--default-color);">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.back') }}
        </span>
    </a>
</div>

<div class="shop-products-container">

    <div class="item-form">
        <h2>{{ __('shop::default.edit_product') }}</h2>
        <form action="{{ route('admin.shop.product.update',$product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="tab-head">
                <div class="tab-title selected" data-tab="general">{{ __('shop::default.general') }}</div>
                <div class="tab-title" data-tab="parameters">{{ __('shop::default.parameters') }}</div>
                <div class="tab-title" data-tab="properties">{{ __('shop::default.properties') }}</div>
            </div>
            <div class="colored-rows-box tab-box showed" id="general">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <x-elfcms::ui.checkbox.switch name="active" id="active"  :checked="$product->active" />
                </div>
                <div class="input-box colored">
                    <label for="category_id">{{ __('shop::default.categories') }}</label>
                    <div class="input-wrapper">
                        {{-- <select name="category_id[]" id="category_id" multiple required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (in_array($category->id,$productCategories)) selected @endif>{{ $category->title }}</option>
                        @endforeach
                        </select> --}}
                        <div class="choosing-box">
                            @foreach ($categories as $category)
                                <div class="choosing-line">
                                    <x-elfcms::ui.checkbox.classic name="category_id[]" id="category_{{ $category->id }}"
                                        value="{{ $category->id }}" color="var(--default-color)" :checked="in_array($category->id,$productCategories)" />
                                    <label for="category_{{ $category->id }}">{{ $category->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" required value="{{ $product->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" required value="{{ $product->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <x-elfcms::ui.checkbox.autoslug textid="title" slugid="slug" checked="true" />
                        </div>
                    </div>
                </div>

                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $product->image }}" :params="['name' => 'image']" :download="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper wide-editor-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ $product->description }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="unit_id">{{ __('shop::default.unit_of_measurement') }}</label>
                    <div class="input-wrapper">
                        <select name="unit_id" id="unit_id">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @if(!empty($product->unit->id) && $unit->id == $product->unit->id) selected @endif>{{ $unit->title }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="brand_id">{{ __('shop::default.brand') }}</label>
                    <div class="input-wrapper">
                        <select name="brand_id" id="brand_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @if(!empty($product->brand->id) && $brand->id == $product->brand->id) selected @endif>{{ $brand->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="vendor_id">{{ __('shop::default.vendor') }}</label>
                    <div class="input-wrapper">
                        <select name="vendor_id" id="vendor_id">
                            <option value="">{{ __('elfcms::default.none') }}</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @if(!empty($product->vendor->id) && $vendor->id == $product->vendor->id) selected @endif>{{ $vendor->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $product->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $product->meta_description }}</textarea>
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
                            <option value="">{{ __('shop::default.none') }}</option>
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
                                <img src="{{ asset(file_path($parameter->value)) }}" alt="Image">
                            @else
                                <img src="{{ asset('/elfcms/admin/modules/shop//images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($parameter->value))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="parameter[{{$parameter->id}}][image]" id="parameter_{{$parameter->id}}_image">
                        </div>
                        @elseif ($parameter->data_type->code == 'file')
                        <x-elfcms::anonymous.button.file name="parameter[{{$parameter->id}}]" value="{{ $parameter->value }}" id="parameter_{{$parameter->id}}" />
                        @elseif ($parameter->data_type->code == 'color')
                        <x-elfcms::anonymous.picker.color :list="$colors" name="parameter[{{$parameter->id}}]" :default="$parameter->colorData" id="parameter_{{$parameter->id}}" />
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
                            <option value="">{{ __('shop::default.none') }}</option>
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
                                <img src="{{ asset(file_path($property->value)) }}" alt="Image">
                            @else
                                <img src="{{ asset('/elfcms/admin/modules/shop//images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($property->value))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="property[{{$property->id}}][image]" id="property_{{$property->id}}_image">
                        </div>
                        @elseif ($property->data_type->code == 'file')
                        <x-elfcms::anonymous.button.file name="property[{{$property->id}}]" value="{{ $property->value }}" id="property_{{$property->id}}" />
                        @elseif ($property->data_type->code == 'color')
                        <x-elfcms::anonymous.picker.color :list="$colors" name="property[{{$property->id}}]" :default="$property->colorData" id="property_{{$property->id}}" />

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
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.shop.product') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="item-form">
        <h2>{{ __('shop::default.items') }}</h2>
        <div class="top-button-box">
            <form action="{{ route('admin.shop.item.create') }}" method="GET">
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <button type="submit" class="button color-text-button small-button">{{ __('shop::default.add_item') }}</button>
            </form>
        </div>
        <div class="colored-rows-box shop-item-table">
            <div class="input-box shop-item-table-head">
                <div class="shop-item-column shop-item-column-id">
                    ID
                </div>
                <div class="shop-item-column shop-item-column-number">
                    {{ __('shop::default.item_number') }}
                </div>
                <div class="shop-item-column shop-item-column-note">
                    {{ __('shop::default.note') }}/{{ __('shop::default.additional_name') }}
                </div>
                <div class="shop-item-column shop-item-column-buttons"></div>
            </div>
        @foreach ($product->items as $item)
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
                        <a href="{{ route('admin.shop.item.edit',$item) }}" class="button icon-button" title="{{ __('shop::default.edit_item') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                        </a>
                    </div>
                    <div class="shop-item-column-button">
                        <form action="{{ route('admin.shop.item.create') }}" method="GET">
                            <input type="hidden" name="copy_id" value="{{$item->id}}">
                            <button type="submit" class="button icon-button" title="{{ __('shop::default.copy') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/copy_all.svg', svg: true) !!}
                            </button>
                        </form>
                    </div>
                    <div class="shop-item-column-button">
                        <form action="{{ route('admin.shop.item.destroy',$item) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="name" value="{{ $item->item_number }}">
                            <input type="hidden" name="is_item" value="1">
                            <button type="submit" class="button icon-button icon-alarm-button" title="{{ __('elfcms::default.delete') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                            </button>
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
                            text = "{{ __('shop::default.are_you_sure_to_deleting_product') }}"

                        if (isItem) {
                            text = "{{ __('shop::default.are_you_sure_to_deleting_item') }}";
                        }
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + categoryId,
                        content: text,
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-text-button danger-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'button color-text-button',
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
