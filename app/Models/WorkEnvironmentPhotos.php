<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkEnvironmentPhotos extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "work_environment_photos";

    protected $fillable = [
        'project_id','test_id','status','name'
    ];
    
}