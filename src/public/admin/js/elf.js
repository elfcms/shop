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
        let itemString = emptyItem.replaceAll('id="newparameter"','id="new_'+newItemId+'"').replaceAll('parameter[newparameter]','newparameter['+newItemId+']').replaceAll('parameter_newparameter','newparameter_'+newItemId).replaceAll('<span>newparameter</span>','');
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
        let itemString = emptyItem.replaceAll('id="newproperty"','id="new_'+newItemId+'"').replaceAll('property[newproperty]','newproperty['+newItemId+']').replaceAll('property_newproperty','newproperty_'+newItemId).replaceAll('<span>newproperty</span>','');
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
        if ((!obj2[key] && obj1[key]) || (obj2[key] && !obj1[key]) || obj1[key] != obj2[key]) {
            return false;
        }
    }
    return true;
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
