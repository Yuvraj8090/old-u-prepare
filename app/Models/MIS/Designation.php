<?php

namespace App\Models\MIS;

use App\Models\Role;
use App\Models\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;

    protected $table   = "mis_designations";
    protected $guarded = [];


    /**
     *
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

}
