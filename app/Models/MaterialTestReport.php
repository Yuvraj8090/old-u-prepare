<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialTestReport extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "work_material_test_report";

    protected $fillable = [
        'project_id','name','status','test_id','document','test_date','project_id','type','remark'
    ];
    
    public function test(){
        return $this->BelongsTo(MaterialTest::class,'test_id','id');
   }
   
   public function getDocumentAttribute($value){
       return url('images/test/report/'.$value);
   }
   
   
   public function gettestDateAttribute($value){
       
       if($value == NULL){
           return NULL;
       }
       return date('d-m-Y',strtotime($value));
   }
}
