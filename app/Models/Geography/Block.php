<?php

namespace App\Models\Geography;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geography_blocks';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['division_id', 'district_id', 'name', 'slug'];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     *
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }


    /**
     *
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
