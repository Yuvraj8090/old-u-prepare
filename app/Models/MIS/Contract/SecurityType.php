<?php

namespace App\Models\MIS\Contract;

use Illuminate\Database\Eloquent\Model;

class SecurityType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_security_types';


    /**
     *
     */
    protected $fillable = ['name', 'slug', 'parent_id'];
}
