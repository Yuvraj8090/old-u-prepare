<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Projects,ProcurementParamValues,ProjectCategory,Media,DefineProject,Role,Districts};

class ProcurementLevelThree extends Controller
{
    public function index(Request $request)    {
        $category = ProjectCategory::all();
        $department = Role::where('affilaited','3')->get();
        $years = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        $districts = Districts::all();
        $data = Projects::query();

        if ($request->search)        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('subcategory','LIKE','%'.$request->search.'%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->search . '%');
            });
        }
        if($request->category)        {
            $data->where('category_id',$request->category);
        }
        if($request->status || $request->status == "0")        {
            if($request->status == 2)            {
                $data->where('stage', '>=', 2);
            }            else            {
                $data->where('stage',$request->status);
            }
        }
        $data = $data->with('paramsValues')->where('procure_level_3',auth()->user()->role->id)->orderBy('id','desc')->get();        
        $data = $this->calculateWeightPercentage($data);
        return view('admin.procurement.index',compact('data','category','department','years','districts'));
    }
    public function edit($id)    {
        $data = Projects::with('defineProject','paramsValues')->where('procure_level_3',auth()->user()->role->id)->find($id);
        
        $defineProject = $data->defineProject ?? [];
        $params = $data->paramsValues ?? [];
        
        $document = true;
        if(!empty( $params )){
            $total = ProcurementParamValues::where('project_id',$id)->where('actual_date',NULL)->count();
            if($total == 0){
                $document = false;
            }
        }
        
        return view('admin.procurement-level-3.project-edit', compact('data','defineProject','params','document'));
    }
    
    public function update($id){
        $data = Projects::whereHas('defineProject','paramsValues')->where('procure_level_3',auth()->user()->id)->orderBy('id','desc')->paginate('20');
        return view('admin.procurement-level-3.index',compact('data'));
    }
    
    
    public function updateSingleThree(Request $request){
        
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'project_id' => 'required|numeric',
            'actual_date' => 'required|date',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $project = Projects::where('id',$request->project_id)->where('procure_level_3',auth()->user()->role->id)->first();
        
        if(!$project){
             return response()->json(['errors' => ['error' => "Warning! Access Denied."]]);
        }
        
        $response = ProcurementParamValues::where('id',$request->id)->where('project_id',$project->id)->first();
        
        $planned_date = $this->chnageDate($response->planned_date);
        $actual_date = $this->chnageDate($request->actual_date);
    
        if(!$response){
            return response()->json(['errors' => ['error' => "Not found!"]]);
        }elseif($planned_date > $actual_date){
            return response()->json(['errors' => ['error' => "Warning! Date can't be less than planned date."]]);
        }else{
            $response->update([ 'actual_date' => $actual_date ]);
        }
    
        $total = ProcurementParamValues::where('project_id',$request->project_id)->where('actual_date',NULL)->count();
        
        if($response){
            
            return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'last' =>  ($total == 0) ? true : false, 
                    'message' =>  'Updated Successfully.',
                    'url' => ""
            ]);
                
           
        }

        return $this->success('error','Procurement Porject Program  ');        
    }
    
    public function UploadBidDocument(Request $request){
        
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'project_id' => 'required|numeric',
            'bid_document' => 'required|file|mimes:pdf|max:10240',
            'pre_bid_document' => 'required|file|mimes:pdf|max:10240',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $project = Projects::where('id',$request->project_id)->where('procure_level_3',auth()->user()->role->id)->first();
        
        if(!$project){
             return response()->json(['errors' => ['error' => "Warning! Access Denied."]],422);
        }
        
        if($request->has('bid_document')){
             
            $file = $request->file('bid_document');
            $filename = 'project_bid_document_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/bid_document', $filename);
            
            $file1 = $request->file('pre_bid_document');
            $filename1 = 'project_pre_bid_document_'.uniqid().'.'.$file1->getClientOriginalExtension();
            $file1->move('images/pre_bid_document', $filename1);
            
            $response1 = Media::create([
                'mediable_id' => $request->id,
                'mediable_type' => DefineProject::class,
                'name' => $filename,
                'file_type_name' => 'Bid Document'
            ]);
            
           $response2 = Media::create([
                'mediable_id' => $request->id,
                'mediable_type' => DefineProject::class,
                'name' => $filename1,
                'file_type_name' => 'Pre Bid Document'
            ]);
            
            if($response1 && $response2){
                Projects::where('id', $request->project_id)->where('procure_level_3', auth()->user()->role->id)->update(['stage' => '1']);
            }
        }
        
        if($response1 && $response2){
            $url = url('procurement/three/work-program/edit/'.$request->project_id);
            return $this->success('updated','Bid Document ',$url);
        }

        return $this->success('error','Bid Document ');
        
    }


}