<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,Role,User};

class AdminAssignProject extends Controller
{
    public function index($id){
        
        $data = Projects::query();
        $data->where('assign_to','!=',$id);
        $data = $data->orderBy('id','desc')->paginate('20');

        $title = 'Project Assign';

        return view('admin.assign-project.index',compact('data','id','title'));
    }

    public function viewAssignedProjects($id){

        $data = Projects::query();
        $data->where('assign_to',$id);
        $data->where('user_id',auth()->user()->id);
        $data = $data->orderBy('id','desc')->paginate('20');
        
        $data = $this->calculateWeightPercentage($data);
        
        
        $title = 'View Assigned Project';
        return view('admin.assign-project.index',compact('data','id','title'));
    }

    public function updateProjectUser($projectId,$AssignUserId){
        
        $response = Projects::find($projectId)->update(['assign_to' => $AssignUserId]);

        if($response = true){
            return back()->with('success','Project Assigned Successfully.');
        }

        return back()->with('error','Please try after sometime.');
    }



}
