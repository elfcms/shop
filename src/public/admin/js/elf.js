console.log('shop init')

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
    const row = th.closest('tr[data-id]'); //document.querySelector('tr[data-id="' + id + '"]');
    if (row) {
        if (th.checked) {
            row.classList.add('deletable');
        }
        else {
            row.classList.remove('deletable');
        }
    }
}

function addUnitItem(emptyItem = null) {
    const container = document.querySelector('table.unit-table tbody');
    if (container) {
        let itemString = emptyItem.replaceAll('id="newunit"','id="new_'+newItemId+'"').replaceAll('unit[newunit]','newunit['+newItemId+']').replaceAll('unit_newunit','newunit_'+newItemId).replaceAll('<span>newunit</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
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
