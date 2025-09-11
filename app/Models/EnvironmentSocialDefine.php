<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentSocialDefine extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "environment_social_define";

    protected $fillable = [
        'project_id','define_project','status','user_id','type','start_date',
        'environment_screening_report','environment_management_plan','social_screening_report',
        'social_resettlement_action_plan','social_management_plan'
    ];
    
    public function getStartDateAttribute($value){
        return !empty($value) ? date('d-m-Y',strtotime($value)) : NULL;
    }
    
    public function getEnvironmentScreeningReportAttribute($value){
        return asset('images/environment/'.$value);
    }
    
    public function getEnvironmentManagementPlanAttribute($value){
         return asset('images/environment/'.$value);
    }
    
     public function getSocialScreeningReportAttribute($value){
         return asset('images/social/'.$value);
    }
    
     public function getsocialResettlementActionPlanAttribute($value){
         return asset('images/social/'.$value);
    }
    
    
    public function getSocialManagementPlanAttribute($value){
         return asset('images/social/'.$value);
    }

}
