@extends('shop::admin.layouts.shop')

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
<form name="price_typeform" class="data-table-box" method="post" action="{{ route('admin.shop.ajax.price-type.fullsave') }}">
    @csrf
    <div class="widetable-wrapper">
        <table class="grid-table price_type-table itemstable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('basic::elf.name') }}</th>
                    <th>{{ __('shop::elf.price_type') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @include('shop::admin.shop.price-type.content.list')
            </tbody>
        </table>
    </div>
    <div class="shop-table-buttons">
        <button class="default-btn alternate-button" title="{{__('shop::elf.add_price_type')}}" data-action="additem">{{ __('shop::elf.add') }}</button>
        {{-- <button class="default-btn" title="{{__('basic::elf.reset_button')}}" data-action="reset">{{ __('basic::elf.reset_button') }}</button> --}}
        <button type="submit" class="default-btn submit-button" disabled="" data-action="save">{{ __('basic::elf.save') }}</button>
    </div>
</form>
    <script>
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="price_typeform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        async function getEmptyItem() {
            let response = await fetch('{{ route("admin.shop.ajax.price-type.empty-item") }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getPriceTypeList () {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route("admin.shop.ajax.price-type.list",true) }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            unitListData = await response.json();
            return unitListData;
        }

        let startPreload = preloadSet('form[name="price_typeform"]');

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

        if (addButton) {
            addButton.addEventListener('click',addPriceTypeItem);
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
                    title: '{{__("shop::elf.are_you_sure")}}',
                    content: '{{__("shop::elf.do_you_want_to_save_your_changes")}}',
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
                            title:'{{__("basic::elf.cancel")}}',
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
                            if (data.result && data.result == 'success') {
                                itemsBox.innerHTML = '';
                                itemsBox.insertAdjacentHTML('beforeend',data.data);
                                const reInputs = itemsBox.querySelectorAll('tr[data-id] td [data-name]');
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
                                popup({
                                    title: '{{__("shop::elf.done")}}',
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
                            }
                            else {
                                if ((data.error || (data.result && data.result == 'error'))) {
                                    if (!data.message) {
                                        data.message = '{{ __("shop::elf.error") }}';
                                    }
                                    popup({
                                        title:'{{__("shop::elf.error")}}',
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

        getPriceTypeList();
        getEmptyItem();
        autoSlug('.autoslug');

    </script>

@endsection
