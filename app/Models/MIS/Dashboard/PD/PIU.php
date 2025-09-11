<?php

namespace App\Models\MIS\Dashboard\PD;

use Illuminate\Database\Eloquent\Model;

class PIU extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dashb_pd_pius';


    /**
     *
     */
    protected $fillable = [
        'name', 'amt_inr', 'amt_usd',
    ];
}
