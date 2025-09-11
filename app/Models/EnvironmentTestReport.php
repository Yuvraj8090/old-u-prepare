<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentTestReport extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "work_environment_report";

    protected $fillable = [
        'project_id','name','status','test_id','document','date','type','remark'
    ];
    
    public function test(){
        return $this->BelongsTo(EnvironmentTest::class,'test_id','id');
   }
   
   
   public function getDocumentAttribute($value){
       
       if($value == NULL){
           return NULL;
       }
       return url('images/test/environment/report/'.$value);
   }
}
