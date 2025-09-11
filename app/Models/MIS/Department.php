<?php

namespace App\Models\MIS;

use App\Models\Role;
use App\Models\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $table   = "mis_departments";
    protected $guarded = [];


    /**
     *
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }


    /**
     *
     */
    public function projects()
    {
        return $this->hasMany(Projects::class, 'dept_id', 'id');
    }


    /**
     *
     */
    public function designations()
    {
        return $this->hasMany(Designation::class, 'dept_id', 'id');
    }


    /**
     *
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'department_roles', 'department_id', 'role_id');
    }
}
