<?php

namespace App\Models;

use App\Models\MIS\Contract\SecurityForm;
use App\Models\MIS\Contract\SecurityType;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractSecurities extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "contract_details";

    protected $fillable = [
        'security_form_id', 'security_type_id',
        'name','contract_id','start_date','security_number',
        'end_security_date','issuing_authority','amount','status',
        'form_of_security','example_of_security'
    ];


    /**
     *
     */
    public function security_type()
    {
        return $this->belongsTo(SecurityType::class, 'security_type_id', 'id');
    }

    /**
     *
     */
    public function security_form()
    {
        return $this->belongsTo(SecurityForm::class, 'security_form_id', 'id');
    }

    /**
     *
     */
    public function contract()
    {
        return $this->belongsTo(Contracts::class, 'contract_id', 'id');
    }


    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('id','desc');
    }


    public function media()
    {
        return $this->morphOne(Media::class, 'mediable')->orderBy('id','desc');
    }
}
