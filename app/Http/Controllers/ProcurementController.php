<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,Media,ProjectCategory,Role,Districts,DefineProject,ProcurementParams, User,ProcurementParamValues, ProcurementProgram};
use Illuminate\Support\Facades\Validator;

class ProcurementController extends Controller
{
    /**
     *
     */
    public function index(request $request)
    {
        $category   = ProjectCategory::all();
        $department = Role::where('affilaited', '2')->whereNotIn('id', [19,25,6])->get();
        $years      = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        $districts  = Districts::all();

        $data = Projects::query();
        $data->with('department', 'defineProject:id,project_id', 'contract');

        if ($request->search)
        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('subcategory','LIKE','%' . $request->search . '%')
                    ->orWhere('assembly', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('constituencie', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('district_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('block', 'LIKE', '%' . $request->search . '%');
            });
        }

        if($request->department)
        {
            $data->where('assign_to', $request->department);
        }

        if($request->category)
        {
            $data->where('category_id', $request->category);
        }

        if($request->status || $request->status == "0")
        {
            if($request->status == 2)
            {
                $data->where('stage', '>=', 2);
            }
            else
            {
                $data->where('stage', $request->status);
            }
        }

        $data = $data->orderBy('id', 'DESC')->paginate('20');
        $data = $this->calculateWeightPercentage($data);

        return view('admin.procurement.index',compact('data', 'category', 'department', 'years', 'districts'));
    }


    public function create($id)
    {
        $data = Projects::with('category', 'params', 'paramsValues', 'department')->find($id);

        return view('admin.procurement.create', compact('data', 'id'));
    }


    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'method_of_procurement'=> 'required',
            'type_of_procurement'=> 'required',
            'bid_fee'              => 'required|numeric',
            'bid_validity'         => 'required|numeric',
            'bid_completion_days'  => 'required|numeric',
            'earnest_money_deposit'=> 'required|numeric',
            'bid_pub_date'         => 'date|before_or_equal:today',
            'bid_pub_doc'          => 'mimes:pdf,jpg,jpeg|max:100240',
        ], [
            'bid_fee.required'            => 'The Tender Fee field is required!',
            'bid_fee.numeric'             => 'The Tender Fee shoudl be a numeric value.',
            'bid_pub_date.date'           => 'The publication date should ve a valid date.',
            'bid_pub_date.before_or_equal'=> 'The bid publication date should be on or before today!',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $check = DefineProject::where('project_id', $id)->first();

        if($check)
        {
            $url = route('procurement.edit', $check->id);

            return $this->success('created', 'Project Defined', $url);
        }

        $request['project_id'] = $id;
        $request['user_id']    = auth()->id();

        if($request->filled('bid_pub_date'))
        {
            $request['bid_pub_date'] = date('Y-m-d', strtotime($request->bid_pub_date));
        }

        $response = DefineProject::create($request->all());

        if($response)
        {
            if($request->has('bid_pub_doc'))
            {
                $file     = $request->file('bid_pub_doc');
                $filename = 'bid_publication_document_'.uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move('images/project/bid_pub_docs', $filename);

                Media::create([
                    'name'         => $filename,
                    'mediable_id'  => $response->id,
                    'mediable_type'=> DefineProject::class,
                ]);
            }

            $url = url('procurement/program/' . $response->id);

            return $this->success('created', 'Project Defined', $url);
        }

        return $this->success('error', 'Project Defined');
    }


    public function edit($id)
    {
        $data    = DefineProject::with('project', 'media', 'media2', 'bid_pub_doc')->findorfail($id);

        $project = Projects::with('category', 'paramsValues', 'department', 'district')->find($data->project_id);
        $params  = ProcurementParamValues::where('project_id', $data->project_id)->get();

        return view('admin.procurement.edit',compact('data', 'project', 'params'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'bid_fee'              => 'required',
            'bid_validity'         => 'required',
            'bid_completion_days'  => 'required|numeric',
            'earnest_money_deposit'=> 'required',
            'bid_pub_date'         => 'nullable|date|before_or_equal:today',
            'bid_pub_doc'          => 'mimes:pdf,jpg,jpeg|max:100240',
        ], [
            'bid_fee.required'            => 'The Tender Fee field is required!',
            'bid_pub_date.date'           => 'The publication date should be a valid date.',
            'bid_pub_date.before_or_equal'=> 'The bid publication date should be on or before today!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $d_proj = DefineProject::find($id);

        if($d_proj)
        {
            if($request->filled('bid_pub_date'))
            {
                $request['bid_pub_date'] = date('Y-m-d', strtotime($request->bid_pub_date));
            }

            $response = $d_proj->update($request->all());

           if($response)
           {
                if($request->has('bid_pub_doc'))
                {
                    $file     = $request->file('bid_pub_doc');
                    $filename = 'bid_publication_document_'.uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move('images/project/bid_pub_docs', $filename);

                    Media::create([
                        'name'         => $filename,
                        'mediable_id'  => $d_proj->id,
                        'mediable_type'=> DefineProject::class,
                    ]);
                }

                $url = route('procurement.edit',$id);

                return $this->success('updated',' Project Defined ',$url);
            }
        }

        return $this->success('error','Project Defined ');

    }


    public function WorkingProgramForm(Request $request, $id)
    {
        $data     = DefineProject::with('project', 'media')->findorfail($id);
        $params   = ProcurementParams::where('category_id', $data->project->category_id)->where('category_type', $data->method_of_procurement)->get();
        $programs = ProcurementProgram::all();

        return view('admin.procurement.program', compact('id', 'data', 'params', 'programs'));
    }


    public function WorkingProgramEditView(Request $request, $id)
    {
        $data     = DefineProject::with('project', 'media', 'media2')->findorfail($id);
        $params   = ProcurementParamValues::where('project_id', $data->project_id)->get();
        $document = false;

        return view('admin.procurement.update-work-program', compact('data', 'id', 'params', 'document'));
    }

}
