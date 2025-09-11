<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,EnvironmentSocialTest,EnvironmentAndSocialTestReport,SubCategory,EnvironmentSocialPhotos};
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class EnvironmentAndSocialQualityTest extends Controller
{   
    
    public function store(Request $request){
        
         $validator = Validator::make($request->all(),[
           'project_id' => 'required|numeric',
           'name' => [
            'required','string', 'max:500',
            Rule::unique('work_material_test')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id);
                }),
            ],
            'type' => 'required',
            'test_id' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        
        $request['module_type'] = $this->CheckEnvironmentSocialType();
        $response = EnvironmentSocialTest::create($request->all());
        
        if($response){
            $url = url()->previous();
            return $this->success('created','Test ',$url);
        }
        return $this->success('error','Test ');    
        
    }

    /**
     * 
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'id'        => 'required|numeric',
           'type'      => 'numeric',
           'project_id'=> 'numeric',
           'name' => [
               'required','string', 'max:500',
                Rule::unique('work_material_test')
                ->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id);
                }),
            ],
            'planned_date'=> 'date'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = EnvironmentSocialTest::find($request->id)->update($request->all());

        if($response)
        {
            if($request->filled(['type', 'project_id', 'planned_date']))
            {
                $planned_date = date('Y-m-d', strtotime($request->planned_date));

                $response = EnvironmentAndSocialTestReport::where('project_id', $request->project_id)
                    ->where('test_id', $request->id)
                    ->where('type', $request->type)
                    ->first();

                if(!$response)
                {
                    $response = EnvironmentAndSocialTestReport::create([
                        'type'      => $request->type,
                        'status'    => 1,
                        'test_id'   => $request->id,
                        'project_id'=> $request->project_id,
                    ]);
                }

                $response = $response->update([
                    'planned_date'=> $planned_date
                ]);
            }

            $url = url()->previous();

            return $this->success('updated','Test ',$url);
        }

        return $this->success('error', 'Test');
    }
    
    public function environmentIndex($id)
    {
        $projectData = Projects::with('subCategory')->find($id);
        $moduleType  = $this->CheckEnvironmentSocialType();

        $data = [];

        for($i=1; $i<=3; $i++)
        {
            $testsWithReports1 = EnvironmentSocialTest::where('type', $i)
                ->where('module_type', $moduleType)
                ->where('have_child', 0)
                ->whereIn('project_id', [0, $id])
                ->WithFilteredReports($id)
                ->WithFilteredSubtests($id)
                ->get();

            $totalTests     = count($testsWithReports1);
            $testsCompleted = $testsWithReports1->filter(function ($test) use ($id)
            {
                $testHasReports = $test->reports;

                $subtestsCompleted = $test->subtests->filter(function ($subtest)
                {
                    return ($subtest->reports != NULL);
                })->isNotEmpty();

                return $testHasReports || $subtestsCompleted;
            });

            $completed = 0;
            if($testsCompleted->count() > 0)
            {
                $completed = number_format(($testsCompleted->count() / $totalTests) * 100);
            }

            if($i == 1)
            {
                $name = "Pre-Construction Phase";
            }
            elseif($i == 2)
            {
               $name = "Construction Phase"; 
            }
            elseif($i == 3)
            {
                $name = "Post-Construction Phase";
            }

            $reportDates = $testsCompleted->map(function ($test) {
                return $test->reports->actual_date ?? NULL;
            });

            $data[] = [
                'id'              => $id,
                'end'             => $reportDates->last() ??  'N/A',
                'name'            => $name,
                'type'            => $i,
                'start'           => $reportDates->first() ?? 'N/A',
                'percentage'      => $completed,
                'total_completed' => $testsCompleted->count(),
                'total_activities'=> $totalTests,
            ];
        }

        $yes = true;

        return view('admin.qualityTest.preEnvironmentIndex',compact('data','id','projectData','yes','moduleType'));
    }
    
    public function environmentFourIndex($id){
        
        $projectData = Projects::with('subCategory')->find($id);
        $moduleType = $this->CheckEnvironmentSocialType();
        
        $data = [];
        
        for($i=1; $i<=3; $i++){
            
            $testsWithReports1 = EnvironmentSocialTest::where('type',$i)->where('module_type',$moduleType)->whereIn('project_id',[0,$id])
            ->WithFilteredReports($id)->WithFilteredSubtests($id)
            ->get();
            // $testsWithReports1 = EnvironmentSocialTest::where('type',$i)->where('module_type',$moduleType)->whereIn('project_id',[0,$id])->get();
            
            $totalTests = count($testsWithReports1);
            $testsCompleted = $testsWithReports1->filter(function ($test) {
                $testHasReports = $test->reports;
                $subtestsCompleted = $test->subtests->filter(function ($subtest) {
                    return ($subtest->reports != NULL);
                })->isNotEmpty();
                return $testHasReports || $subtestsCompleted;
            });
            
            $completed = 0;
            if($testsCompleted->count() > 0){
                 $completed = number_format(($testsCompleted->count() / $totalTests) * 100);
            }
            // $completed = number_format(($testsCompleted->count() / $totalTests) * 100);
            
            if($i == 1){
                $name = "Pre-Construction Phase";
            }elseif($i == 2){
               $name = "Construction Phase"; 
            }elseif($i == 3){
                $name = "Post-Construction Phase";
            }
        
            $reportDates = $testsCompleted->map(function ($test) {
                return $test->reports->actual_date ?? NULL;
            });

            
            $data[] = [
                'name' => $name,
                'total_activities' => $totalTests,
                'total_completed' => $testsCompleted->count(),
                'percentage'=> $completed,
                'type' => $i,
                'id' => $id,
                'start' => $reportDates->first() ?? 'N/A',
                'end' => $reportDates->last() ??  'N/A',
            ];
            
        }
        
        $yes = false;
        return view('admin.qualityTest.preEnvironmentIndex',compact('data','id','projectData','yes','moduleType'));
    }


    public function EnvironmentSocialTests($type, $id)
    {
        $moduleType = $this->CheckEnvironmentSocialType();

        $data = EnvironmentSocialTest::where('test_id', 0)
            ->withCount('subtests')
            ->where('module_type', $moduleType)
            ->where('type', $type)
            ->whereIn('project_id', [0, $id])
            ->WithFilteredReports($id)
            ->WithFilteredSubtests($id)
            ->orderBy('have_child', 'asc')
            ->orderBy('project_id', 'asc')
            ->get();

        if(count($data) > 0)
        {
            foreach($data as $d)
            {
                if($d->id == 6)
                {
                    if(isset($d->subtests))
                    {
                        $d['planned_date'] = $d->subtests[0]->reports->planned_date ?? [];
                        $d['actual_date']  = $d->subtests[0]->reports->actual_date?? [];
                    }
                }
            }
        }

        return view('admin.qualityTest.tests.index', compact('data', 'type', 'id', 'moduleType'));
    }


    public function EnvironmentSocialFourTests($type, $id)
    {
        $moduleType = $this->CheckEnvironmentSocialType();
        $data       = EnvironmentSocialTest::where('test_id', 0)
            ->withCount('subtests')
            ->where('module_type', $moduleType)
            ->where('type', $type)
            ->whereIn('project_id', [0,$id])
            ->WithFilteredReports($id)
            ->WithFilteredSubtests($id)
            ->orderBy('have_child', 'asc')
            ->orderBy('project_id','asc')
            ->get();

        if(count($data) > 0)
        {
            foreach($data as $d)
            {
                if(isset($d->subtests))
                {
                    $d['planned_date'] = $d->subtests[0]->reports->planned_date ?? 'N/A1';
                    $d['actual_date']  = $d->subtests[0]->reports->actual_date ?? 'N/A1';
                }
                else
                {
                    $d['planned_date'] =  'N/A';
                    $d['actual_date'] = 'N/A';
                }
            }
        }

        $not = true;

        return view('admin.qualityTest.tests.four.index',compact('data', 'type', 'id', 'not', 'moduleType'));
    }

    public function EnvironmentSocialChildTests($type,$id,$testId){
        
        $moduleType = $this->CheckEnvironmentSocialType();
        $test = EnvironmentSocialTest::find($testId);
        
        $data = EnvironmentSocialTest::where('test_id',$testId)->withCount('subtests')->where('module_type',$moduleType)->where('type',$type)->whereIn('project_id',[0,$id])
        ->WithFilteredReports($id)->WithFilteredSubtests($id)->orderBy('have_child','asc')->orderBy('project_id','asc')->get();
        
        // $data = EnvironmentSocialTest::where('test_id',$testId)->where('module_type',$moduleType)->where('type',$type)->whereIn('project_id',[0,$id])->get();
        
        
        return view('admin.qualityTest.tests.index',compact('data','type','id','testId','moduleType','test'));
        
    }
    
    
    public function EnvironmentSocialFourChildTests($type,$id,$testId){
        
        $moduleType = $this->CheckEnvironmentSocialType();
        $test = EnvironmentSocialTest::find($testId);
        
                   
        $data = EnvironmentSocialTest::where('test_id',$testId)->withCount('subtests')->where('module_type',$moduleType)->where('type',$type)->whereIn('project_id',[0,$id])
        ->WithFilteredReports($id)->WithFilteredSubtests($id)->orderBy('have_child','asc')->orderBy('project_id','asc')->get();
        
        // $data = EnvironmentSocialTest::where('test_id',$testId)->where('module_type',$moduleType)->where('type',$type)->whereIn('project_id',[0,$id])->get();
        
        $not = false;
        return view('admin.qualityTest.tests.four.index',compact('data','type','id','testId','not','test','moduleType'));
        
    }
    
    public function environmentStore(Request $request){
        
         $validator = Validator::make($request->all(),[
          'project_id' => 'required|numeric',
          'type' => 'required|numeric',
          'name' => [
            'required','string', 'max:500',
            Rule::unique('work_environment_test')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $response = EnvironmentSocialTest::create($request->all());
        
        if($response){
            $url = url('/quality/environement/tests/'.$request->type.'/'.$request->project_id);
            return $this->success('created','Environment Test ',$url);
        }
        return $this->success('error','Environment Test ');    
        
    }
    
    public function environmentDelete($id){
        
         $data = EnvironmentSocialTest::where('id',$id)->where('project_id','!=','0')->delete();
         
         if($data){
             return back()->with('success','Test Deleted Successfully.');
         }
         
        return back()->with('error','Please try again after sometime.');
    }


    /**
     * 
     */
    public function storeRReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_id'     => 'required|numeric',
            'project_id'  => 'required',
            'type'        => 'required|numeric',
            // 'planned_date'=> 'required|date',
            'actual_date' => 'required|date',
            'document'    => 'nullable|mimes:pdf,xls,xlsx|max:5000',
            'status'      => 'required|numeric|in:1,2,3',
            'remark'      => 'nullable|max:1000'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all(); 
        
        if($request->document)
        {
            $file     = $request->file('document');
            $filename = time().rand(1, 9999).'_document.' . $file->extension();

            $file->move('images/test/environment/report/four/', $filename);
            $data['document'] = $filename;
        }

        $data['actual_date']  = date('Y-m-d',strtotime($request->actual_date));
        // $data['planned_date'] = date('Y-m-d',strtotime($request->planned_date));
        // For Safety
        if($request->filled('planned_date'))
        {
            unset($data['planned_date']);
        }

        $response = EnvironmentAndSocialTestReport::where('project_id', $request->project_id)
            ->where('test_id', $request->test_id)
            ->where('type', $request->type)
            ->first();

        if($response)
        {
            $response = $response->update($data);
        }
        else
        {
           $response = EnvironmentAndSocialTestReport::create($data);
        }

        if($response)
        {
            $url = url()->previous();

            return $this->success('created','Test Report ',$url);
        }

        return $this->success('error', 'Test Report ');
    }


    public function ReportEnvironmentDelete($id){
        
        $data = EnvironmentSocialTestReport::where('id',$id)->delete();
         
         if($data){
             return back()->with('success','Test Report Deleted Successfully.');
         }
         
        return back()->with('error','Please try again after sometime.');
    }
    
    
    public function indexPhotosAdmin($moduleType,$projectId,$id){
        
        $test = EnvironmentSocialTest::find($id);
        $data = EnvironmentSocialPhotos::where('project_id',$projectId)->where('test_id',$id)->orderBy('id','desc')->paginate('20');
        
        return view('admin.qualityTest.photos.index',compact('data','projectId','id','test','moduleType'));
    }
    
    public function indexPhotos($projectId,$id){
        
        $test = EnvironmentSocialTest::find($id);
        $moduleType = $this->CheckEnvironmentSocialType();
        $data = EnvironmentSocialPhotos::where('project_id',$projectId)->where('test_id',$id)->orderBy('id','desc')->paginate('20');
        
        return view('admin.qualityTest.photos.index',compact('data','projectId','id','test','moduleType'));
    }
    
     public function allPhotos($projectId){
        
        $moduleType = $this->CheckEnvironmentSocialType();
        $testIds = EnvironmentSocialTest::whereIn('project_id',[0,$projectId])->pluck('id')->toArray();
        $data = EnvironmentSocialPhotos::whereIn('test_id',$testIds)->where('project_id',$projectId)->orderBy('id','desc')->paginate('20');
        
        return view('admin.qualityTest.photos.all-images',compact('data','projectId','moduleType'));
    }
    
    
    public function storePhotos(Request $request){
        
          $validator = Validator::make($request->all(),[
            'test_id' => 'required|numeric',
            'project_id' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:5000'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $data = $request->all(); 
        
            $file = $request->file('image');
            $filename = time().rand(1, 9999).'_img.'.$file->extension();
            $file->move('images/test/environment/report/image/', $filename);
            $data['name'] = $filename;

            $response = EnvironmentSocialPhotos::create($data);

            if($response){
                $url = url()->previous();
                return $this->success('created','Test Photos ',$url);
            }

            return $this->success('error','Test Photos ');
        
    }
    
    public function updatePhotos(Request $request){
        
          $validator = Validator::make($request->all(),[
                'id' => 'required|numeric',
                'images' => 'required|mimes:jpg,jpeg,png|max:5000'
          ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $data = $request->all(); 
        
            $file = $request->file('images');
            $filename = time().rand(1, 9999).'_img.'.$file->extension();
            $file->move('images/test/environment/report/image/', $filename);
            $data['name'] = $filename;

            $response = EnvironmentSocialPhotos::find($request->id)->update($data);

            if($response){
                $url = url()->previous();
                return $this->success('updated','Test Photos ',$url);
            }

            return $this->success('error','Test Photos ');
        
    }

    public function deletePhotos($id){
        
        $data = EnvironmentSocialPhotos::find($id)->delete();
         
         if($data){
             return back()->with('success','Test Photos Deleted Successfully.');
         }
         
        return back()->with('error','Please try again after sometime.');
    }
    
    
     public function EnvironmentSocialAdminIndex($moduleType,$type,$id){
        
        $data = EnvironmentSocialTest::where('test_id',0)->withCount('subtests')->where('module_type',$moduleType)->where('type',$type)
        ->whereIn('project_id',[0,$id])->WithFilteredReports($id)->WithFilteredSubtests($id)
        ->orderBy('have_child','asc')->orderBy('project_id','asc')->get();
        
        if(count($data) > 0){
            foreach($data as $d){
                    $d['planned_date'] = 'N/A';
                    $d['actual_date'] = 'N/A';
                    
                     if(isset($d->subtests)){
                        $d['planned_date'] = $d->subtests[0]->reports->planned_date ?? 'N/A';
                        $d['actual_date'] = $d->subtests[0]->reports->actual_date ?? 'N/A';
                    }
            }
        }
        
        return view('admin.qualityTest.ShowTest',compact('data','type','id','moduleType'));
        
    }
    
        
    
    public function EnvironmentSocialAdminSubTests($moduleType,$id,$type,$testId){
        
        $parent = EnvironmentSocialTest::find($testId);
    
      $data = EnvironmentSocialTest::where('test_id',$testId)->withCount('subtests')->where('module_type',$moduleType)->where('type',$type)
        ->whereIn('project_id',[0,$id])->WithFilteredReports($id)->WithFilteredSubtests($id)
        ->orderBy('have_child','asc')->orderBy('project_id','asc')->get();
        
        
        
        // $data = EnvironmentSocialTest::where('test_id',$testId)->where('module_type',$moduleType)->where('type',$type)->whereIn('project_id',[0,$id])->get();
        
        
        return view('admin.qualityTest.ShowTest',compact('data','type','id','parent','id','moduleType'));
        
    }
    
    
}