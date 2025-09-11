<?php

namespace App\Models;


use App\Models\Grievance\Log;
use App\Models\Grievance\Action;
use App\Models\Grievance\Category;
use App\Models\Grievance\SubCategory;

use App\Models\MIS\Department;
use App\Models\Geography\Block;
use App\Models\Geography\District;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grievance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ref_id', 'user_id', 'block_id', 'district_id',
        'category_id', 'dept_id', 'proj_id', 'subcat_id',
        'registrant', 'address', 'email', 'phone', 'scat_other',
        'description', 'typology', 'document', 'proj_other',
        'on_behalf', 'consent', 'typo_other', 'village',
    ];


    /**
     *
     */
    public function action() : HasOne
    {
        return $this->hasOne(Action::class, 'grievance_id', 'id');
    }


    /**
     *
     */
    public function block() : BelongsTo
    {
        return $this->belongsTo(Block::class);
    }


    /**
     *
     */
    public function district() : BelongsTo
    {
        return $this->belongsTo(District::class);
    }


    /**
     *
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    /**
     *
     */
    public function subcategory() : BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }


    /**
     *
     */
    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    /**
     *
     */
    public function incharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     *
     */
    public function getDateFullFormatAttribute()
    {
        return $this->created_at->format('D') . ', ' . $this->created_at->format('j') . '<sup>' . $this->created_at->format('S') . '</sup> of ' . $this->created_at->format('M Y') . ' at ' . $this->created_at->format('h:m a');
    }

    /**
     *
     */
    public function logs()
    {
        return $this->hasMany(Log::class, 'grievance_id', 'id');
    }
}
