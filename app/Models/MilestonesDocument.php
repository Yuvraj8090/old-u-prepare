<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MilestonesDocument extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "milestone_documents";

    protected $fillable = [
        'milestone_id','name','status'
    ];

    public function milestone(){
        return $this->belongsTo(MileStones::class,'id','milestone_id');
    }
}
