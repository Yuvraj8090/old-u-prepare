<?php

namespace App\Http\Controllers\MIS;

use App\Models\Projects;
use App\Models\MIS\SafeGuardRule;

use App\Http\Controllers\Controller;
use App\Models\MIS\ReportProgressType;

class ReportController extends Controller
{
    /**
     *
     */
    public function envSoc($type)
    {
        $phase = request()->get('phase') ?? 'pre-construction';

        if(in_array($type, ['social', 'environment']))
        {
            $phase = ReportProgressType::where('slug', $phase)->first();

            if($phase)
            {
                $phases          = ReportProgressType::all();
                $projects        = Projects::whereHas('contract')->get();
                $safeguard_rules = SafeGuardRule::with('children')->where('safeguard_type', $type)->where('is_heading', 1)->where('type_id', $phase->id)->get();

                return view('mis.report.env-soc', compact('type', 'phase', 'phases', 'projects', 'safeguard_rules'));
            }
        }

        abort(404);
    }
}
