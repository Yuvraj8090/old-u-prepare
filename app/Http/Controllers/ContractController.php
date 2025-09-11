<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Media;
use App\Models\PWDBOQ;
use App\Models\Projects;
use App\Models\Districts;
use App\Models\Contracts;
use App\Models\ProjectCategory;
use App\Models\ContractSecurities;

use App\Models\MIS\Contract\Milestone\Stage;
use App\Models\MIS\Contract\PhysicalProgress;
use App\Models\MIS\Contract\Milestone\Activity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    /*
     *
     */
    public function index(Request $request)
    {
        $category = ProjectCategory::all();

        $department = Role::where('affilaited', '2')->whereNotIn('id', [19,25,6])->get();

        $years     = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        $districts = Districts::all();

        $data = Projects::query();
        $data->with('department', 'defineProject:id,project_id', 'contract');
        $data->whereIn('stage', [1,2,3]);

        if ($request->search)
        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('subcategory','LIKE','%'.$request->search.'%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->search . '%');
            });
        }

        if($request->category)
        {
            $data->where('category_id', $request->category);
        }

        if($request->status || $request->status == "0")
        {
            if($request->status == 2)
            {
                $data->where('stage', '>=', 2);
            }
            else
            {
                $data->where('stage', $request->status);
            }
        }

        $data->where('procure_level_3', auth()->user()->role->id);

        $data = $data->orderBy('id', 'desc')->paginate('20');

        $data = $this->calculateWeightPercentage($data);

        return view('admin.contract.index',compact('data', 'category', 'department', 'years', 'districts'));
    }


    public function create($id)
    {
        $data   = Projects::with('paramsValues', 'paramsValues', 'defineProject', 'department', 'procureThree')->find($id);
        $params = $data->paramsValues ?? [];

        return view('admin.contract.create',compact('data', 'id', 'params'));
    }


    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'company_name'           => 'required',
            'procurement_contract'   => 'required|numeric|min:1',
            // 'company_resgistered_no' => 'required',
            'contract_signing_date'  => 'required|date|before_or_equal:now',
            'commencement_date'      => 'required|date',
            'initial_completion_date'=> 'required|date',
            // 'actual_completion_date' => 'required|date',
            'bid_Fee'                => 'required',
            // 'registration_type'      => 'required',
            'contract_number'        => 'required',
            'authorized_personel'    => 'required',
            'contact'                => 'required|digits:10',
            'gst_no'                 => 'required|min:15|max:15',
            'email'                  => 'required|email',
            'contractor_address'     => 'required',
            'contract'               => 'required|mimes:pdf|max:512000',
        ],
        [
            'gst_no.required'=> 'GST No. is required',
            'gst_no.min'     => 'GST No. should be of only 15 characters!',
            'gst_no.max'     => 'GST No. should be of only 15 characters!',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request['project_id']              = $id;
        $request['user_id']                 = auth()->user()->id;
        $request['end_date']                = date('Y-m-d');
        $request['contract_unique_id']      = uniqid();
        $request['commencement_date']       = $this->chnageDate($request->commencement_date);
        $request['contract_signing_date']   = $this->chnageDate($request->contract_signing_date);
        $request['actual_completion_date']  = $this->chnageDate($request->actual_completion_date);
        $request['initial_completion_date'] = $this->chnageDate($request->initial_completion_date);

        if($request->has('contract'))
        {
            $file     = $request->file('contract');
            $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());

            $file->move('images/contract', $filename);

            $request['contract_doc'] = $filename;
        }

        $response = Contracts::create($request->all());

       if($response)
       {
            Projects::find($id)->update(['stage' => '2']);

            $url = url('contract');

            return $this->success('created', 'Contract', $url);
        }

        return $this->success('error', 'Contract');
    }


    public function edit($id)
    {
        $contract = ContractSecurities::with('contract')->where('contract_id', $id)->orderBy('id', 'DESC')->paginate('20');
        $data     = Contracts::with('project')->find($id);
        $params   = $data->project->paramsValues ?? [];

        return view('admin.contract.edit', compact('data', 'contract', 'id', 'params'));
    }


    public function view($id)
    {
        $data = Contracts::with('project')->find($id);
        return view('admin.contract.view',compact('data'));
    }


    /**
     *
     */
    public function updateBOQSheet($project_id)
    {
        $project = Projects::find($project_id);

        if($project)
        {
            $edit_sheet = 1;

            return view('admin.milestones.boq', compact('project', 'edit_sheet'));
        }

        abort(404);
    }


    /**
     *
     */
    public function addBOQSheetRow(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            's_no'=> 'required|',
            'rate'=> 'required|numeric',
            'qty' => 'required|numeric',
            'unit'=> 'required|string',
            'item'=> 'required|string',
        ], [
            'qty.numeric'  => 'Quantity should be a numeric value.',
            'rate.numeric' => 'Rate should be a numeric value.',
            'item.string'  => 'Item Name/Detail should be a string value.',
            'qty.required' => 'Kindly provide Quantity value.',
            'item.required'=> 'Item Name/Detail is required.'
        ]);

        if(!$validator->fails())
        {
            if($request->filled('contract_id'))
            {
                $contract = Contracts::find($request->contract_id);

                if($contract)
                {
                    $pwd_boq = PWDBOQ::create([
                        'qty'        => $request->qty,
                        's_no'       => $request->s_no,
                        'item'       => $request->item,
                        'unit'       => $request->unit,
                        'rate'       => $request->rate,
                        'contract_id'=> $contract->id,
                    ]);

                    $return['ok']  = 1;
                    $return['msg'] = 'Entry Record Created Successfully!';
                    $return['url'] = 'reload';
                }
            }
        }
        else
        {
            $return['msg'] = $validator->errors()->first();
        }

        return $return;
    }


    /**
     *
     */
    public function updateBOQSheetData(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if(auth()->user()->role->department == 'PWD' && auth()->user()->role->level == 'TWO')
        {
            if($request->filled('boq_id', 'qty'))
            {
                $boq   = PWDBOQ::find($request->boq_id);

                if($boq)
                {
                    if($boq->qty)
                    {
                        $boq->qty         = $request->qty;

                        if($boq->save())
                        {
                            $return['ok']  = 1;
                            $return['msg'] = 'Entry saved successfully!';
                        }
                        else
                        {
                            $return['msg'] = 'Failed to save entry. Please try again!';
                        }
                    }
                    else
                    {
                        $return['msg'] = 'An invalid edit entry request is detected!';
                    }
                }
                else
                {
                    $return['msg'] = 'An invalid or tampered request is detected';
                }
            }
            else
            {
                if(!$request->filled('qty'))
                {
                    $return['msg'] = 'Quantity field is required!';
                }
            }
        }
        else
        {
            $return['msg'] = 'You are not authorized to perform this action.';
        }

        return $return;
    }


    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'company_name'           => 'required',
            'procurement_contract'   => 'required|numeric|min:1',
            // 'company_resgistered_no' => 'required',
            'contract_signing_date'  => 'required|date|before_or_equal:now',
            'commencement_date'      => 'required|date',
            'initial_completion_date'=> 'required|date',
            // 'actual_completion_date' => 'required|date',
            'bid_Fee'                => 'required',
            // 'registration_type'      => 'required',
            'contract_number'        => 'required',
            'authorized_personel'    => 'required',
            'contact'                => 'required|digits:10',
            'email'                  => 'required|email',
            'gst_no'                 => 'required|min:15|max:15',
            'contractor_address'     => 'required',
            'contract'               => 'nullable|mimes:pdf|max:512000',
        ],
        [
            'gst_no.required'=> 'GST No. is required',
            'gst_no.min'     => 'GST No. should be of only 15 characters!',
            'gst_no.max'     => 'GST No. should be of only 15 characters!',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request['commencement_date']       = $this->chnageDate($request->commencement_date);
        $request['contract_signing_date']   = $this->chnageDate($request->contract_signing_date);
        $request['actual_completion_date']  = $this->chnageDate($request->actual_completion_date);
        $request['initial_completion_date'] = $this->chnageDate($request->initial_completion_date);

        if($request->has('contract'))
        {
            $file     = $request->file('contract');
            $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());

            $file->move('images/contract', $filename);
            $request['contract_doc'] = $filename;
        }

        $response = Contracts::find($id)->update($request->all());

       if($response)
       {
            Projects::find($id)->update(['stage' => '2']);

            $url = url('contract');

            return $this->success('updated', 'Contract', $url);
        }

        return $this->success('error','Contract ');
    }


    public function cancelContactView($id)
    {
        $data = Contracts::with('project')->find($id);

        return view('admin.contract.cancel',compact('data'));
    }


    public function CanacelContract(request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type'       => 'required',
            'reason'     => 'required',
            'documents.*'=>  'required|mimes:pdf|max:10240',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->type == "CANCEL")
        {
            $data = Contracts::find($id)->update([
                'cancel_type'  => $request->type,
                'cancel_reason'=> $request->reason,
                'status'       => '0'
           ]);
        }
        else
        {
            $data = Contracts::find($id)->update([
                'cancel_type'    => $request->type,
                'forclose_reason'=> $request->reason,
                'status'         => '0'
           ]);
        }

        if ($request->hasFile('documents'))
        {
            $files = $request->file('documents');

            foreach ($files as $file)
            {
                $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
                $file->move('images/contract/cancel/', $filename); 

                Media::create([
                    'mediable_id'  => $id,
                    'mediable_type'=> Contracts::class,
                    'name'         => $filename,
                ]);
            }
        }

        if($data)
        {
            $url = route('contract.index');

            return $this->success('updated','Contract Canceled ',$url);
        }

        return $this->success('error','Please try again after somtime.');
    }


    /**
     *
     */
    public function activityStages(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('activity'))
        {
            $activity = Activity::with('stages')->where('slug', $request->activity)->first();

            if($activity)
            {
                $return['ok']   = 1;
                $return['msg']  = $activity->stages->count() . ' stages fetched for the ' . $activity->name . ' successfully!';
                $return['data'] = $activity->stages;
            }
            else
            {
                $return['msg'] = 'Failed to find the activity whose stages are being fetched!';
            }
        }

        return $return;
    }


    /**
     *
     */
    public function savePhysicalProgress(Request $request)
    {
        $return    = ['ok'=> 0, 'msg'=> NULL];

        $validator = Validator::make($request->all(), [
            'activity'   => 'required',
            'stage'      => 'required',
            'progress'   => 'required|numeric',
            'date'       => 'required|date',
            'contract_id'=> 'required|numeric',
            'items'      => 'nullable|string',
        ]);

        if(!$validator->fails())
        {
            $update            = $request->filled('progress_id');
            $stage             = Stage::where('slug', $request->stage)->first();
            $contract          = Contracts::find($request->contract_id);
            $activity          = Activity::where('slug', $request->activity)->first();
            $physical_progress = $update ? PhysicalProgress::find($request->progress_id) : new PhysicalProgress;

            if($contract && $activity && $stage)
            {
                $total_progress        = PhysicalProgress::where('contract_id', $contract->id)->sum('progress');
                $total_progress        = $update ? ($total_progress - $physical_progress->progress) : $total_progress;

                if($total_progress < 100)
                {
                    if(($total_progress + $request->progress) <= 100)
                    {
                        if($request->progress <= $stage->weightage )
                        {
                            $stage_total_weightage = PhysicalProgress::where('contract_id', $contract->id)->where('stage_id', $stage->id)->sum('progress');
                            $stage_total_weightage = ($update && $stage_total_weightage) ? abs($stage->weightage - $stage_total_weightage) : $stage_total_weightage;

                            if($stage->weightage >= ($stage_total_weightage + $request->progress))
                            {
                                $physical_progress->date        = $this->chnageDate($request->date);
                                $physical_progress->items       = $request->items;
                                $physical_progress->stage_id    = $stage->id;
                                $physical_progress->progress    = $request->progress;
                                $physical_progress->activity_id = $activity->id;
                                $physical_progress->contract_id = $contract->id;

                                if($physical_progress->save())
                                {
                                    if($request->hasFile('images'))
                                    {
                                        foreach($request->file('images') as $file)
                                        {
                                            $filename = str_replace(" ", "-", time().rand(1, 9999) . $file->getClientOriginalName());
                                            $file->move('images/milestone/physical/', $filename);

                                            $response = Media::create([
                                                'name'         => $filename,
                                                'remark'       => $request->items,
                                                'project_id'   => $contract->project_id,
                                                'stage_name'   => $stage->name,
                                                'mediable_id'  => $physical_progress->id,
                                                'activity_name'=> $activity->name,
                                                'mediable_type'=> PhysicalProgress::class,
                                            ]);
                                        }
                                    }

                                    $return['ok']  = 1;
                                    $return['url'] = route('projectLevel.physical.view', $contract->project->id);
                                    $return['msg'] = 'Progress saved successfully.';
                                }
                                else
                                {
                                    $return['msg'] = 'Failed to save physical progress this time. Please try again!';
                                }
                            }
                            else
                            {
                                dd($stage_total_weightage, $stage->weightage);
                                $return['msg'] = 'The progress percentage cannot be more than ' . ($stage->weightage - $stage_total_weightage) . '% as ' . $stage_total_weightage . '% of total (' . $stage->weightage . '%) is already assigned.';
                            }
                        }
                        else
                        {
                            $return['msg'] = 'The progress percentage cannot be more than stage weightage: ' . $stage->weightage . '%';
                        }
                    }
                    else
                    {
                        $return['msg'] = 'The progress percentage cannot be more than ' . (100 - $total_progress) . '% as ' . abs($total_progress - $request->progress) . '% of 100% is already assigned';
                    }
                }
                else
                {
                    $return['msg'] = 'Cannot continue as progress has reached 100%';
                }
            }
        }
        else
        {
            $return['msg'] = $validator->errors()->first();
        }

        return $return;
    }


    /**
     *
     */
    public function deletePhysicalProgress(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('progress_id'))
        {
            $progress   = PhysicalProgress::with('contract')->find($request->progress_id);
            $project_id = $progress->contract->project_id;

            if($progress && $progress->delete())
            {
                $return['ok']  = 1;
                $return['url'] = route('projectLevel.physical.view', $project_id);
                $return['msg'] = 'Progress deleted successfully!';
            }
            else
            {
                $return['msg'] = 'Failed to delete the progress this time. Please try again!';
            }
        }

        return $return;
    }
}
