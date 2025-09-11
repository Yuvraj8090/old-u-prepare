<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,MileStones,MilestonesDocument,EnvironmentSocialMilestones,EnvironmentSocialDefine,Media};
use Illuminate\Support\Facades\Validator;
 

class EnvironmentSocialMilestonesController extends Controller
{
    
    private function checkType(){
        
        if( auth()->user()->role->department == 'ENVIRONMENT'){
            return '1';
        }elseif(auth()->user()->role->department == 'SOCIAL'){
           return '2';
        }
        return '0';
        
    }
    
    public function index($type,$projectId){
        
        $project = Projects::find($projectId);
        $data = EnvironmentSocialMilestones::query();

        if( auth()->user()->role->department == 'ENVIRONMENT'){
            $data->where('type','1');
        }elseif(auth()->user()->role->department == 'SOCIAL'){
          $data->where('type','2');
        }
        
        $data = $data->where('project_id',$projectId)->orderBy('id','asc')->paginate('20');
        $id= $projectId;
        
        return view('admin.environment-social.milestone.createmilestone',compact('data','id','project'));
    }

    public function create($id){
        $data = Projects::find($id);
        return view('admin.milestone.createmilestone',compact('data'));
    }
    
    public function store(Request $request){
        
        $validator = Validator::make($request->all(),[
            'start_date' => 'required|date',
            'data.*.project_id' => 'required|numeric',
            'data.*.days' => 'required',
            'data.*.weight' => 'required',
            'data.*.planned_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $data = $request->data;
        $totalWeight = 0;
        $totalWeight = array_sum(array_column($data, 'weight'));

        if ($totalWeight != "100") {
            return response()->json(['errors' => ['error' => "Warning! Total weight is ".$totalWeight." should be 100."]]);
        }
        
        if(CheckEnvironmentLvlThree(auth()->user()->role->department,'THREE') == 'true'){
            $type = 1;
            $tt = "environment";
        }elseif(CheckSocialLvlThree(auth()->user()->role->department,'THREE') == 'true'){
            $type = 2;
              $tt = "social";
        }else{
            return response()->json(['errors' => ['error' => "Warning! Permission Denied."]]);
        }

        $startDate = date('Y-m-d',strtotime($request->start_date));
        EnvironmentSocialDefine::where('project_id', $data[0]['project_id'])->where('type',$type)->update(['start_date' => $startDate]);

        foreach($data as $d){
       
            $conditions = ['id' => $d['id'] ?? '0'];
            $updateData = [
                'weight' => $d['weight'],
                'name' => $d['name'],
                'days' => $d['days'],
                'type' => $type,
                'project_id' => $d['project_id'],
                'planned_date' => $this->chnageDate($d['planned_date']),
            ];
            

            $response =  EnvironmentSocialMilestones::updateOrInsert($conditions, $updateData);
        }     

        if($response){
            $url = url($tt.'/projects/update');
            return $this->success('created',' Milestone ',$url);
        }

        return $this->success('error',' Milestone');        
    }


    public function edit($id){
        
        $data = Projects::query();
        
        $type = $this->checkType();
        
        if($this->CheckEnvironmentLvlThree(auth()->user()->role->department,'THREE')){
              $relation = 'EnvironmentDefineProject';
        }elseif($this->CheckSocialLvlThree(auth()->user()->role->department,'THREE')){
           $relation = 'SocialDefineProject';
        }else{
             $relation = null;
        }
        
        $data = $data->with($relation)->find($id);
        
        $data['start_date'] = $relation ? ($data->$relation->start_date ?? '') : '';
        
        $params = EnvironmentSocialMilestones::where('project_id',$id)->whereType($type)->get();
        
        return view('admin.environment-social.milestone.edit-milestone',compact('params','data'));
        
    }
    
    public function editLevelThree($id){
        
        $data = Projects::find($id);
        
        if($this->CheckEnvironmentLvlThree(auth()->user()->role->department,'THREE')){
            $type = '1';
        }elseif($this->CheckSocialLvlThree(auth()->user()->role->department,'THREE')){
             $type = '2';
        }else{
             $type = '0';
        }
        
        $params = EnvironmentSocialMilestones::where('project_id',$id)->where('type',$type)->get();
        return view('admin.environment-social.level-three.edit',compact('params','data'));
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
        
        $userRole  = auth()->user()->role;
        if($this->CheckEnvironmentLvlThree($userRole->department,'THREE')){
             $column = "environment_level_3";
        }elseif($this->CheckSocialLvlThree($userRole->department,'THREE')){
             $column = "social_level_3";
        }else{
             return response()->json(['errors' => ['error' => "Warning! Access Denied."]]);
        }
        
        // dd($request->project_id,auth()->user()->role->id,$column);
    
        $project = Projects::where('id',$request->project_id)->where($column,auth()->user()->role->id)->first();
        
        if(!$project){
             return response()->json(['errors' => ['error' => "Warning! Access Denied."]]);
        }
        
        $response = EnvironmentSocialMilestones::where('id',$request->id)->where('project_id',$project->id)->first();
        
        $planned_date = $this->chnageDate($response->planned_date);
        $actual_date = $this->chnageDate($request->actual_date);
    
        if(!$response){
            return response()->json(['errors' => ['error' => "Not found!"]]);
        }elseif($planned_date > $actual_date){
            return response()->json(['errors' => ['error' => "Warning! Date can't be less than planned date."]]);
        }else{
            $response->update([ 'actual_date' => $actual_date ]);
        }
    
        // $total = ProcurementParamValues::where('project_id',$request->project_id)->where('actual_date',NULL)->count();
        
        if($response){
            return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'message' =>  'Updated Successfully.',
                    'url' => url('update/milestones/progress/'.$request->project_id)
            ]);
        }
        return $this->success('error','Milestone  ');        
    }
    
    public function viewDocuments($id){
        $type = $this->checkType();
        $data = EnvironmentSocialMilestones::with('document','project')->where('id',$id)->where('type',$type)->first();
        return view('admin.environment-social.milestone.documentView',compact('data'));
    }
    
    public function viewPhotos($id){
        $type = $this->checkType();
        $data = EnvironmentSocialMilestones::with('photos','project')->where('id',$id)->where('type',$type)->first();
        return view('admin.environment-social.milestone.photosView',compact('data'));
    }
    
    public function deleteMilestonePhoto($id){
        $data = Media::find($id)->delete();
        return back()->with('success','Image Deleted Successfully.');
        
    }
    
    


    // public function documentAdd(Request $request,$id){

    //     $validator = Validator::make($request->all(),[
    //         'documents.*' => 'required'
    //     ]);
 
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     if($request->has('documents')){
    //         foreach($request->documents as $doc){
    //             $response  = MilestonesDocument::create([
    //                 'milestone_id' => $id,
    //                 'name' => $doc
    //             ]);
    //         }
    //     }

    //     if($response){
    //         $url = url('project/milestones/edit/'.$id);
    //         return $this->success('created','Milestone Documents',$url);
    //     }

    //     return $this->success('error','Milestone Documents ');
    // }

    // public function UpdateDocument(Request $request){

    //     $validator = Validator::make($request->all(),[
    //         'name' => 'required',
    //         'id' => 'required|numeric'
    //     ]);
 
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     $response = MilestonesDocument::find($request->id)->update([
    //         'name' => $request->name
    //     ]);

    //     if($response){
    //         return $this->success('updated','Milestone Document ','');
    //     }
    //     return $this->success('error','Milestone Document');
    // }

    // public function update(Request $request,$id){

    //     $validator = Validator::make($request->all(),[
    //         'name' => 'required',
    //         'budget' => 'required|numeric',
    //         'percent_of_work' =>  'required|numeric|min:0|max:100',
    //         'amended_start_date' => 'nullable|date',
    //         'amended_end_date' => 'nullable|date',
    //         'project_id' => 'required|numeric'
    //     ]);
 
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     $data = $request->all();

    //     if($request->amended_start_date){
    //         $data['amended_start_date'] = date('Y-m-d',strtotime($request->amended_start_date));
    //     }

    //     if($request->amended_end_date){
    //         $data['amended_end_date'] = date('Y-m-d',strtotime($request->amended_end_date));
    //     }
        
    //     if(auth()->user()->role->department == 'ENVIRONMENT'){
    //       $type = "1";
    //     }elseif(auth()->user()->role->department == 'SOCIAL'){
    //       $type = "2";
    //     }
        
    //     $totalPercentage = MileStones::where('id','!=',$id)->where('project_id',$request->project_id)->whereType($type)->sum('percent_of_work');
    //     if($totalPercentage != 0 && $totalPercentage != 100){
    //         $check = 100 - $totalPercentage;
    //         if($request->percent_of_work > $check ){
    //             return response()->json(['errors' => ['error' => 'Physical Progress percentage cant be more than '.$check ]]);
    //         }
    //     }elseif($totalPercentage == 100){
    //         return response()->json(['errors' => ['error' => 'Warning! MileStone is completed not be able to add more.']]);
    //     }
        
    //     $response = MileStones::find($id)->update($data);
        
    //     if($response){
    //         $url = url('define/project/view/'.$request->project_id);
    //         return $this->success('updated','Project Milestone',$url);
    //     }
    //     return $this->success('error','Project Milestone ');
    // }

    // public function destroy($id){

    //     $response = MileStones::find($id)->delete();

    //     if($response){
    //         return back()->with('success','Milestone is delete successfully.');
    //     }
        
    //     return back()->with('error','Please try again after sometime.');
    // }

}




