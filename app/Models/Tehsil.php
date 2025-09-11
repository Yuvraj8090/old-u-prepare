<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tehsil extends Model
{
    use HasFactory;

    protected $table = "tehsil";

    protected $fillable = [
        'disrict_id','name','status'
    ];


}
