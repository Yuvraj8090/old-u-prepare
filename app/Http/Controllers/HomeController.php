<?php

namespace App\Http\Controllers;

use App\Helpers\Assistant;

use App\Models\Role;
use App\Models\Finance;
use App\Models\Projects;
use App\Models\Districts;
use App\Models\Contracts;
use App\Models\MilestoneValues;
use App\Models\SubCategory;
use App\Models\ProjectCategory;
use App\Models\MilestoneValueUpdated;
use App\Models\MIS\SafeGuardRule;
use App\Models\MIS\ReportProgressType;
use App\Models\MIS\Dashboard\PD\PIU;
use App\Models\MIS\Dashboard\PD\Component;
use App\Models\MIS\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     *
     */
    public function dashboard(Request $request)
{
    $userDepartmentName = $this->getUserDepartmentName();
    $pius = PIU::all();
    $comps = Component::all();
    $components = Component::all();
    $projects = Projects::whereHas('contract')->with('contract.pwd_boqs', 'category')->get();
    $departmentBudgets = $this->getDepartmentBudgets();
    $tableData = $this->getTableData($projects);
    $chart_data = $this->getChartData($pius, $projects);
    $safeguard_rules = $this->getSafeguardRules();

    $view = auth()->user()->username === 'PM-PWD' ? 'admin.dashboard' : 'admin.dashboard';

    return view($view, compact(
        'projects', 'safeguard_rules', 'tableData',
        'components', 'chart_data', 'pius', 'comps', 'departmentBudgets', 'userDepartmentName'
    ));
}
private function getUserDepartmentName()
{
    $user = auth()->user();

    $actualDeptId = in_array($user->department_id, [0, 1]) ? 1 : $user->department_id;

    return Department::where('id', $actualDeptId)->value('name');
}

private function getDepartmentBudgets()
{
    $departments = Department::get(['id', 'name']);

    return $departments->map(function ($dept) {
        // Get project IDs for this department once
        $projectIds = Projects::where('dept_id', $dept->id)->pluck('id');

        // Total contract budget
        $totalBudget = DB::table('contracts')
            ->whereIn('project_id', $projectIds)
            ->sum('procurement_contract');

        // Total milestone values (used budget)
        $budgetUsed = DB::table('milestone_values_updated')
            ->whereIn('project_id', $projectIds)
            ->sum('amount');

        // Count of contracts where status is "signed"
        $contractsSigned = DB::table('contracts')
            ->whereIn('project_id', $projectIds)
            
            ->count();
$contractsSignedItemRate = DB::table('contracts')
    ->whereIn('project_id', $projectIds)
    
    ->where('ms_type', 0)
    ->count();

// Count of signed Item Rate contracts (ms_type = 1)
 
$contractsSignedEPC= DB::table('contracts')
    ->whereIn('project_id', $projectIds)
    
    ->where('ms_type', 1)
    ->count();
        return [
            'id' => $dept->id,
            'name' => $dept->name,
            'contract_as' => $totalBudget,
            'budget_used' => $budgetUsed,
            'budget_remaining' => $totalBudget - $budgetUsed,
            'project_count' => $projectIds->count(),
            'contracts_signed_epc' => $contractsSignedEPC,
    'contracts_signed_item_rate' => $contractsSignedItemRate,
            'contracts_signed_count' => $contractsSigned,
            'utilization_percentage' => $totalBudget > 0
                ? round(($budgetUsed / $totalBudget) * 100, 2)
                : 0,
        ];
    });
}





private function getTableData($projects)
{
    $keys = ['all', 'slopes', 'bridges', 'consultancy'];
    $tableData = (object) array_fill_keys($keys, (object) [
        'epc' => (object) ['stats' => null, 'projects' => []],
        'item' => (object) ['stats' => null, 'projects' => []]
    ]);

    foreach ($projects as $project) {
        $keyIndex = $this->getdKey($project);
        $key = $keys[$keyIndex];

        if ($project->contract->pwd_boqs->count()) {
            $tableData->all->item->projects[] = $project;
            $tableData->$key->item->projects[] = $project;
        } else {
            $tableData->all->epc->projects[] = $project;
            $tableData->$key->epc->projects[] = $project;
        }
    }

    foreach ($tableData as $item) {
        $item->epc->stats = Assistant::getPWDashStats($item->epc->projects);
        $item->item->stats = Assistant::getPWDashStats($item->item->projects);
    }

    return $tableData;
}

private function getChartData($pius, $projects)
{
    $components = Component::all();
    $departments = Role::where('affilaited', 2)->whereNotIn('id', [6, 19, 24, 25, 26, 53])->get(['id', 'department']);

    $data = [
        'comp_pie' => ['amt_inr' => [], 'amt_usd' => [], 'labels' => []],
        'pius_pie' => [
            'bcd' => ['total' => [], 'contract' => [], 'financial' => []],
            'all' => ['amt_inr' => [], 'amt_usd' => [], 'labels' => []],
        ],
    ];

    foreach ($components as $component) {
        $data['comp_pie']['labels'][] = $component->name;
        $data['comp_pie']['amt_inr'][] = $component->amt_inr;
        $data['comp_pie']['amt_usd'][] = $component->amt_usd;
    }

    foreach ($pius as $piu) {
        $piuName = $piu->name;
        $data['pius_pie'][$piuName] = [
            'amt_inr' => $piu->amt_inr,
            'amt_usd' => $piu->amt_usd,
            'amt_cnv' => 0,
            'amt_fin' => 0,
        ];

        foreach ($departments as $dept) {
            if (strtolower($piuName) == strtolower($dept->department)) {
                foreach ($projects as $project) {
                    if ($dept->id == $project->assign_to) {
                        $data['pius_pie'][$piuName]['amt_cnv'] += $project->contract->procurement_contract;
                    }
                }
            }
        }
    }

    foreach ($data['pius_pie'] as $key => $piuData) {
        if (!in_array($key, ['all', 'bcd'])) {
            $data['pius_pie']['all']['labels'][] = $key;
            $data['pius_pie']['all']['amt_inr'][] = $piuData['amt_inr'];
            $data['pius_pie']['all']['amt_usd'][] = $piuData['amt_usd'];
            $data['pius_pie']['bcd']['total'][] = $piuData['amt_inr'];
            $data['pius_pie']['bcd']['contract'][] = $piuData['amt_cnv'];
            $data['pius_pie']['bcd']['financial'][] = $piuData['amt_fin'];
        }
    }

    return $data;
}

private function getSafeguardRules()
{
    return SafeGuardRule::with('children')
        ->where('safeguard_type', 'environment')
        ->where('is_heading', 1)
        ->where('type_id', 3)
        ->get();
}

 private function getdKey($project = null)
    {
        $key = 0;

        if ($project) {
            if ($project->category->name == 'Consultancy Services') {
                $key = 3;
            } elseif ($project->subcategory == 'Bridge') {
                $key = 2;
            } elseif ($project->subcategory == 'Slope Protection') {
                $key = 1;
            }
        }

        return $key;
    }

    // code added on 12 feb
    public function ProcurmentChartLogic($request)
    {
        $procurementTotal = Projects::query();
        $procurementTotal->with('category:id,name');

        if (in_array(auth()->user()->role->department, ['USDMA-PROCUREMENT', 'FOREST-PROCUREMENT', 'RWD-PROCUREMENT', 'PWD-PROCUREMENT', 'PMU-PROCUREMENT'])) {
            $procurementTotal->where('procure_level_3', auth()->user()->role->id);
        }

        $procurementTotal = $procurementTotal->get();

        $procurement['workTotal'] = $procurementTotal->where('category_id', '1')->count();
        $procurement['consultancyTotal'] = $procurementTotal->where('category_id', '2')->count();
        $procurement['goodsTotal'] = $procurementTotal->where('category_id', '3')->count();
        $procurement['othersTotal'] = $procurementTotal->where('category_id', '4')->count();

        $procurementDone = Projects::query();

        if (in_array(auth()->user()->role->department, ['USDMA-PROCUREMENT', 'FOREST-PROCUREMENT', 'RWD-PROCUREMENT', 'PWD-PROCUREMENT', 'PMU-PROCUREMENT'])) {
            $procurementDone->where('procure_level_3', auth()->user()->role->id);
        }

        $procurementDone = $procurementDone->with('contract')->has('contract')->get();

        $procurement['workTotalC'] = $procurementDone->where('category_id', '1')->count();
        $procurement['consultancyTotalC'] = $procurementDone->where('category_id', '2')->count();
        $procurement['goodsTotalC'] = $procurementDone->where('category_id', '3')->count();
        $procurement['othersTotalC'] = $procurementDone->where('category_id', '4')->count();

        $procurement['workTotalP'] = $procurement['workTotal'] - $procurement['workTotalC'];
        $procurement['goodsTotalP'] = $procurement['goodsTotal'] - $procurement['goodsTotalC'];
        $procurement['consultancyTotalP'] = $procurement['consultancyTotal'] - $procurement['consultancyTotalC'];
        $procurement['othersTotalP'] = $procurement['othersTotal'] - $procurement['othersTotalC'];

        $contract['workTotal'] = $procurementDone->where('category_id', '1')->sum(function ($project) {
            return $project->contract->procurement_contract;
        });

        $contract['consultancyTotal'] = $procurementDone->where('category_id', '2')->sum(function ($project) {
            return $project->contract->procurement_contract;
        });

        $contract['goodsTotal'] = $procurementDone->where('category_id', '3')->sum(function ($project) {
            return $project->contract->procurement_contract;
        });

        $contract['othersTotal'] = $procurementDone->where('category_id', '4')->sum(function ($project) {
            return $project->contract->procurement_contract;
        });

        return [
            'procurement' => $procurement,
            'contract' => $contract,
        ];
    }

    public function procurementDashboard()
    {
        $data['totalProjects'] = Projects::count();
        $data['Ongoing'] = Projects::count();
        $data['completed'] = Projects::where('stage', '8')->count();

        return view('admin.procurement-dashboard', compact('data'));
    }

    /**
     *
     */
    public function dashboardDepartment($id)
    {
        $data['totalProjects'] = Projects::whereHas('department', function ($q) use ($id) {
            $q->where('department', $id);
        })->count();

        $data['Ongoing'] = Projects::whereHas('department', function ($q1) use ($id) {
            $q1->where('department', $id);
        })->count();

        $data['completed'] = Projects::whereHas('department', function ($q2) use ($id) {
            $q2->where('department', $id);
        })
            ->where('stage', '8')
            ->count();

        return view('admin.dashboard-department', compact('data', 'id'));
    }

    /**
     *
     */
    public function reportFilters($id)
    {
        $category = ProjectCategory::all();
        $districts = Districts::all();

        return view('admin.report.filters', compact('id', 'category', 'districts'));
    }

    /**
     *
     */
    public function report(Request $request, $id)
    {
        $category = ProjectCategory::all();
        $subcategory = SubCategory::distinct()->get(['name']);
        $districts = Districts::orderBy('name', 'asc')->get();
        $department = Role::where('affilaited', auth()->user()->role_id)
            ->whereNotIn('id', [6, 19, 24, 25, 26])
            ->where('id', '!=', '6')
            ->get();

        $data = Projects::query();
        $data->with('department', 'defineProject', 'milestones', 'contract', 'environmentMilestones', 'socialMilestonesSocial');

        $filters = [
            'category' => 'All',
            'subcategory' => 'All',
            'district' => 'All',
            'department' => 'All',
        ];

        if ($id == 'environment-social-report') {
            $data->where('category_id', '1');
            $filters['category'] = $category->where('id', '1')->value('name');
        } elseif ($request->category) {
            $data->where('category_id', $request->category);
            $filters['category'] = $category->where('id', $request->category)->value('name');
        }

        if ($request->subcategory) {
            $data->where('subcategory', $request->subcategory);
            $filters['subcategory'] = $request->subcategory;
        }

        if ($request->district) {
            $data->where('district_name', $request->district);
            $filters['district'] = $districts->where('name', $request->district)->value('name');
        }

        if ($request->department) {
            $data->where('assign_to', $request->department);
            $filters['department'] = $department->where('id', $request->department)->value('department');
        }

        if ($request->startDate && $request->endDate) {
            $request['startDate'] = date('Y-m-d', strtotime($request->startDate));
            $request['endDate'] = date('Y-m-d', strtotime($request->endDate));

            $data->with([
                'milestones' => function ($query) use ($request) {
                    $query->where('start_date', '>=', $request->startDate)->where('start_date', '<=', $request->endDate)->where('end_date', '>=', $request->startDate)->where('end_date', '<=', $request->endDate);
                },
            ]);
        }

        $data = $data->paginate('50');
        $data = $this->calculateWeightPercentage($data);
        $years = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');

        if ($id == 'procurement-report') {
            return view('admin.report.procurement', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
        } elseif ($id == 'contract-report') {
            return view('admin.report.contract', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
        } elseif ($id == 'execution-report') {
            return view('admin.report.execution', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
        } elseif ($id == 'environment-social-report') {
            $data = $this->EnvironmentAndSocialcalculateWeightPercentageTotal($data);

            return view('admin.report.environment-socia', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
        } elseif ($id == 'project-wise-report') {
            return view('admin.report.project-wise', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
        } elseif ($id == 'duration-report') {
            return view('admin.report.duration', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
        } elseif ($id == 'admin-expense-report') {
            $dataFinance = Finance::query();

            if ($request->year) {
                $dataFinance->where('year', $request->year);
            }

            if ($request->department) {
                $dataFinance->where('agency_id', $request->department);
            }

            if ($request->quarter) {
                $dataFinance->where('quarter', $request->quarter);
            }

            $dataFinance = $dataFinance->with('department')->groupBy('agency_id', 'year')->select('agency_id', 'year', DB::raw('SUM(office_equipment_exp) as office_exp'), DB::raw('SUM(electricty_exp) as electricty_exp'), DB::raw('SUM(transport_exp) as transport_exp'), DB::raw('SUM(salaries_exp) as salaries_exp'), DB::raw('SUM(rent_exp) as rent_exp'), DB::raw('SUM(miscelleneous_exp) as miscelleneous_exp'), DB::raw('SUM(total_exp) as total_exp'))->get();

            $years = Finance::distinct()->selectRaw('year as year')->pluck('year');
            return view('admin.report.admin-expense', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters', 'dataFinance'));
        }

        return view('admin.report.all', compact('data', 'category', 'subcategory', 'districts', 'years', 'department', 'filters'));
    }

    /**
     *
     */
   
}
