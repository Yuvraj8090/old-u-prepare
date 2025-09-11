<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finance extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "project_finance_expense";

    protected $fillable = [
        'user_id','agency_id','quarter','office_equipment_exp','electricty_exp',
        'transport_exp','salaries_exp','miscelleneous_exp','total_exp','status','year','rent_exp'
    ];

    public function department(){
        return $this->HasOne(Role::class,'id','agency_id');
    }

    public function getMonthAttribute($value){
        return date("F", mktime(0, 0, 0, $value, 10));
    }

    
}
