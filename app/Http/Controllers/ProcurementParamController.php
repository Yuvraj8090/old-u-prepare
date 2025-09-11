<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{ProcurementParamValues,Projects,DefineProject, MileStones,MilestoneValues, ProcurementParams, User};

class ProcurementParamController extends Controller
{
    // new code changes 7 Feb 2024
    public function index(Request $request)
    {
        $data = Projects::where('procure_level_3', auth()->user()->id)->paginate('20');

        return view('admin.procurement.level3.index',compact('data'));
    }


    public function edit(Request $request, $id)
    {
        $project             = Projects::with('category','paramsValues','department','district')->find($id);
        $params              = ProcurementParamValues::where('project_id',$id)->get();
        $data                = DefineProject::where('project_id',$id)->with('project')->first();
        $procurementLvlThree = User::where('user_id',auth()->user()->id)->get();

        return view('admin.procurement.level3.edit',compact('data', 'project', 'params', 'procurementLvlThree'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data'               => 'required',
                'data.*.project_id'  => 'required|numeric',
                // 'data.*.procurement_param_id' => '',
                'data.*.days'        => 'required',
                'data.*.weight'      => 'required',
                'data.*.planned_date'=> 'required|date',
            ],
            [
                'data.required'               => 'Please add at least one field',
                'data.*.days.required'        => 'Kindly Provide Days',
                'data.*.weight.required'      => 'Kindly Provide Weightage',
                'data.*.planned_date.required'=> 'Kindly Provide Planned Date'
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->data;

        $totalWeight = 0;
        $totalWeight = array_sum(array_column($data, 'weight'));

        if ($totalWeight != "100")
        {
            return response()->json(['errors' => ['error' => "Warning! Total weight is " . $totalWeight . " should be 100."]]);
        }

        foreach($data as $d)
        {
            $d = (object) $d;

            $conditions = ['id' => ($d->id ?? '0')];

            $updateData = [
                'procurement_param_id'=> $d->procurement_param_id ?? 0,
                // 'weight'              => $d->weight,
                'name'                => $d->name,
                'days'                => $d->days,
                'project_id'          => $d->project_id,
                'planned_date'        => $this->chnageDate($d->planned_date),
            ];

            $record = ProcurementParamValues::find($d->id ?? 0);

            if($record)
            {
                if(empty($record->actual_date) && isset($d->weight) && !empty($d->weight))
                {
                    $updateData['weight'] = $d->weight;
                }
            }
            else{
                $updateData['weight'] = $d->weight;
            }

            $response = ProcurementParamValues::updateOrInsert($conditions, $updateData);
        }

        if($response)
        {
            $url = route('procurement.edit', $request->id);

            return $this->success('updated', 'Procurement Porject Program', $url);
        }

        return $this->success('error', 'Procurement Project Program');
    }


    // New Code added 7/feb/2024
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'data.*.id' => 'required|numeric',
            'data.*.actual_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->data;

        foreach($data as $d)
        {
            $updateData = [
                'actual_date' => $this->chnageDate($d['actual_date']),
            ];

            $response = ProcurementParamValues::find($d['id'])->update($updateData);
        }

        if($response)
        {
            $url = url('procurement-project/edit/'.$request->id);

            return $this->success('updated', 'Procurement Project Program', $url);
        }

        return $this->success('error', 'Procurement Porject Program');
    }


    public function updateSingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'          => 'nullable|numeric',
            'name'        =>  'required',
            'project_id'  => 'required|numeric',
            'days'        => 'required',
            'weight'      => 'required|min:1|max:100',
            'planned_date'=> 'required|date',
            'actual_date' => 'nullable|date',
        ]);

        $customMessages = [
            'name.required' => 'The Work Program Name is required.',
        ];

        $validator->setCustomMessages($customMessages);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->id)
        {
            $totalWeight = ProcurementParamValues::where('id', '!=', $request->id)->where('project_id', $request->project_id)->sum('weight');
        }
        else
        {
            $totalWeight = ProcurementParamValues::where('project_id', $request->project_id)->sum('weight');
        }

        if($totalWeight == 100)
        {
                return response()->json(['errors' => ['error' => "Warning! You cant add more because all weight total reach to 100%."]]);
        }
        else
        {
            $weight = 100 - $totalWeight;

            if($weight < $request->weight)
            {
                return response()->json(['errors' => ['error' => "Warning! You cant this because total weight reached more than 100%."]]);
            }
        }

        $updateData = $request->only(['name', 'project_id', 'days', 'weight']);
        $updateData['planned_date'] = $this->chnageDate($request->planned_date);

        if($request->actual_date)
        {
            $updateData['actual_date'] = $this->chnageDate($request->actual_date);
        }

        if($request->id)
        {
            $response = ProcurementParamValues::find($request->id)->update($updateData);
        }
        else
        {
            $response = ProcurementParamValues::create($updateData);
        }

        if($response)
        {
            $url = route('procurement.edit',$request->procurement_id);

            if($request->id)
            {
                 return $this->success('updated','Porject Program ',$url);
            }
            else
            {
                return $this->success('created','Porject Program ',$url);
            }
        }

        return $this->success('error','Procurement Porject Program  ');
    }



    public function delete($id){

        $check = ProcurementParamValues::find($id);

        if(!$check){
            return response()->json(['errors' =>  ['error' => "Not found!"]]);
        }

        $response = $check->delete();

        if($response){
             return $this->success('deleted','Procurement Program ','');
        }

        return $this->success('error','Procurement Porject Program ');

    }

}
