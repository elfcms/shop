@extends('elfcms::admin.layouts.shop')

@section('shop-content')

<div class="table-search-box">
    <div class="table-search-result-title">
        @if (!empty($search))
            {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.selling.customers') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
        @endif
    </div>
    <form action="{{ route('admin.selling.customers') }}" method="get">
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
@if (Session::has('customerdeleted'))
<div class="alert alert-alternate">{{ Session::get('customerdeleted') }}</div>
@endif
@if (Session::has('customeredited'))
<div class="alert alert-alternate">{{ Session::get('customeredited') }}</div>
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
    <table class="grid-table table-cols-6" style="--first-col:65px; --minw:800px;">
        <thead>
            <tr>
                <th>
                    ID
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    Email
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'email','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['email'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.first_name') }}
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'firstname','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['firstname'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.last_name') }}
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'lastname','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['lastname'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('shop::default.order_quantity') }}
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'orders_count','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['orders_count'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                {{-- <th>
                    {{ __('shop::default.cart_item_quantity') }}
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'cart_items_count','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('cart_items_count',['lastname'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th> --}}
                <th>
                    {{ __('elfcms::default.created') }}
                    <a href="{{ route('admin.selling.customers',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                {{-- <th></th> --}}
            </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
            <tr data-id="{{ $customer->id }}">
                <td>
                    <a href="{{ route('admin.selling.customers.show',$customer) }}" title="{{ implode(' ',[__('shop::default.customer'),$customer->firstname,$customer->lastname,'#'.$customer->id]) }}">{{ $customer->id }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.selling.customers.show',$customer) }}" title="{{ __('elfcms::default.edit_user') . $customer->user->id }}">
                        {{ $customer->user->email }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('admin.selling.customers.show',$customer) }}" title="{{ implode(' ',[__('shop::default.customer'),$customer->firstname,$customer->lastname,'#'.$customer->id]) }}">{{ $customer->firstname }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.selling.customers.show',$customer) }}" title="{{ implode(' ',[__('shop::default.customer'),$customer->firstname,$customer->lastname,'#'.$customer->id]) }}">{{ $customer->lastname }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.selling.orders',['customer'=>$customer->id]) }}" title="{{ __('shop::default.orders_of_user') }}">
                        {{ $customer->orders->count() }}
                    </a>
                </td>
                {{-- <td>
                    <a href="{{ route('admin.selling.carts.show',$customer->cart) }}" title="{{ __('shop::default.cart_of_user') }}">
                        {{ $customer->cart->items->count() }}
                    </a>
                </td> --}}
                <td>{{ $customer->created_at }}</td>
                {{-- <td class="button-column non-text-buttons">
                    <a href="{{ route('admin.selling.customers.show',$customer) }}" class="default-btn edit-button" title="{{ __('elfcms::default.show') }}"></a>
                </td> --}}
            </tr>
        @endforeach
        </tbody>
    </table>
    @if (empty(count($customers)))
        <div class="no-results-box">
            {{ __('elfcms::default.nothing_was_found') }}
        </div>
    @endif
</div>
{{$customers->links('elfcms::admin.layouts.pagination')}}

<script>
    /*const checkForms = document.querySelectorAll('form[data-submit="check"]')

    function setConfirmDelete(forms) {
        if (forms) {
            forms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let customerId = this.querySelector('[name="id"]').value,
                        customerName = this.querySelector('[name="email"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + customerId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_customer') }} "' + customerName + '" (ID ' + customerId + ')?</p>',
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


    const tablerow = document.querySelectorAll('.customertable tbody tr');
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
    }*/
</script>

@endsection
