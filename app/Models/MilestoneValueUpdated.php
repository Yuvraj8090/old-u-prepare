<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MilestoneValueUpdated extends Model

{
    use HasFactory, SoftDeletes;

    protected $table = 'milestone_values_updated';

    protected $fillable = [
        'type',
        'project_id',
        'percentage',
        'amount',
        'date',
        'status',
        'boq',
        'no_of_bills',
        'bill_serial_no',
        'stage_name',
        'activity_name',
    ];

    protected $casts = [
        'date' => 'date',
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'status' => 'integer',
        'boq' => 'integer',
        'no_of_bills' => 'integer',
    ];

    public $timestamps = true;

    public function milestone()
{
    return $this->belongsTo(MileStones::class, 'milestone_id');
}

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Link to Project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    // Linked documents for this milestone value update
    public function documents(): HasMany
    {
        return $this->hasMany(MilestoneValuesDocuments::class, 'milestone_value_id');
    }

    // Optionally: get only one finance-related document (if needed)
    public function financeDoc(): HasOne
{
    return $this->hasOne(MilestoneValuesDocuments::class, 'milestone_value_id');
}


    // Attach media files
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Accessor Methods
    |--------------------------------------------------------------------------
    */
    public static function getTotalPercentage($projectId)
    {
        return self::where('project_id', $projectId)->sum('percentage');
    }

    public static function getTotalAmount($projectId)
    {
        return self::where('project_id', $projectId)->sum('amount');
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
