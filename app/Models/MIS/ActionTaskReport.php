<?php

namespace App\Models\MIS;

use App\Models\MIS\ATR\Component;
use App\Models\MIS\ATR\Organization;
use App\Models\MIS\ATR\Status;
use App\Models\MIS\ATR\SubComponent;
use App\Models\MIS\ATR\TaskType;
use Illuminate\Database\Eloquent\Model;

class ActionTaskReport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'atrs';


    /**
     *
     */
    public function task()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id', 'id');
    }


    /**
     *
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }


    /**
     *
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    /**
     *
     */
    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }


    /**
     *
     */
    public function sub_component()
    {
        return $this->belongsTo(SubComponent::class, 'sub_component_id', 'id');
    }
}
