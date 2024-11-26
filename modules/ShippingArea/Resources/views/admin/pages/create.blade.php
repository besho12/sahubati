@extends('admin::layout')

@component('admin::components.shippingarea.header')
    @slot('title', trans('admin::resource.create', ['resource' => trans('shippingarea::shippingareas.shippingarea')]))

    <li><a href="{{ route('admin.shippingareas.index') }}">{{ trans('shippingarea::shippingareas.shippingareas') }}</a></li>
    <li class="active">{{ trans('admin::resource.create', ['resource' => trans('shippingarea::shippingareas.shippingarea')]) }}</li>
@endcomponent

@section('content')
    <form method="POST" action="{{ route('admin.shippingareas.store') }}" class="form-horizontal" id="shippingarea-create-form" novalidate>
        {{ csrf_field() }}

        {!! $tabs->render(compact('shippingarea')) !!}
    </form>
@endsection

@include('shippingarea::admin.shippingareas.partials.shortcuts')

@push('globals')
    @vite([
        'modules/Page/Resources/assets/admin/sass/main.scss',
        'modules/Page/Resources/assets/admin/js/main.js',
    ])
@endpush
