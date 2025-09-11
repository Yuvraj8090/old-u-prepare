<?php

namespace App\Models\MIS\Dashboard\PD;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dashb_pd_components';


    /**
     *
     */
    protected $fillable = [
        'name', 'amt_inr', 'amt_usd',
    ];
}
