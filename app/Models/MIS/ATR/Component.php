<?php

namespace App\Models\MIS\ATR;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'atr_components';


    /**
     *
     */
    public function sub_components()
    {
        return $this->hasMany(SubComponent::class);
    }
}
