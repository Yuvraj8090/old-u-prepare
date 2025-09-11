<?php

namespace App\Models\MIS\Contract;

use App\Models\Media;
use App\Models\Contracts;
use App\Models\MIS\Contract\Milestone\Stage;
use App\Models\MIS\Contract\Milestone\Activity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalProgress extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_physical_progress';


    /**
     *
     */
    protected $fillable = [
        'date', 'items', 'stage_id', 'progress', 'activity_id', 'contract_id'
    ];


    /**
     *
     */
    public function contract() : BelongsTo
    {
        return $this->belongsTo(Contracts::class, 'contract_id', 'id');
    }


    /**
     *
     */
    public function stage() : BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id', 'id');
    }


    /**
     *
     */
    public function activity() : BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }


    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
