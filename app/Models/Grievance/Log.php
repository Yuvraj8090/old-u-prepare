<?php

namespace App\Models\Grievance;

use App\Models\User;
use App\Models\Grievance;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grievance_logs';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['grievance_id', 'user_id', 'forward_to', 'is_revert', 'remark', 'type', 'title'];


    /**
     *
     */
    public function grievance()
    {
        return $this->belongsTo(Grievance::class);
    }


    /**
     *
     */
    public function forwarded_user()
    {
        return $this->belongsTo(User::class, 'forward_to', 'id');
    }
}
