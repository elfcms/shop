@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <form name="colorform" class="data-table-box" method="post" action="{{ route('admin.shop.ajax.color.fullsave') }}">
        @csrf
        <div class="grid-table-wrapper">
            <table class="grid-table color-table itemstable table-cols"
                style="--first-col:4rem; --last-col:7.5rem; --minw:50rem; --cols-count:5;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('elfcms::default.name') }}</th>
                        <th>{{ __('elfcms::default.code') }}</th>
                        <th>{{ __('shop::default.image') }}</th>
                        {{-- <th>{{ __('shop::default.default') }}</th> --}}
                        <th>{{ __('elfcms::default.to_removal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @include('elfcms::admin.shop.color.content.list')
                </tbody>
            </table>
        </div>
        <div class="dynamic-table-buttons">
            <button class="button round-button theme-button" title="{{ __('shop::default.add') }}" data-action="additem">
                {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
                <span>{{ __('shop::default.add') }}</span>
            </button>
            <button type="submit" class="button color-text-button success-button" disabled=""
                data-action="save">{{ __('elfcms::default.save') }}</button>
        </div>
    </form>
    <script>
        const colorPointers = document.querySelectorAll('.color-pointer-wrapper');
        const colorPictureBoxes = document.querySelectorAll('.inputfile');
        const inputs = document.querySelectorAll('tr[data-id] td [data-name]');
        const addButton = document.querySelector('button[data-action="additem"]');
        const saveButton = document.querySelector('button[data-action="save"]');
        const form = document.querySelector('form[name="colorform"]');
        let emptyItem;
        let unitListData;
        let controlData = {};
        let newItemId = 0;

        function setPictureButton(element) {
            if (typeof element == 'string') {
                element = document.querySelector(element);
            }
            if (!(element instanceof HTMLElement)) {
                return false;
            }
            inputFileImg(element);
            const delButton = element.closest('.image-button').querySelector('.delete-image');
            if (delButton) {
                delButton.addEventListener('click', function() {
                    checkUnitChange(element);
                });
            }
        }


        if (colorPictureBoxes) {
            [].forEach.call(colorPictureBoxes, function(box) {
                imageInit(box);
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
                text.addEventListener('input', function() {
                    if (this.value.match(/#[a-f0-9]{6}\b/gi)) {
                        color.value = this.value;
                    }
                });
                color.addEventListener('input', function() {
                    text.value = this.value;
                });
            }
        }

        if (colorPointers) {
            /* colorPointers.forEach(wrapper => {
                setColorPoint(wrapper);
            }); */
            /* [].forEach.call(colorPointers, function(wrapper) {
                setColorPoint(wrapper);
            }); */
        }

        async function getEmptyItem() {
            let response = await fetch('{{ route('admin.shop.ajax.color.empty-item') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            emptyItem = await response.text();

            return emptyItem;
        }

        async function getColorList() {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route('admin.shop.ajax.color.list', true) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            unitListData = await response.json();
            return unitListData;
        }

        let startPreload = preloadSet('form[name="colorform"]');

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
            /* inputs.forEach(input => {
                input.addEventListener('input', function() {
                    checkUnitChange(this);
                });
            }); */

            [].forEach.call(inputs, function(input) {
                input.addEventListener('input', function() {
                    checkUnitChange(this);
                });
                /* input.addEventListener('change',function(){
                    checkUnitChange(this);
                }); */
            });
        }

        /* document.addEventListener('initInputFile', function(e) {
            const targetId = e.detail?.targetId || '#inputfile_default';
            inputfileInit(targetId);
        }); */

        function addColorItem() {
            if (!emptyItem) return false;
            if (!newItemId && newItemId !== 0) return false;
            const container = document.querySelector('table.color-table tbody');
            if (container) {
                let itemString = emptyItem.replaceAll('id="newcolor"', 'id="new_' + newItemId + '"').replaceAll(
                    'color[newcolor]', 'newcolor[' + newItemId + ']').replaceAll('color_newcolor', 'newcolor_' +
                    newItemId).replaceAll('<span>newcolor</span>', '');
                container.insertAdjacentHTML('beforeend', itemString);
                const newRow = container.lastElementChild;
                if (newRow) {
                    newRow.classList.add('edited');
                    const colors = newRow.querySelectorAll('.color-pointer-wrapper');
                    if (colors) {
                        colors.forEach(wrapper => {
                            //setColorPoint(wrapper);
                        });
                    }
                    const files = newRow.querySelectorAll('input[type="file"]');
                    if (files) {
                        [].forEach.call(files, function(file) {
                            const picBox = file.closest('.inputfile');
                            if (picBox) {
                                picBox.id = 'inputfile_' + uniqid();
                                setTimeout(() => {
                                    imageInit(picBox);
                                }, 200);
                            }
                        });
                    }
                }
                newItemId++;
                autoSlug(newRow.querySelectorAll('.autoslug'));
                setSaveEnabled();
            }
        }

        if (addButton) {
            addButton.addEventListener('click', addColorItem);
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
                                emptyNamesCheck,
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

                // Check to empty names
                function emptyNamesCheck() {
                    const newRows = document.querySelectorAll('tr.edited');
                    let emptyNames = 0;
                    if (newRows) {
                        [].forEach.call(newRows, function(row) {
                            const nameInput = row.querySelector('[name^="newcolor"][name*="name"]');
                            const pictureInput = row.querySelector('[name^="newcolor"][type="file"]');
                            if (nameInput && pictureInput) {
                                if (!nameInput.value.length && pictureInput.value.length) {
                                    emptyNames++;
                                }
                            }
                        });
                    }
                    if (emptyNames > 0) {
                        popup({
                            title: '{{ __('shop::default.are_you_sure') }}',
                            content: '{{ __('shop::default.rows_with_empty_names_will_not_be_saved') }}',
                            buttons: [{
                                    title: 'OK',
                                    class: 'button color-text-button danger-button',
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
                            class: 'danger'
                        });
                    }
                    else {
                        saveForm();
                    }
                }

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
                                const fileInputs = itemsBox.querySelectorAll(
                                    'tr[data-id] td input[type="file"]');
                                if (reInputs) {

                                    [].forEach.call(reInputs, function(reInputs) {
                                        input.addEventListener('input', function() {
                                            checkUnitChange(this);
                                        });
                                        autoSlug('.autoslug');
                                    });
                                }
                                if (fileInputs) {
                                    [].forEach.call(fileInputs, function(fileInput) {
                                        const picBox = fileInput.closest('.inputfile');
                                        if (picBox) {
                                            picBox.id = 'inputfile_' + uniqid();
                                            setTimeout(() => {
                                                imageInit(picBox);
                                            }, 200);
                                        }
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
                                saveButton.disabled = true;
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

        getColorList();
        getEmptyItem();
        autoSlug('.autoslug');

        function imageInit(inputfileBox) {
            let box = null;
            if (typeof inputfileBox == 'string') {
                box = document.querySelector(inputfileBox);
            } else {
                box = inputfileBox;
            }
            if (!box || !(box instanceof HTMLElement)) {
                return;
            }
            const inputfile = box.querySelector('input[type="file"]');
            const inputfileDelete = box.querySelector('.inputfile-delete');
            const inputfileIcon = box.querySelector('.inputfile-icon');
            const inputfileTitle = box.querySelector('.inputfile-title');
            const inputfileButtons = box.querySelector('.inputfile-buttons');
            const inputfileHidden = box.querySelector('input[type="hidden"]');

            inputfileDelete.addEventListener('click', () => {
                inputfileHidden.value = null;
                inputfileButtons.classList.add('hidden');
                inputfileIcon.innerHTML = '';
                inputfileIcon.insertAdjacentHTML('beforeend', `{!! iconHtmlLocal('/elfcms/admin/images/icons/upload.svg', svg: true) !!}`);
                inputfileTitle.textContent = `{{ __('elfcms::default.choose_file') }}`;
            });

            inputfile.addEventListener('change', function(e) {
                const files = e.target.files
                if (files) {
                    if (inputfileTitle) {
                        inputfileTitle.textContent = files[0].name
                    }
                    if (FileReader && files && files.length) {
                        var fReader = new FileReader();
                        fReader.onload = function() {
                            if (inputfileIcon) {
                                let type = files[0].type.split('/')[1];
                                if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'svg+xml', 'webp', 'ico',
                                        'vnd.microsoft.icon'
                                    ].includes(
                                        type)) {
                                    const img = document.createElement('img')
                                    img.src = fReader.result;
                                    inputfileIcon.innerHTML = '';
                                    inputfileIcon.appendChild(img);
                                } else {
                                    fetch(adminPath + '/helper/file-icon-data/' + (type ?? 'any'), {
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then((response) => {
                                            return response.text();
                                        })
                                        .then((data) => {
                                            inputfileIcon.innerHTML = '';
                                            inputfileIcon.insertAdjacentHTML('beforeend', data);
                                        });
                                }
                                inputfileHidden.value = null;
                                inputfileButtons.classList.remove('hidden');
                            }
                        };
                        fReader.readAsDataURL(files[0]);
                    }
                }
            })
        }
    </script>
@endsection
