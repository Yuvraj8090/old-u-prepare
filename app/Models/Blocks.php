<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Blocks extends Model
{
    use HasFactory;

    protected $table = "blocks";

    protected $fillable = [
        'district_id','name','status'
    ];


}
