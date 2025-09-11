<?php

namespace App\Models;

use App\Models\MIS\BOQEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PWDBOQ extends Model
{
    use SoftDeletes;

    protected $table = 'pwd_boqs';

    protected $fillable = [
        'qty', 's_no', 'item', 'unit', 'rate',
        'title', 'heading', 'section', 'contract_id'
    ];


    public function entries()
    {
        return $this->hasMany(BOQEntry::class, 'boq_item_id', 'id');
    }
    public function contract()
{
    return $this->belongsTo(Contracts::class, 'contract_id');
}



}
