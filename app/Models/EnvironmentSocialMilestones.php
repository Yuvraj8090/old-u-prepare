<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentSocialMilestones extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "environment_social_milestones";
    
    protected $fillable = [ 
        'type','project_id','weight','name','days','planned_date','actual_date','status'
    ];

    public function project(){
        return $this->belongsTo(Projects::class);
    }
    
    public function photos(){
        return $this->morphMany(Media::class, 'mediable')->where('file_type_name','image');
    }
    
    public function document(){
        return $this->morphMany(Media::class, 'mediable')->where('file_type_name','document');
    }

  
    
    

}
