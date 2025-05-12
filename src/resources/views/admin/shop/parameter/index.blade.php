@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <form name="parameterform" class="data-table-box" method="post" action="{{ route('admin.shop.ajax.parameter.fullsave') }}">
        @csrf
        <div class="grid-table-wrapper">
            <table class="grid-table parameter-table table-cols"
                style="--first-col:4rem; --last-col:7.5rem; --minw:50rem; --cols-count:8;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('elfcms::default.name') }}</th>
                        <th></th>
                        <th>{{ __('elfcms::default.code') }}</th>
                        <th>{{ __('shop::default.data_type') }}</th>
                        <th>{{ __('elfcms::default.description') }}</th>
                        <th>{{ __('shop::default.is_filter') }}</th>
                        <th>{{ __('elfcms::default.to_removal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @include('elfcms::admin.shop.parameter.content.list')
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
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]:not([data-loop])');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="parameterform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        async function getEmptyItem() {
            let response = await fetch('{{ route('admin.shop.ajax.parameter.empty-item') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getParameterList() {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route('admin.shop.ajax.parameter.list', true) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            unitListData = await response.json();
            return unitListData;
        }

        function showOptions(element) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            if (!element || !(element instanceof HTMLSelectElement)) {
                return false;
            }
            const row = element.closest('tr[data-id="' + element.dataset.id + '"]');
            if (row) {
                const subrow = row.querySelector('.table-subrow');
                if (subrow) {
                    if (element.options[element.selectedIndex].dataset.code == 'list') {
                        subrow.classList.add('showed');
                    } else {
                        subrow.classList.remove('showed');
                    }
                }
            }
        }

        function addOption(button, isnew = true) {
            if (typeof button === 'string') {
                button = document.querySelector(button);
            }
            if (!button || !(button instanceof HTMLElement)) {
                return false;
            }
            let newparam = 'new';
            if (!isnew) {
                newparam = '';
            }
            const table = button.closest('.shop-option-table');
            //const parent = button.closest('tr[data-id]');
            if (table) {
                const box = table.querySelector('.shop-option-table-body');
                if (box) {
                    let i = 0;
                    const rows = box.querySelectorAll('.shop-option-table-row');
                    if (rows && rows.length && rows.length > 0) {
                        i = rows.length;
                    }
                    const rowString = `
                    <div class="shop-option-table-row">
                        <div class="shop-option-table-column">
                            <input type="text" name="${newparam}parameter[${button.dataset.id}][options][${i}][key]" value="" oninput="checkOptionChange(this)" data-loop="${i}" data-name="key">
                        </div>
                        <div class="shop-option-table-column">
                            <input type="text" name="${newparam}parameter[${button.dataset.id}][options][${i}][value]" value="" oninput="checkOptionChange(this)" data-loop="${i}" data-name="value">
                        </div>
                        <div class="shop-option-table-column">
                            <div class="small-checkbox" style="--switch-color:var(--danger-color)">
                                <input type="checkbox" name="${newparam}parameter[${button.dataset.id}][options][${i}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="${i}" data-name="delete">
                                <i></i>
                            </div>
                        </div>
                    </div>
                    `;
                    box.insertAdjacentHTML('beforeend', rowString);
                }
            }
        }

        let startPreload = preloadSet('form[name="parameterform"]');

        let dataLoadInterval = setInterval(() => {
            if (typeof unitListData === 'object') {
                for (let key in unitListData.data) {
                    let subdata = {};
                    for (let subkey in unitListData.data[key]) {
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
                input.addEventListener('input', function() {
                    checkParamChange(this);
                });
                /* input.addEventListener('change',function(){
                    checkUnitChange(this);
                }); */
            });
        }

        if (addButton) {
            addButton.addEventListener('click', addParameterItem);
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

            });
        }

        if (saveButton) {
            saveButton.addEventListener('click', function(e) {
                e.preventDefault();
                popup({
                    title: '{{ __('shop::default.are_you_sure') }}',
                    content: '{{ __('elfcms::default.are_you_sure_you_want_to_save_the_changes') }}',
                    buttons: [{
                            title: 'OK',
                            class: 'button color-text-button info-button',
                            callback: [
                                saveForm,
                                'close'
                            ]
                        },
                        {
                            title: '{{ __('elfcms::default.cancel') }}',
                            class: 'button color-text-button',
                            callback: 'close'
                        }
                    ],
                    class: 'alternate'
                });

                function saveForm() {
                    const formData = new FormData(form);
                    const itemsBox = form.querySelector('tbody');
                    let preloader = preloadSet('.big-container');
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: formData
                    }).then(
                        (result) => result.json()
                    ).then(
                        (data) => {
                            if (data.result && data.result == 'success') {
                                itemsBox.innerHTML = '';
                                itemsBox.insertAdjacentHTML('beforeend', data.data);
                                const reInputs = itemsBox.querySelectorAll('tr[data-id] td [data-name]');
                                if (reInputs) {
                                    reInputs.forEach(input => {
                                        input.addEventListener('input', function() {
                                            checkParamChange(this);
                                        });
                                        /* input.addEventListener('change',function(){
                                            checkUnitChange(this);
                                        }); */
                                        autoSlug('.autoslug');
                                    });
                                }
                                popup({
                                    title: '{{ __('shop::default.done') }}',
                                    content: data.message,
                                    buttons: [{
                                        title: 'OK',
                                        class: 'button color-text-button info-button',
                                        callback: 'close'
                                    }],
                                    class: 'alternate'
                                });
                            } else {
                                if ((data.error || (data.result && data.result == 'error'))) {
                                    if (!data.message) {
                                        data.message = '{{ __('shop::default.error') }}';
                                    }
                                    popup({
                                        title: '{{ __('shop::default.error') }}',
                                        content: data.message,
                                        buttons: [{
                                            title: 'OK',
                                            class: 'button color-text-button danger-button',
                                            callback: 'close'
                                        }],
                                        class: 'danger'
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

        getParameterList();
        getEmptyItem();
        autoSlug('.autoslug');
    </script>

@endsection
