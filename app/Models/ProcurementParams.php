<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementParams extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "procurement_params";

    protected $fillable = [
        'category_id','category_type','name','weight','status'
    ];

    public function project(){
        return $this->belongsTo(ProjectCategory::class,'category_id','id');
    }
        

}
