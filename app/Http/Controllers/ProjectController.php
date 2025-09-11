<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MIS\Department;
use App\Models\{Projects,ProjectCategory,Role,DefineProject,User,MileStones,
Media,SubCategory,Assembly,Constituencies,Blocks, Contracts, Districts,MilestoneValues,
MaterialTest,EnvironmentTest,PWDBOQ};
use App\Models\MilestoneValueUpdated;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        $data = Projects::query();
        $data->with('department', 'defineProject', 'milestones', 'category');

        if ($request->name)
        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('subcategory','LIKE','%'.$request->name.'%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->name . '%');
            });
        }

        auth()->user()->update([
            'district'=> $request->filled('project_districts') ? json_encode($request->project_districts) : NULL
        ]);

        if(auth()->user()->district)
        {
            $data->whereIn('district_name', json_decode(auth()->user()->district));
        }

        if($request->department)
        {
            $data->where('assign_to', $request->department);
        }

        if($request->category)
        {
            $data->where('category_id', $request->category);
        }

        if($request->subcategory)
        {
            $data->where('subcategory', $request->category);
        }

        if($request->filled('status') || intval($request->status))
        {
            $data->where('stage', $request->status);
        }

        if($request->year)
        {
            $data->whereYear('hpc_approval_date', $request->year);
        }

        if($request->completion_year)
        {
            $data->where('status', '3')->whereYear('created_at', $request->completion_year);
        }

        if(auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT')
        {
            $s_arr = [0, 1];

            if(!$request->filled('status') || ($request->filled('status') && $request->status != 5))
            {
                $s_arr[] = 5;
            }

            $data->where('assign_to', auth()->user()->role->id);
            $data->whereNotIn('stage', $s_arr);
        }

        $data = $data->orderBy('id', 'desc')->get();
        $data = $this->calculateWeightPercentage($data);

        $category    = ProjectCategory::all();
        $subcategory = SubCategory::orderBy('name')->get()->unique('name');

        $department = Role::where('affilaited', auth()->user()->role_id)->whereNotIn('id', [6, 19, 24, 25, 26])->where('id', '!=', '6')->get();

        $years = Projects::distinct()
            ->selectRaw('YEAR(hpc_approval_date) as year')
            ->pluck('year');

        $districts         = Districts::orderBy('name', 'asc')->get();
        $project_districts = Projects::distinct('district_name')->orderBy('district_name')->get('district_name AS name');

        return view('admin.project.index', compact('data', 'category', 'districts', 'years', 'department', 'project_districts', 'subcategory'));
    }


    /**
     *
     */
    public function indexAjax(Request $request)
    {
        $data = Projects::query();
        $data->with('department','defineProject','milestones','category');

        if($request->name)
        {
            $data->orwhere('name','LIKE','%'.$request->name.'%');
            $data->orWhereHas('category',function($query) use ($request){
                $query->where('name','LIKE','%'.$request->name.'%');
            });
            $data->orWhereHas('department',function($query) use ($request){
                $query->where('department','LIKE','%'.$request->name.'%');
            });
        }

        if($request->status || $request->status == "0"){
            $data->where('stage',$request->status);
        }

        if($request->year){
            $data->whereYear('hpc_approval_date',$request->year);
        }

        if($request->completion_year){
            $data->where('status','3')->whereYear('created_at',$request->completion_year);
        }

        if(auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT'){
            $data->where('assign_to',auth()->user()->role->id);
            $data->whereNotIn('stage',[0,1]);
        }

        $data = $data->orderBy('id','desc')->paginate('20');
        $data = $this->calculateWeightPercentage($data);

        $i = 0;
        $transformedData['data'] = $data->getCollection()->map(function ($project) use (&$i) {

            return [
                'id' => ++$i.'.',
                'name' => $project->name,
                'category' => $project->category->name,
            ];
        });

        return response()->json($transformedData);

        return view('admin.project.index', compact('data','category','districts','years','department'));
    }


       public function show($id)
    {
        $projectData = Projects::with([
            'defineProject',
            'contract.physical_progress', // eager load
            'milestones.values',
            'district',
            'procureThree',
            'category',
            'paramsValues',
            'PiuLvlThree',
            'EnvironmentDefineProject',
            'SocialDefineProject',
            'environmentMilestones',
            'socialMilestonesSocial',
            'subCategory',
        ])->findOrFail($id);

        // Extract common variables
        $contracts = $projectData->contract;
        $defineProject = $projectData->defineProject ?? [];
        $milestones = $this->milestoneCalculate($projectData->milestones);
        $params = $projectData->paramsValues ?? [];
        $mileStonechartData = $this->getProjectMilestoneData($projectData);
        $milestonePhysicalProgress = $this->calculatePhysicalProgressPerMilestone($projectData->milestones);
        
        // ---------- MATERIAL TESTS ----------
        $materialData = MaterialTest::where('type', 1)
            ->whereIn('project_id', [0, $id])
            ->get();

        $materialData->each(function ($test) use ($id) {
            $test->total_reports = $test->reports()->where('project_id', $id)->count();
            $test->passed_reports = $test->reports()->where('project_id', $id)->where('status', 1)->count();
            $test->failed_reports = $test->reports()->where('project_id', $id)->where('status', 0)->count();
        });

        $materialData['status_percentage'] = $materialData->sum('total_reports') ? number_format(($materialData->sum('passed_reports') / $materialData->sum('total_reports')) * 100, 2) : 0;

        // ---------- CATEGORY TESTS ----------
        $categoryData = MaterialTest::where('type', 2)
            ->where('category', $projectData->subCategory->id ?? null)
            ->whereIn('project_id', [0, $id])
            ->get();

        $categoryData->each(function ($test) use ($id) {
            $test->total_reports = $test->reports()->where('project_id', $id)->count();
            $test->passed_reports = $test->reports()->where('project_id', $id)->where('status', 1)->count();
            $test->failed_reports = $test->reports()->where('project_id', $id)->where('status', 0)->count();
        });

        $categoryData['status_percentage'] = $categoryData->sum('total_reports') ? number_format(($categoryData->sum('passed_reports') / $categoryData->sum('total_reports')) * 100, 2) : 0;

        // ---------- ENVIRONMENT TESTS ----------
        $environmentTest = EnvironmentTest::with('reports')
            ->whereIn('project_id', [0, $id])
            ->get();

        $environmentTest->each(function ($test) use ($id) {
            $test->total_reports = $test->reports()->where('project_id', $id)->count();
            $test->passed_reports = $test->reports()->where('project_id', $id)->where('status', 1)->count();
            $test->failed_reports = $test->reports()->where('project_id', $id)->where('status', 0)->count();
        });

        $evTotal = $environmentTest->count();
        $evPass = $environmentTest->sum('passed_reports');
        $evFailed = $environmentTest->sum('failed_reports');
        $evStatusPercentage = $evTotal && $evPass ? number_format(($evPass / $evTotal) * 100, 2) : 0;

        // ---------- PHYSICAL PROGRESS (Fixed) ----------
        $physicalProgress = 0;
        if ($contracts && is_object($contracts)) {
    $sum = DB::table('contract_physical_progress')
        ->where('contract_id', $contracts->id)
        ->sum('progress');

    $physicalProgress += $sum;
}
        $milestoneFinancial = $this->calculateFinancialProgressPerMilestone($id);

        // ---------- SAFEGUARDS & CALCULATIONS ----------
        $envSafeguards = $this->environmentAndSocialTestCalc($projectData, 'environment');
        $socSafeguards = $this->environmentAndSocialTestCalc($projectData, 'social');
        $environmentCalculation = $this->EnviornmentSocialtestCalculation(1, $id);
        $socialCalculation = $this->EnviornmentSocialtestCalculation(2, $id);

        // ---------- RETURN VIEW ----------
        return view('admin.project.view', [
            'data' => $projectData,
            'contracts' => $contracts,
            'defineProject' => $defineProject,
            'params' => $params,
            'milestonePhysicalProgress' => $milestonePhysicalProgress,
            'milestones' => $milestones,
            'mileStonechartData' => $mileStonechartData,
            'materialData' => $materialData,
            'categoryData' => $categoryData,
            'evTotal' => $evTotal,
            'milestoneFinancial' => $milestoneFinancial,
            'evPass' => $evPass,
            'evFailed' => $evFailed,
            'evStatusPercentage' => $evStatusPercentage,
            'environmentCalculation' => $environmentCalculation,
            'socialCalculation' => $socialCalculation,
            'envSafeguards' => $envSafeguards,
            'socSafeguards' => $socSafeguards,
            'physicalProgress' => $physicalProgress,
        ]);
    }
private function calculateFinancialProgressPerMilestone($projectId)
{
    $milestoneValues = MilestoneValueUpdated::select([
        'percentage', 'amount'
    ])->where('project_id', $projectId)->get();

    $totalAmountSpent = $milestoneValues->sum('amount');
    $totalPercentage = $milestoneValues->sum('percentage');

    return [
        'total_amount_spent' => $totalAmountSpent,
        'total_percentage' => $totalPercentage,
    ];
}
private function calculatePhysicalProgressPerMilestone($milestones)
{
    if (!$milestones || $milestones->isEmpty()) {
        return 0;
    }

    $totalProgress = 0;
    $count = 0;

    foreach ($milestones as $milestone) {
        // Check if progress field exists and is numeric
        if (isset($milestone->progress) && is_numeric($milestone->progress)) {
            $totalProgress += $milestone->progress;
            $count++;
        }
    }

    // Return average progress percentage
    return $count > 0 ? number_format($totalProgress / $count, 2) : 0;
}


    public function view($id)
    {
        $data = Projects::with([
            'defineProject','contract','milestones',
            'district','procureThree','category','paramsValues',
            'milestones','PiuLvlThree','EnvironmentDefineProject',
            'SocialDefineProject','environmentMilestones','socialMilestonesSocial'
        ])->findorfail($id);

        $contracts     = $data->contract ?? [];
        $defineProject = $data->defineProject ?? [];
        $milestones    = $this->milestoneCalculate($data->milestones) ?? [];
        $params        = $data->paramsValues ?? [];

          return view('admin.project.project-view', compact('data', 'contracts', 'defineProject', 'params', 'milestones'));
    }


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
                            $percent           = $mile->percentage;
                            $physicalProgress += $percent;
                        }

                        if($mile->type == 2)
                        {
                            $financialProgress += $mile->percentage;
                        }
                    }

                    $physicalProgress = (int) $physicalProgress;
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


    public function level2Index(Request $request)
    {
        $data = Projects::query();
        $data->with('department','defineProject','PiuLvlThree');

        if($request->department)
        {
            $data->where('assign_to',$request->department);
        }

        if($request->category)
        {
            $data->where('category_id',$request->category);
        }

        if($request->status)
        {
            $data->where('status',$request->status);
        }

        if($request->year)
        {
            $data->whereYear('hpc_approval_date',$request->year);
        }

        if(auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT')
        {
            $data->where('assign_to',auth()->user()->role->id);
        }

        if(auth()->user()->role->level == 'TWO')
        {
            $data->where('stage','3');
        }

        $data = $data->orderBy('id','desc')->paginate('20');

        // dd($data->toArray());

        $category = ProjectCategory::all();

        $department = Role::where('affilaited',auth()->user()->role_id)->where('id','!=','6')->get();

        $years = Projects::distinct()
        ->selectRaw('YEAR(hpc_approval_date) as year')
        ->pluck('year');

        $districts = Districts::orderBy('name','asc')->get();

        $AssignDepartment = User::query();
        $AssignDepartment->with('role')->where('user_id',auth()->user()->id);
        $AssignDepartment = $AssignDepartment->get();

        return view('admin.assign-project.project-level-2', compact('data','category','districts','years','department','AssignDepartment'));
    }


    public function create()
    {
        if(auth()->user()->role_id !== 1)
        {
            $category       = ProjectCategory::get();
            $assembly       = Assembly::orderBy('name', 'asc')->get();
            $constituencies = Constituencies::orderBy('name', 'asc')->get();
            $districts      = Districts::orderBy('name', 'asc')->get();
            // $department     = Department::with('children')->where('slug', 'piu')->first();       // To be implemented when Roles/Permission will be ready
            $department     = Role::where('affilaited', auth()->user()->role_id)->whereNotIn('id', [6, 19, 24, 25, 26])->get();

            return view('admin.project.create',compact('category', 'assembly', 'department', 'constituencies', 'districts'));
        }

        abort(404);
    }


    public function getTehsilBlocks($id = NULL)
    {
        $data = ['const'=> [], 'tehsils'=> [], 'districts'=> []];

        if(!$id)
        {
            $id = request()->get('assembly');
        }

        $assembly = Assembly::where('name', $id)->first();

        if($assembly)
        {
            $constituency = Constituencies::find($assembly->constituency_id);
            $district     = Districts::where($assembly->district_id);
            $teshils      = Blocks::where('district_id', $assembly->district_id)->get();

            $data = [
                'const'   => $constituency,
                'teshils' => $teshils,
                'district'=> $district,
            ];
        }

        return $data;
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_type'              => 'required',
            'category_id'               => 'required|exists:projects_category,id',
            'subcategory'               => 'required|exists:sub_category,name',
            // 'approved_by'             => 'required|max:250',
            // 'name'                      => 'required|unique:projects,name',
            'name'                      => 'required|string|min:10',
            'number'                    => 'required|min:6|unique:projects,number',
            'estimate_budget'           => 'required|numeric|min_digits:6',
            'assign_to'                 => 'required|exists:roles,id',
            // 'assign_to'                 => 'required|exists:departments,id',
            // 'assembly'                  => 'required',
            // 'constituencie'             => 'required',
            'district'                  => 'required',
            // 'block'                     => 'required',
            'dec_approval_document'     => [
                'required',
                File::types(['pdf'])->max(10 * 1024),
            ],
            'hpc_approval_document'     => [
                'required',
                File::types(['pdf'])->max(10 * 1024),
            ],
            'dec_approval_date'         => 'required|date|before_or_equal:today',
            'hpc_approval_date'         => 'required|date|after:dec_approval_date|before_or_equal:today',
            // 'dec_approval_letter_number'=> 'required|unique:projects,dec_approval_letter_number|max:250',
            // 'hpc_approval_letter_number'=> 'required|unique:projects,hpc_approval_letter_number|max:250',
            'dec_approval_letter_number'=> 'required|max:250',
            'hpc_approval_letter_number'=> 'required|max:250',
        ], [
            'number.min'                => 'Package Number must bet atleast 6 characters.',
            'number.unique'             => 'This Package Number is already used for another project.',
            'number.required'           => 'Kindly enter the project number.',
            'project_type.required'     => 'Please select project type',
            'category_id.required'      => 'Category field is required',
            'district.required'         => 'District field is required',
            'estimate_budget.numeric'   => 'The Estimated budget value should be in numeric',
            'assign_to.required'        => 'Kindly Choose Department for the project.',
            'assign_to.exists'          => 'The selected department is not found in our records',
	        'dec_approval_document.max' => 'DEC Approval Document must not be more than 10MB size',
	        'hpc_approval_document.max' => 'HPC Approval Document must not be more than 10MB size',
            'estimate_budget.min_digits'=> 'The Estimated budget value should be in lakhs',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->hasFile('hpc_approval_document') && $request->hasFile('dec_approval_document'))
        {
            $checkRole      = Role::where('id', $request->assign_to)->first();
            $checkID        = ($checkRole->department ?? '').'-PROCUREMENT-LEVEL-THREE';
            $procurement_id = Role::where('name', $checkID)->first();

            $data           = $request->all();

            if($request->category_id == "1")
            {
                $checkRole   = Role::where('id', $request->assign_to)->first();
                $checkID     = ($checkRole->department ?? '').'-ENVIRONMENT-LEVEL-THREE';
                $environment = Role::where('name', $checkID)->first();

                // $checkRole = Role::where('id', $request->assign_to)->first();
                $checkID   = ($checkRole->department ?? '').'-SOCIAL-LEVEL-THREE';
                $social    = Role::where('name', $checkID)->first();

                $data['environment_level_3'] = $environment->id;
                $data['social_level_3']      = $social->id;
            }

            if(!$procurement_id)
            {
                return response()->json(['errors' => ['error' => 'Warning! Role account does not exist!']]);
            }

            $dept = Department::where('name', $checkRole->department)->first();

            if($dept)
            {
                $data['dept_id']      = $dept->id;
            }

            $data['user_id']           = auth()->id();
            $data['unique_id']         = uniqid();
            $data['district_name']     = $request->district;
            $data['procure_level_3']   = $procurement_id->id;
            $data['dec_approval_date'] = date('Y-m-d', strtotime($request->dec_approval_date));
            $data['hpc_approval_date'] = date('Y-m-d', strtotime($request->hpc_approval_date));

            $response = Projects::create($data);

            // if($request->has('approval_document'))
            // {
            // Save DEC Approval Document
            $file     = $request->file('dec_approval_document');
            $filename = 'dec_approval_document_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/project', $filename);

            Media::create([
                'name'          => $filename,
                'mediable_id'   => $response->id,
                'mediable_type' => Projects::class,
                'file_type_name'=> 'DEC Approval Document',
            ]);
            // }

            // Save HPC Approval Document
            $file     = $request->file('hpc_approval_document');
            $filename = 'hpc_approval_document_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/project', $filename);

            Media::create([
                'name'          => $filename,
                'mediable_id'   => $response->id,
                'mediable_type' => Projects::class,
                'file_type_name'=> 'HPC Approval Document',
            ]);

            if($response)
            {
                $url = route('project.index');

                return $this->success('created', 'Project', $url);
            }
        }
        else
        {
            return response()->json(['errors' => ['DEC & HPC Approval Documents are required']]);
        }

        return $this->success('error', 'Project');
    }


    public function edit(Projects $Projects,$id)
    {
        $data           = Projects::find($id);
        $category       = ProjectCategory::get();
        $assembly       = Assembly::orderBy('name', 'asc')->get();
        $constituencies = Constituencies::orderBy('name', 'asc')->get();
        $districts      = Districts::orderBy('name', 'asc')->get();
        $department     = Role::where('affilaited', auth()->user()->role_id)->whereNotIn('id', [6,19,24,25,26])->get();

        return view('admin.project.edit', compact('data', 'category', 'assembly','department','constituencies','districts'));
    }

    public function getSubCategory($id = NULL)
    {
        if(!$id)
        {
            $id = request()->get('category_id');
        }

        $data = SubCategory::where('category_id', $id)->orderBy('id', 'desc')->get();

        return response()->json($data);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_type' => 'required',
            'category_id'  => 'required|exists:projects_category,id',
            'sub_category' => 'required|exists:sub_category,name',
            // 'approved_by'  => 'required|max:250',
            'dec_approval_letter_number' => [
                'required',
                // Rule::unique('projects', 'dec_approval_letter_number')->ignore($id),
            ],
            'hpc_approval_letter_number' => [
                'required',
                // Rule::unique('projects', 'hpc_approval_letter_number')->ignore($id),
            ],
            'name' => [
                'required',
                'min:10',
                Rule::unique('projects', 'name')->ignore($id),
            ],
            'number' => [
                'required',
                'min:6',
                Rule::unique('projects', 'number')->ignore($id),
            ],
            'dec_approval_date'    => 'required|date|before_or_equal:today',
            'hpc_approval_date'    => 'required|date|after:dec_approval_date|before_or_equal:today',
            'dec_approval_document'=> [
                File::types(['pdf'])->max(10 * 1024)
            ],
            'hpc_approval_document'=> [
                File::types(['pdf'])->max(10 * 1024)
            ],
            'estimate_budget'      => 'required|numeric|min_digits:6',
            'assign_to'            => 'required|exists:roles,id',
            // 'assembly'             => 'required',
            // 'constituencie'        => 'required',
            'district'             => 'required',
            // 'block'                => 'required',
        ], [
            'category_id.required'     => 'Category field is required',
            'district_id.required'     => 'District field is required',
            'name.required'            => 'Name field is required',
            'name.unique'              => 'Name must be unique',
            'number.required'          => 'Number field is required',
            'number.unique'            => 'Number must be unique',
            'approval_date.required'   => 'Approval date field is required',
            'approval_date.date'       => 'Approval date must be a valid date',
            'estimate_budget.required' => 'Estimate budget field is required',
            'estimate_budget.numeric'  => 'Estimate budget must be a number',
            'assign_to.required'       => 'Kindly Select Department for the project.',
            'assign_to.exists'         => 'The selected department is not found in our records',
            'approval_document'        => 'nullable|mimes:pdf|max:10240',
	        'dec_approval_document.max'=> 'DEC Approval Document must not be more than 10MB size',
	        'hpc_approval_document.max'=> 'HPC Approval Document must not be more than 10MB size'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        // $request['approval_date'] = date('Y-m-d',strtotime($request->approval_date));

        $checkRole      = Role::where('id',$request->assign_to)->first();
        $checkID        = ($checkRole->department ?? '').'-PROCUREMENT-LEVEL-THREE';
        $procurement_id = Role::where('name',$checkID)->first();

        if($request->category_id == "1")
        {
            $checkRole   = Role::where('id',$request->assign_to)->first();
            $checkID     = ($checkRole->department ?? '').'-ENVIRONMENT-LEVEL-THREE';
            $environment = Role::where('name',$checkID)->first();

            $checkRole = Role::where('id',$request->assign_to)->first();
            $checkID   = ($checkRole->department ?? '').'-SOCIAL-LEVEL-THREE';
            $social    = Role::where('name',$checkID)->first();

            $request['environment_level_3'] = $environment->id;
            $request['social_level_3']      = $social->id;
        }

        if(!$procurement_id)
        {
            return response()->json(['errors' => ['error' => 'Warning! Role account does not exist!']]);
        }

        $dept = Department::where('name', trim($checkRole->department))->first();

        if($dept)
        {
            $request['dept_id']      = $dept->id;
        }

        $request['district_name']     = $request->district;
        $request['subcategory']       = $request->sub_category;
        $request['procure_level_3']   = $procurement_id->id;
        $request['dec_approval_date'] = date('Y-m-d', strtotime($request->dec_approval_date));
        $request['hpc_approval_date'] = date('Y-m-d', strtotime($request->hpc_approval_date));

        $response = Projects::find($id)->update($request->all());

        if($request->has('dec_approval_document'))
        {
            $file     = $request->file('dec_approval_document');
            $filename = 'dec_approval_document_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/project', $filename);

            Media::create([
                'name'          => $filename,
                'mediable_id'   => $id,
                'mediable_type' => Projects::class,
                'file_type_name'=> 'DEC Approval Document',
            ]);
        }

        if($request->has('hpc_approval_document'))
        {
            $file     = $request->file('hpc_approval_document');
            $filename = 'hpc_approval_document_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/project', $filename);

            Media::create([
                'name'          => $filename,
                'mediable_id'   => $id,
                'mediable_type' => Projects::class,
                'file_type_name'=> 'HPC Approval Document',
            ]);
        }

        if($response)
        {
            $url = url('manage/project/edit');

            return $this->success('updated', 'Project ', $url);
        }

        return $this->success('error','Project');
    }


    public function defineProjectView($id)
    {
        $data = Projects::with('department', 'defineProject', 'contract', 'paramsValues')->findOrFail($id);

        $defineProject = $data->defineProject ?? [];
        $contracts     = $data->contract ?? NULL;
        $params        = $data->paramsValues ?? [];

        $milestone  = MileStones::with('values')->where('project_id', $id)->orderBy('id', 'asc')->get();
        $department = User::with('role')->where('user_id', auth()->user()->id)->get();

        $milestone = $this->milestoneCalculationsSingle($milestone);

        $assignDepartmentDetails = NULL;

        if(isset($data->assign_level_2))
        {
            $assignDepartmentDetails = User::find($data->assign_level_2);
        }

        $amAmount = MileStones::where('project_id', $id)->whereType(1)->sum('budget');
        $mCentage = MileStones::where('project_id', $id)->whereType(1)->sum('percent_of_work');

        // Available Amount for Allocation of Total Contract Value

        $avafAllocation = $contracts ? ( intval($contracts['procurement_contract']) - intval($amAmount) ) : 0;
        $avcfAllocation = 100 - $mCentage;

        return view('admin.define.create', compact(
            'data', 'defineProject', 'contracts', 'params', 'milestone', 'department',
            'assignDepartmentDetails', 'avafAllocation', 'avcfAllocation', 'amAmount', 'mCentage'
        ));
    }


    public function defineProjectByLvlTwo(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'scope_of_work'=> 'required|max:5000',
            'objective' => 'required|max:5000',
            'department' => 'required|numeric',
            // 'supervisor_name' => 'required|max:255',
            // 'designation' => 'required',
            // 'contact' => 'required|digits:10',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = DefineProject::find($id);

        if($response->assign_level_2 != $request->department)
        {
            Projects::where('id',$request->project_id)->where('assign_to',auth()->user()->role->id)->update(['assign_level_2' => $request->department]);
        }

        $request['supervisor_deisgnation'] = $request->designation;
        $request['supervisor_contact'] = $request->contact;

        $response = $response->update($request->all());

        if($response)
        {
            Projects::find($request->project_id)->update(['stage' => '3']);
            $url = url('define/project/view/'.$request->project_id.'/#milestonesButton');
            return $this->success('created','Project Defined ', '');
        }

        return $this->success('error','Project Defined');
    }


    public function destroy(Projects $Projects)
    {
        $Projects->delete();

        return redirect()->route('admin.project.index')->with('success', 'Projects deleted successfully');
    }

    public function AssignProjectlevelThree(Request $request)
    {
        $data = Projects::where('id',$request->project_id)
        ->where('assign_to',auth()->user()->role->id)->update([
            'assign_level_2' => $request->department
        ]);

        if($data){
            return back()->with('success','Project Assigned Successfully.');
        }

        return back()->with('error','Please try again after sometime.');
    }


    public function showDepartmentDetails($id)
    {
        $data = User::find($id);

        return response()->json($data);
    }


    public function getProjectMilestoneData($data)
    {
        $mileData = [];

        if(isset($data->contract) && isset($data->milestones))
        {
            $startDate =   Carbon::parse($data->contract->contract_signing_date);
            $endDate = Carbon::parse($data->contract->end_date);
            $months = $this->generateMonthsList($startDate, $endDate);
            $contractAmount  = $data->contract->procurement_contract;

            $allMilestonesData = $data->milestones->map(function($milestone) {
                return [
                    'id' => $milestone->id,
                    'percent_of_work' => $milestone->percent_of_work,
                ];
            })->toArray();

            $physicalPercentage = 0;
            $financialPercentage = 0;

            if(count($months) > 0){
                foreach($months as $m){

                    $date = Carbon::createFromFormat('M Y', $m);
                    $month = $date->month;
                    $year = $date->year;

                    foreach($allMilestonesData as  $dd){

                        $id = $dd['id'];
                        $percentage = $dd['percent_of_work'];

                        $physical = MilestoneValues::where('milestone_id',$id)->where('type','1')
                        ->whereMonth('date', $month)->whereYear('date', $year)
                        ->sum('percentage');

                        $financial = MilestoneValues::where('milestone_id',$id)->where('type','2')
                        ->whereMonth('date', $month)->whereYear('date', $year)
                        ->sum('amount');

                        $financialPercentage += ($financial != 0) ? (($financial /  $contractAmount) * 100) : 0;
                        $physicalPercentage += ($physical != 0) ? (($percentage * $physical)/100) : 0;
                    }

                    $financialPercentage = intval($financialPercentage);
                    $physicalPercentage = intval($physicalPercentage);

                    if($financialPercentage  > 95){
                        $financialPercentage = 100;
                    }

                    if($physicalPercentage  > 95){
                        $physicalPercentage = 100;
                    }

                    $mileData[] = [$m,$physicalPercentage,$financialPercentage];

                }
            }

            // dd($mileData);

            return $mileData;
        }

        return 'No Data Found';
    }


    /**
     *
     */
    public function delete(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('project_id'))
        {
            $project = Projects::find($request->project_id);

            if($project)
            {
                $project->delete();

                $return['ok']  = 1;
                $return['msg'] = 'Project deleted successfully!';
            }
        }

        return $return;
    }


    /**
     *
     */
    public function cancel(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('project_id'))
        {
            $project = Projects::find($request->project_id);

            if($project)
            {
                $project->stage = 5;
                $project->save();

                $return['ok']  = 1;
                $return['msg'] = 'Project cancelled successfully!';
            }
        }

        return $return;
    }


    /**
     *
     */
    public function projectBOQ(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request detected!'];

        if($request->filled('contract_id'))
        {
            $viewo          = $request->view;
            $month          = $request->filled('month') ? $request->month : date('n');
            $date           = $request->filled('date') ? $request->date : date('Y-m-d');
            $contract       = Contracts::find($request->contract_id);
            $current_date   = date('Y-m-d');
            $is_sheet_edit  = $request->filled('entry') && $request->entry == 'sheet' ? 1 : 0;
            $is_sheet_edit  = ($is_sheet_edit && auth()->user()->role->department == 'PWD' && auth()->user()->role->level == 'TWO') ? 1 : 0;
            $is_sheet_entry = $request->filled('entry') && $request->entry == 'daily' ? 1 : 0;

            if($contract)
            {
                // $boqs = PWDBOQ::all();
                $boqs = $contract->pwd_boqs;

                $return['ok'] = 1;
                $return['msg'] = 'Contract BOQ Sheet Data Imported Successfully!';

                $boq_html  = '<table class="table table-bordered table-group-divider table-boq-sheet">';
                $boq_html .= '<thead>';
                $boq_html .= '<tr><th colspan="2"></th><th colspan="4"><small>BOQ as Per Contract Bond</small></th>';

                if($is_sheet_entry)
                {
                    $boq_html .= '<th colspan="2"><small>Since Previous</small></th>';
                    // for($k=1;$k<=$month;$k++)
                    // {
                        $boq_html .= '<th colspan="2"><small>' . date('d M Y', strtotime($date)) . '</small></th>';
                    // }
                    $boq_html .= '<th colspan="2"><small>Up to Date</small></th>';
                }
                $boq_html .= '</tr>';
                $boq_html .= '<tr>';
                $boq_html .= '<th>S.No.</th>';
                $boq_html .= '<th style="width: 67%;">Item</th>';
                $boq_html .= '<th>Unit</th>';
                $boq_html .= '<th>Qty.</th>';
                $boq_html .= '<th>Rate</th>';
                $boq_html .= '<th>Amount</th>';

                if($is_sheet_entry)
                {
                    $boq_html .= '<th>Qty.</th>';
                    $boq_html .= '<th>Amount</th>';
                    $boq_html .= '<th>Qty.</th>';
                    $boq_html .= '<th>Amount</th>';
                    // for($k=1;$k<=$month;$k++)
                    // {
                        $boq_html .= '<th>Qty.</th>';
                        $boq_html .= '<th>Amount</th>';
                    // }
                }
                $boq_html .= '</tr>';
                $boq_html .= '</thead>';
                $boq_html .= '<tbody>';

                $totalAmount = 0;
                $prevAmTotal = 0;

                $lin = 0;
                // Loop through each BOQ Entry
                foreach($boqs as $boq)
                {
                    $boq_html .= '<tr>';

                    if($boq->title)
                    {
                        $boq_html .= '<td colspan="12" class="font-weight-bold">' . $boq->item . '</td>';
                    }
                    else
                    {
                        $boq_html .= '<td>' . $boq->s_no ?? '' . '</td>';

                        if($boq->heading)
                        {
                            $boq_html .= '<td colspan="11" class="font-weight-bold">' . $boq->item . '</td>';
                        }
                        else
                        {
                            $amount       = $boq->rate * $boq->qty;
                            $totalAmount += $amount;

                            $boq_html .= '<td>' . $boq->item . '</td>';
                            $boq_html .= '<td>' . $boq->unit . '</td>';
                            $boq_html .= '<td class="num">';
                            if(!$boq->section)
                            {
                                if($is_sheet_edit)
                                {
                                    $boq_html .= '<input name="liveinput_' . $lin . '_' . str_replace('-', '', $date) . '" type="numeric" class="form-control live" value="' . $boq->qty . '" _bid="' . $boq->id . '" _mid="' . $month . '" _date="' . $date .'" _esheet="yes" ' . ($viewo ? 'disabled' : '') . '>';
                                }else
                                {
                                    $boq_html .= $boq->qty;
                                }
                            }
                            $boq_html .= '</td>';
                            // $boq_html .= '<td class="num">' . (!$boq->section ? $boq->qty : '') . '</td>';
                            $boq_html .= '<td class="num' . (!$boq->section ? ' calcr' : '') . '">' . (!$boq->section ? $boq->rate : '') . '</td>';
                            $boq_html .= '<td class="num' . (!$boq->section ? ' calca' : '') . '">' . (!$boq->section ? $amount : '')  . '</td>';

                            // If Sheet Entry Requested
                            if($is_sheet_entry)
                            {
                                $boq->load('entries');

                                if($boq->entries->count())
                                {
                                    $prev_qty = 0;
                                    foreach($boq->entries as $entry)
                                    {
                                        if($entry->date < $date)
                                        // if($entry->month_id < intval($month))
                                        {
                                            $prev_qty += $entry->qty;
                                        }
                                    }

                                    $prevAmount   = $prev_qty * $boq->rate;
                                    $prevAmTotal += $prevAmount;

                                    $boq_html .= '<td class="num">' . $prev_qty .'</td>';
                                    $boq_html .= '<td class="num calca">' . ($prev_qty * $boq->rate) . '</td>';
                                }
                                else
                                {
                                    $boq_html .= '<td class="num">0</td>';
                                    $boq_html .= '<td class="num calca">0</td>';
                                }

                                unset($entry);

                                // for($m=1;$m<=$month;$m++)
                                // {
                                    // if($k <= date('j'))
                                    // {
                                        $boq_html .= '<td class="p-0">';

                                        if($boq->unit && $boq->rate)
                                        {
                                            $value   = '';

                                            if($boq->entries->count())
                                            {
                                                foreach($boq->entries as $entry)
                                                {
                                                    if($date == $entry->date)
                                                    {
                                                        $value = $entry->qty;
                                                        break;
                                                    }
                                                }
                                            }

                                            $boq_html .= '<input name="liveinput_' . $lin . '_' . str_replace('-', '', $date) . '" type="numeric" class="form-control live" value="' . $value . '" _bid="' . $boq->id . '" _mid="' . $month . '" _date="' . $date .'" '  . ($viewo ? 'disabled' : '') .  '>';
                                        }

                                        $boq_html .= '</td>';
                                        $boq_html .= '<td class="calcl_cev num calcl_' . str_replace('-', '', $date) . '"></td>';
                                        $boq_html .= '<td class="calcl_utq num"></td>';
                                        $boq_html .= '<td class="calcl_uta num"></td>';
                                    // }
                                // }
                            }
                        }
                    }

                    $boq_html .= '</tr>';
                    ++$lin;
                }

                $boq_html .= '<tr>';
                $boq_html .= '<td class="text-center font-weight-bold" colspan="5">Total</td>';
                $boq_html .= '<td class="fbtots num">' . $totalAmount . '</td>';

                if($is_sheet_entry)
                {
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="fbtots num">' . $prevAmTotal . '</td>';
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="cea_tot num"></td>';
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="uda_tot num"></td>';
                }

                $boq_html .= '</tr>';

                $gstAmount = ($totalAmount * 18) / 100;
                $pstAmount = ($prevAmTotal * 18) / 100;

                $boq_html .= '<tr>';
                $boq_html .= '<td class="text-center font-weight-bold" colspan="5">GST@18%</td>';
                $boq_html .= '<td class="fbtots num">' . $gstAmount . '</td>';

                if($is_sheet_entry)
                {
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="fbtots num">' . $pstAmount . '</td>';
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="cegst_tot num"></td>';
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="udgst_tot num"></td>';
                }

                $boq_html .= '</tr>';
                $boq_html .= '<tr>';
                $boq_html .= '<td class="text-center font-weight-bold" colspan="5">Grand Total</td>';
                $boq_html .= '<td class="fbtots num">' . $totalAmount + $gstAmount . '</td>';

                if($is_sheet_entry)
                {
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="fbtots num">' . $prevAmTotal + $pstAmount . '</td>';
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="ceg_tot num"></td>';
                    $boq_html .= '<td></td>';
                    $boq_html .= '<td class="udg_tot num"></td>';
                }

                $boq_html .= '</tr>';
                $boq_html .= '</tbody>';
                $boq_html .= '</table>';

                $return['boq'] = $boq_html;
            }
        }

        return $return;
    }



    private function generateMonthsList($startDate, $endDate){
        $months = [];

        while ($startDate->lte($endDate)) {
            $months[] = $startDate->format('M Y');
            $startDate->addMonth();
        }

        return $months;
    }

}
