<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assembly extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "assembly";

    protected $fillable = [
       'name','status','district_id','constituency_id'
    ];

    public function project(){
        return $this->HasOne(Projects::class);
    }

}
