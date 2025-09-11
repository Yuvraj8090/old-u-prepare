<?php

namespace App\Models\MIS\Contract\Milestone;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_milestone_activities';


    /**
     *
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class, 'activity_id', 'id');
    }
}
