@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.shop.category.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('shop::default.create_category') }}
            </span>
        </a>
        <form action="{{ route('admin.shop.category') }}" method="get">
            <div class="round-input-wrapper">
                <button type="submit" class="button round-button theme-button inner-button default-highlight-button">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/search.svg', width: 18, height: 18, svg: true) !!}
                </button>
                <input type="search" name="search" id="search" value="{{ $search ?? '' }}"
                    placeholder="{{ __('elfcms::default.search') }}">
            </div>
        </form>
        @if (!empty($search))
            <div class="table-search-result-title">
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a
                    href="{{ route('admin.shop.category') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            </div>
        @endif
    </div>
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:4rem; --last-col:13rem; --minw:50rem; --cols-count:7;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.shop.category',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.title') }}
                        <a href="{{ route('admin.shop.category',UrlParams::addArr(['order'=>'title','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['title'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.slug') }}
                        <a href="{{ route('admin.shop.category',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    {{-- <th>{{ __('elfcms::default.preview') }}</th>
                    <th>{{ __('elfcms::default.image') }}</th>
                    <th>{{ __('elfcms::default.description') }}</th> --}}
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.shop.category',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.shop.category',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.active') }}
                        <a href="{{ route('admin.shop.category',UrlParams::addArr(['order'=>'active','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['active'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr data-id="{{ $category->id }}" class="level-{{ $category->level }}@empty ($category->active) inactive @endempty">
                    <td class="subline-{{ $category->level }}">{{ $category->id }}</td>
                    <td>
                        <a href="{{ route('admin.shop.category.edit',$category) }}">
                            {{ $category->title }}
                        </a>
                    </td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>
                    @if ($category->active)
                        {{ __('elfcms::default.active') }}
                    @else
                        {{ __('elfcms::default.not_active') }}
                    @endif
                    </td>
                    <td class="table-button-column">
                        <form action="{{ route('admin.shop.product.create') }}" method="GET">
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <button type="submit" class="button icon-button" title="{{ __('shop::default.create_product') }}">
                                {!! iconHtmlLocal('elfcms/admin/modules/shop/images/icons/buttons/product_add.svg', svg: true) !!}
                            </button>
                        </form>
                        <a href="{{ route('admin.shop.category.edit',$category) }}" class="button icon-button" title="{{ __('elfcms::default.edit') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                        </a>
                        <form action="{{ route('admin.shop.category.update',$category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                            <input type="hidden" name="notedit" value="1">
                            @if ($category->active == 1)
                                    <button type="submit" class="button icon-button"
                                        title="{{ __('elfcms::default.deactivate') }}">
                                        {!! iconHtmlLocal('elfcms/admin/modules/shop/images/icons/buttons/active.svg', svg: true) !!}
                                    </button>
                                @else
                                    <button type="submit" class="button icon-button"
                                        title="{{ __('elfcms::default.activate') }}">
                                        {!! iconHtmlLocal('elfcms/admin/modules/shop/images/icons/buttons/inactive.svg', svg: true) !!}
                                    </button>
                                @endif
                        </form>
                        <form action="{{ route('admin.shop.category.destroy',$category) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $category->id }}">
                            <input type="hidden" name="name" value="{{ $category->name }}">
                            <button type="submit" class="button icon-button icon-alarm-button"
                                title="{{ __('elfcms::default.delete') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                            </button>
                        </form>
                        {{-- <div class="contextmenu-content-box">
                            <a href="{{ route('admin.shop.category',UrlParams::addArr(['category'=>$category])) }}" class="contextmenu-item">
                                {{ __('shop::default.show_products') }}
                            </a>
                            <form action="{{ route('admin.shop.product.create') }}" method="GET">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <button type="submit" class="contextmenu-item">{{ __('shop::default.create_product') }}</button>
                            </form>
                            <a href="{{ route('admin.shop.category.edit',$category) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                            <form action="{{ route('admin.shop.category.update',$category) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($category->active == 1)
                                    {{ __('elfcms::default.deactivate') }}
                                @else
                                    {{ __('elfcms::default.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.shop.category.destroy',$category) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $category->id }}">
                                <input type="hidden" name="name" value="{{ $category->name }}">
                                <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                            </form>
                        </div> --}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($categories)))
            <div class="no-results-box">
                {{ __('elfcms::default.nothing_was_found') }}
            </div>
        @endif
    </div>

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        /* if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let categoryId = this.querySelector('[name="id"]').value,
                        categoryName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + categoryId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_category') }} "' + categoryName + '" (ID ' + categoryId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-text-button danger-button',
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
        } */

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let categoryId = this.querySelector('[name="id"]').value,
                            categoryName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title:'{{ __('elfcms::default.deleting_of_element') }}' + categoryId,
                            content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_category') }} "' + categoryName + '" (ID ' + categoryId + ')?</p>',
                            buttons:[
                                {
                                    title:'{{ __('elfcms::default.delete') }}',
                                    class:'button color-text-button danger-button',
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

        const tablerow = document.querySelectorAll('.shop-category-table tbody tr');
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
