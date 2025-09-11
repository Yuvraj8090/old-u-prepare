<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "sub_category";

    protected $fillable = [
       'category_id', 'name','status'
    ];

    public function project(){
        return $this->HasOne(Projects::class);
    }

}
