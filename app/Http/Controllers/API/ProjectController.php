<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Projects;
use App\Models\Districts;
use App\Models\ProjectCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * 
     */
    public function index()
    {
        $user_role = auth()->user()->role;

        $dept = explode('-', $user_role->department);

        $data = Projects::query();

        if(in_array('ENVIRONMENT', $dept))
        {
            $data->has('contract');
            $data->where('category_id', '1');
            $data->whereNotIn('stage', [0, 1]);

            if($this->CheckEnvironmentLvlThree($user_role->department, 'THREE'))
            {
                $data->where('environment_level_3', $user_role->id);
            }
            elseif($user_role->department == "ENVIRONMENT" &&  $user_role->level == 'TWO')
            {
                $data->with('environmentMilestones');
            }
            else
            {
                $data->where('es_level_four', auth()->id());
            }
        }
        else
        {
            $data->where('assign_level_2', auth()->id());
            $data->whereIn('stage', [3, 4]);
        }

        $data = $data->latest()->get(['id', 'name', 'number AS project_id']);

        return response([
            'ok'      => 1,
            'projects'=> $data
        ], 200);
    }


    /**
     * 
     */
    public function images(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];
        $images = [];

        if($request->filled('project_id'))
        {
            $project = Projects::with(['milestones.document', 'milestones.values'=> function($query) {
                $query->with(['milestoneDocs', 'media'])->where('type', 1);
            }])->where('number', $request->project_id)->first();

            if($project)
            {
                if($project->milestones)
                {
                    foreach($project->milestones as $milestone)
                    {
                        if($milestone->values)
                        {
                            foreach($milestone->values as $mvalue)
                            {
                                if($mvalue->media)
                                {
                                    foreach($mvalue->media as $media)
                                    {
                                        $images[] = (object) [
                                            'id'        => $media->id,
                                            'file'      => asset('images/milestone/site/' . $media->name),
                                            'created_at'=> $media->created_at,
                                            'updated_at'=> $media->created_at
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }

                unset($return['msg']);

                $scode            = 200;
                $return['images'] = $images;
            }
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function details(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('project_id'))
        {
            $data = Projects::where('number', $request->project_id)->first();
            // $data = $this->calculateWeightPercentage($data);

            $category   = ProjectCategory::all();
            $department = Role::where('affilaited', auth()->user()->role_id)->where('id', '!=', '6')->get();
            $years      = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
            $districts  = Districts::orderBy('name', 'asc')->get();

            $details = (object) [
                'name'             => $data->name,
                'block'            => $data->block,
                'budget'           => $data->estimate_budget,
                'assembly'         => $data->assembly,
                'category'         => $data->category->name,
                'district'         => $data->district_name,
                'department'       => $data->department->department,
                'subcategory'      => $data->subCategory->name,
                'project_type'     => $data->project_type,
                'constituency'     => $data->constituencie,
                'dec_approval_date'=> $data->dec_approval_date,
                'hpc_approval_date'=> $data->hpc_approval_date,
            ];

            unset($return['msg']);

            $scode             = 200;
            $return['ok']      = 1;
            $return['project'] = $details;
        }

        return response($return, $scode);
    }
    

    /**
     * 
     */
    private function envProjects()
    {
        $userRole  = auth()->user()->role;

        $data = Projects::query();
        $data->has('contract');
        $data->where('category_id', '1');
        $data->whereNotIn('stage', [0,1]);

        if($this->CheckSocialLvlThree($userRole->department,'THREE'))
        {
            $data->where('social_level_3',$userRole->id);
        }
        elseif($userRole->department == "SOCIAL" &&  $userRole->level == 'TWO')
        {
            $data->with('socialMilestonesSocial');
        }

        $data = $data->orderBy('id','desc')->paginate('20');
        $data = $this->EnvironmentAndSocialcalculateWeightPercentage($data);

        $department = Role::where('affilaited','2')->whereNotIn('id',[6,19,25])->get();
    }
}
