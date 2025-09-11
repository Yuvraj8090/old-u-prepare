<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCategory extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "projects_category";

    protected $fillable = [
        'name ','status','methods_of_procurement'
    ];

    public function project(){
        return $this->HasOne(Projects::class);
    }

    public function getMethodsOfProcurementAttribute($value) {
        return is_null($value) ? NULL : json_decode($value);
    }
}
