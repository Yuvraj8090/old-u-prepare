<?php

namespace App\Models;


use App\Models\MIS\SafeguardEntry;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MIS\Department;
class Projects extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "projects";

    const STAGE_INITIAL = [
        "0" => 'Yet To Intiated',
        '1' => 'Initiated',
        '2' => 'contracted',
        '3' => 'defined',
        '4' => 'Set milestones',
    ];

    protected $fillable = [
        'unique_id','user_id','dept_id','category_id','assign_to','name','number','project_type',
        'dec_approval_date','hpc_approval_date','estimate_budget','status','start_date','end_date',
        'dec_approval_letter_number','hpc_approval_letter_number','approved_by','approval_number',
        'stage','assign_level_2','procure_level_3' ,'environment_level_3','social_level_3' ,
        'district_name','assembly','constituencie','block','subcategory','es_level_four'
    ];
    // In Projects.php model
public function milestoneValuesUpdated()
{
    return $this->hasMany(MilestoneValueUpdated::class, 'project_id');
}

public function getMilestoneValueUpdatedTotalAmountAttribute()
{
    return $this->milestoneValuesUpdated()->sum('amount');
}

public function getMilestoneValueUpdatedTotalPercentageAttribute()
{
    return $this->milestoneValuesUpdated()->sum('percentage');
}

    public function contract()
    {
        return $this->HasOne(Contracts::class,'project_id','id');
    }
    public function contracts()
{
    return $this->hasMany(Contracts::class,'project_id','id');
}

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function subCategory()
    {
        return $this->hasOne(SubCategory::class,'name','subcategory','category_id','category_id');
    }
   public function departments_d()
{
    return $this->belongsTo(Department::class, 'dept_id');
}
    public function department()
    {
        return $this->belongsTo(Role::class, 'assign_to', 'id');
    }

 
   
    public function defineProject()
    {
        return $this->hasOne(DefineProject::class,'project_id','id');
    }

    public function params()
    {
        return $this->hasMany(ProcurementParams::class,'category_id','id');
    }

    public function paramsValues()
    {
        return $this->hasMany(ProcurementParamValues::class, 'project_id', 'id');
    }


     public function milestones()
    {
        return $this->HasMany(MileStones::class,'project_id','id');
    }
 public function district()
    {
        return $this->HasOne(Districts::class,'name','district_name');
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable')->latest();
    }
    public function dec_approval_doc()
    {
        return $this->morphOne(Media::class, 'mediable')->where('file_type_name', 'DEC Approval Document')->latest();
    }

    public function hpc_approval_doc()
    {
        return $this->morphOne(Media::class, 'mediable')->where('file_type_name', 'HPC Approval Document')->latest();
    }

    // 
    public function PiuLvlThree()
    {
        return $this->belongsTo(User::class,'assign_level_2','id');
    }

    public function socialThree()
    {
        return $this->belongsTo(User::class,'social_level_3','id');
    }

    public function procureThree()
    {
        return $this->belongsTo(User::class,'procure_level_3','id');
    }





   
    public function environmentMilestones()
    {
        return $this->HasMany(EnvironmentSocialMilestones::class,'project_id','id')->where('type','1');
    }

    public function socialMilestonesSocial()
    {
        return $this->HasMany(EnvironmentSocialMilestones::class,'project_id','id')->where('type','2');
    }


   
    

    public function EnvironmentDefineProject(){
        return $this->hasOne(EnvironmentSocialDefine::class,'project_id','id')->where('type','1');
    }

    public function SocialDefineProject(){
        return $this->hasOne(EnvironmentSocialDefine::class,'project_id','id')->where('type','2');
    }


    /**
     *
     */
    public function incharge()
    {
        return $this->belongsTo(User::class, 'assign_level_2', 'id');
    }

    /**
     *
     */
    public function safeguard_entries()
    {
        $this->hasMany(SafeguardEntry::class, 'project_id', 'id');
    }
     public function environmentThree()
    {
        return $this->belongsTo(User::class,'environment_level_3','id');
    }
}
