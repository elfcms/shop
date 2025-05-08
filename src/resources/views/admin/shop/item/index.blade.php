@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<nav class="pagenav">
    <div class="abstract-nav-line">
        <a href="{{ route('admin.shop.product.create') }}" class="default-btn submit-button create-button">{{ __('shop::default.create_product') }}</a>
    </div>
</nav>
    <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.shop.product') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.shop.product') }}" method="get">
            <div class="input-box">
                <label for="search">
                    {{ __('elfcms::default.search') }}
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
    <div class="grid-table-wrapper">
        <table class="grid-table shop-products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.title') }}</th>
                    <th>{{ __('elfcms::default.slug') }}</th>
                    <th>{{ __('shop::default.unit_of_measurement') }}</th>
                    <th>{{ __('shop::default.active') }}</th>
                </tr>
                <tr class="subtable-head">
                    <th>ID</th>
                    <th>{{ __('shop::default.alternate_name') }}</th>
                    <th>{{ __('shop::default.item_number') }}</th>
                    <th class="align-center">{{ __('shop::default.balance') }}</th>
                    <th>{{ __('shop::default.active') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr data-id="{{ $product->id }}" @empty ($product->active) inactive @endempty">
                    <td>
                        {{ $product->id }}
                    </td>
                    <td>
                        {{ $product->title }}
                    </td>
                    <td>
                        {{ $product->slug }}
                    </td>
                    <td>
                        {{ $product->unit->title }}
                    </td>
                    <td>
                        <i @class(['icon-checkbox-round', 'checked' => $product->active])></i>
                        <div class="contextmenu-content-box">
                            <a href="{{ route('admin.shop.product.edit',$product) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                            <form action="{{ route('admin.shop.product.update',$product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$product->active }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($product->active == 1)
                                    {{ __('elfcms::default.deactivate') }}
                                @else
                                    {{ __('elfcms::default.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.shop.product.destroy',$product) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->title }}">
                                <button type="submit" class="contextmenu-item warning">{{ __('elfcms::default.delete') }}</button>
                            </form>
                            <form action="{{ route('admin.shop.item.create') }}" method="GET">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="contextmenu-item">{{ __('shop::default.add_item') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <tr class="subtable-row">
                    <td colspan="5">
                        <table class="grid-table shop-products-table">
                            @forelse ($product->items as $item)
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
                                <td>
                                    {{ $item->balance }}
                                </td>
                                <td>
                                    <i @class(['icon-checkbox-round', 'checked' => $item->active])></i>
                                    <div class="contextmenu-content-box">
                                        <a href="{{ route('admin.shop.item.edit',$item) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                                        <form action="{{ route('admin.shop.item.update',$item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="id" id="id" value="{{ $item->id }}">
                                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$item->active }}">
                                            <input type="hidden" name="notedit" value="1">
                                            <button type="submit" class="contextmenu-item">
                                            @if ($item->active == 1)
                                                {{ __('elfcms::default.deactivate') }}
                                            @else
                                                {{ __('elfcms::default.activate') }}
                                            @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.shop.item.destroy',$item) }}" method="POST" data-submit="check">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <input type="hidden" name="name" value="{{ $item->item_number }}">
                                            <button type="submit" class="contextmenu-item warning">{{ __('elfcms::default.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td>
                                    {{-- {{ __('elfcms::default.nothing_was_found') }} --}}
                                    <form action="{{ route('admin.shop.item.create') }}" method="GET">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="default-btn submit-button">{{ __('shop::default.add_item') }}</button>
                                    </form>
                                </td>
                            </tr>
                            @endforelse
                        </table>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($products)))
            <div class="no-results-box">
                {{ __('elfcms::default.nothing_was_found') }}
            </div>
        @endif
    </div>

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let productId = this.querySelector('[name="id"]').value,
                        productName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + productId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_product') }} "' + productName + '" (ID ' + productId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'button color-text-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        }

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let productId = this.querySelector('[name="id"]').value,
                            productName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title:'{{ __('elfcms::default.deleting_of_element') }}' + productId,
                            content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_product') }} "' + productName + '" (ID ' + productId + ')?</p>',
                            buttons:[
                                {
                                    title:'{{ __('elfcms::default.delete') }}',
                                    class:'default-btn delete-button',
                                    callback: function(){
                                        self.submit()
                                    }
                                },
                                {
                                    title:'{{ __('elfcms::default.cancel') }}',
                                    class:'button color-text-button',
                                    callback:'close'
                                }
                            ],
                            class:'danger'
                        })
                    })
                })
            }
        }

        setConfirmDelete(checkForms)

        const tablerow = document.querySelectorAll('.shop-products-table tbody tr:has(.contextmenu-content-box):not(.subtable-row)');
        if (tablerow) {
            tablerow.forEach(row => {
                row.addEventListener('contextmenu',function(e){
                    e.preventDefault()
                    let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
                    let forms  = content.querySelectorAll('form[data-submit="check"]')
                    setConfirmDelete(forms)
                    contextPopup(content,{'left':e.x,'top':e.y})
                })
            })
        }
    </script>

@endsection
