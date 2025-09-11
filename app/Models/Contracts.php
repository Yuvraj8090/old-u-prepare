<?php

namespace App\Models;

use App\Models\MIS\Contract\PhysicalProgress;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contracts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "contracts";

    protected $fillable = [
        'contract_unique_id','project_id','contract_signing_date',
        'end_date','bid_Fee','contract_agency','authorized_personel',
        'contact','email','contractor_address','cancel_reason','forclose_reason','user_id',
        'cancel_type','status','procurement_contract','company_resgistered_no','company_name',
        'aadhaar_no','contract_number','registration_type','contract_doc', 'commencement_date',
        'initial_completion_date', 'actual_completion_date', 'gst_no', 'end_date'
    ];
    protected $casts = [
    'procurement_contract' => 'float',
];
/**
 * Calculate total BOQ contract value (qty × rate)
 */
public function getTotalBoqAmountAttribute()
{
    return $this->pwd_boqs->sum(function ($boq) {
        return $boq->qty * $boq->rate;
    });
}

/**
 * Calculate executed (used) BOQ value from entries
 */
public function getUsedBoqAmountAttribute()
{
    return $this->pwd_boqs->flatMap->entries->sum(function ($entry) {
        return $entry->boq_item ? $entry->qty * $entry->boq_item->rate : 0;
    });
}

/**
 * Percentage of BOQ used (executed)
 */
public function getBoqUsedPercentageAttribute()
{
    $total = $this->total_boq_amount;
    if ($total == 0) {
        return 0;
    }
    return round(($this->used_boq_amount / $total) * 100, 2);
}


    public function project(){
        return $this->belongsTo(Projects::class,'project_id','id');
    }

    public function security(){
        return $this->hasMany(ContractSecurities::class,'contract_id','id');
    }

    public function media(){
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getContractDocAttribute($value){
        return asset('images/contract/'.$value);
    }

    public function ContractAmendDetails(){
        return $this->hasOne(Contracts::class,'contract_id','id');
    }


    public function pwd_boqs()
    {
        return $this->hasMany(PWDBOQ::class, 'contract_id', 'id');
    }


    /**
     *
     */
    public function physical_progress() : HasMany
    {
        return $this->hasMany(PhysicalProgress::class, 'contract_id', 'id');
    }
}
