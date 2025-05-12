@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.shop.payment.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('shop::default.create_payment') }}
            </span>
        </a>
        <form action="{{ route('admin.shop.payment') }}" method="get">
            <div class="round-input-wrapper">
                <button type="submit" class="button round-button theme-button inner-button default-highlight-button">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/search.svg', width: 18, height: 18, svg: true) !!}
                </button>
                <input type="search" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
            </div>
        </form>
        @if (!empty($search))
            <div class="table-search-result-title">
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a
                    href="{{ route('admin.shop.payment') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            </div>
        @endif
    </div>
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:4rem; --last-col:8rem; --minw:50rem; --cols-count:6;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.shop.payment', UrlParams::addArr(['order' => 'id', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['id' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.shop.payment', UrlParams::addArr(['order' => 'name', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['name' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.shop.payment', UrlParams::addArr(['order' => 'slug', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['slug' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.shop.payment', UrlParams::addArr(['order' => 'created_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['created_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.shop.payment', UrlParams::addArr(['order' => 'updated_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['updated_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr data-id="{{ $payment->id }}"
                        class="level-{{ $payment->level }}@empty($payment->active) inactive @endempty">
                        <td>{{ $payment->id }}</td>
                        <td>
                            <a href="{{ route('admin.shop.payment.edit', $payment) }}">
                                {{ $payment->name }}
                            </a>
                        </td>
                        <td>{{ $payment->slug }}</td>
                        <td>{{ $payment->created_at }}</td>
                        <td>{{ $payment->updated_at }}</td>
                        <td class="table-button-column">
                            <a href="{{ route('admin.shop.payment.edit', $payment) }}" class="button icon-button"
                                title="{{ __('elfcms::default.edit') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                            </a>
                            <form action="{{ route('admin.shop.payment.destroy', $payment) }}" method="POST"
                                data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $payment->id }}">
                                <input type="hidden" name="name" value="{{ $payment->name }}">
                                <button type="submit" class="button icon-button icon-alarm-button"
                                    title="{{ __('elfcms::default.delete') }}">
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                </button>
                            </form>
                            {{-- <div class="contextmenu-content-box">
                                <a href="{{ route('admin.shop.payment.edit', $payment) }}"
                                    class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                                <form action="{{ route('admin.shop.payment.destroy', $payment) }}" method="POST"
                                    data-submit="check">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $payment->id }}">
                                    <input type="hidden" name="name" value="{{ $payment->name }}">
                                    <button type="submit"
                                        class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                                </form>
                            </div> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (empty(count($payments)))
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
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        let paymentId = this.querySelector('[name="id"]').value,
                            paymentName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title: '{{ __('elfcms::default.deleting_of_element') }}' + paymentId,
                            content: '<p>{{ __('shop::default.are_you_sure_to_deleting_payment_method') }} "' +
                                paymentName + '" (ID ' + paymentId + ')?</p>',
                            buttons: [{
                                    title: '{{ __('elfcms::default.delete') }}',
                                    class: 'button color-text-button danger-button',
                                    callback: function() {
                                        self.submit()
                                    }
                                },
                                {
                                    title: '{{ __('elfcms::default.cancel') }}',
                                    class: 'button color-text-button',
                                    callback: 'close'
                                }
                            ],
                            class: 'danger'
                        })
                    })
                })
            }
        }

        setConfirmDelete(checkForms)

        const tablerow = document.querySelectorAll('.payment-table tbody tr');
        if (tablerow) {
            tablerow.forEach(row => {
                row.addEventListener('contextmenu', function(e) {
                    e.preventDefault()
                    let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
                    let forms = content.querySelectorAll('form[data-submit="check"]')
                    setConfirmDelete(forms)
                    contextPopup(content, {
                        'left': e.x,
                        'top': e.y
                    })
                })
            })
        }
    </script>
@endsection
