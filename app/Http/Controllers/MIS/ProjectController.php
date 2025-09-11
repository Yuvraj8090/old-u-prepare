<?php

namespace App\Http\Controllers\MIS;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

use App\Models\Projects;
use App\Models\MilestoneValueUpdated;
use App\Models\Districts;
use App\Models\SubCategory;
use App\Models\MaterialTest;
use App\Models\EnvironmentTest;
use App\Models\ProjectCategory;
use App\Models\MilestoneValues;



use App\Http\Controllers\Controller;
use App\Models\MileStones;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Projects::query();
        $projects->with('department', 'defineProject', 'milestones', 'category', 'contract', 'incharge');

        /**------------------------------------------------**/
        $userRole = auth()->user()->role;

        if ($userRole->level == 'TWO') {
            if (in_array($userRole->department, ['ENVIRONMENT', 'SOCIAL'])) {
                $projects->where('category_id', '1');
                $projects->whereNotIn('stage', [0, 1]);
            } elseif ($userRole->department != 'PROCUREMENT') {
                $projects->where('assign_to', $userRole->id);
                $projects->whereIn('stage', [2, 3]);
            }
        } elseif ($userRole->level == 'THREE') {
            if (in_array($userRole->department, ['USDMA-ENVIRONMENT', 'FOREST-ENVIRONMENT', 'RWD-ENVIRONMENT', 'PWD-ENVIRONMENT', 'PMU-ENVIRONMENT'])) {
                $projects->where('environment_level_3', $userRole->id);
                $projects->has('contract');
                $projects->whereNotIn('stage', [0, 1]);
            } elseif (in_array($userRole->department, ['USDMA-SOCIAL', 'FOREST-SOCIAL', 'RWD-SOCIAL', 'PWD-SOCIAL', 'PMU-SOCIAL'])) {
                $projects->where('social_level_3', $userRole->id);
                $projects->has('contract');
                $projects->whereNotIn('stage', [0, 1]);
            }
        }

        if (in_array($userRole->department, ['USDMA-PROCUREMENT', 'FOREST-PROCUREMENT', 'RWD-PROCUREMENT', 'PWD-PROCUREMENT', 'PMU-PROCUREMENT'])) {
            $procurementData = $projects->where('procure_level_3', $userRole->id);
        }

        if (in_array($userRole->name, ['PMU-LEVEL-THREE', 'PIU-LEVEL-THREE-PWD', 'PIU-LEVEL-THREE-RWD', 'PIU-LEVEL-THREE-FOREST', 'PIU-LEVEL-THREE-USDMA'])) {
            $projects->where('assign_level_2', auth()->user()->id);
            $projects->whereIn('stage', [3]);
        }

        if (in_array($userRole->name, ['PMU-ENVIRONMENT-FOUR', 'PWD-ENVIRONMENT-FOUR', 'RWD-ENVIRONMENT-FOUR', 'FOREST-ENVIRONMENT-FOUR', 'USDMA-ENVIRONMENT-FOUR'])) {
            $projects->where('es_level_four', auth()->user()->id);
            $projects->where('category_id', '1');
        }

        if (in_array($userRole->name, ['PMU-SOCIAL-FOUR', 'PWD-SOCIAL-FOUR', 'RWD-SOCIAL-FOUR', 'FOREST-SOCIAL-FOUR', 'USDMA-SOCIAL-FOUR'])) {
            $projects->where('es_level_four', auth()->user()->id);
            $projects->where('category_id', '1');
        }
        /**------------------------------------------------**/
        $avdepts = ['PMU', 'PIU', 'PWD', 'RWD', 'USDMA', 'FOREST'];
        $user_dept = explode('-', auth()->user()->role->department);

        foreach ($user_dept as $u_dept) {
            if (in_array($u_dept, $avdepts)) {
                $user_dept = $u_dept;
                break;
            }
        }

        $user_dept = Role::where('affilaited', 2)
            ->whereNotIn('id', [6, 19, 24, 25, 26])
            ->where('department', $user_dept)
            ->first();
        $dept_tfltr = $request->filled('department') ? intval($request->department) : ($user_dept ? $user_dept->id : 0);

        if ($request->name) {
            $projects->where(function ($query) use ($request) {
                $query
                    ->orWhere('name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('subcategory', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->name . '%');
            });
        }

        auth()
            ->user()
            ->update([
                'district' => $request->filled('project_districts') ? json_encode($request->project_districts) : null,
            ]);

        if (auth()->user()->district) {
            $projects->whereIn('district_name', json_decode(auth()->user()->district));
        }

        if ($dept_tfltr && $user_dept != 'PMU') {
            $projects->where('assign_to', $dept_tfltr);
        }

        if ($request->category) {
            $projects->where('category_id', $request->category);
        }

        if ($request->subcategory) {
            $projects->where('subcategory', $request->subcategory);
        }

        if ($request->filled('status') || intval($request->status)) {
            $projects->where('stage', $request->status);
        }

        if ($request->year) {
            $projects->whereYear('hpc_approval_date', $request->year);
        }

        if ($request->completion_year) {
            $projects->where('status', '3')->whereYear('created_at', $request->completion_year);
        }

        if (auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT') {
            $s_arr = [0, 1];

            if (!$request->filled('status') || ($request->filled('status') && $request->status != 5)) {
                $s_arr[] = 5;
            }

            $projects->where('assign_to', auth()->user()->role->id);
            $projects->whereNotIn('stage', $s_arr);
        }

        $projects = $projects->orderBy('id', 'desc')->get();
        $projects = $this->calculateWeightPercentage($projects);
  
$projects->each(function ($project) {
    // Initialize financial tracking variables
    $totalAmountSpent = 0;
    $totalWeightedFinancial = 0;
    $totalMilestoneWeight = 0;
    $contractValue = $project->contract->procurement_contract ?? 0;

    // Get all milestone values for this project
    $milestoneValues = MilestoneValueUpdated::where('project_id', $project->id)->get();

    if ($milestoneValues->isNotEmpty()) {
        foreach ($milestoneValues as $milestoneValue) {
            $amount = $milestoneValue->amount ?? 0;
            $percentOfWork = $milestoneValue->percentage ?? 0;

            $totalAmountSpent += $amount;

            // Calculate weighted financial progress
            $weightedAmount = ($percentOfWork * $amount) / 100;
            $totalWeightedFinancial += $weightedAmount;
            $totalMilestoneWeight += $percentOfWork;
        }
    }

    // Set all required financial metrics on the project object
    $project->financial_amount_spent = $totalAmountSpent;
    $project->contract_value = $contractValue;

    $project->financial_completion_percent = $contractValue > 0
        ? min(100, round(($totalAmountSpent / $contractValue) * 100, 2))
        : 0;

    $project->ProjectTotalfinancialProgress = $totalMilestoneWeight > 0
        ? round(($totalWeightedFinancial / $totalMilestoneWeight) * 100, 2)
        : 0;
});

        $category = ProjectCategory::all();
        $subcategory = SubCategory::orderBy('name')->get()->unique('name');
        // $department  = Role::where('affilaited', 2)->whereNotIn('id', [6, 19, 24, 25, 26])->where('id', '!=', '6')->get();
        $department = Role::where('affilaited', 2)
            ->whereNotIn('id', [6, 19, 24, 25, 26])
            ->get();

        $years = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');

        $districts = Districts::orderBy('name', 'asc')->get();
        $project_districts = Projects::distinct('district_name')->orderBy('district_name')->get('district_name AS name');

        // return view('admin.project.index', compact('data', 'category', 'districts', 'years', 'department', 'project_districts', 'subcategory'));
        return view('mis.project.index', compact('projects', 'category', 'districts', 'years', 'department', 'project_districts', 'subcategory', 'dept_tfltr'));
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
        $contracts = $projectData->contract ?? [];
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
        foreach ($contracts as $contract) {
            $sum = DB::table('contract_physical_progress')
                ->where('contract_id', $contract->id)

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

    /**
     *
     */
       public function viewMilestones($project_id)
{
    $id = $project_id;
    $view = 'yes';

    // Load full project data with relationships
    $projectData = Projects::with([
        'defineProject',
        'contract.physical_progress',
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

    // Calculate milestone-related data
    $milestones = $this->milestoneCalculate($projectData->milestones);


    return view('admin.milestones.milestone-index-2', [
        'id' => $id,
        'view' => $view,
        'data' => $milestones,
        'project' => $projectData
    ]);
}


    /**
     *
     */
 public function viewMilestoneFinancial($project_id, $milestone_id = null)
{
    $project = Projects::findOrFail($project_id); // Get project info

    // Use MilestoneValueUpdated (type = 2) and name it $milestoneValues
    $milestoneValues = MilestoneValueUpdated::with(['financeDoc', 'milestone'])
        ->where('type', 2)
        ->where('project_id', $project_id)
        ->get();

    // Load related milestone data (for document info and reference)
    $data = MileStones::with('document')->where('project_id', $project_id)->get();

    // Find the next milestone where submitted amount < budget
    $nextMilestone = null;
    foreach ($data as $milestone) {
        $totalAmount = $milestoneValues->where('milestone_id', $milestone->id)->sum('amount');

        if ($totalAmount < $milestone->budget) {
            $milestone->used_amount = $totalAmount;
            $nextMilestone = $milestone;
            break;
        }
    }

    // Total budget from related milestone records
    $totalProjectBudget = $milestoneValues->sum(function ($item) {
        return optional($item->milestone)->budget ?? 0;
    });

    // Total submitted amount
    $totalSubmitted = $milestoneValues->sum('amount');

    // Financial progress percentage
    $overallProgress = $totalProjectBudget > 0
        ? round(($totalSubmitted / $totalProjectBudget) * 100, 2)
        : 0;

    return view('admin.projectLevel3.financial-progress', compact(
        'project',
        'data',
        'milestoneValues',    // ✅ matches updated view variable
        'project_id',
        'nextMilestone',
        'totalProjectBudget',
        'totalSubmitted',
        'overallProgress'
    ));
}



    /**
     *
     */
    public function viewMilestonesBOQ($project_id)
    {
        $view = 'yes';
        $project = Projects::with('contract')->find($project_id);

        if (!is_null($project->contract->ms_type) && $project->contract->ms_type && $project->contract->pwd_boqs->count()) {
            return view('admin.milestones.boq', compact('project', 'view'));
        }

        return redirect()
            ->back()
            ->withErrors(['error' => 'Cannot continue. As this is not an Item-wise contract and BOQ has not been uploaded for it.']);
    }

    /**
     *
     */
    public function viewQualityReports($project_id, $type)
    {
        $id = $project_id;
        $view = 'yes';
        $type = $type == 'material' ? 1 : ($type == 'bridge' ? 2 : '');
        $projectData = Projects::with('subCategory')->find($project_id);

        $data = MaterialTest::query();

        $category = null;

        if ($type == 2) {
            $data->where('category', $projectData->subCategory->id);
            $category = $projectData->subCategory->id;
        }

        $data->WithFilteredReports($project_id);

        $data = $data
            ->where('type', $type)
            ->whereIn('project_id', [0, $project_id])
            ->get();

        $data->each(function ($materialTest) use ($project_id) {
            $materialTest->total_reports = $materialTest->reports->count() ?? 0;
            $materialTest->passed_reports = $materialTest->reports->where('status', '1')->count() ?? 0;
            $materialTest->failed_reports = $materialTest->reports->where('status', '0')->count() ?? 0;

            if ($materialTest->total_reports != 0 && $materialTest->passed_reports != 0) {
                $materialTest->status_percentage = ($materialTest->passed_reports / $materialTest->total_reports) * 100;
            } else {
                $materialTest->status_percentage = 0;
            }

            $startReport = $materialTest->reports->first();
            $lastReport = $materialTest->reports->last();

            $materialTest->start_date = $startReport->test_date ?? null;
            $materialTest->end_date = $lastReport->test_date ?? null;

            $materialTest->duration = 'X';

            if ($materialTest->start_date && $materialTest->end_date) {
                $startDate = Carbon::parse($materialTest->start_date);
                $endDate = Carbon::parse($materialTest->end_date);

                $daysCount = $startDate->diffInDays($endDate);
                $materialTest->duration = $daysCount;
            }

            if ($materialTest->start_date == $materialTest->end_date) {
                $materialTest->end_date = null;
            }
        });

        // dd($data->toArray());

        return view('admin.qualityTest.material.index', compact('view', 'data', 'id', 'type', 'category', 'projectData'));
    }

    /**
     *
     */
    public function viewCompliancesSheet($project_id, $type)
    {
        $view = 'yes';
        $phase = request('phase') ?? null;
        $project = Projects::findOrFail($project_id);
        $entryType = $type;

        return view('mis.project.tracking.sheet', compact('entryType', 'project', 'phase', 'view'));
    }

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
                } else {
                    $financialProgress = 0;
                }

                $d->physicalProgress = $physicalProgress;
                $d->financialProgress = $financialProgress;
            }
        }

        return $data;
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

    public function getProjectMilestoneData($data)
    {
        $mileData = [];

        if (isset($data->contract) && isset($data->milestones)) {
            $startDate = Carbon::parse($data->contract->contract_signing_date);
            $endDate = Carbon::parse($data->contract->end_date);
            $months = $this->generateMonthsList($startDate, $endDate);
            $contractAmount = $data->contract->procurement_contract;

            $allMilestonesData = $data->milestones
                ->map(function ($milestone) {
                    return [
                        'id' => $milestone->id,
                        'percent_of_work' => $milestone->percent_of_work,
                    ];
                })
                ->toArray();

            $physicalPercentage = 0;
            $financialPercentage = 0;

            if (count($months) > 0) {
                foreach ($months as $m) {
                    $date = Carbon::createFromFormat('M Y', $m);
                    $month = $date->month;
                    $year = $date->year;

                    foreach ($allMilestonesData as $dd) {
                        $id = $dd['id'];
                        $percentage = $dd['percent_of_work'];

                        $physical = MilestoneValues::where('milestone_id', $id)->where('type', '1')->whereMonth('date', $month)->whereYear('date', $year)->sum('percentage');

                        $financial = MilestoneValues::where('milestone_id', $id)->where('type', '2')->whereMonth('date', $month)->whereYear('date', $year)->sum('amount');

                        $financialPercentage += $financial != 0 ? ($financial / $contractAmount) * 100 : 0;
                        $physicalPercentage += $physical != 0 ? ($percentage * $physical) / 100 : 0;
                    }

                    $financialPercentage = intval($financialPercentage);
                    $physicalPercentage = intval($physicalPercentage);

                    if ($financialPercentage > 95) {
                        $financialPercentage = 100;
                    }

                    if ($physicalPercentage > 95) {
                        $physicalPercentage = 100;
                    }

                    $mileData[] = [$m, $physicalPercentage, $financialPercentage];
                }
            }

            // dd($mileData);

            return $mileData;
        }

        return 'No Data Found';
    }

    private function generateMonthsList($startDate, $endDate)
    {
        $months = [];

        while ($startDate->lte($endDate)) {
            $months[] = $startDate->format('M Y');
            $startDate->addMonth();
        }

        return $months;
    }
}
