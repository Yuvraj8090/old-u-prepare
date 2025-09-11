<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MileStones extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "procuremnt_milestones";

    protected $fillable = [
        'type', 'user_id', 'project_id', 'name', 'percent_of_work',
        'start_date', 'end_date', 'amended_start_date', 'amended_end_date',
        'financial_progress', 'physical_progress', 'accumulative', 'status', 'budget',
    ];

    public function project()
{
    return $this->belongsTo(Projects::class, 'project_id');
}



    public function document()
    {
        return $this->hasMany(MilestonesDocument::class,'milestone_id','id');
    }


    public function values()
    {
        return $this->hasMany(MilestoneValues::class,'milestone_id','id');
    }


    public function milestoneValues()
{
    return $this->hasMany(MilestoneValues::class, 'milestone_id', 'id');
}


public function getTotalAmountAttribute()
{
    return $this->milestoneValues()->sum('amount');
}

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

}
