@extends('admin::layout')

@component('admin::components.shippingarea.header')
    @slot('title', trans('admin::resource.create', ['resource' => trans('shippingarea::shipping_areas.shippingarea')]))

    <li><a href="{{ route('admin.shipping_areas.index') }}">{{ trans('shippingarea::shipping_areas.shipping_areas') }}</a></li>
    <li class="active">{{ trans('admin::resource.create', ['resource' => trans('shippingarea::shipping_areas.shippingarea')]) }}</li>
@endcomponent
@php
$shippingarea=$shippingArea;
@endphp
@section('content')
    <form method="POST" action="{{ route('admin.shipping_areas.store') }}" class="form-horizontal" id="shippingarea-create-form" novalidate>
        {{ csrf_field() }}

        {!! $tabs->render(compact('shippingarea')) !!}
    </form>
@endsection

@include('shippingarea::admin.shipping_areas.partials.shortcuts')

@push('globals')
    @vite([
        'modules/Page/Resources/assets/admin/sass/main.scss',
        'modules/Page/Resources/assets/admin/js/main.js',
    ])
@endpush
