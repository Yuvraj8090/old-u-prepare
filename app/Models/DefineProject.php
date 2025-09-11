<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefineProject extends Model
{
    use HasFactory ,SoftDeletes;

    protected $table = "define_project";

    protected $fillable = [
        'user_id','project_id','hpc_number','method_of_procurement',
        'bid_number','bid_fee','earnest_money_deposit','epd','status',
        'hpc_date','scope_of_work','objective','supervisor_name',
        'supervisor_deisgnation','supervisor_contact','bid_validity',
        'bid_completion_days','bid_pub_date'
    ];

    public function project()
    {
        return $this->belongsTo(Projects::class,'project_id','id');
    }

    public function bid_pub_doc()
    {
        return $this->morphOne(Media::class, 'mediable')->latest();
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable')->where('file_type_name','Bid Document')->latest();
    }

    public function media2()
    {
        return $this->morphOne(Media::class, 'mediable')->where('file_type_name','Pre Bid Document')->latest();
    }
}
