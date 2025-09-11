<?php

namespace App\Http\Controllers\API;

use App\Models\Media;
use App\Models\Projects;
use App\Models\MileStones;
use App\Models\MilestoneValues;
use App\Models\EnvironmentSocialTest;
use App\Models\EnvironmentSocialPhotos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PhaseController extends Controller
{
    /**
     * 
     */
    public function index(Request $request)
    {
        $scode      = 400;
        $return     = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];
        $milestones = [];

        if($request->filled('project_id'))
        {
            $moduleType = $this->CheckEnvironmentSocialType();

            $phases = [];

            for($i=1; $i<=3; $i++)
            {
                $testsWithReports1 = EnvironmentSocialTest::where('type', $i)
                    ->where('module_type', $moduleType)
                    ->whereIn('project_id', [0, $request->project_id])
                    ->WithFilteredReports($request->project_id)
                    ->WithFilteredSubtests($request->project_id)
                    ->get();

                $totalTests        = $testsWithReports1->count();
                $testsCompleted    = $testsWithReports1->filter(function ($test) {
                    $testHasReports    = $test->reports;
                    $subtestsCompleted = $test->subtests->filter(function ($subtest) {
                        return ($subtest->reports != NULL);
                    })->isNotEmpty();

                    return $testHasReports || $subtestsCompleted;
                });

                $completed = 0;

                if($testsCompleted->count() > 0)
                {
                    $completed = number_format(($testsCompleted->count() / $totalTests) * 100);
                }

                if($i == 1)
                {
                    $name = "Pre-Construction Phase";
                }
                elseif($i == 2)
                {
                    $name = "Construction Phase"; 
                }
                elseif($i == 3)
                {
                    $name = "Post-Construction Phase";
                }

                $reportDates = $testsCompleted->map(function ($test) {
                    return $test->reports->actual_date ?? NULL;
                });

                $phases[] = [
                    'name'            => $name,
                    'type_id'         => $i,
                    'end_date'        => $reportDates->last() ??  'N/A',
                    'project_id'      => $request->project_id,
                    'start_date'      => $reportDates->first() ?? 'N/A',
                    'percentage'      => $completed,
                    'total_completed' => $testsCompleted->count(),
                    'total_activities'=> $totalTests,
                ];
            }

            unset($return['msg']);

            $scode            = 200;
            $return['ok']     = 1;
            $return['phases'] = $phases;
        }

        return response($return, $scode);
    }
    
    
    /**
     * 
     */
    public function activities(Request $request)
    {
        $scode      = 400;
        $return     = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];
        $activities = [];

        if($request->filled('type_id', 'project_id'))
        {
            $scode      = 422;

            $activities = $this->getActivityRecords($request);

            unset($return['msg']);

            $scode             = 200;
            $return['ok']      = 1;
            $return['records'] = $activities;
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function subActivities(Request $request)
    {
        $scode   = 400;
        $return  = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];
        $records = [];

        if($request->filled('type_id', 'project_id', 'activity_id'))
        {
            $scode   = 422;
            $records = $this->getActivityRecords($request);

            unset($return['msg']);

            $scode             = 200;
            $return['ok']      = 1;
            $return['records'] = $records;
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function activityImages(Request $request)
    {
        $scode      = 400;
        $return     = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];
        $activities = [];

        if($request->filled('activity_id', 'project_id'))
        {
            $test       = EnvironmentSocialTest::find($request->activity_id);
            $moduleType = $this->CheckEnvironmentSocialType();
            $photos     = EnvironmentSocialPhotos::where('project_id', $request->project_id)->where('test_id', $request->activity_id)->orderBy('id', 'desc')->get();

            unset($return['msg']);

            $scode            = 200;
            $return['ok']     = 1;
            $return['images'] = $photos;
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function uploadActivityImage(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
            'activity_id'=> 'required|numeric',
            'image'      => 'required|image|mimes:jpg,jpeg,png',
        ]);

        if(!$validator->fails())
        {
            $scode  = 422;
            $record = MilestoneValues::with('media')->latest()->find($request->mppr_id);

            if($request->hasFile('image'))
            {
                $file     = $request->file('image');
                $filename = str_replace(" ", "-" , time() . rand(1, 9999) . $file->getClientOriginalName());
                $file->move('images/test/environment/report/image/', $filename); 

                $result = EnvironmentSocialPhotos::create([
                    'name'      => $filename,
                    'test_id'   => $request->activity_id,
                    'project_id'=> $request->project_id,
                ]);

                if($result)
                {
                    unset($return['msg']);

                    $scode           = 200;
                    $return['ok']    = 1;
                    $return['image'] = $result;
                }
                else
                {
                    $return['msg'] = 'Unable to process your request at this time. Please try again!';
                }
            }
        }
        else
        {
            $return['msg'] = $validator->errors()->first();
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    private function getActivityRecords(Request $request)
    {
        $records    = [];
        $moduleType = $this->CheckEnvironmentSocialType();
        $activities = EnvironmentSocialTest::query();

        if($request->filled('activity_id'))
        {
            $activities->where('test_id', $request->activity_id);
        }
        else
        {
            $activities->where('test_id', 0);
        }

        $activities = $activities->withCount('subtests')
            ->where('module_type', $moduleType)
            ->where('type', $request->type_id)
            ->whereIn('project_id', [0,$request->project_id])
            ->WithFilteredReports($request->project_id)
            ->WithFilteredSubtests($request->project_id)
            ->orderBy('have_child', 'asc')
            ->orderBy('project_id','asc')
            ->get();

        foreach($activities as $activity)
        {
            $complied = 'N/A';

            if($activity->have_child)
            {
                $complied = '-';
            }
            elseif($activity->reports)
            {
                if($activity->reports->status == 1)
                {
                    $complied = 'Yes';
                }
                elseif($activity->reports->status == 2)
                {
                    $complied = 'No';
                }
                elseif($activity->reports->status == 3)
                {
                    $complied = 'Not Applicable';
                }
                else
                {
                    $complied = 'N/A';
                }
            }

            if($activity->reports)
            {
                $actual_date  = $activity->reports->actual_date;
                $planned_date = $activity->reports->planned_date;
            }
            elseif(isset($activity->subtests))
            {
                $actual_date  = $activity->subtests[0]->reports->actual_date ?? 'N/A1';
                $planned_date = $activity->subtests[0]->reports->planned_date ?? 'N/A1';
            }
            else
            {
                $actual_date  = 'N/A';
                $planned_date =  'N/A';
            }

            $records[] = [
                'id'            => $activity->id,
                'name'          => $activity->name,
                'action'        => $activity->have_child ? '-' : ($activity->reports ? 'Update' : 'Add'),
                'photos'        => $activity->have_child ? '-' : ($activity->reports ? 1 : 'N/A'),
                'remarks'       => $activity->have_child ? '-' : ($activity->reports->remark ?? 'N/A'),
                'document'      => $activity->have_child ? '-' : ($activity->reports->document ?? 'N/A'),
                'complied'      => $complied,
                'actual_date'   => $actual_date,
                'planned_date'  => $planned_date,
                'sub_activities'=> $activity->have_child ? $activity->subtests_count : 1,
            ];
        }

        return $records;
    }
}

