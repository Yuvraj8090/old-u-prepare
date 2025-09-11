<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentTest extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "work_environment_test";

    protected $fillable = [
        'project_id','name','status','type'
    ];
    
   public function reports(){
        return $this->hasOne(EnvironmentTestReport::class,'test_id','id','project_id','project_id');
   }
   
   
    public function scopeWithFilteredReports($query, $projectId)
    {
        return $query->with(['reports' => function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        }]);
    }
    
    public function scopeWithHasReports($query, $projectId)
    {
        return $query->whereHas('reports', function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        });
    }
}
