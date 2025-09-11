<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,MaterialTest,MaterialTestReport,EnvironmentTest,EnvironmentTestReport,SubCategory};

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class QualityTestController extends Controller
{
    public function index($type, $id)
    {
        $projectData = Projects::with('subCategory')->find($id);

        $data = MaterialTest::query();

        $category = NULL;

        if($type == "2")
        {
            $data->where('category',$projectData->subCategory->id);
            $category = $projectData->subCategory->id;
        }

        $data->WithFilteredReports($id);

        $data = $data->where('type', $type)->whereIn('project_id', [0, $id])->get();

        $data->each(function ($materialTest) use ($id)
        {
            $materialTest->total_reports  = $materialTest->reports->count() ?? 0;
            $materialTest->passed_reports = $materialTest->reports->where('status', '1')->count() ?? 0;
            $materialTest->failed_reports = $materialTest->reports->where('status', '0')->count() ?? 0;

            if($materialTest->total_reports != 0 && $materialTest->passed_reports  != 0)
            {
                $materialTest->status_percentage = ($materialTest->passed_reports/$materialTest->total_reports) * 100;
            }
            else
            {
                $materialTest->status_percentage = 0;
            }

            $startReport = $materialTest->reports->first();
            $lastReport  = $materialTest->reports->last();

            $materialTest->start_date = $startReport->test_date ?? NULL;
            $materialTest->end_date   = $lastReport->test_date ?? NULL;

            $materialTest->duration = "X";

            if($materialTest->start_date && $materialTest->end_date)
            {
                $startDate = Carbon::parse($materialTest->start_date);
                $endDate   = Carbon::parse($materialTest->end_date);

                $daysCount = $startDate->diffInDays($endDate);
                $materialTest->duration = $daysCount;
            }

            if($materialTest->start_date  == $materialTest->end_date)
            {
                $materialTest->end_date = NULL;
            }
        });

        // dd($data->toArray());

        return view('admin.qualityTest.material.index', compact('data', 'id', 'type', 'category', 'projectData'));
    }

    public function store(Request $request){

         $validator = Validator::make($request->all(),[
           'project_id' => 'required|numeric',
           'name' => [
            'required','string', 'max:500',
            Rule::unique('work_material_test')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id);
                }),
            ],
            'type'    => 'required',
            'category'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = MaterialTest::create($request->all());

        if($response){
            $url = url('quality/update/'.$request->type.'/'.$request->project_id);
            return $this->success('created','Test ',$url);
        }
        return $this->success('error','Test ');

    }

    public function delete($id){

         $data = MaterialTest::where('id',$id)->where('project_id','!=','0')->delete();

         if($data){
             return back()->with('success','Test Deleted Successfully.');
         }

        return back()->with('error','Please try again after sometime.');
    }

    public function indexReport($type, $id, $projectId)
    {
        $test = MaterialTest::find($id);
        $data = MaterialTestReport::with('test')->where('type', $type)->where('test_id', $id)->where('project_id', $projectId)->get();

        return view('admin.qualityTest.material.report', compact('data', 'id', 'test', 'projectId', 'type'));
    }

    public function storeReport(Request $request){

        $validator = Validator::make($request->all(),[
            'type' => 'required|numeric',
            'document' => 'required|mimes:pdf|max:5000',
            'name' => 'required',
            'date' => 'required|date',
            'status' => 'required|numeric',
            'test_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        if($request->document){
            $file = $request->file('document');
            $filename = time().rand(1, 9999).'_document.'.$file->extension();
            $file->move('images/test/report/', $filename);
            $data['document'] = $filename;
        }

        $data['test_date'] = date('Y-m-d',strtotime($request->date));
        $response = MaterialTestReport::create($data);

        if($response){
            $url = url('/quality/report/index/'.$request->type.'/'.$request->test_id.'/'.$request->project_id);
            return $this->success('created','Test Report ',$url);
        }

        return $this->success('error','Test Report ');
    }


    public function updateReport(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'      => 'required',
            'type'    => 'required|numeric',
            'document'=> 'nullable|mimes:pdf|max:5000',
            'name'    => 'required',
            'date'    => 'required|date',
            'status'  => 'required|numeric',
            'test_id' => 'required|numeric'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        if($request->document)
        {
            $file     = $request->file('document');
            $filename = time().rand(1, 9999).'_document.'.$file->extension();

            $file->move('images/test/report/', $filename);
            $data['document'] = $filename;
        }

        $data['test_date'] = date('Y-m-d', strtotime($request->date));
        $response = MaterialTestReport::find($request->id)->update($data);

        if($response)
        {
            $url = url()->previous();
            return $this->success('updated', 'Test Report ', $url);
        }

        return $this->success('error','Test Report ');
    }


    public function ReportDelete($id)
    {
        $data = MaterialTestReport::where('id',$id)->delete();

         if($data){
             return back()->with('success','Test Report Deleted Successfully.');
         }

        return back()->with('error','Please try again after sometime.');
    }


    public function environmentIndex($id){

        $projectData = Projects::with('subCategory')->find($id);

        $totalTests1 = EnvironmentTest::where('type',1)->whereIn('project_id',[0,$id])->count();
        $testsWithReports1 = EnvironmentTest::WithHasReports($id)->where('type',1)->whereIn('project_id',[0,$id])->count();
        $data1 = ($totalTests1 > 0) ? ($testsWithReports1 / $totalTests1) * 100 : 0;

        $totalTests2 = EnvironmentTest::where('type',2)->whereIn('project_id',[0,$id])->count();
        $testsWithReports2 = EnvironmentTest::WithHasReports($id)->where('type',2)->whereIn('project_id',[0,$id])->count();
        $data2 = ($totalTests2 > 0) ? ($testsWithReports2 / $totalTests2) * 100 : 0;

        $totalTests3 = EnvironmentTest::where('type',3)->whereIn('project_id',[0,$id])->count();
        $testsWithReports3 = EnvironmentTest::WithHasReports($id)->where('type',3)->whereIn('project_id',[0,$id])->count();
        $data3 = ($totalTests3 > 0) ? ($testsWithReports3 / $totalTests3) * 100 : 0;

        return view('admin.qualityTest.environment.preIndex',compact('data1','data2','data3','id','projectData'));
    }


    public function environmentTests($type, $id)
    {
        $data = EnvironmentTest::WithFilteredReports($id)->where('type', $type)->whereIn('project_id', [0, $id])->get();

        return view('admin.qualityTest.environment.index', compact('data', 'type', 'id'));
    }


    public function environmentStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
            'type'       => 'required|numeric',
            'name'       => [
                'required','string', 'max:500',
                Rule::unique('work_environment_test')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id);
                }),
            ],
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = EnvironmentTest::create($request->all());

        if($response)
        {
            $url = url('/quality/environement/tests/' . $request->type . '/' . $request->project_id);

            return $this->success('created', 'Environment Test ', $url);
        }

        return $this->success('error', 'Environment Test ');
    }


    public function environmentDelete($id)
    {
         $data = EnvironmentTest::where('id',$id)->where('project_id','!=','0')->delete();

         if($data)
         {
             return back()->with('success','Test Deleted Successfully.');
         }

        return back()->with('error','Please try again after sometime.');
    }


    public function storeEnvironmentReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'      => 'required|numeric',
            'document'  => 'nullable|mimes:pdf|max:5000',
            'date'      => 'required|date',
            'status'    => 'required|numeric',
            'test_id'   => 'required|numeric',
            'project_id'=> 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        if($request->document)
        {
            $file     = $request->file('document');
            $filename = time().rand(1, 9999).'_document.'.$file->extension();

            $file->move('images/test/environment/report/', $filename);
            $data['document'] = $filename;
        }

        $data['date'] = date('Y-m-d', strtotime($request->date));
        $response     = EnvironmentTestReport::where('project_id', $request->project_id)->where('test_id', $request->test_id)->where('type', $request->type)->first();

        if($response)
        {
            $response = $response->update($data);
            $tt = "updated";
        }
        else
        {
           $response = EnvironmentTestReport::create($data);
           $tt       = "created";
        }

        if($response)
        {
            $url = url()->previous();

            return $this->success($tt,'Test Report ',$url);
        }

        return $this->success('error','Test Report ');
    }


    public function ReportEnvironmentDelete($id){

         $data = EnvironmentTestReport::where('id',$id)->delete();

         if($data){
             return back()->with('success','Test Report Deleted Successfully.');
         }

        return back()->with('error','Please try again after sometime.');
    }


    public function adminTestReport($id)
    {
        $projectData = Projects::with('subCategory')->find($id);

        $materialData = MaterialTest::query();

        $materialData = $materialData->where('type','1')->whereIn('project_id',[0,$id])->get();

        $materialData->each(function ($materialTest) use ($id) {

            $materialTest->total_reports = $materialTest->reports()->where('project_id',$id)->count();
            $materialTest->passed_reports = $materialTest->reports()->where('project_id',$id)->where('status', '1')->count();
            $materialTest->failed_reports = $materialTest->reports()->where('project_id',$id)->where('status', '0')->count();

        });

        if($materialData->sum('total_reports') != 0 && $materialData->sum('passed_reports')  != 0){
                $materialData['status_percentage'] = number_format((($materialData->sum('passed_reports')/$materialData->sum('total_reports')) * 100),'2');
        }else{
                $materialData['status_percentage'] = 0;
        }

        $categoryData = MaterialTest::query();
        $categoryData->where('category',$projectData->subCategory->id);
        $categoryData = $categoryData->where('type','2')->whereIn('project_id',[0,$id])->get();

        $categoryData->each(function ($categorylTest) use ($id) {

            $categorylTest->total_reports = $categorylTest->reports()->where('project_id',$id)->count();
            $categorylTest->passed_reports = $categorylTest->reports()->where('project_id',$id)->where('status', '1')->count();
            $categorylTest->failed_reports = $categorylTest->reports()->where('project_id',$id)->where('status', '0')->count();

        });

        if($categoryData->sum('total_reports') != 0 && $categoryData->sum('passed_reports')  != 0){
                $categoryData['status_percentage'] = number_format((($categoryData->sum('passed_reports')/$categoryData->sum('total_reports')) * 100),'2');
        }else{
                $categoryData['status_percentage'] = 0;
        }

        $environmentTest = EnvironmentTest::query();
        $environmentTest->WithFilteredReports($id);
        $environmentTest = $environmentTest->whereIn('project_id',[0,$id])->get();

        $environmentTest->each(function ($evTest) use ($id) {
            $evTest->total_reports = $evTest->reports->where('project_id',$id)->count() ?? 0;
            $evTest->passed_reports = $evTest->reports->where('project_id',$id)->where('status', '1')->count() ?? 0;
            $evTest->failed_reports = $evTest->reports->where('project_id',$id)->where('status', '0')->count() ?? 0;
        });

        $evTotal = $environmentTest->count();
        $evPass = $environmentTest->sum('passed_reports');
        $evFailed = $environmentTest->sum('failed_reports');

        if($evTotal != 0 && $evPass != 0){
            $evStatusPercentage = number_format((($evPass/$evTotal) * 100),'2');
        }else{
            $evStatusPercentage = 0;
        }

        return view('admin.qualityTest.index',compact('projectData','materialData','categoryData','evTotal','evPass','evFailed','evStatusPercentage'));
    }
}
