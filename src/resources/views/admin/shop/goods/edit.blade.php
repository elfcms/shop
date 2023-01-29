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
                        @if (!empty($parameter->options))
                        <select name="parameter[{{$parameter->id}}]" id="parameter_{{$parameter->id}}" @if($parameter->multiple) multiple @endif>
                            <option value="">{{ __('shop::elf.none') }}</option>
                            @foreach ($parameter->options as $value => $text)
                            <option value="{{$value}}"
                            @if (is_array($parameter->value) && in_array($value,$parameter->value))
                            selected
                            @endif>{{$text}}</option>
                            @endforeach
                        </select>
                        @endif

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
                        {{-- <div class="file-button">
                            <input type="hidden" name="parameter[{{$parameter->id}}][path]" id="parameter_{{$parameter->id}}_path" value="{{ $parameter->value }}">
                            <input type="file" name="parameter[{{$parameter->id}}][file]" id="parameter_{{$parameter->id}}_file">
                            @if (!empty($parameter->value))
                            <div class="file-wrapper">
                                <a href="{{ $parameter->value }}" class="file-link" target="_blank" rel="noopener noreferrer">{{ basename($parameter->value) }}</a>
                                <div class="delete-file">&#215;</div>
                            </div>
                            @endif
                        </div> --}}
                        <x-basic::anonymous.button.file name="parameter[{{$parameter->id}}]" value="{{ $parameter->value }}" id="parameter_{{$parameter->id}}" />
                        @elseif ($parameter->data_type->code == 'color')

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
                    <input type="checkbox" name="used_property[{{$property->id}}]" id="used_property_{{$property->id}}">
                    <label for="property_{{$property->id}}">{{ $property->name }}</label>
                    <div class="input-wrapper">
                        @if ($property->data_type->code == 'text' || $property->data_type->code == 'json')
                        <textarea name="property[{{$property->id}}]" id="property_{{$property->id}}">{{ $property->goods_values($goods->id) }}</textarea>
                        @elseif ($property->data_type->code == 'list')

                        {{-- @elseif ($property->data_type->code == 'image')

                        @elseif ($property->data_type->code == 'color') --}}

                        {{-- @elseif ($property->data_type->code == 'string')
                        <input type="text" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->goods_values($goods->id) }}">
                        @elseif ($property->data_type->code == 'int')
                        <input type="number" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->goods_values($goods->id) }}" step="1"> --}}
                        @elseif ($property->data_type->code == 'bool')
                        <input type="checkbox" name="property[{{$property->id}}]" id="property_{{$property->id}}" @if ($property->goods_values($goods->id)) checked @endif value="1">
                        @else
                        <input type="{{ $property->data_type->field[0] }}" name="property[{{$property->id}}]" id="property_{{$property->id}}" value="{{ $property->goods_values($goods->id) }}">
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
    const imageInputs = document.querySelectorAll('.image-button input[type="file"]')
    if (imageInputs) {
        imageInputs.forEach(imageInput => {
            inputFileImg(imageInput)
        });
    }
    autoSlug('.autoslug')
    //add editor
    runEditor('#description')
    </script>

@endsection
