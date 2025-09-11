<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\MIS\SafeguardEntry;
use App\Models\MIS\ReportProgressType;

use Illuminate\Routing\Controller as BaseController;

use App\Models\{Projects,Role,Finance,User,EnvironmentSocialTest};
use Illuminate\Contracts\Database\Eloquent\Builder;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success($type, $message, $url = "", $data = NULL)
    {
        $success    = true;
        $statusCode = 200;

        switch ($type) {
            case 'created':
                $message = $message.' Created Successfully.';
                break;
            case 'updated':
                $message = $message . ' Updated Successfully.';
                break;
            case 'deleted':
                $message = $message. ' Deleted Successfully.';
                break;
                // Add more cases for other success types if needed
            case 'error':
                $success    = false;
                $message    = $message ?: 'Error! Please try again after sometime.';
                $statusCode = 400;
                break;
            default:
                $message = $message. 'Operation Successful.';
                break;
        }

        return response()->json([
            'url'       => $url,
            'data'      => $data,
            'success'   => $success,
            'message'   => $message,
            'statusCode'=> $statusCode,
        ])->setStatusCode($statusCode);
    }


    public function chnageDate($date)
    {
        if(is_null($date))
        {
            return NULL;
        }

        return date('Y-m-d', strtotime($date));
    }


    // code added on 12 feb
    public function physicalChartLogic($request)
    {
        $userRole    = auth()->user()->role;
        $allProjects = Projects::query();

        if(!empty($request->p))
        {
            $role = Role::where('level', 'TWO')->where('department', $request->p)->first();
            $allProjects->where('assign_to', $role->id);
        }

        if($userRole->level == "TWO")
        {
            if (in_array($userRole->department, ['ENVIRONMENT', 'SOCIAL']))
            {
                // $allProjects->has('contract');
                $allProjects->where('category_id', '1');
                $allProjects->whereNotIn('stage', [0, 1]);
            }
            elseif ($userRole->department != "PROCUREMENT")
            {
                $allProjects->where('assign_to', $userRole->id);
                $allProjects->whereIn('stage', [2, 3]);
            }
        }
        elseif ($userRole->level == "THREE")
        {
            if (in_array($userRole->department, ['USDMA-ENVIRONMENT', 'FOREST-ENVIRONMENT', 'RWD-ENVIRONMENT', 'PWD-ENVIRONMENT', 'PMU-ENVIRONMENT']))
            {
                $allProjects->where('environment_level_3', $userRole->id);
                $allProjects->has('contract');
                $allProjects->whereNotIn('stage', [0, 1]);
            }
            elseif (in_array($userRole->department, ['USDMA-SOCIAL', 'FOREST-SOCIAL', 'RWD-SOCIAL', 'PWD-SOCIAL', 'PMU-SOCIAL']))
            {
                $allProjects->where('social_level_3', $userRole->id);
                $allProjects->has('contract');
                $allProjects->whereNotIn('stage', [0, 1]);
            }
        }

        if (in_array($userRole->department, ['USDMA-PROCUREMENT', 'FOREST-PROCUREMENT', 'RWD-PROCUREMENT', 'PWD-PROCUREMENT', 'PMU-PROCUREMENT']))
        {
            $procurementData = $allProjects->where('procure_level_3', $userRole->id);
        }

        if (in_array($userRole->name, ['PMU-LEVEL-THREE', 'PIU-LEVEL-THREE-PWD', 'PIU-LEVEL-THREE-RWD', 'PIU-LEVEL-THREE-FOREST', 'PIU-LEVEL-THREE-USDMA']))
        {
            $allProjects->where('assign_level_2', auth()->user()->id);
            $allProjects->whereIn('stage', [3]);
        }

        if (in_array($userRole->name, ['PMU-ENVIRONMENT-FOUR', 'PWD-ENVIRONMENT-FOUR', 'RWD-ENVIRONMENT-FOUR', 'FOREST-ENVIRONMENT-FOUR', 'USDMA-ENVIRONMENT-FOUR']))
        {
            $allProjects->where('es_level_four', auth()->user()->id);
            $allProjects->where('category_id', '1');
        }

        if (in_array($userRole->name, ['PMU-SOCIAL-FOUR', 'PWD-SOCIAL-FOUR', 'RWD-SOCIAL-FOUR', 'FOREST-SOCIAL-FOUR', 'USDMA-SOCIAL-FOUR']))
        {
            $allProjects->where('es_level_four', auth()->user()->id);
            $allProjects->where('category_id', '1');
        }

        // if($userRole->level == "TWO" && $userRole->department != "PROCUREMENT"){
        //     $allProjects->where('assign_to',$userRole->id);
        //     $allProjects->whereIn('stage',[3]);
        // }elseif(in_array($userRole->department,['ENVIRONMENT','SOCIAL']) && $userRole->level == "TWO"){
        //     $allProjects->where('category_id','1');
        // }elseif($userRole->level == "THREE" && $userRole->department == 'ENVIRONMENT'){
        //     $allProjects->where('environment_level_3',$userRole->id);
        // }elseif($userRole->level == "THREE" && $userRole->department == 'SOCIAL'){
        //      $allProjects->where('social_level_3',$userRole->id);
        // }

        // if(in_array($userRole->department,['USDMA-PROCUREMENT','FOREST-PROCUREMENT','RWD-PROCUREMENT','PWD-PROCUREMENT','PMU-PROCUREMENT'])){
        //     $procurementData = $allProjects->where('procure_level_3', $userRole->id);
        // }

        // if(in_array($userRole->department,['PMU-LEVEL-THREE','PIU-LEVEL-THREE-PWD','PIU-LEVEL-THREE-RWD','PIU-LEVEL-THREE-FOREST','PIU-LEVEL-THREE-USDMA'])){
        //     $allProjects->where('assign_level_2',auth()->user()->id);
        //     $allProjects->whereIn('stage',[3]);
        // }

        if($request->id)
        {
            $allProjects->where('id', $request->id);
        }

        $allProjects  = $allProjects->get();

        // dd($allProjects->count());

        $projects['workTotal']         = collect($allProjects)->where('category_id', '1')->count();
        $projects['goodsTotal']        = collect($allProjects)->where('category_id', '3')->count();
        $projects['othersTotal']       = collect($allProjects)->where('category_id', '4')->count();
        $projects['consultancyTotal']  = collect($allProjects)->where('category_id', '2')->count();

        $data['totalProjects']         = $allProjects->count();
        $data['Ongoing']               = $allProjects->whereIn('status', [0, 1, 2])->count();

        $projects['workTotalP']        = $allProjects->where('category_id', '1')->count();
        $projects['goodsTotalP']       = $allProjects->where('category_id', '3')->count();
        $projects['otherTotalP']       = $allProjects->where('category_id', '4')->count();
        $projects['consultancyTotalP'] = $allProjects->where('category_id', '2')->count();

        $projects['workTotalC']        = $projects['workTotal'] - $projects['workTotalP'];
        $projects['goodsTotalC']       = $projects['goodsTotal'] - $projects['goodsTotalP'];
        $projects['otherTotalC']       = $projects['othersTotal'] - $projects['otherTotalP'];
        $projects['consultancyTotalC'] = $projects['consultancyTotal'] - $projects['consultancyTotalP'];

        if($userRole->department != "PROCUREMENT")
        {
            $data['completed'] = $allProjects->where('status', '3')->count();
        }
        else
        {
            $data['completed'] = 0;
        }

        return [
            'data'       => $data,
            'projects'   => $projects,
            'allProjects'=> $allProjects
        ];
    }


    // code added on 12 feb
    public function financailProgressLogic($request)
    {
        $projectsData = Projects::query();

        if(!empty($request->f))
        {
            $role = Role::where('level','TWO')->where('department',$request->f)->first();
            $projectsData->where('assign_to',$role->id);
        }

        if($request->id)
        {
            $projectsData->where('id',$request->id);
        }

        if(auth()->user()->role->level == "TWO" && auth()->user()->role->department != "PROCUREMENT")
        {
            $projectsData->where('assign_to',auth()->user()->role->id);
            $projectsData->whereIn('stage',[3]);
        }

        if(auth()->user()->role->level == "THREE" && auth()->user()->role->department != "PROCUREMENT")
        {
            $projectsData->where('assign_level_2',auth()->user()->id);
            $projectsData->whereIn('stage',[3]);
        }

        $projectsData  = $projectsData->get();

        $projects['Work']        = collect($projectsData)->where('category_id','1')->pluck('id')->toArray();
        $projects['Consultancy'] = collect($projectsData)->where('category_id','2')->pluck('id')->toArray();
        $projects['Goods']       = collect($projectsData)->where('category_id','3')->pluck('id')->toArray();
        $projects['others']      = collect($projectsData)->where('category_id','4')->pluck('id')->toArray();

        $projectWorks = $this->getFinancialProject($projects['Work']);

        // dd($projectWorks->toArray());

        $financial['Works'] =  $this->calculateSum($projectWorks);

        $projectGoods       = $this->getFinancialProject($projects['Goods']);
        $financial['Goods'] =  $this->calculateSum($projectGoods);

        $projectConsultancy       = $this->getFinancialProject($projects['Consultancy']);
        $financial['Consultancy'] =  $this->calculateSum($projectConsultancy);

        $projectOthes        = $this->getFinancialProject($projects['others']);
        $financial['others'] =  $this->calculateSum($projectOthes);

        $financial['officeExense'] = $this->getOfficeExpenseDetails($request);


        return $financial;
    }


    // code added on 12 feb
    public function getOfficeExpenseDetails($request)
    {
        $data = Finance::query();

        if($request->f)
        {
            $role = Role::with('users')->where('level','TWO')->where('department',$request->f)->first();
            $user = User::where('role_id',$role->id)->first();
            $data->where('user_id',($user->id ?? '0'));
        }

        if((auth()->user()->role->level == "TWO" || auth()->user()->role->level == "THREE")  && auth()->user()->role->department != "PROCUREMENT")
        {
            $role = Role::with('users')->where('level',auth()->user()->role->level)->where('department',auth()->user()->role->department)->first();
            $user = User::where('role_id',$role->id ?? 0)->first();
            $data->where('user_id',$user->id);
        }

        return  $data->get()->sum('total_exp');
    }


    // code added on 12 feb
    public function getFinancialProject($values)
    {
        if(!$values)
        {
            return [];
        }

        $projectGoods = Projects::query();
        $projectGoods->whereIn('id',$values);
        $projectGoods = $projectGoods->whereHas('milestones',function($query)
        {
            $query->whereHas('values',function($qq) {
                    $qq->where('type','2');
            });
        });

        $projectGoods = $projectGoods->with(['milestones' => function ($query) {
            $query->withSum('values', 'amount');
        }])->get();

        return $projectGoods;
    }


    // code added on 12 feb
    public function calculateSum($projectGoods)
    {
        $sumAmount = 0;

        if(count($projectGoods) > 0)
        {
            foreach ($projectGoods as $project)
            {
                foreach ($project->milestones as $milestone)
                {
                    $sumAmount += $milestone->values_sum_amount;
                }
            }
        }

        return $sumAmount;
    }


    /**
     *
     */
    public function calculateWeightPercentage($data = [])
    {
        if(empty($data))
        {
            return $data;
        }

        if(count($data) > 0)
        {
            foreach($data as $d)
            {
                $d['weightCompleted']  = $this->calculateWeight($d->paramsValues);
                $d['projectStatus']    = $this->stage($d->stage);

                $milestoneCalculations = $this->milestoneCalculations($d);

                if(isset($d->contract->procurement_contract) && $d->ProjectTotalfinancialAccumulativeCost)
                {
                    $progress = ($d->ProjectTotalfinancialAccumulativeCost / $d->contract->procurement_contract) * 100;
                    $d['ProjectTotalfinancialProgress'] =  round($progress, 2);
                }
                else
                {
                    $d['ProjectTotalfinancialProgress'] = 0;
                }

                // dd($milestoneCalculations->toArray());
            }
        }

       return $data;
    }


    public function calculateWeight($data = [])
    {
        if(empty($data))
        {
            return 0;
        }

        return $data->whereNotNull('actual_date')->sum('weight');
    }


    /**
     *
     */
    public function stage($stage = "")
    {
        // $status = 'N/A';

        // if($stage == 0)
        // {
        //     // $status = "Yet to initiate";
        //     $status = "Pending for Procurement";
        // }
        // elseif($stage == 1)
        // {
        //     $status = "Pending for Contract";
        // }
        // elseif($stage == 2)
        // {

        // }
        // elseif($stage == 3)
        // {
        //     $status = "Ongoing";
        // }
        // elseif($stage == 4)
        // {
        //     $status = "Completed";
        // }

        switch($stage)
        {
            case 0:
                $status = 'Pending for Procurement';
                break;
            case 1:
                $status = 'Pending for Contract';
                break;
            case 2:
                $status = 'Pending for Milestone';
                break;
            case 3:
                $status = 'On Going';
                break;
            case 4:
                $status = 'Completed';
                break;
            case 5:
                $status = 'Cancelled';
                break;
            default:
                $status = 'N/A';
                break;
        }

        return $status;
    }


    public function milestoneCalculations(Projects $project)
    {
        $project['ProjectTotalphysicalProgress']             = 0;
        $project['ProjectTotalfinancialProgress']            = 0;
        $project['ProjectTotalfinancialAccumulativeCost']    = 0;
        $project['project_total_physical_accumulative_cost'] = 0;

        if($project->contract && $project->contract->pwd_boqs->count())
        {
            $boq_total = 0;
            $boq_entry_total = 0;

            foreach($project->contract->pwd_boqs as $boq_item)
            {
                if($boq_item->qty)
                {
                    $boq_total += ($boq_item->qty * $boq_item->rate);
                }

                if($boq_item->entries->count())
                {
                    foreach($boq_item->entries as $entry)
                    {
                        if($entry->qty)
                        {
                            $boq_entry_total += $entry->qty * $boq_item->rate;
                        }
                    }
                }
            }
            $project['ProjectTotalphysicalProgress']             = number_format($boq_entry_total * 100 / $boq_total, 2);
            $project['project_total_physical_accumulative_cost'] = $boq_entry_total;
        }
        elseif($project->contract && $project->contract->physical_progress->count())
        {
            foreach($project->contract->physical_progress as $progress)
            {
                $physicalProgress = 0;
                $financialProgress = 0;

                $project->ProjectTotalphysicalProgress             += $progress->progress;
                $project->ProjectTotalfinancialAccumulativeCost    += 0;
                $project->project_total_physical_accumulative_cost += 0;


            }
        }
        elseif(0 && $project->milestones->count())
        {
            foreach ($project->milestones as $milestone)
            {
                $physicalProgress = 0;
                $financialProgress = 0;

                $mileTotalPercentage = $milestone->percent_of_work ?? 0;

                if (isset($milestone->values))
                {
                    $physicalValues              = $milestone->values->where('type', 1)->sum('percentage') ?? 0;
                    $finanacialValues            = $milestone->values->where('type', 2)->sum('percentage') ?? 0;
                    $physicalAccumaulativeCost   = $milestone->values->where('type', 1)->sum('amount') ?? 0;
                    $finanacialAccumaulativeCost = $milestone->values->where('type', 2)->sum('amount') ?? 0;

                    if ($mileTotalPercentage != 0 && $physicalValues != 0)
                    {
                        $physicalProgress = $mileTotalPercentage * ($physicalValues / 100);
                        $physicalProgress = round($physicalProgress, 2);
                    }

                    $milestone['physicalProgress'] = $physicalProgress;
                    // $milestone['financialProgress'] = $financialProgress;
                    $milestone['financialAccumulativeAmount'] = $finanacialAccumaulativeCost;
                }

                $project['ProjectTotalphysicalProgress']             += $physicalProgress;
                $project['ProjectTotalfinancialAccumulativeCost']    += $finanacialAccumaulativeCost;
                $project['project_total_physical_accumulative_cost'] += $physicalAccumaulativeCost;
            }

            // dd($data->milestones->toArray());
        }

        return $project;
    }


    public function milestoneCalculationsSingle($data = [])
    {
        if (count($data) > 0)
        {
            foreach ($data as $mile)
            {
                $physicalProgress  = 0;
                $financialProgress = 0;

                $mileTotalPercentage = $mile->percent_of_work ?? 0;

                if (isset($mile->values))
                {
                    $physicalValues              = $mile->values->where('type', 1)->sum('percentage') ?? 0;
                    $finanacialValues            = $mile->values->where('type', 2)->sum('percentage') ?? 0;
                    $finanacialAccumaulativeCost = $mile->values->where('type', 2)->sum('amount') ?? 0;

                    if ($mileTotalPercentage != 0 && $physicalValues != 0)
                    {
                        $physicalProgress = $mileTotalPercentage * ($physicalValues / 100);
                        $physicalProgress = round($physicalProgress, 2);
                    }

                    if ($mileTotalPercentage != 0 && $finanacialValues != 0)
                    {
                        $financialProgress = $mileTotalPercentage * ($finanacialValues / 100);
                        $financialProgress = round($financialProgress, 2);
                    }

                    $mile['physicalProgress']            = $physicalProgress;
                    $mile['financialProgress']           = $financialProgress;
                    $mile['financialAccumulativeAmount'] = $finanacialAccumaulativeCost;
                }
            }
        }

        return $data;
    }


    public function CheckEnvironmentLvlThree($department, $level)
    {
        $status = false;

        $userRole = auth()->user()->role;

        if($userRole->level === $level && in_array($userRole->department,['PWD-ENVIRONMENT','PMU-ENVIRONMENT','USDMA-ENVIRONMENT','RWD-ENVIRONMENT','FROEST-ENVIRONMENT']))
        {
            $status = true;
        }

        return $status;
    }


    public function CheckSocialLvlThree($department, $level)
    {
        $status = false;

        $userRole = auth()->user()->role;

        if($userRole->level === $level && in_array($userRole->department,['PWD-SOCIAL','PMU-SOCIAL','USDMA-SOCIAL','RWD-SOCIAL','FROEST-SOCIAL']))
        {
            $status = true;
        }

        return $status;
    }


    public function CalculateEnvironmentAndsocialMileStones($data = [])
    {
        $data['TotalProgress'] = 0;

        $data1 = $data->milestones;

        if (count($data1) > 0)
        {
            foreach ($data1 as $mile)
            {
                $totalProgress       = 0;
                $mileTotalPercentage = $mile->percent_of_work ?? 0;

                if (isset($mile->values))
                {
                    $physicalValues              = $mile->values->where('type', 1)->sum('percentage') ?? 0;
                    $finanacialValues            = $mile->values->where('type', 2)->sum('percentage') ?? 0;
                    $finanacialAccumaulativeCost = $mile->values->where('type', 2)->sum('amount') ?? 0;

                    if ($mileTotalPercentage != 0 && $physicalValues != 0)
                    {
                        $physicalProgress = $mileTotalPercentage * ($physicalValues / 100);
                        $physicalProgress = round($physicalProgress, 2);
                    }

                    if ($mileTotalPercentage != 0 && $finanacialValues != 0)
                    {
                        $financialProgress = $mileTotalPercentage * ($finanacialValues / 100);
                        $financialProgress = round($financialProgress, 2);
                    }

                    $mile['physicalProgress']            = $physicalProgress;
                    $mile['financialProgress']           = $financialProgress;
                    $mile['financialAccumulativeAmount'] = $finanacialAccumaulativeCost;
                }

                $data['ProjectTotalphysicalProgress']          += $physicalProgress;
                $data['ProjectTotalfinancialProgress']         += $financialProgress;
                $data['ProjectTotalfinancialAccumulativeCost'] += $finanacialAccumaulativeCost;
            }

            // dd($data->milestones->toArray());
        }

        return $data;
    }


    public function EnvironmentAndSocialcalculateWeightPercentage($data = [])
    {
        if(count($data) == 0)
        {
            return $data;
        }

        $userRole = auth()->user()->role;

        foreach($data as $d)
        {
            $status = "Yet to initiate";

            if($userRole->level == 'TWO')
            {
                if($userRole->department == "ENVIRONMENT")
                {
                    $d['weightCompleted'] = $d->environmentMilestones ? $d->environmentMilestones->where('actual_date','!=',NULL)->sum('weight') : '0';
                    $d['projectStatus']   = $d->EnvironmentDefineProject ? $this->EnvironmentAndSocialStatus($d->EnvironmentDefineProject) : $status;
                }
                elseif($userRole->department == "SOCIAL")
                {
                    $d['weightCompleted'] = $d->socialMilestonesSocial ? $d->socialMilestonesSocial->where('actual_date','!=',NULL)->sum('weight') : '0';
                    $d['projectStatus']   = $d->SocialDefineProject ? $this->EnvironmentAndSocialStatus($d->SocialDefineProject) : $status;
                }
                else
                {
                    $d['weightCompleted'] = 0;
                }
            }
            else
            {
                if($this->CheckEnvironmentLvlThree($userRole->department,'THREE'))
                {
                    $d['weightCompleted'] = $d->environmentMilestones ? $d->environmentMilestones->where('type','1')->where('actual_date','!=',NULL)->sum('weight') : '0';
                    $d['projectStatus']   = $d->EnvironmentDefineProject ? $this->EnvironmentAndSocialStatus($d->EnvironmentDefineProject) : $status;

                }
                elseif($this->CheckSocialLvlThree($userRole->department,'THREE'))
                {
                    $d['weightCompleted'] = $d->socialMilestonesSocial ? $d->socialMilestonesSocial->where('type','2')->where('actual_date','!=',NULL)->sum('weight') : '0';
                    $d['projectStatus']   = $d->SocialDefineProject ? $this->EnvironmentAndSocialStatus($d->SocialDefineProject) : $status;
                }
                else
                {
                    $d['weightCompleted'] = 0;
                }
            }
        }

       return $data;
    }


    public function EnvironmentAndSocialcalculateWeightPercentageTotal($data = [])
    {
        if(count($data) == 0)
        {
            return $data;
        }

        foreach($data as $d)
        {
            $d['EnvironmentWeightCompleted'] = $d->environmentMilestones ? $d->environmentMilestones->where('actual_date', '!=', NULL)->sum('weight') : '0';
            $d['SocialWeightCompleted'] =     $d->socialMilestonesSocial ? $d->socialMilestonesSocial->where('actual_date', '!=', NULL)->sum('weight') : '0';
        }

       return $data;
    }


    public function EnvironmentAndSocialStatus($data)
    {
        if(empty($data))
        {
            return "Yet to initiate";
        }

        if($data->status == "1")
        {
            return  "Pending";
        }
        elseif($data->status == "2")
        {
            return "Initiated";
        }
        elseif($data->status == "3")
        {
            return "Completed";
        }
        else
        {
            return "Yet to initiate";
        }
    }


    public function CheckEnvironmentSocialType()
    {
        $status = 20;

        $userRole   = auth()->user()->role;
        $department = $userRole->department;

        if (strpos($department, 'ENVIRONMENT') !== false)
        {
            $status = 1;
        }
        elseif (strpos($department, 'SOCIAL') !== false)
        {
            $status = 2;
        }

        return $status;
    }


    public function EnviornmentSocialtestCalculation($moduleType,$projectId)
    {
        $data = [];

        for($i=1; $i<=3; $i++)
        {
            // $testsWithReports1 = EnvironmentSocialTest::where('type',$i)->where('module_type',$moduleType)->whereIn('project_id',[0,$projectId])->get();

            $testsWithReports1 = EnvironmentSocialTest::where('type', $i)
                ->where('module_type', $moduleType)
                ->whereIn('project_id', [0, $projectId])
                ->WithFilteredReports($projectId)
                ->WithFilteredSubtests($projectId)
                ->get();

            $totalTests     = count($testsWithReports1);
            $testsCompleted = $testsWithReports1->filter(function ($test) {
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

            $data[] = [
                'name'            => $name,
                'total_activities'=> $totalTests,
                'total_completed' => $testsCompleted->count(),
                'percentage'      => $completed,
                'type'            => $i,
                'id'              => $projectId,
                'start'           => $reportDates->first() ?? 'N/A',
                'end'             => $reportDates->last() ??  'N/A',
            ];

        }

        return $data;
    }


    /**
     *
     */
    public function environmentAndSocialTestCalc($project, $safeguard_type)
    {
        $p_steps = ReportProgressType::with(['safeguard_rules'=> function (Builder $query) use ($safeguard_type) {

            $query->where('is_heading', 0)->where('safeguard_type', $safeguard_type);

        }])->get();

        $steps = [];

        // Kindly Optimize the Query for Better Performance
        foreach($p_steps as $p_step)
        {
            if($p_step->safeguard_rules->count())
            {
                $data = (object) ['rules'=> $p_step->safeguard_rules->count(), 'completed'=> 0];
                $step = (object) ['name' => $p_step->name, 'data'=> NULL];

                foreach($p_step->safeguard_rules as $s_rule)
                {
                    $sr_entry = SafeguardEntry::where('rule_id', $s_rule->id)->where('project_id', $project->id)->orderBy('entry_date', 'DESC')->first();

                    if($sr_entry && $sr_entry->applicable == 1)
                    {
                        $data->completed += 1;
                    }
                }

                $steps[]    = $step;
                $step->data = $data;
            }
        }

        return $steps;
    }
}
