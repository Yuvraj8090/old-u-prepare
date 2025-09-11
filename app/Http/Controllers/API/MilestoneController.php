<?php

namespace App\Http\Controllers\API;

use App\Models\Media;
use App\Models\Projects;
use App\Models\MileStones;
use App\Models\MilestoneValues;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MilestoneController extends Controller
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
            $project = Projects::with('milestones')
                ->where('number', $request->project_id)
                ->where('category_id', 1)
                ->where(function($query) {
                    $query->where('environment_level_3', auth()->user()->role->id)
                    ->orWhere('assign_level_2', auth()->id());
                })->first();

            if($project)
            {
                if($project->milestones && $project->milestones->count())
                {
                    $pms = $this->milestoneCalculate($project->milestones);

                    foreach($pms as $milestone)
                    {
                        $milestones[] = (object) [
                            'id'       => $milestone->id,
                            'name'     => $milestone->name,
                            'weightage'=> $milestone->percent_of_work,
                            'progress' => $milestone->physicalProgress,
                        ];
                    }
                }

                unset($return['msg']);

                $scode                = 200;
                $return['ok']         = 1;
                $return['milestones'] = $milestones;
            }
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function physicalProgress(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('milestone_id'))
        {
            $milestone = MileStones::with('document')->find($request->milestone_id);

            if($milestone)
            {
                $milestones      = [];
                $milestoneValues = MilestoneValues::with('milestoneDocs')->where('type', '1')->where('milestone_id', $request->milestone_id)->get();

                foreach($milestoneValues as $value)
                {
                    $ms_value = (object) [
                        'id'        => $value->id,
                        'date'      => $value->date,
                        'status'    => $value->status,
                        'amount'    => $value->amount,
                        'percentage'=> $value->percentage,
                        'documents' => []
                    ];

                    foreach($value->milestoneDocs as $doc)
                    {
                        $ms_value->documents[] = asset('/images/physical_progress/' . $doc->file);
                    }

                    $milestones[] = $ms_value;
                }

                $milestone = (object) [
                    'id'     => $milestone->id,
                    'name'   => $milestone->name,
                    'records'=> $milestones
                ];
            }

            unset($return['msg']);

            $scode               = 200;
            $return['ok']        = 1;
            $return['milestone'] = $milestone;
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function physicalProgressImages(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('mppr_id'))
        {
            $record = MilestoneValues::with('media')->latest()->find($request->mppr_id);
            $images      = [];

            if($record && $record->media)
            {

                foreach($record->media as $image)
                {
                    $images[] = (object) [
                        'id'        => $image->id,
                        'parent_id' => $image->mediable_id,
                        'file'      => asset('images/milestone/site/' . $image->name)
                    ];
                }
            }

            unset($return['msg']);

            $scode            = 200;
            $return['ok']     = 1;
            $return['images'] = $images;
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function uploadPhysicalProgressImage(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            'mppr_id'=> 'required|numeric|exists:milestone_values,id',
            'image'  => 'required|image|mimes:jpg,jpeg,png',
        ]);

        if(!$validator->fails())
        {
            $scode  = 422;
            $record = MilestoneValues::with('media')->latest()->find($request->mppr_id);

            if($request->hasFile('image'))
            {
                $file     = $request->file('image');
                $filename = str_replace(" ", "-" , time() . rand(1, 9999) . $file->getClientOriginalName());
                $file->move('images/milestone/site/', $filename); 

                $result = Media::create([
                    'name'         => $filename,
                    'mediable_id'  => $request->mppr_id,
                    'mediable_type'=> MilestoneValues::class,
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
    public function updatePhysicalProgress(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            'milestone_id'=> 'required|numeric',
            'progress'    => 'required|numeric|min:0|max:100',
            'date'        => 'required|date'
        ]);

        if(!$validator->fails())
        {
            $milestone = Milestones::find($request->milestone_id);

            if($milestone)
            {
                $scode   = 422;
                $centage = MilestoneValues::where('milestone_id', $request->milestone_id)->whereType(1)->sum('percentage');

                if($centage < 100)
                {
                    $avail_centage = max(0, (100 - $centage));
                    $sent_centage  = intval($request->progress);

                    if($sent_centage <= $avail_centage)
                    {
                        $data                 = $request->all();

                        $data['type']         = 1;
                        $data['date']         = date('Y-m-d', strtotime($request->date));
                        $data['percentage']   = $request->progress;
                        $data['milestone_id'] = $request->milestone_id;

                        if(MilestoneValues::create($data))
                        {
                            $scode         = 200;
                            $return['ok']  = 1;
                            $return['msg'] = 'Milestone Updated Successfully';
                        }
                    }
                    else
                    {
                        $return['msg'] = 'Physical Progress percentage can\'t be more than ' . $avail_centage;
                    }
                }
                else
                {
                    $return['msg'] = 'Warning! MileStone is completed not be able to add more.';
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
    public function milestoneCalculate($data = [])
    {
        if($data)
        {
            foreach($data as $d)
            {
                $financialProgress = 0;
                $physicalProgress  = 0;

                if (count($d->values) > 0)
                {
                    foreach ($d->values as $mile)
                    {
                        if($mile->type == 1)
                        {
                            $percent = $mile->percentage;
                            $physicalProgress += $percent;
                        }

                        if($mile->type == 2)
                        {
                            $financialProgress += $mile->percentage;
                        }
                    }

                    $physicalProgress  = (int) $physicalProgress;
                    $financialProgress = (int) $financialProgress;

                }
                else
                {
                    $financialProgress = 0;
                }

                $d->physicalProgress = $physicalProgress;
                $d->financialProgress = $financialProgress;
            }
        }

        return $data;
    }
}

