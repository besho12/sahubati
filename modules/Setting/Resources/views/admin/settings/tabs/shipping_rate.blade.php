<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('shipping_rate_enabled', trans('setting::attributes.shipping_rate_enabled'), trans('setting::settings.form.enable_shipping_rate'), $errors, $settings) }}
        {{ Form::text('translatable[shipping_rate_label]', trans('setting::attributes.translatable.shipping_rate_label'), $errors, $settings, ['required' => true]) }}
        {{-- {{ Form::number('shipping_rate_cost', trans('setting::attributes.shipping_rate_cost'), $errors, $settings, ['min' => 0, 'required' => true]) }} --}}
    </div>
</div>
