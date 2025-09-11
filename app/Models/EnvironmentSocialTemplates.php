<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentSocialTemplates extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "es_templates";

    protected $fillable = [
        'name', 'excel', 'status', 'type'
    ];
    
    public function getExcelAttribute($value){
        if(!$value){
            return Null;
        }
        
        return url('excel/'.$value);
    }
    
     public function getCreatedAtAttribute($value){
         
        if(!$value){
            return Null;
        }
        
        return date('d-m-Y h:i A',strtotime($value));
    }
    

    
}
