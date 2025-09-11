<?php

namespace App\Models\Geography;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geography_divisions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['district_id', 'name'];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     *
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }


    /**
     *
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }
}
