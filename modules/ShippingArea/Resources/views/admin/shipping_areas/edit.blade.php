@extends('admin::layout')
@php
$shippingarea=$shippingArea;
@endphp
@component('admin::components.shippingarea.header')
    @slot('title', trans('admin::resource.edit', ['resource' => trans('shippingarea::shipping_areas.shippingarea')]))
    @slot('subtitle', $shippingarea->title)

    <li><a href="{{ route('admin.shipping_areas.index') }}">{{ trans('shippingarea::shipping_areas.shipping_areas') }}</a></li>
    <li class="active">{{ trans('admin::resource.edit', ['resource' => trans('shippingarea::shipping_areas.shippingarea')]) }}</li>
@endcomponent
@section('content')
    <form method="POST" action="{{ route('admin.shipping_areas.update', $shippingarea) }}" class="form-horizontal" id="shippingarea-edit-form" novalidate>
        {{ csrf_field() }}
        {{ method_field('put') }}

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
