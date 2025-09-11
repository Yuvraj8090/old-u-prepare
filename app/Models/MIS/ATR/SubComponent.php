<?php

namespace App\Models\MIS\ATR;

use Illuminate\Database\Eloquent\Model;

class SubComponent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'atr_sub_components';


    /**
     *
     */
    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
