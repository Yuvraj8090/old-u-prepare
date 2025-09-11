<?php

namespace App\Models\MIS;

use App\Models\PWDBOQ;
use Illuminate\Database\Eloquent\Model;

class BOQEntry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pwd_boqs_entries';


    /**
     *
     */
    public function boq_item()
    {
        return $this->belongsTo(PWDBOQ::class, 'boq_item_id', 'id');
    }


    /**
     *
     */
    public function month()
    {
        return $this->belongsTo(Month::class);
    }


public function contract()
{
    return $this->hasOneThrough(
        \App\Models\Contracts::class,
        \App\Models\PWDBOQ::class,
        'id',           // Local key on PWDBOQ
        'id',           // Local key on Contracts
        'boq_item_id',  // Foreign key on BOQEntry
        'contract_id'   // Foreign key on PWDBOQ
    );
}

}
