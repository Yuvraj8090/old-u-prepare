<?php

namespace App\Http\Controllers;

use App\Helpers\Assistant;
use Illuminate\Http\Request;

use App\Imports\BOQImport;

use Exception;

use App\Models\PWDBOQ;
use App\Models\Projects;
use App\Models\Contracts;
use App\Models\MileStones;
use App\Models\MIS\Month;
use App\Models\MIS\BOQEntry;
use App\Models\MilestonesDocument;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class MilesStonesController extends Controller
{
    // code added on 15 feb 2024
    public function index($id = "0")
    {
        $data = MileStones::query();

        if( auth()->user()->role->department == 'ENVIRONMENT')
        {
            $data->where('type', '2');
        }
        elseif(auth()->user()->role->department == 'SOCIAL')
        {
           $data->where('type', '3');
        }
        else
        {
            $data->where('type', '1');
        }

        $data = $data->where('project_id', $id)->orderBy('id', 'desc')->paginate('20');

        return view('admin.milestones.index', compact('data', 'id'));
    }


    public function create($id)
    {
        $data = Projects::find($id);

        return view('admin.milestones.create',compact('data'));
    }


    public function store(Request $request, $id)
    {
        $return = ['error', 'An invalid request is detected!', NULL, NULL];

        // Do Validation Here, Left coz of time constraint
        $contract = Contracts::with('project')->where('project_id', $id)->first();

        if($request->filled('ms_type'))
        {
            $epc       = intval($request->ms_type);
            $msgs      = [];

            if($epc && $request->hasFile('boq_sheet') && !$contract->pwd_boqs()->count())
	        {
	            $params    = ['boq_sheet'=> 'required|mimes:xlsx,xls,csv'];
	        }

            if(!$epc || ($epc && $request->filled('percent_of_work')))
            {
                $params = [
                    'name'           => 'required',
                    'budget'         => 'required|numeric',
                    'percent_of_work'=> 'required|numeric|min:0|max:100',
                    'start_date'     => 'required|date',
                    'end_date'       => 'required|date',
                ];

                $msgs['name.required'] = 'The Milestone Name field is required!';
            }

            $validator = Validator::make($request->all(), $params, $msgs);

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()]);
            }

            $contract = Contracts::with('project')->where('project_id', $id)->first();

            if($contract)
            {
                if($epc && $request->hasFile('boq_sheet'))
                {
                    // Check if BOQs Exists for this Contracat
                    $exists = PWDBOQ::where('contract_id', $contract->id)->first();

                    if(!$exists)
                    {
                        try {
                            // Upload Excel File
                            $result = Assistant::upload($request->boq_sheet, 'contract', 'get');

                            if($result->done)
                            {
                                $contract->boq_sheet = $result->file;
                                $contract->save();

                                $result = Excel::import(new BOQImport($contract), public_path($contract->boq_sheet));

                                if($result)
                                {
                                    $contract->ms_type = $epc;
                                    $contract->save();

                                    if($contract->project->stage < 3)
                                    {
                                        $contract->project->stage = 3;
                                        $contract->project-save();
                                    }
                                }
                                else
                                {
                                    $return[1] = 'Failed to save BOQ Sheet Data! Kindly Contact Admin.';
                                }

                                $return[0] = 'success';
                                $return[1] = 'BOQ Sheet Uploaded Successfully';
                                $return[3] = ['id'=> $contract->id, 'epc'=> 1];
                            }
                            else
                            {
                                $return['msg'] = 'Failed to upload BOQ Sheet. Please Try Again!';
                            }
                        } catch(Exception $e) {
                            $return[1] = $e->getMessage();
                        }
                    }
                    else
                    {
                        $return[1] = 'BOQ Sheet Already uploaded for this contract!';
                    }
                }
                else
                {
                    $totalPercentage = MileStones::where('project_id', $id)->whereType(1)->sum('percent_of_work');
                    $allocatedAmount = MileStones::where('project_id', $id)->whereType(1)->sum('budget');

                    $available_budget_amount = 0;

                    if($contract->procurement_contract > intval($allocatedAmount))
                    {
                        $available_budget_amount = $contract->procurement_contract - $allocatedAmount;
                    }

                    if($available_budget_amount < intval($request->budget))
                    {
                        return response((['errors'=> ['error' => 'Failed to set budget. Available contract value for allocationa is: ₹' . number_format($available_budget_amount) ]]));
                    }

                    if($totalPercentage != 0 && $totalPercentage != 100)
                    {
                        $check = 100 - $totalPercentage;

                        if($request->percent_of_work > $check )
                        {
                            return response()->json(['errors' => ['error' => 'Physical Progress percentage cannot be more than ' . $check]]);
                        }
                    }
                    elseif($totalPercentage == 100)
                    {
                        return response()->json(['errors' => ['error' => 'Warning! MileStone is completed not be able to add more.']]);
                    }

                    $data               = $request->all();
                    $data['type']       = 1; // code added 16 feb 2024
                    $data['user_id']    = auth()->user()->id;
                    $data['end_date']   = date('Y-m-d', strtotime($request->end_date));
                    $data['start_date'] = date('Y-m-d', strtotime($request->start_date));
                    $data['project_id'] = $id;

                    $response = MileStones::create($data);

                    if($request->has('documents'))
                    {
                        foreach($request->documents as $doc)
                        {
                            MilestonesDocument::create([
                                'name'        => $doc,
                                'milestone_id'=> $response->id,
                            ]);
                        }
                    }

                    if($response)
                    {
                        $contract->ms_type = $epc;
                        $contract->save();

                        if($contract->project->stage < 3)
                        {
                            $contract->project->stage = 3;
                            $contract->project-save();
                        }

                        $url = url('define/project/view/' . $id . '/#milestonesButton');

                        return $this->success('created', 'Project Milestone', $url, ['epc'=> 0]);
                    }
                }
            }
            else
            {
                $return['msg'] = 'Failed to find the contract!';
            }
        }
        else
        {
            $return[1] = 'Please Select Milestone Type!';
        }

        return $this->success($return[0], $return[1], $return[2], $return[3]);
    }


    /**
     *
     */
    public function updateBoqEntry(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('boq_id', 'boq_date', 'qty'))
        {
            $boq   = PWDBOQ::with('entries')->find($request->boq_id);

            if((int) $request->qty >= 0)
            {
                if($boq)
                {
                    $entry = BOQEntry::where('boq_item_id', $boq->id)->where('date', $request->boq_date)->first();
                    $entry = $entry ?: new BOQEntry;
    
                    // Check if supplied quantity is less than the assigned quantities till now
                    $qty_till = 0;
    
                    if($boq->entries->count())
                    {
                        foreach($boq->entries as $boq_entry)
                        {
                            if($boq_entry->date !== $request->boq_date)
                            {
                                $qty_till += $boq_entry->qty;
                            }
                        }
                    }
    
                    if( ($qty_till + $request->qty) <= $boq->qty )
                    {
                        $entry->qty         = $request->qty;
                        $entry->date        = $request->boq_date;
                        $entry->month_id    = 0;
                        $entry->boq_item_id = $boq->id;
    
                        if($entry->save())
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
                        $return['msg'] = 'The qty cannot exceed ' . ($boq->qty - $qty_till) . ' as ' . $qty_till . ' out of ' . $boq->qty  . ' is already alloted';
                    }
                }
            }
            else
            {
                $return['msg'] = 'Negative values are not allowed!';
            }
        }
        else
        {
            if(!$request->filled('qty'))
            {
                $return['msg'] = 'Quantity field is required!';
            }
        }

        return $return;
    }


    public function edit($id)
    {
        $data = MileStones::with('document')->find($id);

        return view('admin.milestones.edit', compact('data'));
        // return response()->json($data);
    }


    public function documentAdd(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'documents.*' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->has('documents')){
            foreach($request->documents as $doc){
                $response  = MilestonesDocument::create([
                    'milestone_id' => $id,
                    'name' => $doc
                ]);
            }
        }

        if($response){
            $url = url('project/milestones/edit/'.$id);
            return $this->success('created','Milestone Documents',$url);
        }

        return $this->success('error','Milestone Documents ');
    }


    public function UpdateDocument(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = MilestonesDocument::find($request->id)->update([
            'name' => $request->name
        ]);

        if($response){
            return $this->success('updated','Milestone Document ','');
        }
        return $this->success('error','Milestone Document');
    }


    public function update(Request $request, $id = NULL)
    {
        $input_data = [
            'name'              => 'required',
            'budget'            => 'required|numeric',
            'percent_of_work'   => 'required|numeric|min:0|max:100',
            'amended_start_date'=> 'nullable|date',
            'amended_end_date'  => 'nullable|date',
            'project_id'        => 'required|numeric'
        ];

        $msgs = [];

        if(!$id)
        {
            $msgs['milestone_id.numeric']  = 'Cannot proceed. An invalid request detected!';
            $msgs['milestone_id.required'] = 'Cannot proceed. An invalid request detected!';

            $input_data['milestone_id']          = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $input_data, $msgs);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        // foreach($request->documents as $key => $document)
        // {
        //     dump($key, $document);
        // }

        // dd('done');

        $id   = $id ?: $request->milestone_id;
        $data = $request->all();

        unset($data['documents']);

        if($request->amended_start_date)
        {
            $data['amended_start_date'] = date('Y-m-d',strtotime($request->amended_start_date));
        }

        if($request->amended_end_date)
        {
            $data['amended_end_date'] = date('Y-m-d',strtotime($request->amended_end_date));
        }

        $totalPercentage = MileStones::where('id', '!=', $id)->where('project_id', $request->project_id)->whereType(1)->sum('percent_of_work');

        if($totalPercentage != 0 && $totalPercentage != 100)
        {
            $check = 100 - $totalPercentage;

            if($request->percent_of_work > $check)
            {
                return response()->json(['errors' => ['error' => 'Physical Progress percentage cant be more than '.$check ]]);
            }
        }
        elseif($totalPercentage == 100)
        {
            return response()->json(['errors' => ['error' => 'Warning! MileStone is completed not be able to add more.']]);
        }

        $response = MileStones::find($id)->update($data);

        if($request->documents)
        {
            foreach($request->documents as $key => $document)
            {
                if($key > 0)
                {
                    MilestonesDocument::find($key)->update([
                        'name'=> $document[0]
                    ]);
                }
                elseif(count($document))
                {
                    foreach($document as $doc)
                    {
                        MilestonesDocument::create([
                            'name'        => $doc,
                            'milestone_id'=> $request->milestone_id,
                        ]);
                    }
                }
            }
        }

        if($response)
        {
            $url = url('define/project/view/'.$request->project_id.'/#milestonesButton');

            return $this->success('updated','Project Milestone',$url);
        }

        return $this->success('error','Project Milestone ');
    }


    public function destroy($id)
    {
        $response = MileStones::find($id)->delete();

        if($response)
        {
            return back()->with('success','Milestone is delete successfully.');
        }

        return back()->with('error','Please try again after sometime.');
    }
}
