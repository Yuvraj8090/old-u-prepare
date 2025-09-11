<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EnvironmentSocialMilestones,Media};

use Illuminate\Support\Facades\Validator;

class EnvironmentAndSocialPhotos extends Controller
{
    
    public function index(Request $request,$id){

        $milestone = EnvironmentSocialMilestones::with('photos')->find($id);
        
        $data = $milestone->photos ?? [];
        $id = $milestone->id;
        $projectId = $milestone->project_id;
        return view('admin.environment-social.photos.index',compact('data','id','projectId','milestone'));
    }
    
    public function viewPhotos($id){
        $milestone = EnvironmentSocialMilestones::with('photos')->find($id);
        $data = $milestone->photos ?? [];
        return view('admin.environment-social.view.photo',compact('data','milestone'));
    }
    
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'project_id' => 'required',
            'id' => 'required',
            'images.*' => 'required|mimes:jpeg,png,jpg|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $check = EnvironmentSocialMilestones::find($request->id);
        
        if($request->has('images')){
            if(count($request->images) > 0){
                $files = $request->file('images');
                foreach ($files as $file) {
                    $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
                    $file->move('images/milestone/photos/', $filename); 
                    
                    $response = Media::create([
                        'project_id' => $request->project_id,
                        'mediable_id' => $request->id,
                        'mediable_type' => EnvironmentSocialMilestones::class,
                        'name' => $filename,
                        'file_type_name' => 'image'
                    ]);
                }
            }
        }

        if($response){
            $url = url('es/milestone/images/'.$request->id);
            return $this->success('created',' Milestone Images ',$url);
        }
        
        return $this->success('error',' Milestone Images ');
        
    }
    
    public function update(Request $request){
        
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'milestone_id' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg|max:5000',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if($request->has('image')){
                
            $file = $request->file('image');
            $filename = str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
            $file->move('images/milestone/photos/', $filename); 
                    
            $response = Media::find($request->id)->update([
                'name' => $filename,
            ]);
        }

        if($response){
            $url = url('es/milestone/images/'.$request->milestone_id);
            return $this->success('created','Project Milestone',$url);
        }
        
        return $this->success('error','Project Milestone ');
        
    }
    
    public function destroy(Request $request,$id){
        $data = Media::find($id)->delete();
        return back()->with('message','Image Deleted Successfully.');
    }
    

}