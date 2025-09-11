<?php

namespace App\Models;

use App\Models\MIS\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = "roles";

    protected $fillable = [
        'name','affilaited','department','level','level_name'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->hasMany(Role::class, 'id', 'assign_to');
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
