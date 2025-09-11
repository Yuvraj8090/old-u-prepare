<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EnvironmentSocialTemplates};
use Illuminate\Support\Facades\Validator;
 

class EnvironmentSocialTemplateController extends Controller
{
    
    public function index(Request $request){
        $type = $this->CheckEnvironmentSocialType();
        $data = EnvironmentSocialTemplates::where('type',$type)->orderBy('id','desc')->paginate('20');
        return view('admin.template.index',compact('data'));
        
    }
    
    
    public function fourIndex(Request $request){
        $type = $this->CheckEnvironmentSocialType();
        $data = EnvironmentSocialTemplates::where('type',$type)->orderBy('id','desc')->paginate('20');
        return view('admin.template.fourindex',compact('data'));
        
    }
    
    public function store(Request $request){
    
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'excelfile' => 'required|mimes:xlsx,xls|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $type = $this->CheckEnvironmentSocialType();
        $request['type'] = $type;
        
        if($request->has('excelfile')){
            $file = $request->file('excelfile');
            $filename = $request->name.str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
            $file->move('excel/', $filename); 
            $request['excel'] = $filename;
        }
        
        $response = EnvironmentSocialTemplates::create($request->all());
        
        
        
        if($response){
            $url = url()->previous();
            return $this->success('created', 'Template ',$url);
        }
        
        return $this->success('error','Template ');
    }
    
    
    public function update(Request $request){
    
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'name' => 'required|max:255',
            'excelfile' => 'nullable|mimes:xlsx,xls|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $type = $this->CheckEnvironmentSocialType();
        $request['type'] = $type;
        
        if($request->has('excelfile')){
            $file = $request->file('excelfile');
            $filename = $request->name.str_replace(" ","-",time().rand(1,9999).$file->getClientOriginalName());
            $file->move('excel/', $filename); 
            $request['excel'] = $filename;
        }
        
        $response = EnvironmentSocialTemplates::find($request->id)->update($request->all());
        
        if($response){
            $url = url()->previous();
            return $this->success('updated', 'Template ',$url);
        }
        
        return $this->success('error','Template ');
    }
    
    public function delete($id){
        
        $response = EnvironmentSocialTemplates::find($id)->delete();
        
        if($response){
            return back()->with('success','Template Deleted Successfully.');
        }
        
        return back()->with('success','Error! Please Try again after sometime.');
        
    }
    
}