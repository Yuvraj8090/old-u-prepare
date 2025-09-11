<?php

namespace App\Models\MIS;

use Illuminate\Database\Eloquent\Model;

class ReportProgressType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'env_soc_progress_types';


    public function safeguard_rules()
    {
        return $this->hasMany(SafeGuardRule::class, 'type_id', 'id');
    }
}
