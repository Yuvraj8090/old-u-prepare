<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Projects, ProjectCategory, Role, Districts};
use App\Models\{MileStones, MilestonesDocument, MilestoneValues, MilestoneValuesDocuments, MilestoneValueUpdated};
use App\Models\Contracts; 
use Illuminate\Support\Facades\DB;
use App\Models\MIS\Contract\PhysicalProgress;
use App\Models\MIS\Contract\Milestone\Stage;
use App\Models\MIS\Contract\Milestone\Activity;

class ProjectLevelthreeController extends Controller
{
    public function index()
    {
        $data = Projects::with('contract', 'defineProject')
            ->where('assign_level_2', auth()->user()->id)
            ->whereIn('stage', [3, 4])
            ->orderBy('id', 'desc')
            ->paginate('20');

        $data = $this->calculateWeightPercentage($data);

        return view('admin.projectLevel3.index', compact('data'));
    }

public function create($id)
{
    // Load project with all relationships and progress data
    [$project, $contracts, $data] = $this->loadProjectWithProgressCreate($id);

    // Use contract value as budget baseline (fallback to estimate budget if needed)
    $totalBudget = $project->contract->procurement_contract ?? $project->estimate_budget ?? 0;

    // Calculate physical progress (using both methods for compatibility)
    $physicalProgress = $project->contract->physical_progress->sum('progress') ?? 
                       DB::table('contract_physical_progress')
                          ->where('contract_id', $project->contract->id ?? 0)
                          ->sum('progress');

    // Attach additional totals to project for Blade view
      $totalPhysicalProgress = $project->contract->physical_progress->sum('progress') ?? 0;
    $project->physical_progress = $physicalProgress;
    // If no milestone data was loaded by loadProjectWithProgress, load it now
    if (!$data) {
        $data = MileStones::with(['project:id,name', 'values'])
            ->where('project_id', $id)
            ->paginate(20);

        $data = $this->milestoneCalculate($data);
    }

    return view('admin.milestones.milestone-index', compact(
        'data',
        'id',
        'totalPhysicalProgress',
        'project',
        'totalBudget',
        'contracts'
    ));
}

private function loadProjectWithProgressCreate($id)
{
    $project = Projects::with([
        'contract.physical_progress',
        'milestones.milestoneValues', 
        'milestones.values',
        'defineProject',
        'district',
        'procureThree',
        'category',
        'paramsValues',
        'PiuLvlThree',
        'EnvironmentDefineProject',
        'SocialDefineProject',
        'environmentMilestones',
        'socialMilestonesSocial',
        'subCategory'
    ])->find($id);

    if (!$project) {
        abort(404, 'Project not found');
    }

    // Get all contracts for the project
    $contracts = Contracts::where('project_id', $id)->get();

    // Calculate total milestone amount from both possible sources
    $totalMilestoneAmount = 0;
    
    // From milestone_values_updated table (original method)
    $totalMilestoneAmount += DB::table('milestone_values_updated')
        ->where('project_id', $id)
        ->where('type', 2)
        ->sum('amount');

    // From milestone relationships (new method)
    foreach ($project->milestones ?? [] as $milestone) {
        foreach ($milestone->milestoneValues ?? [] as $value) {
            $totalMilestoneAmount += $value->amount ?? 0;
        }
    }

    $project->milestone_amount_total = $totalMilestoneAmount;
    
    // Calculate progress percentage using the appropriate budget source
    $budget = $project->contract->procurement_contract ?? $project->estimate_budget ?? 0;
    $project->progress_percentage = $budget > 0 
        ? round(($totalMilestoneAmount / $budget) * 100, 2) 
        : 0;

    // Load milestone data if needed
    $data = null;
    if (!$project->contract?->ms_type && !$project->contract?->boq_sheet) {
        $data = MileStones::with(['project:id,name', 'values'])
            ->where('project_id', $id)
            ->paginate(20);

        $data = $this->milestoneCalculate($data);
    }

    return [$project, $contracts, $data];
}

    public function updateProjectMilestone($project_id)
    {
        // Reuse the same logic as 'create'
        [$project, $contracts, $data] = $this->loadProjectWithProgress($project_id);

        return view(
            'admin.milestones.milestone-index-2',
            compact(
                'data',
                'project_id',
                'project',
                'contracts' // ✅ Add contracts to fix undefined variable
            )
        );
    }

    private function loadProjectWithProgress($id)
    {
        $project = Projects::with(['contract.physical_progress', 'milestones.milestoneValues', 'milestones.values', 'defineProject', 'district', 'procureThree', 'category', 'paramsValues', 'PiuLvlThree', 'EnvironmentDefineProject', 'SocialDefineProject', 'environmentMilestones', 'socialMilestonesSocial', 'subCategory'])->find($id);

        if (!$project) {
            abort(404, 'Project not found');
        }

        $contracts = $project->contract ?? collect();

        // Calculate total milestone amount
        $totalMilestoneAmount = 0;
        foreach ($project->milestones ?? [] as $milestone) {
            foreach ($milestone->milestoneValues ?? [] as $value) {
                $totalMilestoneAmount += $value->amount ?? 0;
            }
        }

        $estimateBudget = $project->estimate_budget ?? 0;
        $project->milestone_amount_total = $totalMilestoneAmount;

        $project->progress_percentage = $estimateBudget > 0 ? round(($totalMilestoneAmount / $estimateBudget) * 100, 2) : 0;

        $data = null;
        if (!$project->contract?->ms_type && !$project->contract?->boq_sheet) {
            $data = MileStones::with(['project:id,name', 'values'])
                ->where('project_id', $id)
                ->paginate(20);

            $data = $this->milestoneCalculate($data);
        }

        return [$project, $contracts, $data];
    }

    /**
     *
     */
    public function updateBOQSheet($id)
    {
        $project = Projects::with('contract')->find($id);

        if (!is_null($project->contract->ms_type) && $project->contract->ms_type && $project->contract->pwd_boqs->count()) {
            return view('admin.milestones.boq', compact('project'));
        }

        return redirect()
            ->back()
            ->withErrors(['error' => 'Cannot continue. As this is not an Item-wise contract and BOQ has not been uploaded for it.']);
    }

    /**
     *
     */

    public function milestoneCalculate($data = [])
    {
        if ($data) {
            foreach ($data as $d) {
                $financialProgress = 0;
                $physicalProgress = 0;

                if (count($d->values) > 0) {
                    foreach ($d->values as $mile) {
                        if ($mile->type == 1) {
                            $percent = $mile->percentage;
                            $physicalProgress += $percent;
                        }

                        if ($mile->type == 2) {
                            $financialProgress += $mile->percentage;
                        }
                    }

                    $physicalProgress = (int) $physicalProgress;
                    $financialProgress = (int) $financialProgress;
                }

                $d->physicalProgress = $physicalProgress;
                $d->financialProgress = $financialProgress;
            }
        }

        return $data;
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
            'financial_progress' => 'required|numeric|min:0|max:100',
            'physical_progress' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = MileStones::where('project_id', $request->project_id)
            ->where('id', $id)
            ->update([
                'financial_progress' => $request->financial_progress,
                'physical_progress' => $request->physical_progress,
            ]);

        if ($response) {
            $url = url('/update/project/create/' . $request->project_id);

            return $this->success('updated', 'MileStone  ', $url);
        }

        return $this->success('error', 'MileStone  ');
    }

    /**
     *
     */
    public function WorkProgress(Request $request)
    {
        $data = Projects::query();
        $data->with('department', 'defineProject', 'milestones');

        if ($request->department) {
            $data->where('assign_to', $request->department);
        }

        if ($request->category) {
            $data->where('category_id', $request->category);
        }

        if ($request->status) {
            $data->where('status', $request->status);
        }

        if ($request->year) {
            $data->whereYear('hpc_approval_date', $request->year);
        }

        $data->where('assign_level_2', auth()->user()->id);
        $data->whereIn('stage', [3, 4]);
        $data = $data->orderBy('id', 'desc')->paginate('20');

        $data = $this->calculateWeightPercentage($data);

        if ($data->count()) {
            foreach ($data as $project) {
                $boq_total = 0;

                if ($project->contract) {
                    if ($project->contract->ms_type) {
                        if ($project->contract->pwd_boqs->count()) {
                            foreach ($project->contract->pwd_boqs as $boq) {
                                $boq_total += $boq->qty * $boq->rate;
                            }

                            $project->contract->pwd_boq_total = $boq_total;
                        }
                    }
                }
            }
        }

        $category = ProjectCategory::all();
        $department = Role::where('affilaited', auth()->user()->role_id)
            ->where('id', '!=', '6')
            ->get();
        $years = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        $districts = Districts::orderBy('name', 'asc')->get();

        // return view('admin.PMU-LVL3.work_progress', compact('data', 'category', 'districts', 'years', 'department'));
        return view('mis.project.index', compact('data', 'category', 'districts', 'years', 'department'));
    }

    public function edit($id)
    {
        $data = Projects::find($id);

        return view('admin.projectLevel3.edit', compact('data'));
    }

    public function update()
    {
        $data = Projects::where('assign_level_2', auth()->user()->id)->paginate('20');
    }

    public function physicalProgressUpdateForm($id)
    {
        // $data       = MileStones::with('document')->find($id);

        $data = Projects::with('contract.physical_progress')->find($id);
        $stages = Stage::all();
        $progress = PhysicalProgress::where('contract_id', $data->contract->id)->sum('progress');

        $activities = Activity::with('stages')->get();
        $mileStones = MilestoneValues::with('milestoneDocs')->where('type', '1')->where('milestone_id', $id)->get();

        return view('admin.projectLevel3.physical-progress', compact('data', 'stages', 'mileStones', 'activities', 'progress'));
    }
     public function physicalProgressViewForm($id)
    {
        // $data       = MileStones::with('document')->find($id);

        $data = Projects::with('contract.physical_progress')->find($id);
        $stages = Stage::all();
        $progress = PhysicalProgress::where('contract_id', $data->contract->id)->sum('progress');

        $activities = Activity::with('stages')->get();
        $mileStones = MilestoneValues::with('milestoneDocs')->where('type', '1')->where('milestone_id', $id)->get();

        return view('admin.projectLevel3.physical-progress-1', compact('data', 'stages', 'mileStones', 'activities', 'progress'));
    }

public function financialProgressUpdateForm($project_id)
{
    // Fetch project
    $project = Projects::findOrFail($project_id);

    // Get financial milestone entries directly (type = 2)
    $milestoneValues = MilestoneValueUpdated::with(['financeDoc', 'milestone']) // assuming relation to milestone
        ->where('type', 2)
        ->where('project_id', $project_id)
        ->get();

    // Calculate total project budget by summing related milestone budgets (optional if needed)
    $totalProjectBudget = $milestoneValues->sum(function ($item) {
        return optional($item->milestone)->budget ?? 0;
    });

    // Calculate total amount submitted
    $totalSubmitted = $milestoneValues->sum('amount');

    // Calculate progress percentage
    $overallProgress = $totalProjectBudget > 0
        ? round(($totalSubmitted / $totalProjectBudget) * 100, 2)
        : 0;

    return view('admin.projectLevel3.financial-progress', compact(
        'project',
        'milestoneValues',
        'project_id',
        'totalProjectBudget',
        'totalSubmitted',
        'overallProgress'
    ));
}





    public function boqFinancialUpdate($id)
    {
        $boqf = 1;
        $data = Projects::find($id);

        return view('admin.projectLevel3.financial-progress', compact('boqf', 'data'));
    }

    public function PhysicalProressUpdate(Request $request, $id)
    {
        $array = [
            'stage_name' => 'required|string',
            'activity_name' => 'required|string',
            'physical_progress' => 'required|numeric|min:00.01|max:100',
            'date' => 'required|date',
        ];

        $docs = MilestonesDocument::where('milestone_id', $id)->get();

        $totalPercentage = MilestoneValues::where('milestone_id', $id)->whereType(1)->sum('percentage');

        if ($totalPercentage != 0 && $totalPercentage != 100) {
            $check = 100 - $totalPercentage;

            if ($request->physical_progress > $check) {
                return response()->json(['errors' => ['error' => 'Physical Progress percentage cant be more than ' . $check]]);
            }
        } elseif ($totalPercentage == 100) {
            return response()->json(['errors' => ['error' => 'Warning! MileStone is completed not be able to add more.']]);
        }

        if (count($docs) > 0) {
            foreach ($docs as $doc) {
                $array[str_replace(' ', '-', $doc->name)] = 'required|mimes:pdf';
            }
        }

        $validator = Validator::make($request->all(), $array, [
            'stage_name.required' => 'Please provide Stage Name as it is mandatory.',
            'activity_name.required' => 'Please provide Activity Name as it is mandatory.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        $data['type'] = 1;
        $data['date'] = date('Y-m-d', strtotime($request->date));
        $data['percentage'] = $request->physical_progress;
        $data['stage_name'] = $request->stage_name;
        $data['milestone_id'] = $id;
        $data['activity_name'] = $request->activity_name;

        $response = MilestoneValues::create($data);

        if (count($docs) > 0) {
            foreach ($docs as $doc) {
                $imageName = str_replace(' ', '-', $doc->name);
                $file = $request->file($imageName);
                $filename = time() . rand(1, 9999) . '_milestone_doc_image.' . $file->extension();

                $file->move('images/physical_progress/', $filename);

                MilestoneValuesDocuments::create([
                    'milestone_value_id' => $response->id,
                    'document_name' => $doc->name,
                    'file' => $filename,
                ]);
            }
        }

        if ($response) {
            $url = url('update/physical/progress/' . $id);

            return $this->success('updated', 'MileStone ', $url);
        }

        return $this->success('error', 'MileStone ');
    }

  public function FinancialProressUpdate(Request $request, $projectId)
{
    $update = $request->filled('msv_id');

    $validator = Validator::make(
        $request->all(),
        [
            'no_of_bills' => 'required|numeric',
            'bill_serial_no' => 'required|string',
            'financial_progress' => 'required|numeric|min:1|max:100',
            'amount' => 'required|numeric|min:1',
            'payment_slip' => 'nullable|mimes:pdf',
            'date' => 'required|date',
        ],
        [
            'payment_slip' => 'Please provide payment slip in PDF format.',
        ]
    );

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()]);
    }

    // ✅ Step 1: Get project and its total budget from contract
    $project = Projects::with('contract')->find($projectId);

    if (!$project || !$project->contract) {
        return response()->json(['errors' => ['error' => 'Project or contract not found.']]);
    }

    $totalBudget = $project->contract->procurement_contract ?? 0;

    if ($totalBudget <= 0) {
        return response()->json(['errors' => ['error' => 'Contract budget not defined or zero.']]);
    }

    // ✅ Step 2: Calculate total amount already submitted
    $totalSubmitted = MilestoneValueUpdated::where('project_id', $projectId)
        ->where('type', 2)
        ->sum('amount');

    // Adjust total if updating existing entry
    if ($update) {
        $existing = DB::table('milestone_values_updated')->where('id', $request->msv_id)->first();
        if ($existing) {
            $totalSubmitted -= $existing->amount;
        }
    }

    $newAmount = $request->amount;

    if (($totalSubmitted + $newAmount) > $totalBudget) {
        $remaining = $totalBudget - $totalSubmitted;
        return response()->json([
            'errors' => [
                'amount' => "You can only enter up to {$remaining} based on the project budget.",
            ],
        ]);
    }

    // ✅ Step 3: Save/update milestone value entry
    $data = $request->only([
        'no_of_bills',
        'bill_serial_no',
        
        'amount',
        'date',
    ]);
    $data['type'] = 2;
    $data['percentage'] = $request->financial_progress;
    $data['project_id'] = $projectId;
    $data['date'] = date('Y-m-d', strtotime($request->date));
    

    if ($update && $existing) {
    DB::table('milestone_values_updated')
        ->where('id', $request->msv_id)
        ->update($data);
    $milestoneValueId = $request->msv_id;
} else {
    $milestoneValueId = DB::table('milestone_values_updated')->insertGetId($data);
}

    // ✅ Step 4: Store uploaded file if present
    if ($request->hasFile('payment_slip')) {
        $file = $request->file('payment_slip');
        $filename = time() . rand(1, 9999) . '_payment_slip.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/finance_payment_slips/'), $filename);

        MilestoneValuesDocuments::create([
            'milestone_value_id' => $milestoneValueId,
            'document_name' => 'payment_slip',
            'file' => $filename,
        ]);
    }

    return $this->success('updated', 'Milestone Financial Progress', url('update/financial/progress/' . $projectId));
}

}
