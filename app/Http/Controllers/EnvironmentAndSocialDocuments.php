<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EnvironmentSocialMilestones,Media};
use Illuminate\Support\Facades\Validator;

class EnvironmentAndSocialDocuments extends Controller
{
    public function index(Request $request,$id){
        $milestone = EnvironmentSocialMilestones::with('document')->find($id);
            
        $data = $milestone->document ?? [];
        $id = $milestone->id;
        $projectId = $milestone->project_id;
        
        return view('admin.environment-social.document.index',compact('data','id','projectId','milestone'));
    }
    
    public function viewDocuments($id){
        $milestone = EnvironmentSocialMilestones::with('document')->find($id);
        $data = $milestone->document ?? [];
        return view('admin.environment-social.view.document',compact('data','milestone'));
    }
    
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'project_id' => 'required',
            'id' => 'required',
            'document.*' => 'required|mimes:pdf|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $check = EnvironmentSocialMilestones::find($request->id);
        
        if($request->has('document')){
            if(count($request->document) > 0){
                $files = $request->file('document');
                foreach ($files as $file) {
                    $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
                    $file->move('images/milestone/document/', $filename); 
                    
                    $response = Media::create([
                        'mediable_id' => $request->id,
                        'mediable_type' => EnvironmentSocialMilestones::class,
                        'name' => $filename,
                        'file_type_name' => 'document'
                    ]);
                }
            }
        }

        if($response){
            $url = url('es/milestone/documents/'.$request->id);
            return $this->success('created','Milestone Document',$url);
        }
        
        return $this->success('error','Milestone Document ');
    }
    
    public function update(Request $request){
        
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'image' => 'required|mimes:pdf|max:5000',
            'milestone_id' => 'required|numeric'
        ]);
        
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->has('image')){
                
            $file = $request->file('image');
            $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
            $file->move('images/milestone/document/', $filename); 
                    
            $response = Media::find($request->id)->update([
                'name' => $filename,
            ]);
        }

        if($response){
            $url = url('es/milestone/documents/'.$request->milestone_id);
            return $this->success('updated','Project Milestone',$url);
        }
        
        return $this->success('error','Project Milestone ');
    
    }

        
    
    public function destroy(Request $request,$id){
        $data = Media::find($id)->delete();
        return back();
    }
}