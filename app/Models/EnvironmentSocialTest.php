<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentSocialTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "environment_social_test";

    protected $fillable = [
        'project_id', 'name', 'status', 'type', 'test_id', 'module_type', 'have_child','excel'
    ];
    
    public function reports()
    {
        return $this->hasOne(EnvironmentAndSocialTestReport::class, 'test_id', 'id');
    }

    public function subtests()
    {
        return $this->hasMany(self::class, 'test_id', 'id');
    }
    
    public function scopeWithHasReports($query, $projectId)
    {
        return $query->whereHas('reports', function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        });
    }
        
    public function scopeWithFilteredReports($query, $projectId)
    {
        return $query->with(['reports' => function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        }]);
    }

    public function scopeWithFilteredSubtests($query, $projectId)
    {
        return $query->with(['subtests' => function($query) use ($projectId) {
            $query->with(['reports' => function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            }])->whereHas('reports', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->orderBy('id','desc');
        }]);
    }
    
    public function getPlannedDateAttribute($value){
        
        if(!$value){
           return NULL;
        }
        
        return date('d-m-Y',strtotime($value));
    }
}
