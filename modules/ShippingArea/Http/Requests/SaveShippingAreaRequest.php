<?php

namespace Modules\ShippingArea\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\ShippingArea\Entities\ShippingArea;
use Modules\Core\Http\Requests\Request;

class SaveShippingAreaRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var array
     */
    protected $availableAttributes = 'ShippingArea::attributes';


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => $this->getSlugRules(),
            'name' => 'required',
            'is_active' => 'required|boolean',
        ];
    }


    private function getSlugRules()
    {
        $rules = $this->route()->getName() === 'admin.pages.update'
            ? ['required']
            : ['sometimes'];

        $slug = ShippingArea::withoutGlobalScope('active')->where('id', $this->id)->value('slug');

        $rules[] = Rule::unique('pages', 'slug')->ignore($slug, 'slug');

        return $rules;
    }
}
