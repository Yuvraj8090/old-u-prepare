<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MilestoneValues extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'milestone_values';

    protected $fillable = [
        'type',
        'milestone_id',
        'project_id',
        'percentage',
        'status',
        'no_of_bills',
        'bill_serial_no',
        'amount',
        'date',
        'boq',
        'stage_name',
        'activity_name',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot Method to Auto-fill Project ID
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function ($value) {
            if (!$value->project_id && $value->milestone_id) {
                $milestone = MileStones::find($value->milestone_id);
                if ($milestone) {
                    $value->project_id = $milestone->project_id;
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(MileStones::class, 'milestone_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    public function milestoneDocs(): HasMany
    {
        return $this->hasMany(MilestoneValuesDocuments::class, 'milestone_value_id');
    }

    public function financeDoc(): HasOne
    {
        return $this->hasOne(MilestoneValuesDocuments::class, 'milestone_value_id');
    }

    public function document(): HasMany
    {
        return $this->hasMany(MilestonesDocument::class, 'milestone_id', 'milestone_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getDateAttribute($value): ?string
    {
        return $value ? date('d-m-Y', strtotime($value)) : null;
    }
}
