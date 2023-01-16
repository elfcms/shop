@extends('shop::admin.layouts.shop')

@section('shop-content')
<nav class="pagenav">
    <div class="abstract-nav-line">
        <a href="{{ route('admin.shop.goods.create') }}" class="default-btn submit-button create-button">{{ __('shop::elf.create_goods') }}</a>
    </div>
</nav>
    <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('basic::elf.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.shop.goods') }}" title="{{ __('basic::elf.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.shop.goods') }}" method="get">
            <div class="input-box">
                <label for="search">
                    {{ __('basic::elf.search') }}
                </label>
                <div class="input-wrapper">
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
                </div>
                <div class="non-text-buttons">
                    <button type="submit" class="default-btn search-button"></button>
                </div>
            </div>
        </form>
    </div>
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
    <div class="widetable-wrapper">
        <table class="grid-table shop-goods-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('basic::elf.title') }}</th>
                    <th>{{ __('basic::elf.slug') }}</th>
                    <th>{{ __('shop::elf.unit_of_measurement') }}</th>
                    <th>{{ __('shop::elf.active') }}</th>
                </tr>
                <tr class="subtable-head">
                    <th>ID</th>
                    <th>{{ __('shop::elf.alternate_name') }}</th>
                    <th>{{ __('shop::elf.item_number') }}</th>
                    <th class="align-center">{{ __('shop::elf.balance') }}</th>
                    <th>{{ __('shop::elf.active') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($goodsCollection as $goods)
                <tr data-id="{{ $goods->id }}" @empty ($goods->active) inactive @endempty">
                    <td>
                        {{ $goods->id }}
                    </td>
                    <td>
                        {{ $goods->title }}
                    </td>
                    <td>
                        {{ $goods->slug }}
                    </td>
                    <td>
                        {{ $goods->unit->title }}
                    </td>
                    <td></td>
                </tr>
                <tr class="subtable-row">
                    <td colspan="5">
                        <table class="grid-table shop-goods-table">
                            @forelse ($goods->items as $item)
                            <tr>
                                <td>
                                    {{ $item->id }}
                                </td>
                                <td>
                                    {{ $item->alternate_name }}
                                </td>
                                <td>
                                    {{ $item->item_number }}
                                </td>
                                <td class="align-center">
                                    {{ $item->balance }}
                                </td>
                                <td>
                                    <i class="icon-checkbox-round"></i>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td>{{ __('basic::elf.nothing_was_found') }}</td>
                            </tr>
                            @endforelse
                        </table>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($goodsCollection)))
            <div class="no-results-box">
                {{ __('basic::elf.nothing_was_found') }}
            </div>
        @endif
    </div>

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        /* if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let goodsId = this.querySelector('[name="id"]').value,
                        goodsName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('basic::elf.deleting_of_element') }}' + goodsId,
                        content:'<p>{{ __('basic::elf.are_you_sure_to_deleting_goods') }} "' + goodsName + '" (ID ' + goodsId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('basic::elf.delete') }}',
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('basic::elf.cancel') }}',
                                class:'default-btn cancel-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        } */

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let goodsId = this.querySelector('[name="id"]').value,
                            goodsName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title:'{{ __('basic::elf.deleting_of_element') }}' + goodsId,
                            content:'<p>{{ __('basic::elf.are_you_sure_to_deleting_goods') }} "' + goodsName + '" (ID ' + goodsId + ')?</p>',
                            buttons:[
                                {
                                    title:'{{ __('basic::elf.delete') }}',
                                    class:'default-btn delete-button',
                                    callback: function(){
                                        self.submit()
                                    }
                                },
                                {
                                    title:'{{ __('basic::elf.cancel') }}',
                                    class:'default-btn cancel-button',
                                    callback:'close'
                                }
                            ],
                            class:'danger'
                        })
                    })
                })
            }
        }

        //setConfirmDelete(checkForms)

        const tablerow = document.querySelectorAll('.shop-goods-table tbody tr');
        /* if (tablerow) {
            tablerow.forEach(row => {
                row.addEventListener('contextmenu',function(e){
                    e.preventDefault()
                    let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
                    let forms  = content.querySelectorAll('form[data-submit="check"]')
                    setConfirmDelete(forms)
                    contextPopup(content,{'left':e.x,'top':e.y})
                })
            })
        } */
    </script>

@endsection
