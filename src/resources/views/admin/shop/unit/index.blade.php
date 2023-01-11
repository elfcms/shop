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
    <div class="data-table-box">
    <div class="widetable-wrapper">
        <table class="grid-table unit-table">
            <thead>
                <tr>
                    <th>
                        ID
                        {{-- <a href="{{ route('admin.shop.unit',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                    </th>
                    <th>
                        {{ __('basic::elf.name') }}
                        {{-- <a href="{{ route('admin.shop.unit',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                    </th>{{--
                    <th>
                        {{ __('basic::elf.created') }}
                        <a href="{{ route('admin.shop.unit',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.updated') }}
                        <a href="{{ route('admin.shop.unit',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th> --}}
                    <th>{{ __('basic::elf.abbr') }}</th>
                    <th>{{ __('basic::elf.step') }}</th>
                    <th>{{ __('basic::elf.max') }}</th>
                    <th>{{ __('basic::elf.min') }}</th>
                    <th>{{ __('basic::elf.name_plural') }}</th>
                    <th>{{ __('basic::elf.lang_name') }}</th>
                    <th>{{ __('basic::elf.lang_name_plural') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @include('shop::admin.shop.unit.content.list')
            </tbody>
        </table>
    </div>
    <div class="shop-table-buttons">
        <button class="default-btn alternate-button" title="{{__('shop::elf.add_unit_of_measurement')}}" data-action="additem">{{ __('shop::elf.add') }}</button>
        <button class="default-btn submit-button" disabled="">{{ __('basic::elf.save') }}</button>
    </div>
</div>
    <script>
        let emptyItem;
        async function getEmptyItem() {
            /* if (emptyItem !== null && emptyItem !== '') {
                return emptyItem;
            } */
            let response = await fetch('{{ route("admin.shop.ajax.unit.empty-item") }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            emptyItem = await response.text();

            return emptyItem;
        }
        getEmptyItem();

        let unitListData;
        async function getUnitList () {
            if (unitListData !== null && typeof unitListData == 'object') {
                return unitListData;
            }
            let response = await fetch('{{ route("admin.shop.ajax.unit.list",true) }}',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
            unitListData = await response.json();
            return unitListData;
        }
        getUnitList();

        let controlData = {};

        //let editedFields = {};

        setTimeout(() => {
            for (let key in unitListData.data) {
                //editedFields[key] = false;
                let subdata = {};
                for (let subkey in unitListData.data[key]){
                    subdata[subkey] = unitListData.data[key][subkey];
                }
                controlData[key] = subdata;
            }
        }, 1000);

        let editedFields = {};
/*
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
        } */

        const inputs = document.querySelectorAll('tr[data-id] td input[data-name]');
        if (inputs) {
            inputs.forEach(input => {
                input.addEventListener('input',function(){
                    checkChange(this);
                });
            });
        }
/*
        function setRowDelete(th) {
            console.log(th);
            const row = th.closest('tr[data-id]');
            if (row) {
                const id = row.dataset.id;
                const name = th.dataset.name;
                const editInput = row.querySelector('input[name="unit['+id+'][edited]"]');
                if (id && name) {
                    controlData[id][name] = th.value == '' ? null : th.value;
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
            }
        } */

        let newItemId = 0;
/*
        function addItem() {
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
        } */
    </script>

@endsection
