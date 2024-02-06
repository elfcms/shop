@extends('elfcms::admin.layouts.shop')

@section('shop-content')
    @if (Session::has('action_result'))
    <div class="alert alert-alternate">{{ Session::get('action_result') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<form name="colorform" class="data-table-box" method="post" action="{{ route('admin.shop.ajax.color.fullsave') }}">
    @csrf
    <div class="widetable-wrapper">
        <table class="grid-table color-table itemstable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('shop::default.image') }}</th>
                    {{-- <th>{{ __('shop::default.default') }}</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @include('elfcms::admin.shop.color.content.list')
            </tbody>
        </table>
    </div>
    <div class="shop-table-buttons">
        <button class="default-btn alternate-button" title="{{__('shop::default.add_color')}}" data-action="additem">{{ __('shop::default.add') }}</button>
        {{-- <button class="default-btn" title="{{__('elfcms::default.reset_button')}}" data-action="reset">{{ __('elfcms::default.reset_button') }}</button> --}}
        <button type="submit" class="default-btn submit-button" disabled="" data-action="save">{{ __('elfcms::default.save') }}</button>
    </div>
</form>
    <script>
        const colorPointers = document.querySelectorAll('.color-pointer-wrapper');
        const colorPictures = document.querySelectorAll('.color-picture-wrapper input[type="file"]');
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="colorform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        function setPictureButton(element) {
            console.log(element);
            if (typeof element == 'string') {
            console.log('str');
                element = document.querySelector(element);
            }
            if (!(element instanceof HTMLElement)) {
                return false;
            }
            console.log('el');
            inputFileImg(element);
            const delButton = element.closest('.image-button').querySelector('.delete-image');
            if (delButton) {
                delButton.addEventListener('click',function(){
                    checkUnitChange(element);
                });
            }
        }

        if (colorPictures) {
            colorPictures.forEach(element => {
                setPictureButton(element);
            });
        }



        function setColorPoint(wrapper) {
            if (!wrapper) return false;
            if (typeof wrapper == 'string') {
                wrapper = document.querySelector(wrapper);
            }
            if (!(wrapper instanceof HTMLElement)) {
                return false;
            }
            const text = wrapper.querySelector('input[type="text"]');
            const color = wrapper.querySelector('input[type="color"]');
            if (text && color) {
                text.addEventListener('input',function(){
                    if (this.value.match( /#[a-f0-9]{6}\b/gi )) {
                        color.value = this.value;
                    }
                });
                color.addEventListener('input',function(){
                    text.value = this.value;
                });
            }
        }

        if (colorPointers) {
            colorPointers.forEach(wrapper => {
                setColorPoint(wrapper);
            });
        }

        async function getEmptyItem() {
            let response = await fetch('{{ route("admin.shop.ajax.color.empty-item") }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getColorList () {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route("admin.shop.ajax.color.list",true) }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            unitListData = await response.json();
            return unitListData;
        }

        let startPreload = preloadSet('form[name="colorform"]');

        let dataLoadInterval = setInterval(() => {
            if (typeof unitListData === 'object') {
                for (let key in unitListData.data) {
                    let subdata = {};
                    for (let subkey in unitListData.data[key]){
                        subdata[subkey] = unitListData.data[key][subkey];
                    }
                    controlData[key] = subdata;
                }
                clearInterval(dataLoadInterval);
                preloadUnset(startPreload);
            }
        }, 1000);

        if (inputs) {
            inputs.forEach(input => {
                input.addEventListener('input',function(){
                    checkUnitChange(this);
                });
                /* input.addEventListener('change',function(){
                    checkUnitChange(this);
                }); */
            });
        }



        function addColorItem() {
            if (!emptyItem) return false;
            if (!newItemId && newItemId !== 0) return false;
            const container = document.querySelector('table.color-table tbody');
            if (container) {
                let itemString = emptyItem.replaceAll('id="newcolor"','id="new_'+newItemId+'"').replaceAll('color[newcolor]','newcolor['+newItemId+']').replaceAll('color_newcolor','newcolor_'+newItemId).replaceAll('<span>newcolor</span>','');
                container.insertAdjacentHTML('beforeend',itemString);
                const newRow = container.lastElementChild;
                if (newRow) {
                    newRow.classList.add('edited');
                    const colors = newRow.querySelectorAll('.color-pointer-wrapper');
                    if (colors) {
                        colors.forEach(wrapper => {
                            setColorPoint(wrapper);
                        });
                    }
                    const files = newRow.querySelectorAll('input[type="file"]');
                    if (files) {
                        files.forEach(file => {
                            setPictureButton(file);
                        });
                    }
                }
                newItemId++;
                autoSlug(newRow.querySelectorAll('.autoslug'));
                setSaveEnabled();
            }
        }

        if (addButton) {
            addButton.addEventListener('click',addColorItem);
        }

        if (form) {
            form.addEventListener('submit',function(e){
                e.preventDefault();

            });
        }

        if (saveButton) {
            saveButton.addEventListener('click',function(e){
                e.preventDefault();
                popup({
                    title: '{{__("shop::default.are_you_sure")}}',
                    content: '{{__("shop::default.do_you_want_to_save_your_changes")}}',
                    buttons:[
                        {
                            title:'OK',
                            class:'default-btn alternate-button',
                            callback: [
                                saveForm,
                                'close'
                            ]
                        },
                        {
                            title:'{{__("elfcms::default.cancel")}}',
                            class:'default-btn cancel-button',
                            callback:'close'
                        }
                    ],
                    class:'alternate'
                });
                function saveForm() {
                    const formData = new FormData(form);
                    const itemsBox = form.querySelector('tbody');
                    let preloader = preloadSet('.big-container');
                    fetch(form.action,{
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: formData
                    }).then(
                        (result) => result.json()
                    ).then (
                        (data) => {
                            console.log(data);
                            if (data.result && data.result == 'success') {
                                itemsBox.innerHTML = '';
                                itemsBox.insertAdjacentHTML('beforeend',data.data);
                                const reInputs = itemsBox.querySelectorAll('tr[data-id] td [data-name]');
                                const fileInputs = itemsBox.querySelectorAll('tr[data-id] td input[type="file"]');
                                if (reInputs) {
                                    reInputs.forEach(input => {
                                        input.addEventListener('input',function(){
                                            checkUnitChange(this);
                                        });
                                        /* input.addEventListener('change',function(){
                                            checkUnitChange(this);
                                        }); */
                                        autoSlug('.autoslug');
                                    });
                                }
                                if (fileInputs) {
                                    fileInputs.forEach(fileInput => {
                                        setPictureButton(fileInput);
                                    });
                                }
                                popup({
                                    title: '{{__("shop::default.done")}}',
                                    content: data.message,
                                    buttons:[
                                        {
                                            title:'OK',
                                            class:'default-btn alternate-button',
                                            callback:'close'
                                        }
                                    ],
                                    class:'alternate'
                                });
                                saveButton.disabled = true;
                            }
                            else {
                                if ((data.error || (data.result && data.result == 'error'))) {
                                    if (!data.message) {
                                        data.message = '{{ __("shop::default.error") }}';
                                    }
                                    popup({
                                        title:'{{__("shop::default.error")}}',
                                        content:data.message,
                                        buttons:[
                                            {
                                                title:'OK',
                                                class:'default-btn delete-button',
                                                callback:'close'
                                            }
                                        ],
                                        class:'danger'
                                    });
                                }
                            }
                            preloadUnset(preloader);
                        }
                    ).catch(error => {
                        preloadUnset(preloader);
                    });
                }
            });
        }

        getColorList();
        getEmptyItem();
        autoSlug('.autoslug');

    </script>

@endsection
