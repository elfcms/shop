@extends('elfcms::admin.layouts.shop')

@section('shop-content')
<nav class="pagenav">
    <div class="abstract-nav-line">
        <a href="{{ route('admin.shop.brand.create') }}" class="default-btn submit-button create-button">{{ __('shop::default.create_brand') }}</a>
    </div>
</nav>

    <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.shop.brand') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.shop.brand') }}" method="get">
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
    <div class="widetable-wrapper">
        <table class="grid-table brand-table">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.shop.brand',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.shop.brand',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.slug') }}
                        <a href="{{ route('admin.shop.brand',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.shop.brand',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.shop.brand',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($brands as $brand)
                <tr data-id="{{ $brand->id }}" class="level-{{ $brand->level }}@empty ($brand->active) inactive @endempty">
                    <td>{{ $brand->id }}</td>
                    <td>
                        <a href="{{ route('admin.shop.brand.edit',$brand) }}">
                            {{ $brand->name }}
                        </a>
                    </td>
                    <td>{{ $brand->slug }}</td>
                    <td>{{ $brand->created_at }}</td>
                    <td>{{ $brand->updated_at }}</td>
                    <td class="button-column non-text-buttons">
                        <a href="{{ route('admin.shop.brand.edit',$brand) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        <form action="{{ route('admin.shop.brand.destroy',$brand) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $brand->id }}">
                            <input type="hidden" name="name" value="{{ $brand->name }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                        </form>
                        <div class="contextmenu-content-box">
                            <a href="{{ route('admin.shop.brand.edit',$brand) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                            <form action="{{ route('admin.shop.brand.destroy',$brand) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $brand->id }}">
                                <input type="hidden" name="name" value="{{ $brand->name }}">
                                <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($brands)))
            <div class="no-results-box">
                {{ __('elfcms::default.nothing_was_found') }}
            </div>
        @endif
    </div>

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')
        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let brandId = this.querySelector('[name="id"]').value,
                            brandName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title:'{{ __('elfcms::default.deleting_of_element') }}' + brandId,
                            content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_brand') }} "' + brandName + '" (ID ' + brandId + ')?</p>',
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

        setConfirmDelete(checkForms)

        const tablerow = document.querySelectorAll('.brand-table tbody tr');
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
