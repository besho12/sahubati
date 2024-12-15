<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('paylink_enabled', trans('setting::attributes.paylink_enabled'), trans('setting::settings.form.enable_paylink'), $errors, $settings) }}
        {{ Form::text('translatable[paylink_label]', trans('setting::attributes.translatable.paylink_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[paylink_description]', trans('setting::attributes.translatable.paylink_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}
    </div>
</div>
