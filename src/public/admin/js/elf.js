function setCheckClosePage(check=true) {
    if (check) {
        window.addEventListener('beforeunload',checkClosePage);
    }
    else {
        window.removeEventListener('beforeunload',checkClosePage);
    }
}

function checkClosePage(e) {
    e.preventDefault();
    e.returnValue = '';
}

function setUnitRowDelete(th) {
    const row = th.closest('tr[data-id]');
    if (row) {
        if (th.checked) {
            row.classList.add('deletable');
        }
        else {
            row.classList.remove('deletable');
        }
        setSaveEnabled();
    }
}

function checkUnitChange(th) {
    const row = th.closest('tr[data-id]');
    console.log(row);
    if (row) {
        const id = row.dataset.id;
        const name = th.dataset.name;
        const editInput = row.querySelector('input[name="unit['+id+'][edited]"]');
        if (id && name) {
            let value = th.value;
            if (th.type == 'checkbox') {
                if (th.checked) {
                    value = 1;
                }
                else {
                    value = 0;
                }
            }
            if (value === '') {
                value = null;
            }
            controlData[id][name] = value;
            if (objectCompare(controlData[id],unitListData.data[id])) {
                row.classList.remove('edited');
                if (editInput) {
                    editInput.value="0";
                }
            }
            else {
                row.classList.add('edited');
                if (editInput) {
                    editInput.value="1";
                }
            }
        }
        setSaveEnabled();
    }
}

function checkParamChange(th,props=false) {
    const row = th.closest('tr[data-id]');
    if (row) {
        let inputName = 'parameter';
        if (props) {
            inputName = 'property';
        }
        const id = row.dataset.id;
        const name = th.dataset.name;
        const editInput = row.querySelector('input[name="'+inputName+'['+id+'][edited]"]');
        const optionDeleteInput = row.querySelector('.shop-option-table input[type="checkbox"]:checked');
        if (id && name) {
            let value = th.value;
            if (th.type == 'checkbox') {
                if (th.checked) {
                    value = 1;
                }
                else {
                    value = 0;
                }
            }
            if (value === '') {
                value = null;
            }
            controlData[id][name] = value;
            if (!optionDeleteInput && objectCompare(controlData[id],unitListData.data[id]) && objectCompare(controlData[id]['options'],unitListData.data[id]['options'])) {
                row.classList.remove('edited');
                if (editInput) {
                    editInput.value="0";
                }
            }
            else {
                row.classList.add('edited');
                if (editInput) {
                    editInput.value="1";
                }
            }
        }
        setSaveEnabled();
    }
}

function checkOptionChange(th) {
    const row = th.closest('tr[data-id]');
    if (row) {
        const id = row.dataset.id;
        const editInput = row.querySelector('input[name="unit['+id+'][edited]"]');
        const inputs = row.querySelectorAll('.shop-option-table-column input');
        if (!id || ! inputs) {
            return false;
        }
        let isDelete = false;
        let rows = [];
        let options = {};
        inputs.forEach((input, i) => {
            if (input.dataset.name == 'delete') {
                if (input.checked) {
                    isDelete = true;
                }
            }
            else {
                if (!rows[input.dataset.loop]) {
                    rows[input.dataset.loop] = [];
                }
                rows[input.dataset.loop][input.dataset.name] = input.value;
            }
        });

        rows.forEach(element => {
            options[element.key] = element.value;
        });

        controlData[id]['options'] = options;

        if (!isDelete && objectCompare(controlData[id]['options'],unitListData.data[id]['options']) && objectCompare(controlData[id],unitListData.data[id])) {
            row.classList.remove('edited');
            if (editInput) {
                editInput.value="0";
            }
        }
        else {
            row.classList.add('edited');
            if (editInput) {
                editInput.value="1";
            }
        }
        setSaveEnabled();
    }
}

function addUnitItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector('table.unit-table tbody');
    if (container) {
        let itemString = emptyItem.replaceAll('id="newunit"','id="new_'+newItemId+'"').replaceAll('unit[newunit]','newunit['+newItemId+']').replaceAll('unit_newunit','newunit_'+newItemId).replaceAll('<span>newunit</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
        setSaveEnabled();
    }
}

function addParameterItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector('table.parameter-table tbody');
    if (container) {
        console.log(emptyItem);
        let itemString = emptyItem.replaceAll('btn" data-id="newparameter"','btn" data-id="'+newItemId+'"').replaceAll('id="newparameter"','id="new_'+newItemId+'"').replaceAll('parameter[newparameter]','newparameter['+newItemId+']').replaceAll('parameter_newparameter','newparameter_'+newItemId).replaceAll('<span>newparameter</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
        autoSlug(newRow.querySelectorAll('.autoslug'));
        setSaveEnabled();
    }
}

function addPropertyItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector('table.property-table tbody');
    if (container) {
        let itemString = emptyItem.replaceAll('btn" data-id="newproperty"','btn" data-id="'+newItemId+'"').replaceAll('id="newproperty"','id="new_'+newItemId+'"').replaceAll('property[newproperty]','newproperty['+newItemId+']').replaceAll('property_newproperty','newproperty_'+newItemId).replaceAll('<span>newproperty</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
        autoSlug(newRow.querySelectorAll('.autoslug'));
        setSaveEnabled();
    }
}

function addCurrencyItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector('table.currency-table tbody');
    if (container) {
        let itemString = emptyItem.replaceAll('id="newcurrency"','id="new_'+newItemId+'"').replaceAll('currency[newcurrency]','newcurrency['+newItemId+']').replaceAll('currency_newcurrency','newcurrency_'+newItemId).replaceAll('<span>newcurrency</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
        autoSlug(newRow.querySelectorAll('.autoslug'));
        setSaveEnabled();
    }
}

function isEditedUnits() {
    const editedRows = document.querySelectorAll('tr[data-id].edited');
    if (editedRows && editedRows.length) {
        return true;
    }
    return false;
}

function isDeletableUnits() {
    const editedRows = document.querySelectorAll('tr[data-id].deletable');
    if (editedRows && editedRows.length) {
        return true;
    }
    return false;
}

function setSaveEnabled() {
    const saveButton = document.querySelector('button[data-action="save"]');
    if (saveButton) {
        if (isDeletableUnits() || isEditedUnits()) {
            saveButton.disabled = false;
        }
        else {
            saveButton.disabled = true;
        }
    }
}

function objectCompare(obj1, obj2) {
    if (typeof obj1 != 'object' || typeof obj2 != 'object') {
        return null
    }
    for (let key in obj1) {
        if (typeof obj2[key] !== 'object' || typeof obj1[key] !== 'object') {
            //return objectCompare(obj1[key],obj2[key]);
            if ((!obj2[key] && obj1[key]) || (obj2[key] && !obj1[key]) || obj1[key] != obj2[key]) {
                return false;
            }
        }
    }
    return true;
}



function openPickerColor(button, container = document)  {
    if (typeof button === 'string') {
        button = container.querySelector(button);
    }
    if (!button || !(button instanceof HTMLElement)) {
        return false;
    }
    const box = button.closest('.shop-color-picker-box');
    if (box) {
        const list = box.querySelector('.shop-color-picker-list');
        if (list) {
            list.classList.remove('closed');
        }
    }
}

function closePickerColor(button, container = document) {
    if (typeof button === 'string') {
        button = container.querySelector(button);
    }
    if (!button || !(button instanceof HTMLElement)) {
        return false;
    }
    const list = button.closest('.shop-color-picker-list');
    if (list) {
        list.classList.add('closed');
    }
}

function setPickerColor(item, container = document) {
    if (typeof item === 'string') {
        item = container.querySelector(item);
    }
    if (!item || !(item instanceof HTMLInputElement)) {
        return false;
    }
    const parent = item.closest('.shop-color-picker-item');
    if (parent) {
        const box = parent.closest('.shop-color-picker-box');
        const list = box.querySelector('.shop-color-picker-list');
        if (list) {
            list.classList.add('closed');
        }
        if (box) {
            const button = box.querySelector('.shop-color-picker-button');
            if (button) {
                const clone = parent.cloneNode(true);
                if (clone) {
                    const inp = clone.querySelector('input[type="radio"]');
                    if (inp) {
                        inp.remove();
                    }
                    button.innerHTML = '';
                    button.append(clone);
                }
            }
        }
    }
}
