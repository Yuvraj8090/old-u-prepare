<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MilestoneValuesDocuments extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "milestone_value_docuements";

    protected $fillable = [
        'milestone_value_id','document_name','file','status'
    ];
    public function milestoneValueUpdated(): BelongsTo
{
    return $this->belongsTo(MilestoneValueUpdated::class, 'milestone_value_id');
}


    // public function milestone(){
    //     return $this->belongsTo(MileStones::class,'id','milestone_id');
    // }
}
