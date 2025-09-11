<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MilestoneValues,Media,MileStones};
use Illuminate\Support\Facades\Validator;

class MileStoneSiteController extends Controller
{

    // Code added 7 feb 2024
    public function index(Request $request, $id)
    {
        $data = MilestoneValues::with('media', 'milestone')->orderBy('id', 'desc')->find($id); //   // Code changes 8 Feb 2024

        return view('admin.sites.index', compact('data', 'id'));
    }


    /**
     *
     */
    public function milestoneImages($id)
    {
        $data = MileStones::with('project.contract', 'media')->orderBy('id', 'desc')->find($id);

        return view('admin.sites.index', compact('data', 'id'));
    }


    /**
     *
     */
    public function deleteMilestoneImage(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected.'];

        if($request->filled('media_id'))
        {
            $media = Media::find($request->media_id);

            if($media)
            {
                if($media->delete())
                {
                    $return['ok'] = 1;
                    $return['msg'] = 'The Image is deleted successfully';
                }
                else
                {
                    $return['msg'] = 'Failed to delete the image this time. Please try again!';
                }
            }
            else
            {
                $return['msg'] = 'Failed to find the image to be deleted';
            }
        }

        return $return;
    }


    /**
     *
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
{
    $rules = [
        'siteimages'    => 'required|array',
        'siteimages.*'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'remark'        => 'required|string',
        'stage_name'    => 'required|string',
        'activity_name' => 'required|string',
    ];

    if (!isset($request->ms_img)) {
        $rules['id'] = 'required|numeric|exists:milestone_values_updated,id';
    } else {
        $rules['id'] = 'required|numeric|exists:procuremnt_milestones,id';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()]);
    }

    // Determine target model (Milestone or MilestoneValue)
    if ($request->has('ms_img')) {
        $model = MileStones::with('project')->findOrFail($request->id);
    } else {
        $model = MilestoneValues::with('milestone.project')->findOrFail($request->id);
    }

    // Get the related project_id
    $projectId = $request->has('ms_img') 
        ? ($model->project->id ?? null) 
        : ($model->milestone->project->id ?? null);

    if (!$projectId) {
        return response()->json(['error' => 'Associated project not found.']);
    }

    // Save images
    foreach ($request->file('siteimages') as $file) {
        $filename = str_replace(' ', '-', time() . rand(1, 9999) . $file->getClientOriginalName());
        $file->move(public_path('images/milestone/site/'), $filename);

        Media::create([
            'name'          => $filename,
            'remark'        => $request->remark,
            'stage_name'    => $request->stage_name,
            'activity_name' => $request->activity_name,
            'project_id'    => $projectId, // Optional if your media table has this field
            'mediable_id'   => $model->id,
            'mediable_type' => get_class($model),
        ]);
    }

    // Redirect or return success
    $redirectUrl = $request->has('ms_img')
        ? route('site.milestone.images', $request->id)
        : url('/site/index/' . $request->id);

    return $this->success('created', 'Project Milestone', $redirectUrl);
}



    /**
     *
     *
     * @param Request $request
     * @return void
     */
    // Code added on 8 feb 2024
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'           => 'required|numeric',
            'siteimage'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'remark'       => 'required|string',
            'stage_name'   => 'required|string',
            'activity_name'=> 'required|string',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->has('siteimage'))
        {
            $file     = $request->file('siteimage');
            $filename = str_replace(" ", "-", time() . rand(1, 9999) . $file->getClientOriginalName());

            $file->move('images/milestone/site/', $filename);

            $response = Media::find($request->id)->update([
                'name'         => $filename,
                'remark'       => $request->remark,
                'stage_name'   => $request->stage_name,
                'activity_name'=> $request->activity_name,
            ]);
        }

        if($response)
        {
            $url =  isset($request->ms_img) ? route('site.milestone.images', $request->milestoneId) : url('/site/index/' . $request->milestoneId);
            // $url = url('/site/index/' . $request->milestoneId);

            return $this->success('created', 'Project Milestone', $url);
        }

        return $this->success('error','Project Milestone ');
    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $data = Media::find($id)->delete();

        return back()->with('message', 'Image Removed Successfully.');
    }
}
