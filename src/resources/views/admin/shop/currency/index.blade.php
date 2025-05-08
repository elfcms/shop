@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<form name="currencyform" class="data-table-box" method="post" action="{{ route('admin.shop.ajax.currency.fullsave') }}">
    @csrf
    <div class="grid-table-wrapper">
        <table class="grid-table currency-table itemstable table-cols" style="--first-col:4rem; --last-col:7.5rem; --minw:50rem; --cols-count:8;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('shop::default.symbol') }}</th>
                    <th>{{ __('shop::default.symbol_code') }}</th>
                    <th>{{ __('shop::default.symbol_html') }}</th>
                    <th>{{ __('shop::default.default') }}</th>
                    <th>{{ __('elfcms::default.to_removal') }}</th>
                </tr>
            </thead>
            <tbody>
                @include('elfcms::admin.shop.currency.content.list')
            </tbody>
        </table>
    </div>
    <div class="dynamic-table-buttons">
        <button class="button round-button theme-button" title="{{ __('shop::default.add') }}"
            data-action="additem">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span>{{ __('shop::default.add') }}</span>
        </button>
        <button type="submit" class="button color-text-button success-button" disabled=""
            data-action="save">{{ __('elfcms::default.save') }}</button>
    </div>
</form>
    <script>
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="currencyform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        async function getEmptyItem() {
            let response = await fetch('{{ route("admin.shop.ajax.currency.empty-item") }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getCurrencyList () {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route("admin.shop.ajax.currency.list",true) }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            unitListData = await response.json();
            return unitListData;
        }

        let startPreload = preloadSet('form[name="currencyform"]');

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
            addButton.addEventListener('click',addCurrencyItem);
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
                    content: '{{__("elfcms::default.are_you_sure_you_want_to_save_the_changes")}}',
                    buttons:[
                        {
                            title:'OK',
                            class:'button color-text-button info-button',
                            callback: [
                                saveForm,
                                'close'
                            ]
                        },
                        {
                            title:'{{__("elfcms::default.cancel")}}',
                            class:'button color-text-button',
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
                                    title: '{{__("shop::default.done")}}',
                                    content: data.message,
                                    buttons:[
                                        {
                                            title:'OK',
                                            class:'button color-text-button info-button',
                                            callback:'close'
                                        }
                                    ],
                                    class:'alternate'
                                });
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

        getCurrencyList();
        getEmptyItem();
        autoSlug('.autoslug');

    </script>

@endsection
