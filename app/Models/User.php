<?php

namespace App\Models;

use App\Models\MIS\Department;
use App\Models\MIS\Permission;
use App\Models\MIS\Designation;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'role_id',
        'department_id',
        'username',
        'status',
        'phone_no',
        'profile_image',
        'designation',
        'designation_id',
	'district',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'=> 'datetime',
        'password'         => 'hashed',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);  // Each user can only have one role
    }


    public function projects()
    {
        return $this->HasMany(Projects::class, 'id', 'assign_to');
    }


    public function getProfilePicAttribute()
    {
        $image = 'user.png';

        if($this->profile_image)
        {
            $image = $this->profile_image;
        }
        else
        {
            if($this->gender == 'female')
            {
                $image = 'usress.png';
            }
        }

        return asset('images/user/profile/' . $image);
    }


    public function roles()
    {
        return $this->belongsToMany(\App\Models\MIS\Role::class, 'user_roles', 'user_id', 'role_id');
    }


    /**
     * The permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }


    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }


    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists() ||
               $this->roles()->whereHas('permissions', function ($query) use ($permission) {
                   $query->where('name', $permission);
               })->exists();
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    /**
     *
     */
    public function designatien()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }


    /**
     *
     */
    public function getRDeptAttribute()
    {
        $dept   = auth()->user()->role->department;
        $dept   = explode('-', $dept);
        $r_dept = NULL;

        foreach($dept as $dpt)
        {
            if(in_array($dpt, ['PWD', 'PMU', 'PIU', 'RWD', 'USDMA']))
            {
                $r_dept = $dpt;

                break;
            }
        }

        return $r_dept;
    }


    /**
     *
     */
    public function getDeptSecAttribute()
    {
        $section = auth()->user()->role->department;
        $section = explode('-', $section);
        $r_sec   = NULL;

        foreach($section as $sec)
        {
            if(in_array($sec, ['SOCIAL', 'FOREST', 'ENVIRONMENT']))
            {
                $r_sec = $sec;

                break;
            }
        }

        return $r_sec;
    }
}
