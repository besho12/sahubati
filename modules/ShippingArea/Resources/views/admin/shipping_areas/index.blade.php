
@extends('admin::layout')

@component('admin::components.shippingarea.header')
    @slot('title', trans('shippingarea::shipping_areas.shipping_areas'))

    <li class="active">{{ trans('shippingarea::shipping_areas.shipping_areas') }}</li>
@endcomponent

@component('admin::components.shippingarea.index_table')
    @slot('resource', 'shipping_areas')
    @slot('buttons', ['create'])
    @slot('name', trans('shippingarea::shipping_areas.shippingarea'))

    @slot('thead')
        <tr>
            @include('admin::partials.table.select_all')

            <th>{{ trans('admin::admin.table.id') }}</th>
            <th>{{ trans('shippingarea::shipping_areas.table.name') }}</th>
            <th>{{ trans('admin::admin.table.status') }}</th>
            <th data-sort>{{ trans('admin::admin.table.created') }}</th>
        </tr>
    @endslot
@endcomponent

@push('scripts')
    <script type="module">
        new DataTable('#shipping_areas-table .table', {
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                { data: 'id', width: '5%' },
                { data: 'name', name: 'translations.name', orderable: false, defaultContent: '' },
                { data: 'status', name: 'is_active', searchable: false },
                { data: 'created', name: 'created_at' },
            ],
        });
    </script>
@endpush
