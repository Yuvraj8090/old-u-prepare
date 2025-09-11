<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentSocialPhotos extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "environment_social_test_photos";

    protected $fillable = [
        'project_id','test_id','status','name'
    ];
    
    public function getCreatedAtAttribute($value){
        
        if(!$value){
            return NULL;
        }
        
        return date('d-m-Y h:i A',strtotime($value));
        
    } 
    
    
    public function getNameAttribute($value){
        
        if(!$value){
            return NULL;
        }
        
        return url('images/test/environment/report/image/'.$value);
        
    } 
    
    
    
     public function getUpdatedAtAttribute($value){
        
        if(!$value){
            return NULL;
        }
        
        return date('d-m-Y h:i A',strtotime($value));
        
    } 
    
}