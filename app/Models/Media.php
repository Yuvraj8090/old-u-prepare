<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Media extends Model
{
    use HasFactory,  SoftDeletes;

    protected $table = "medias";

    protected $fillable = [
        'project_id', 'mediable_id', 'mediable_type', 'name',
        'year', 'status', 'file_type_name', 'activity_name', 
        'stage_name', 'remark', 'type', 'path'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            $media->year = date('Y');
        });
    }

    public function security()
    {
        return $this->belongsTo(Security::class, 'security_id');
    }

    public function mediable()
    {
        return $this->morphTo();
    }


    /**
     *
     */
    public function scopeOfProject($query, $project_id)
    {
        return $query->where('project_id', $project_id);
    }
}
