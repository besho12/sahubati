<?php

namespace Modules\ShippingArea\Entities;

use Modules\Support\Eloquent\TranslationModel;

class ShippingAreaTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
