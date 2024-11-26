@extends('admin::layout')

@component('admin::components.shippingarea.header')
    @slot('title', trans('admin::resource.edit', ['resource' => trans('shippingarea::shippingareas.shippingarea')]))
    @slot('subtitle', $shippingarea->title)

    <li><a href="{{ route('admin.shippingareas.index') }}">{{ trans('shippingarea::shippingareas.shippingareas') }}</a></li>
    <li class="active">{{ trans('admin::resource.edit', ['resource' => trans('shippingarea::shippingareas.shippingarea')]) }}</li>
@endcomponent

@section('content')
    <form method="POST" action="{{ route('admin.shippingareas.update', $shippingarea) }}" class="form-horizontal" id="shippingarea-edit-form" novalidate>
        {{ csrf_field() }}
        {{ method_field('put') }}

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
