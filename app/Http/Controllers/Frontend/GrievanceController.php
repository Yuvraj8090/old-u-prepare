<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\DummyData;


use App\Mail\EMail;
use App\Models\Projects;
use App\Models\Grievance;
use App\Models\MIS\Department;
use App\Models\Grievance\Log;
use App\Models\Geography\Block;
use App\Models\Geography\District;
use App\Models\Grievance\ComplaintDetail;
use App\Models\Grievance\ComplaintNature;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class GrievanceController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $typology    = DummyData::typology();
        $districts   = District::orderBy('name')->get();
        // $categories  = Category::orderBy('name')->get();
        $categories  = ComplaintNature::orderBy('name')->get();

        return view('public.page.grievance.register', compact('typology', 'districts', 'categories'));
    }


    /**
     *
     */
    public function details(Request $request)
    {
        // $grievance = Grievance::with(['project.incharge'])->where('ref_id', 'GR00001')->first();
        // dd($grievance);
        $request->validate([
            'grievance_no'=> 'required|string',
        ]);

        $grievance = Grievance::where('ref_id', $request->grievance_no)->first();

        if($grievance)
        {
            return redirect()->back()->withErrors(['grm'=> $grievance]);
        }

        return redirect()->back()->withInput()->withErrors(['grievance_no'=> 'We did not found any record with the provided Grievance Number.']);
    }


    /**
     *
     */
    public function save(Request $request)
    {
        $return = [];

        $request->validate([
            // '_captcha'            => ['required', 'simple_captcha'],
            'full_name'           => ['nullable', 'string'],
            'address'             => ['nullable', 'string'],
            'email'               => ['nullable', 'email:filter'],
            'phone'               => ['nullable', 'digits:10'],
            'typology'            => ['required'],                                                       // Grievance Related to
            'typo_other'          => [Rule::when($request->typology == 'other', 'required|string')],     // Grievance Related to Other
            // 'category'            => [Rule::requiredIf($request->typology !== 'other')],                 // Nature of Complaint
            'category'            => ['required'],                                                       // Nature of Complaint
            'subcategory'         => [Rule::requiredIf($request->typology !== 'other')],                 // Detail of Complaint
            'scat_other'          => [Rule::when($request->subcategory == 'other', 'required|string')],  // Detail of Complaint Other
            'district'            => ['required'],
            'block'               => ['required'],
            'village'             => ['nullable', 'string'],
            'project'             => ['required'],
            'proj_other'          => [Rule::when($request->project == 'other', 'required|string')],
            'description'         => ['nullable', 'string'],
            'file'                => ['nullable', File::types(['jpg', 'jpeg', 'pdf', 'mp4'])],
            'g-recaptcha-response'=> ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => env('GOOGLE_RECAPTCHA_SECRET'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

                if (!($response->json()['success'] ?? false))
                {
                    $fail('Google reCAPTCHA verification failed.');
                }
            }],
        ], [
            'full_name.string'             => 'Full name should be a string value.',
            'address.string'               => 'Address should be a string value.',
            'village.string'               => 'Village value should be a string.',
            // 'phone.required'               => 'The mobile number is required.',
            'category.required_if'         => 'The category field is required.',
            'subcategory.required_if'      => 'The sub-category field is required.',
            'desc.required'                => 'The description field value should be a string.',
            'typo_other.required_if'       => 'Please specify typology other value.',
            'scat_other.required_if'       => 'Please specify subcategory other value.',
            'scat_other.string'            => 'Please specify valid other value for sub-category.',
            'scat_other.required'          => 'Please specify other value for sub-category.',
            'proj_other.string'            => 'Please specify valid other value for project',
            'proj_other.required'          => 'Please specify other value for project',
            'g-recaptcha-response.required'=> 'CAPTCHA Verification Failed.',
        ]);

        $typology   = $request->typology != 'other' ? DummyData::typology($request->typology, 1) : $request->typology;

        if($typology)
        {
            $district   = District::where('slug', $request->district)->first();
            // $department = $request->typology != 'other' ? Role::where('department', $typology->dept)->first() : 0;
            $department = ($request->typology != 'other') ? Department::where('name', $typology->dept)->first() : 0;

            if($district)
            {
                $block = Block::where('slug', $request->block)->first();
                // dd($block, $request);
                // if($block)
                // {
                    // $category = Category::where('slug', $request->category)->first();
                    $category = ComplaintNature::where('slug', $request->category)->first();

                    // if($request->typology == 'other' || $category)
                    if($category)
                    {
                        // $subCat = SubCategory::where('slug', $request->subcategory)->first();
                        $subCat = ComplaintDetail::where('slug', $request->subcategory)->first();

                        if($request->subcategory == 'other' || $subCat)
                        {
                            $project_id = $request->project !== 'other' ? decrypt($request->project) : 0;
                            $project = Projects::find($project_id);

                            if($project || $request->project == 'other')
                            {
                                $file_path = NULL;

                                if($request->hasFile('file'))
                                {
                                    $file      = $request->file('file');
                                    $file_name = 'grievance_doc_' . time() . '.' . $file->getClientOriginalExtension();
                                    $file_path = 'uploads/' . date('Y') . '/' . date('m') . '/';
                                    // $doc_path  = $file->storeAs($file_path, $file_name);

                                    if($file->move($file_path, $file_name))
                                    {
                                        $file_path = $file_path . $file_name;
                                    }
                                }

                                $lgid = Grievance::latest()->first();
                                $lgid = $lgid ? ($lgid->id + 1) : 1;
                                $lgid = 'GR' . str_pad($lgid, 5, '0', STR_PAD_LEFT);

                                $grievance = Grievance::create([
                                    'ref_id'     => $lgid,
                                    'address'    => $request->filled('address') ? $request->address : NULL,
                                    'email'      => $request->filled('email') ? $request->email : NULL,
                                    'phone'      => $request->phone,
                                    'dept_id'    => $department ? $department->id : $department,
                                    'proj_id'    => $project ? $project->id : 0,
                                    'user_id'    => ($project && $project->incharge) ? $project->incharge->id : 0,
                                    'village'    => $request->filled('village') ? $request->village : NULL,
                                    'typology'   => $request->typology,
                                    'block_id'   => $block ? $block->id : 0,
                                    'document'   => $file_path,
                                    'consent'    => $request->consent == 'yes' ? 'Yes' : 'No',
                                    'subcat_id'  => $subCat ? $subCat->id : 0,
                                    'on_behalf'  => $request->behalf == 'yes' ? 'Yes' : 'No',
                                    'registrant' => $request->filled('full_name') ? $request->full_name : NULL,
                                    'typo_other' => $request->filled('typo_other') ? $request->typo_other : NULL,
                                    'proj_other' => $request->filled('proj_other') ? $request->proj_other : NULL,
                                    'district_id'=> $district->id,
                                    'category_id'=> $category ? $category->id : 0,
                                    'scat_other' => $request->filled('scat_other') ? $request->scat_other : NULL,
                                    'description'=> $request->filled('description') ? $request->description : NULL,
                                ]);

                                Log::create([
                                    'title'       => 'Grievance registered and assigned to ' . ($project->incharge ? $project->incharge->name : 'GRM Nodal'),
                                    'user_id'     => $grievance->user_id,
                                    'grievance_id'=> $grievance->id
                                ]);

                                if($grievance->user_id)
                                {
                                    // Create a log entry for JE Assignment
                                    Log::create([
                                        'title'       => 'Grievance is at JE Level.',
                                        'user_id'     => $grievance->user_id,
                                        'grievance_id'=> $grievance->id
                                    ]);
                                }

                                $message = 'Dear';

                                if($grievance->name)
                                {
                                    $message .= ' ' . $grievance->name;
                                }

                                $message .= ', your grievance has been recorded.<br />Your Grievance ID is ' . $grievance->ref_id . '.';
                                $message .= '<br />You can check your grievance status on the website at <a href="' . route('public.grievance.status') . '">' . route('public.grievance.status') . '</a>';

                                // Send SMS/EMAIL to User if (Phone/Email) Provided
                                if($grievance->email)
                                {
                                    $subject  = 'Grievance Registration Confirmation - ' . config('app.name');
                                    $details  = (object) [
                                        'message'  => $message,
                                        'user_name'=> $grievance->registrant,
                                    ];
                                    $template = 'mail.grievance.registered';

                                    try {
                                        Mail::to($grievance->email)->send(new EMail($details, $subject, $template));
                                    }
                                    catch (Exception $e)
                                    {
                                        // dd($e);
                                    }
                                }

                                return redirect()->back()->withErrors(['success'=> $message]);
                            }
                            else
                            {
                                $return['project'] = 'Invalid project value is detected';
                            }
                        }
                        else
                        {
                            // $return['subcategory'] = 'Invalid sub-category value is detected.';
                            $return['subcategory'] = 'Invalid detail of complaint value is detected.';
                        }
                    }
                    else
                    {
                        // $return['category'] = 'Invalid category value is detected.';
                        $return['category'] = 'Invalid nature of complaint value is detected.';
                    }
                // }
                // else
                // {
                    // $return['block'] = 'Invalid block value is detected.';
                // }
            }
            else
            {
                $return['district'] = 'Invalid district value is detected.';
            }
        }
        else
        {
            $return['typology'] = 'Invalid typology value is detected.';
        }

        return redirect()->back()->withInput()->withErrors($return);
    }


    /**
     *
     */
    public function getProjects(Request $request)
    {
        // dd($request->all());
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('slug'))
        {
            $projects = Projects::where('district_name', $request->slug)->get();

            // $project = Projects::where('block', $request->slug)->get(['id AS slug', 'name']);

            $return['msg']  = 'Currently there are no projects running for this block.';
            $return['ok']   = 1;
            $return['data'] = [];

            if($projects->count())
            {
                $return['msg']  = $projects->count() . ' Project fetched successfully.';
                $return['data'] = $this->buildSluggedData($projects, true);
            }
        }

        return $return;
    }


    /**
     *
     */
    public function getDistricts(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected.'];

        if($request->filled('slug'))
        {
            $typology = $request->slug != 'other' ? DummyData::typology($request->slug, 1) : 0;

            $all_d = 0;

            if($typology)
            {
                // $dept = Role::where('department', $typology->dept)->first();
                $dept      = Department::where('name', $typology->dept)->first();

                $districts = Projects::select('district_name AS name')->where('dept_id', $dept->id)->distinct()->get();

                $data = [];

                if($districts->count() > 1 || ($districts->count() == 1 && $districts[0]->name != 'All'))
                {
                    $data = $this->buildSluggedData($districts);
                }
                else
                {
                    $all_d = 1;
                }
            }
            else
            {
                $all_d = 1;
            }

            if($all_d)
            {
                $data = District::select(['name', 'slug'])->get();
            }

            $return['ok']   = 1;
            $return['msg']  = count($data) . ' Districts fetched successfully!';
            $return['data'] = $data;
        }

        return $return;
    }


    /**
     *
     */
    public function getBlocks(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('slug', 'grt'))
        {
            $district = District::where('slug', $request->slug)->first();

            if($district)
            {
                $typology = $request->grt != 'other' ? DummyData::typology($request->grt, 1) : 0;

                $data = [];

                // if($typology)
                // {
                    // $dept = Role::where('department', $typology->dept)->first();
                    $dept = $typology ? Department::where('name', $typology->dept)->first() : 0;

                    // $blocks = Projects::select('block')->where('dept_id', $dept->id)->distinct()->get();

                    $blocks = Projects::query();
                    $blocks->select('block');

                    if($dept)
                    {
                        $blocks->where('dept_id', $dept->id);
                    }

                    $blocks->where('block', '!=', 'NA');
                    $blocks->whereNotNull('block');
                    $blocks = $blocks->distinct()->get();
                    $data   = Block::where('district_id', $district->id)->get(['slug', 'name']);
                    /**
                    if($blocks->count())
                    {
                        foreach($blocks as $block)
                        {
                            if(!empty($block->block))
                            {
                                $data[] = (object) ['name'=> $block->block, 'slug'=> Str::slug($block->block)];
                            }
                        }
                    }
                    */
                // }
                // else
                // {
                    // $data = Block::where('district_id', $district->id)->get(['slug', 'name']);
                // }

                $return['ok']   = 1;
                $return['msg']  = count($data) . ' blocks fetched.';
                $return['data'] = $data;
            }
        }

        return $return;
    }


    /**
     *
     */
    public function getSubCats(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('slug'))
        {
            // $category = Category::where('slug', $request->slug)->first();
            $category = ComplaintNature::where('slug', $request->slug)->first();

            if($category)
            {
                // $scats = SubCategory::where('category_id', $category->id)->get(['slug', 'name']);
                $scats = ComplaintDetail::where('nature_id', $category->id)->get(['slug', 'name']);

                $return['ok']   = 1;
                $return['msg']  = $scats->count() . ' subcategories fetched.';
                $return['data'] = $scats;
            }
        }

        return $return;
    }


    /**
     *
     */
    private function buildSluggedData($records, $enid = false)
    {
        $return = [];

        foreach($records as $record)
        {
            if(!empty($record->name))
            {
                $return[] = (object) ['name'=> $record->name, 'slug'=> $enid ? encrypt($record->id) : Str::slug($record->name)];
            }
        }

        return $return;
    }
}
