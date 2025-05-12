<form action="{{ route('admin.shop.items.pictures.groupSave',$item) }}" method="POST" name="shop-item-picture-list" id="shop-item-picture-list">
    @method('POST')
    @csrf
    <div class="shop-item-pictures-content dragndrop-wrapper" draggable="false" data-uploadtext="{{__('shop::default.file_upload')}}">
        <a href="{{route('admin.shop.items.pictures.create',$item)}}" class="shop-item-picture-add shop-item-picture-tile" id="shopitempicturecreate" title="{{__('shop::default.create_item')}}"></a>
        @if (!empty($item->pictures))
        @foreach ($item->pictures as $picture)
            @include('elfcms::admin.shop.item_pictures.content.item')
        @endforeach
        @endif
    </div>
    <div class="shop-item-pictures-buttons">
        <button class="button color-text-button submit-button" disabled style="--button-color:var(--default-color,var(--success-color))">{{ __('elfcms::default.save') }}</button>
    </div>
</form>


<script>
const submitItems = document.querySelector('.shop-item-pictures-buttons .submit-button');
const editItemElements = document.querySelectorAll('.shop-item-picture-element');
const createButton = document.querySelector('#shopitempicturecreate');
const dragndropBox = document.querySelector('.dragndrop-wrapper');
const itemListForm = document.querySelector('#shop-item-picture-list');

if (itemListForm) {
    itemListForm.addEventListener('submit',function(e){
        e.preventDefault();
        return false;
    });
}

if (submitItems) {
    submitItems.addEventListener('click',function(e){
        e.preventDefault();
        let form = itemListForm;
        if (!form) {
            form = this.closest('form');
        }
        if (!form) {
            return false;
        }
        const itemsForDelete = document.querySelectorAll('.fordelete');
        if (itemsForDelete && itemsForDelete.length > 0) {
            popup({
                title:'{{__("shop::default.deleting_items")}}',
                content:'{{__("shop::default.marked_items_will_be_removed")}}',
                buttons:[
                    {
                        title:'{{__("elfcms::default.delete")}}',
                        class:'button color-text-button danger-button',
                        callback: [
                            function(){
                                itemListSave (form);
                            },
                            'close'
                        ]
                    },
                    {
                        title:'{{__("elfcms::default.cancel")}}',
                        class:'button color-text-button',
                        callback:'close'
                    }
                ],
                class:'danger'
            });
        }
        else {
            itemListSave (form);
        }
    });
}

function preloadSet(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (!(element instanceof HTMLElement) && element !== document) {
        return false;
    }
    const preloader = document.createElement('div')
    preloader.classList.add('preload-wrapper');
    preloader.insertAdjacentHTML('beforeend','<div class="preload-box"><div></div><div></div><div></div></div>');
    element.append(preloader);

    return preloader;
}

function preloadUnset(preloader) {
    if (typeof preloader === 'string') {
        preloader = document.querySelector(preloader);
    }
    if (!(preloader instanceof HTMLElement)) {
        return false;
    }
    preloader.remove();
}

function itemListSave(form) {
    if (!form || !form.action) {
        return false;
    }
    const formData = new FormData(form);
    const itemsBox = document.querySelector('.shop-item-pictures-content');
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
                const forDeletes = document.querySelectorAll('.shop-item-picture-element.fordelete');
                if (forDeletes) {
                    forDeletes.forEach(delElem => {
                        delElem.remove();
                    });
                }
                submitItems.disabled = true;
                popup({
                    title:'{{__("shop::default.edit_item")}}',
                    content:'{{__("shop::default.item_edited_successfully")}}',
                    buttons:[
                        {
                            title:'OK',
                            class:'button color-text-button',
                            callback:'close'
                        }
                    ],
                    class:'submit'
                });
            }
            else {
                if (data.errors && data.message) {
                    popup({
                        title:'{{__("shop::default.error")}}',
                        content:data.message,
                        buttons:[
                            {
                                title:'OK',
                                class:'button color-text-button',
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

function filesUpload (files) {
    if (!files) {
        return false;
    }

    let fileNames = [];
    let n = 0;
    for(key in files) {
        if (files[key] instanceof File) {
            fileNames[n] = files[key].name;
            n++;
        }
    };
    if (n > 0) {
        popup({
            title:'&nbsp;',
            content:'{{__("shop::default.following_files_will_be_downloaded")}}: <br><br>' + fileNames.join('<br>') + '<p>{{__("shop::default.are_you_sure")}}</p>',
            buttons:[
                {
                    title:'{{__("elfcms::default.cancel")}}',
                    class:'button color-text-button',
                    callback:'close'
                },
                {
                    title:'OK',
                    class:'button color-text-button',
                    callback: [
                        function(){
                            for(key in files) {
                                if (files[key] instanceof File) {
                                    fileUpload(files[key],key);
                                }
                            };
                        },
                        'close'
                    ]
                }
            ],
            class:'alternate'
        });
    }
}

function createLoder () {
    const box = document.createElement('div');
    box.classList.add('item-loader-box');
    const counter = document.createElement('div');
    counter.classList.add('item-loader-counter');
    const bar = document.createElement('div');
    bar.classList.add('item-loader-bar');
    const progress = document.createElement('div');
    bar.append(progress);
    box.append(counter, bar);
    return {
        box: box,
        counter: counter,
        bar: bar,
        progress: progress
    }
}

function fileUpload (file, key = null) {
    if (!(file instanceof File)) {
        return false;
    }

    const item = createItem({
        id: key,
        name: file.name,
        position: 1000,
        image: '/elfcms/admin/modules/shop/images/empty.png',
    },true);

    item.href = 'javascript:void(0)';

    dragndropBox.append(item);

    const loader = createLoder();

    item.append(loader.box);

    const formData = new FormData();
    formData.append('image', file, file.name);
    formData.append('_token','{{csrf_token()}}');
    formData.append('_method','POST');
    formData.append('active','1');
    eajax('{{route("admin.shop.items.pictures.store",$item)}}',{
        method: 'post',
        formData: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        resolve: function(result) {
            let answer = JSON.parse(result.response);
            if (result.status > 200) {
                let message = '';
                if (result.status == 422) {
                    for(key in answer) {
                        message += answer[key].join(" ");
                    }
                }
                else if (result.status == 500) {
                    message = answer.message;
                }
                else {
                    message = 'Error ' + result.status;
                }
                popup({
                title: 'Error ' + result.status,
                content: message,
                buttons:[
                    {
                        title:'OK',
                        class:'button color-text-button',
                        callback:[
                            function(){
                                item.remove();
                            },
                            'close'
                        ]
                    }
                ],
                class:'danger'
            });
            }
            else if (answer && answer.result && answer.result == 'success') {
                setItemData(item, answer.data, true, function(){
                    if (loader.box) {
                        loader.box.remove();
                    }
                });
            }
            else {
                loader.counter.innerHTML = 'Error';
            }
        },
        progress: function(e) {
            if (e.lengthComputable) {
                let percent = Math.round(e.loaded / e.total * 100) + '%';
                loader.counter.innerHTML = percent;
                loader.progress.style.width = percent;
            }
        },
        uploadResolve: null,
        errorIgnore: true
    });
}

function setItemData (item, data, empty=false, callback=null) {
    if (typeof item === 'string') {
        item = document.querySelector(item);
    }
    if (!(item instanceof HTMLElement)) {
        return false;
    }

    item.href = '/admin/shop/items/{{$item->id}}/pictures/'+data.id+'/edit';
    item.dataset.id = data.id;
    item.style.order = data.position;
    item.title = '__("elfcms::default.edit") ' + data.name;
    const img = item.querySelector('img');
    if (img) {
        if (data.thumbnail) {
            img.src = data.thumbnail;
        }
        else if (data.preview) {
            img.src = data.preview;
        }
        else {
            img.src = data.image;
        }
    }
    if (empty) {
        const deleteBox = document.createElement('div');
        deleteBox.classList.add('delete-item-box');
        deleteBox.title = '{{__("elfcms::default.delete")}}'
        const deleteInp = document.createElement('input');
        deleteInp.type = 'checkbox';
        deleteInp.name = `item[${data.id}][delete]`;
        deleteInp.id = `item_${data.id}_delete`;
        deleteInp.dataset.field = 'delete';
        deleteInp.addEventListener('click',function(e){
            e.stopPropagation();
            submitItems.disabled = false;
            if (this.checked) {
                item.classList.add('fordelete')
            }
            else {
                item.classList.remove('fordelete')
            }
        });
        deleteBox.append(deleteInp);
        deleteBox.insertAdjacentHTML('beforeend','<i></i>');
        item.append(deleteBox);
        item.addEventListener('click',function(e){
            e.preventDefault();
            editItem(item.href,item);
        });
        item.insertAdjacentHTML('beforeend',`<input type="hidden" name="item[${data.id}][position]" value="${data.position}" data-field="position">`);
    }
    else {
        const deleteInp = item.querySelector('.delete-item-box input[type="checkbox"]');
        deleteInp.type = 'checkbox';
        deleteInp.name = `item[${data.id}][delete]`;
        deleteInp.id = `item_${data.id}_delete`;
    }
    if (callback && typeof callback === 'function') {
        callback();
    }
}

function createItem (data, empty = false) {
    const newItem = document.createElement('a');
    newItem.href = '/admin/shop/items/{{$item->id}}/pictures/'+data.id+'/edit';
    newItem.classList.add('shop-item-picture-tile','shop-item-picture-element');
    newItem.dataset.id = data.id;
    newItem.style.order = data.position;
    //newItem.title = '__("elfcms::default.edit") ' + data.name;
    newItem.draggable = true;
    const img = document.createElement('img');
    if (img) {
        if (data.thumbnail) {
            img.src = data.thumbnail;
        }
        else if (data.preview) {
            img.src = data.preview;
        }
        else {
            img.src = data.image;
        }
        newItem.append(img)
    }

    if (!empty) {
        const deleteBox = document.createElement('div');
        deleteBox.classList.add('delete-item-box');
        deleteBox.title = '{{__("elfcms::default.delete")}}'
        const deleteInp = document.createElement('input');
        deleteInp.type = 'checkbox';
        deleteInp.name = `item[${data.id}][delete]`;
        deleteInp.id = `item_${data.id}_delete`;
        deleteInp.dataset.field = 'delete';
        deleteInp.addEventListener('click',function(e){
            e.stopPropagation();
            submitItems.disabled = false;
            if (this.checked) {
                newItem.classList.add('fordelete')
            }
            else {
                newItem.classList.remove('fordelete')
            }
        });
        deleteBox.append(deleteInp);
        deleteBox.insertAdjacentHTML('beforeend','<i></i>');
        newItem.append(deleteBox);
        newItem.addEventListener('click',function(e){
            e.preventDefault();
            editItem(newItem.href,newItem);
        });
        newItem.insertAdjacentHTML('beforeend',`<input type="hidden" name="item[${data.id}][position]" value="${data.position}" data-field="position">`);
    }

    return newItem;
}

function dndInit(element) {

    let isMoved = false;

    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (!element) {
        return false;
    }
    element.addEventListener('dragstart', function (e) {
        e.target.classList.add('dragged');
    });
    element.addEventListener('dragleave', function (e) {
        element.classList.remove('filedrag');
    });

    element.addEventListener('drop',function(e){
        e.preventDefault();
        element.classList.remove('filedrag');
        if (e.target === element && e.dataTransfer.files && e.dataTransfer.files.length) {
            filesUpload(e.dataTransfer.files);
        }
    });

    element.addEventListener('dragend', function (e) {
        e.preventDefault();
        element.classList.remove('filedrag');
        e.target.classList.remove('dragged');
        const items = element.querySelectorAll('.shop-item-picture-element');
        if (items && isMoved) {
            submitItems.disabled = false;
            let i = 1;
            items.forEach(item => {
                item.style.order = i;
                let positionInput = item.querySelector('input[data-field="position"]');
                if (positionInput) {
                    positionInput.value = i;
                }
                i++;
            });
        }
    });

    const getNextElement = (cursorPosition, currentElement) => {
        const currentElementCoord = currentElement.getBoundingClientRect();
        const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

        const nextElement = (cursorPosition < currentElementCenter) ?
            currentElement :
            currentElement.nextElementSibling;

        return nextElement;
    };

    element.addEventListener('dragover', function (e) {
        e.preventDefault();

        if (e.dataTransfer.types && e.dataTransfer.types.includes('Files')) {
            element.classList.add('filedrag');
        }
        else {
            element.classList.remove('filedrag');
        }

        const activeElement = element.querySelector('.dragged');

        if (!activeElement) {
            return false;
        }

        const currentElement = e.target;
        let isDraggable = activeElement !== currentElement &&
        currentElement.classList.contains('shop-item-picture-element');

        if (!isDraggable) {
            return false;
        }

        let insertAfter = false;

        const currentElementCoord = currentElement.getBoundingClientRect();
        const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

        if (e.clientX > currentElementCenter) {
            insertAfter = true;
        }

        const nextElement = getNextElement(e.clientX, currentElement);

        if (
            nextElement &&
            activeElement === nextElement.previousElementSibling ||
            activeElement === nextElement
        ) {
            return false;
        }

        isMoved = true;

        if (insertAfter) {
            if (currentElement.nextElementSibling) {
                activeElement.style.order = currentElement.nextElementSibling.style.order;
                element.insertBefore(activeElement, currentElement.nextElementSibling);
            }
            else {
                activeElement.style.order = Number.parseInt(currentElement.style.order) + 1
                element.append(activeElement);
            }
        }
        else {
            activeElement.style.order = currentElement.style.order
            element.insertBefore(activeElement, currentElement);
        }

    });
}

function editItem(action,currentItem,isEdit=true){
    const createBoxWrapper = document.createElement('div');
    createBoxWrapper.classList.add('shop-item-picture-create-popup-wrapper');
    const createBox = document.createElement('div');
    createBox.classList.add('shop-item-picture-create-popup-box');
    createBoxWrapper.append(createBox);
    const closeBox = document.createElement('a');
    closeBox.classList.add('shop-item-picture-create-popup-close');
    closeBox.title = '{{__("elfcms::default.cancel")}}';
    closeBox.addEventListener('click',function(e){
        e.preventDefault();
        createBoxWrapper.innerHTML = '';
        createBoxWrapper.remove();
    });
    createBox.append(closeBox);
    document.body.append(createBoxWrapper);
    fetch(action,{
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        credentials: 'same-origin',
    }).then(
        (result) => result.text()
    ).then (
        (data) => {
            if (data) {
                createBox.insertAdjacentHTML('afterbegin',data);
                const createForm = createBox.querySelector('form');
                if (createForm) {
                    const previewInput = createForm.querySelector('input[type="file"][name="preview"]')
                    if (previewInput) {
                        //inputFileImg(previewInput)
                        previewBox = previewInput.closest('.inputfile');
                        if (previewBox) {
                            imageInit(previewBox);
                        }
                    }
                    const imageInput = createForm.querySelector('input[type="file"][name="image"]')
                    if (imageInput) {
                        //inputFileImg(imageInput)
                        imageBox = imageInput.closest('.inputfile');
                        if (imageBox) {
                            imageInit(imageBox);
                        }
                    }
                    const thumbnailInput = createForm.querySelector('input[type="file"][name="thumbnail"]')
                    if (thumbnailInput) {
                        //inputFileImg(thumbnailInput)
                        thumbnailBox = thumbnailInput.closest('.inputfile');
                        if (thumbnailBox) {
                            imageInit(thumbnailBox);
                        }
                    }

                    const submitButton = createForm.querySelector('[type="submit"]');
                    const newSubmitButton = submitButton.cloneNode(true);
                    const submitButtonBox = submitButton.parentNode;
                    submitButtonBox.append(newSubmitButton);
                    submitButton.remove();
                    const infoMessageBox = document.createElement('div');
                    submitButtonBox.append(infoMessageBox);

                    function formSubmit () {
                        if (infoMessageBox) {
                            infoMessageBox.innerHTML = '';
                        }
                        const formData = new FormData(createForm);
                        fetch(createForm.action,{
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
                                if (data.result && data.result == 'success' && data.data) {
                                    const itemsBox = document.querySelector('.shop-item-pictures-content');
                                    if (isEdit) {
                                        currentItem.style.order = data.data.position;
                                        currentItem.title = '__("elfcms::default.edit") ' + data.data.name;

                                        const img = currentItem.querySelector('img');
                                        if (img) {
                                            if (data.data.defaultThumbnail) {
                                                img.src = (data.prefix ?? '') + '/' + data.data.defaultThumbnail;
                                            }
                                            else if (data.data.defaultPreview) {
                                                img.src = (data.prefix ?? '') + '/' + data.data.defaultPreview;
                                            }
                                            else if (data.data.thumbnail) {
                                                img.src = (data.prefix ?? '') + '/' + data.data.thumbnail;
                                            }
                                            else if (data.data.preview) {
                                                img.src = (data.prefix ?? '') + '/' + data.data.preview;
                                            }
                                            else {
                                                img.src = (data.prefix ?? '') + '/' + data.data.image;
                                            }
                                        }
                                        currentItem.href = '/admin/shop/items/{{$item->id}}/pictures/'+data.data.id+'/edit';
                                    }
                                    else if (itemsBox) {
                                        const newItem = document.createElement('a');
                                        newItem.href = '/admin/shop/items/{{$item->id}}/pictures/'+data.data.id+'/edit';
                                        newItem.classList.add('shop-item-picture-tile','shop-item-picture-element');
                                        newItem.dataset.id = data.data.id;
                                        newItem.style.order = data.data.position;
                                        newItem.title = '__("elfcms::default.edit") ' + data.data.name;
                                        newItem.draggable = true;
                                        const img = document.createElement('img');
                                        if (img) {
                                            img.src = data.data.image;
                                            newItem.append(img)
                                        }
                                        const deleteBox = document.createElement('div');
                                        deleteBox.classList.add('delete-item-box');
                                        deleteBox.title = '{{__("elfcms::default.delete")}}'
                                        const deleteInp = document.createElement('input');
                                        deleteInp.type = 'checkbox';
                                        deleteInp.name = `item[${data.data.id}][delete]`;
                                        deleteInp.id = `item_${data.data.id}_delete`;
                                        deleteInp.dataset.field = 'delete';
                                        deleteInp.addEventListener('click',function(e){
                                            e.stopPropagation();
                                            submitItems.disabled = false;
                                            if (this.checked) {
                                                newItem.classList.add('fordelete')
                                            }
                                            else {
                                                newItem.classList.remove('fordelete')
                                            }
                                        });
                                        deleteBox.append(deleteInp);
                                        deleteBox.insertAdjacentHTML('beforeend','<i></i>');
                                        newItem.append(deleteBox);
                                        newItem.addEventListener('click',function(e){
                                            e.preventDefault();
                                            editItem(newItem.href,newItem);
                                        });
                                        newItem.insertAdjacentHTML('beforeend',`<input type="hidden" name="item[${data.data.id}][position]" value="${data.data.position}" data-field="position">`);

                                        itemsBox.append(newItem);
                                    }
                                    createBoxWrapper.remove();
                                }
                                else {
                                    if ((data.errors || data.result == 'error') && data.message) {
                                        let errorString = '<div class="alert alert-danger">'+data.message+'</div>';
                                        infoMessageBox.insertAdjacentHTML('beforeend',errorString);
                                    }
                                }
                            }
                        ).catch(error => {
                            //
                        });
                    }
                    setTimeout (function(){
                        createForm.addEventListener('submit',function(e){
                            e.preventDefault();
                            formSubmit();
                        });
                        if (newSubmitButton) {
                            newSubmitButton.addEventListener('click',function(e){
                                e.preventDefault();
                                formSubmit();
                            });
                        }
                        //shopPicTagFormInit();
                    },500)
                }
            }
        }
    ).catch(error => {
        //
    });
}
// Edit item
if (editItemElements) {
    editItemElements.forEach(editElement => {
        editElement.draggable = true;
        editElement.addEventListener('click',function(e){
            e.preventDefault();
            editItem(this.href,this);
        });
        const deleteInput = editElement.querySelector('input[data-field="delete"]');
        if (deleteInput) {
            deleteInput.addEventListener('click',function(e){
                e.stopPropagation();
                submitItems.disabled = false;
                if (this.checked) {
                    editElement.classList.add('fordelete')
                }
                else {
                    editElement.classList.remove('fordelete')
                }
            });
        }
    });
}
// Create item
if (createButton) {
    createButton.addEventListener('click',function(e){
        e.preventDefault();
        editItem(this.href,this,false);
    });
}

dndInit(dragndropBox);

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
