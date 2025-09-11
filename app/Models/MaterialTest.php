<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialTest extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "work_material_test";

    protected $fillable = [
        'project_id','name','status','type','category'
    ];
    
   public function reports(){
        return $this->hasMany(MaterialTestReport::class,'test_id','id','project_id','project_id');
   }
   
    public function scopeWithFilteredReports($query, $projectId)
    {
        return $query->with(['reports' => function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        }]);
    }
}
