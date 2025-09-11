<?php

namespace App\Http\Controllers;

use App\Models\{Projects,ProjectCategory,Role,Districts,MileStones,MilestonesDocument,User,EnvironmentSocialDefine,SubCategory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnvironmentAndSocialController extends Controller
{
    /**
     *
     */
    public function index(Request $request, $type = "")
    {
        $userRole  = auth()->user()->role;

        $data = Projects::query();
        // $data->has('contract');
        $data->where('category_id', '1');
        // $data->whereNotIn('stage', [0, 1]);

        if ($request->name)
        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('subcategory','LIKE','%'.$request->name.'%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->name . '%');
            });
        }

        if($request->department)
        {
            $data->where('assign_to',$request->department);
        }

        if($request->category)
        {
            $data->where('category_id',$request->category);
        }

        if($request->subcategory)
        {
            $data->where('subcategory',$request->category);
        }

        if($request->status || $request->status == "0")
        {
            $data->where('stage',$request->status);
        }

        if($request->year)
        {
            $data->whereYear('hpc_approval_date',$request->year);
        }

        if($request->completion_year)
        {
            $data->where('status','3')->whereYear('created_at',$request->completion_year);
        }

        $data->with('EnvironmentDefineProject','department','SocialDefineProject');

        if($this->CheckEnvironmentLvlThree($userRole->department,'THREE'))
        {
            $data->where('environment_level_3',$userRole->id);
        }
        elseif($userRole->department == "ENVIRONMENT" &&  $userRole->level == 'TWO')
        {
            $data->with('environmentMilestones');
         }

        if($this->CheckSocialLvlThree($userRole->department,'THREE'))
        {
            $data->where('social_level_3',$userRole->id);
        }
        elseif($userRole->department == "SOCIAL" &&  $userRole->level == 'TWO')
        {
            $data->with('socialMilestonesSocial');
        }

        // $data = $data->orderBy('id','desc')->paginate(10);
        $data = $data->orderBy('id','desc')->get();
        $data = $this->EnvironmentAndSocialcalculateWeightPercentage($data);

        $years     = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        $districts = Districts::orderBy('name','asc')->get();

        if(in_array($type, ['environment', 'social']) )
        {
            return view('admin.environment-social.level-three.index', compact('data', 'districts', 'years'));
        }

        $department = Role::where('affilaited', '2')->whereNotIn('id', [6, 19, 25])->get();

        return view('admin.environment-social.index', compact('data', 'districts', 'years', 'department'));
    }


    public function fourIndex(Request $request, $type = "")
    {
        $userRole  = auth()->user()->role;

        $data = Projects::query();
        // $data->has('contract');
        $data->where('category_id', '1');
        // $data->whereNotIn('stage', [0, 1]);

        $data->where('es_level_four', auth()->id());

        if ($request->name)
        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('subcategory','LIKE','%'.$request->name.'%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->name . '%');
            });
        }

        if($request->category)
        {
            $data->where('category_id',$request->category);
        }

        if($request->subcategory)
        {
            $data->where('subcategory',$request->subcategory);
        }

        if($request->status || $request->status == "0")
        {
            $data->where('stage', $request->status);
        }

        if($request->year)
        {
            $data->whereYear('hpc_approval_date', $request->year);
        }

        if($request->completion_year)
        {
            $data->where('status', '3')->whereYear('created_at', $request->completion_year);
        }

        $data = $data->orderBy('id', 'desc')->get();
        $data = $this->EnvironmentAndSocialcalculateWeightPercentage($data);


        $years       = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        // $category    = ProjectCategory::all();
        $districts   = Districts::orderBy('name','asc')->get();
        $department  = Role::where('affilaited', '2')->whereNotIn('id', [6, 19, 25])->get();
        $subcategory = SubCategory::orderBy('name')->get()->unique('name');

        return view('admin.es_level_four.index', compact('data', 'districts', 'years', 'subcategory'));
    }


     public function createMileStones(Request $request,$id){

        $data = MileStones::query();

        if( auth()->user()->role->department == 'ENVIRONMENT'){
            $data->where('type','2');
        }

        if(auth()->user()->role->department == 'SOCIAL'){
           $data->where('type','3');
        }

        $milestone  = $data->where('project_id',$id)->orderBy('id','asc')->paginate('20');

        $data = Projects::find($id);

        return view('admin.environment-social.milestones',compact('milestone','id','data'));

     }

    public function milestoneStore(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'percent_of_work' =>  'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(auth()->user()->role->department == 'ENVIRONMENT'){
           $type = "2";
        }elseif(auth()->user()->role->department == 'SOCIAL'){
           $type = "3";
        }

        $totalPercentage = MileStones::where('project_id',$id)->whereType($type)->sum('percent_of_work');

        if($totalPercentage != 0 && $totalPercentage != 100){
            $check = 100 - $totalPercentage;
            if($request->percent_of_work > $check ){
                return response()->json(['errors' => ['error' => 'Physical Progress percentage cant be more than '.$check ]]);
            }
        }elseif($totalPercentage == 100){
            return response()->json(['errors' => ['error' => 'Warning! MileStone is completed not be able to add more.']]);
        }

        $data = $request->all();
        $data['budget'] = 0;
        $data['user_id'] = auth()->user()->id;
        $data['start_date'] =  date('Y-m-d',strtotime($request->start_date));
        $data['type'] = $type;
        $data['end_date'] = date('Y-m-d',strtotime($request->end_date));
        $data['project_id'] = $id;

        $response = MileStones::create($data);

        if($request->has('documents')){
            foreach($request->documents as $doc){
                MilestonesDocument::create([
                    'milestone_id' => $response->id,
                    'name' => $doc
                ]);
            }
        }

        if($response){
            $url = url('/project/milestones/'.$id);
            return $this->success('created','Project Milestone',$url);
        }
        return $this->success('error','Project Milestone ');

    }

    public function edit($id){
        $data = MileStones::with('document')->find($id);
        return response()->json($data);
    }

    public function update(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'percent_of_work' =>  'required|numeric|min:0|max:100',
            'amended_start_date' => 'nullable|date',
            'amended_end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        if($request->amended_start_date){
            $data['amended_start_date'] = date('Y-m-d',strtotime($request->amended_start_date));
        }

        if($request->amended_end_date){
            $data['amended_end_date'] = date('Y-m-d',strtotime($request->amended_end_date));
        }

        if(auth()->user()->role->department == 'ENVIRONMENT'){
           $type = "2";
        }elseif(auth()->user()->role->department == 'SOCIAL'){
           $type = "3";
        }

        $totalPercentage = MileStones::where('id','!=',$id)->where('project_id',$request->project_id)->whereType($type)->sum('percent_of_work');
        if($totalPercentage != 0 && $totalPercentage != 100){
            $check = 100 - $totalPercentage;
            if($request->percent_of_work > $check ){
                return response()->json(['errors' => ['error' => 'Physical Progress percentage cant be more than '.$check ]]);
            }
        }elseif($totalPercentage == 100){
            return response()->json(['errors' => ['error' => 'Warning! MileStone is completed not be able to add more.']]);
        }

        $response = MileStones::find($id)->update($data);

        if($response){
            $url = url('/project/milestones/'.$request->project_id);
            return $this->success('updated','Project Milestone',$url);
        }
        return $this->success('error','Project Milestone ');

    }

    public function defineProjectStoreView(Request $request,$id)
    {
        $data  = Projects::with('department')->find($id);
        $users = User::with('role')->where('user_id', auth()->id())->where('status', 1)->get();

        return view('admin.environment-social.defineProject', compact('data', 'users'));
    }


    public function ViewPage(Request $request, $id)
    {
        $userRole  = auth()->user()->role;

        $data = Projects::query();
        // $data->has('contract');
        $data->where('category_id', '1');
        $data->where('id', $id);
        $data->with('department', 'contract');

        if($userRole->department == "ENVIRONMENT" && $userRole->level == 'TWO')
        {
            $data->with('environmentMilestones','EnvironmentDefineProject');
        }
        elseif($userRole->department == "SOCIAL" && $userRole->level == 'TWO')
        {
            $data->with('socialMilestonesSocial', 'SocialDefineProject');
        }

        $data = $data->first();

        $contracts              = $data->contract ?? [];
        $socialCalculation      = NULL;
        $environmentCalculation = NULL;

        if($userRole->department == "ENVIRONMENT" && $userRole->level == 'TWO')
        {
            $defineProject          = $data->EnvironmentDefineProject ?? [];
            $environmentCalculation = $this->EnviornmentSocialtestCalculation(1, $id);
            //  $milestones =  $data->environmentMilestones ?? [];
        }
        elseif($userRole->department == "SOCIAL" && $userRole->level == 'TWO')
        {
            $defineProject     = $data->SocialDefineProject ?? [];
            $socialCalculation = $this->EnviornmentSocialtestCalculation(2, $id);
            //  $milestones = $data->socialMilestonesSocial ?? [];
        }

        return view('admin.environment-social.view', compact('data','contracts','defineProject','environmentCalculation','socialCalculation'));
    }


    public function defineProjectEditView(Request $request,$id)
    {
        $userRole = auth()->user()->role;

        $data = Projects::query();
        $data->with('department');

        if($userRole->department == "ENVIRONMENT" &&  $userRole->level == 'TWO')
        {
            $data->with('EnvironmentDefineProject');
        }

        if($userRole->department == "SOCIAL" &&  $userRole->level == 'TWO')
        {
            $data->with('SocialDefineProject');
        }

        $data = $data->find($id);

        $data['defineProject'] = $data->EnvironmentDefineProject ?? $data->SocialDefineProject ?? [];

        $users = User::where('user_id', auth()->id())->where('status', 1)->get();

        return view('admin.environment-social.editDefineProject',compact('data','users'));
    }

    public function storeDefineProject(Request $request){

        $checkType = CheckESLevelThree();

        $array =  [
            'project_id' => 'required',
            'scope_of_project' =>  'required|max:5000',
            'assign_to' =>'required|numeric'
        ];

        // if($checkType == 1){
        //     $additional = [
        //       'environment_screening_report'  => 'required|mimes:pdf|max:10240',
        //       'environment_management_plan' => 'required|mimes:pdf|max:10240',
        //     ];
        // }elseif($checkType == 2){
        //     $additional = [
        //       'social_screening_report'  => 'required|mimes:pdf|max:10240',
        //       'social_resettlement_action_plan' => 'nullable|mimes:pdf|max:10240',
        //       'social_management_plan' => 'required|mimes:pdf|max:10240',
        //     ];
        // }else{
        //      return response()->json(['errors' => ['error' => 'Error! Please Try again after refresh page.']]);
        // }
        // $array = array_merge($array,$additional);

        $validator = Validator::make($request->all(),$array);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data['project_id'] = $request->project_id;
        $data['define_project'] = $request->scope_of_project;

        // if($checkType == 1){

        //     if($request->has('environment_screening_report')){
        //         $file = $request->file('environment_screening_report');
        //         $filename = 'environment_screening_report' . uniqid() . '.' . $file->getClientOriginalExtension();
        //         $file->move('images/environment', $filename);
        //         $data['environment_screening_report'] = $filename;
        //     }

        //     if($request->has('environment_management_plan')){
        //         $file1 = $request->file('environment_management_plan');
        //         $filename1 = 'environment_management_plan_'.uniqid().'.'.$file1->getClientOriginalExtension();
        //         $file1->move('images/environment', $filename1);
        //         $data['environment_management_plan'] = $filename1;
        //      }


        // }elseif($checkType == 2){

        //     if($request->has('social_screening_report')){
        //         $file = $request->file('social_screening_report');
        //         $filename = 'social_screening_report_'.uniqid().'.'.$file->getClientOriginalExtension();
        //         $file->move('images/social', $filename);
        //         $data['social_screening_report'] = $filename;
        //     }

        //     if($request->has('social_resettlement_action_plan')){
        //         $file1 = $request->file('social_resettlement_action_plan');
        //         $filename1 = 'social_resettlement_action_plan_'.uniqid().'.'.$file1->getClientOriginalExtension();
        //         $file1->move('images/social', $filename1);
        //         $data['social_resettlement_action_plan'] = $filename1;
        //     }

        //     if($request->has('social_management_plan')){
        //         $file2 = $request->file('social_management_plan');
        //         $filename2 = 'social_management_plan_'.uniqid().'.'.$file2->getClientOriginalExtension();
        //         $file2->move('images/social', $filename2);
        //         $data['social_management_plan'] = $filename2;
        //     }
        // }

        $data['type'] = $checkType;
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 1;

        $response = EnvironmentSocialDefine::create($data);

        Projects::find($request->project_id)->update(['es_level_four' => $request->assign_to]);


        if($response){
            $url = url('environment/projects/update');
            return $this->success('created','Project Defined ',$url);
        }

        return $this->success('error','Project Defined ');

    }


    public function updateDefineProject(Request $request,$id){

        $checkType = CheckESLevelThree();

        $array =  [
            'project_id' => 'required',
            'scope_of_project' =>  'required|max:5000',
            'assign_to' => 'required|numeric'
        ];

        // if($checkType == 1){
        //     $additional = [
        //       'environment_screening_report'  => 'nullable|mimes:pdf|max:10240',
        //       'environment_management_plan' => 'nullable|mimes:pdf|max:10240',
        //     ];
        // }elseif($checkType == 2){
        //     $additional = [
        //       'social_screening_report'  => 'nullable|mimes:pdf|max:10240',
        //       'social_resettlement_action_plan' => 'nullable|mimes:pdf|max:10240',
        //       'social_management_plan' => 'nullable|mimes:pdf|max:10240',
        //     ];
        // }else{
        //      return response()->json(['errors' => ['error' => 'Error! Please Try again after refresh page.']]);
        // }
        // $array = array_merge($array,$additional);


        $validator = Validator::make($request->all(),$array);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $data['define_project'] = $request->scope_of_project;

        // if($checkType == 1){

        //     if($request->has('environment_screening_report')){
        //         $file = $request->file('environment_screening_report');
        //         $filename = 'environment_screening_report' . uniqid() . '.' . $file->getClientOriginalExtension();
        //         $file->move('images/environment', $filename);
        //         $data['environment_screening_report'] = $filename;
        //     }

        //     if($request->has('environment_management_plan')){
        //         $file1 = $request->file('environment_management_plan');
        //         $filename1 = 'environment_management_plan_'.uniqid().'.'.$file1->getClientOriginalExtension();
        //         $file1->move('images/environment', $filename1);
        //         $data['environment_management_plan'] = $filename1;
        //      }


        // }elseif($checkType == 2){

        //     if($request->has('social_screening_report')){
        //         $file = $request->file('social_screening_report');
        //         $filename = 'social_screening_report_'.uniqid().'.'.$file->getClientOriginalExtension();
        //         $file->move('images/social', $filename);
        //         $data['social_screening_report'] = $filename;
        //     }

        //     if($request->has('social_resettlement_action_plan')){
        //         $file1 = $request->file('social_resettlement_action_plan');
        //         $filename1 = 'social_resettlement_action_plan_'.uniqid().'.'.$file1->getClientOriginalExtension();
        //         $file1->move('images/social', $filename1);
        //         $data['social_resettlement_action_plan'] = $filename1;
        //     }

        //     if($request->has('social_management_plan')){
        //         $file2 = $request->file('social_management_plan');
        //         $filename2 = 'social_management_plan_'.uniqid().'.'.$file2->getClientOriginalExtension();
        //         $file2->move('images/social', $filename2);
        //         $data['social_management_plan'] = $filename2;
        //     }

        // }

        $response = EnvironmentSocialDefine::find($id)->update($data);

        Projects::find($request->project_id)->update(['es_level_four' => $request->assign_to]);

        if($response){
            return $this->success('created','Project Defined ','');
        }

        return $this->success('error','Project Defined ');

    }

}
