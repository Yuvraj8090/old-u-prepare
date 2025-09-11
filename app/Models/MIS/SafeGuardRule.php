<?php

namespace App\Models\MIS;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;

class SafeGuardRule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'env_soc_safeguard_rules';


    /**
     *
     */
    protected $fillable = [
        'indx', 'name', 'is_heading', 'type_id',
        'safeguard_type', 'label_1', 'label_2',
        'label_3', 'label_4', 'parent_id', 'sheet_type'
    ];


    /**
     *
     */
    public function children()
    {
        return $this->hasMany(SafeGuardRule::class, 'parent_id', 'id');
    }


    /**
     *
     */
    public function entries()
    {
        return $this->hasMany(SafeguardEntry::class, 'rule_id', 'id');
    }


    /**
     *
     */
    public function progress_type()
    {
        return $this->belongsTo(ReportProgressType::class, 'type_id', 'id');
    }


    /**
     *
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
