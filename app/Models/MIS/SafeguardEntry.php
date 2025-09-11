<?php

namespace App\Models\MIS;

use Illuminate\Database\Eloquent\Model;

class SafeguardEntry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'env_soc_safeguard_entries';


    /**
     *
     */
    protected $fillable = [
        'rule_id', 'user_id', 'project_id', 'entry_date',
        'label_1', 'label_2', 'label_3', 'label_4',
    ];
}
