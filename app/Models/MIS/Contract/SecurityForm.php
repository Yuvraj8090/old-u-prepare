<?php

namespace App\Models\MIS\Contract;

use Illuminate\Database\Eloquent\Model;

class SecurityForm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_security_forms';


    /**
     *
     */
    protected $fillable = ['name', 'slug'];
}
