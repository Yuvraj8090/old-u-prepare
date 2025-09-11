<?php

namespace App\Models\Grievance;

use App\Models\Grievance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Action extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grievance_actions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grievance_id', 'pact', 'fact', 'pact_doc', 'fact_doc'
    ];


    /**
     *
     */
    public function grievance() : BelongsTo
    {
        return $this->belongsTo(Grievance::class, 'grievance_id', 'id');
    }
}
