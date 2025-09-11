<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentAndSocialTestReport extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "environment_social_test_report";

    protected $fillable = [
        'project_id','name','status','test_id','document','date','type','remark','actual_date','planned_date'
    ];
    
    public function test(){
        return $this->BelongsTo(EnvironmentTest::class,'test_id','id');
   }
   
   
   public function getDocumentAttribute($value){
       
       if($value == NULL){
           return NULL;
       }
       return url('images/test/environment/report/four/'.$value);
   }
   
   public function getPlannedDateAttribute($value){
        
        if(!$value){
           return NULL;
        }
        
        return date('d-m-Y',strtotime($value));
    }
    
    public function getActualDateAttribute($value){
        
        if(!$value){
           return NULL;
        }
        
        return date('d-m-Y',strtotime($value));
    }
}
