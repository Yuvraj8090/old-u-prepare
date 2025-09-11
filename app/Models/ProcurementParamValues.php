<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementParamValues extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "procuremnt_value_by_category";

    protected $fillable = [
        'project_id','procurement_param_id','weight','planned_date','actual_date','days','name'
    ];

    public function project(){
        return $this->belongsTo(Projects::class,'project_id','id');
    }

    public function getplannedDateAttribute($value){
        return $value ? date('d-m-Y',strtotime($value)) : NULL; 
    }

    public function getActualDateAttribute($value){
        return $value ? date('d-m-Y',strtotime($value)) : NULL; 
    }
}
