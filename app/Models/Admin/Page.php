<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $table = 'pages';

    protected $dates = ['deleted_at'];

    public function navigation()
    {
        return $this->hasOne(Navigation::class, 'id', 'parent_menu');
    }
}
