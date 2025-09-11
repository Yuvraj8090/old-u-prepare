<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractAmendDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "contract_amend";

    protected $fillable = [
        'contract_id','cost','amend_date','document','status'
    ];

    public function contract(){
        return $this->belongsTo(Contracts::class,'contract_id','id');
    }
}
