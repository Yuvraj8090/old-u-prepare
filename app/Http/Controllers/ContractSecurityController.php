<?php

namespace App\Http\Controllers;

use App\Models\{Contracts, ContractSecurities,Media};

use App\Models\MIS\Contract\SecurityType;
use App\Models\MIS\Contract\SecurityForm;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ContractSecurityController extends Controller
{
    /**
     *
     */
    public function index(Request $request, $id)
    {
        $data          = ContractSecurities::with(['contract', 'security_type', 'security_form'])->where('contract_id', $id)->orderBy('id', 'DESC')->paginate('20');
        $contract      = Contracts::with('project')->find($id);
        $securityTypes = SecurityType::all();
        $securityForms = SecurityForm::all();

        return view('admin.contract.security.index', compact('data', 'id', 'contract', 'securityForms', 'securityTypes'));
    }


    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'security_type_id' => 'required|numeric',
            'security_form_id' => 'required|numeric',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'security_number'  => 'required',
            'issuing_authority'=> 'required',
            'amount'           => 'required|numeric|regex:/^\d{1,50}$/',
            'files.*'          => 'required|mimes:pdf|max:10240',
        ],
        [
            'security_type_id.numeric' => 'Security name is required!',
            'security_formid.required' => 'Security name is required!',
            'security_type_id.numeric' => 'Form of Security is required!',
            'security_type_id.required'=> 'Form of Security is required!',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request['start_date']        = $this->chnageDate($request->start_date);

        $request['contract_id']       = $id;
        $request['end_security_date'] = $this->chnageDate($request->end_date);

        $response = ContractSecurities::create($request->all());

        if ($request->hasFile('files'))
        {
            $files = $request->file('files');

            foreach ($files as $file)
            {
                $filename = str_replace(" ", "-", time().rand(1,9999).$file->getClientOriginalName());

                $file->move('images/security', $filename);

                Media::create([
                    'name'         => $filename,
                    'mediable_id'  => $response->id,
                    'mediable_type'=> ContractSecurities::class,
                ]);
            }
        }

       if($response)
       {
            $url = url('contract-security/index/'.$id);

            return $this->success('created','Contract ',$url);
        }

        return $this->success('error','Contract ');
    }


    public function edit($id)
    {
        $data          = ContractSecurities::with('contract', 'medias')->find($id);
        $securityTypes = SecurityType::all();
        $securityForms = SecurityForm::all();

        $medias        = $data->medias ?? [];

        return view('admin.contract.security.edit', compact('data', 'medias', 'securityForms', 'securityTypes'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'security_id'      => 'required|numeric',
            'security_type_id' => 'required|numeric',
            // 'name'             => 'required|string|max:200',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            // 'form_of_security' => 'required',
            'security_number'  => 'required',
            'issuing_authority'=> 'required',
            'amount'           => 'required|numeric|regex:/^\d{1,50}$/',
            'files.*'          => 'nullable|file|mimes:pdf',
        ],
        [
            'security_id.numeric'      => 'Security name is required!',
            'security_id.required'     => 'Security name is required!',
            'security_type_id.numeric' => 'Form of Security is required!',
            'security_type_id.required'=> 'Form of Security is required!',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request['start_date']        = $this->chnageDate($request->start_date);
        $request['end_security_date'] = $this->chnageDate($request->end_date);

        if ($request->hasFile('files'))
        {
            $files = $request->file('files');

            foreach ($files as $file)
            {
                $filename = str_replace(" ", "-", time().rand(1, 9999) . $file->getClientOriginalName());
                $file->move('images/security', $filename);

                Media::create([
                    'name'         => $filename,
                    'mediable_id'  => $id,
                    'mediable_type'=> ContractSecurities::class,
                ]);
            }
        }

        $response = ContractSecurities::find($id)->update($request->all());

       if($response)
       {
            $url = url('contract-security/edit/'.$id);

            return $this->success('Updated', 'Contract Security ', $url);
        }

        return $this->success('error', 'Contract ');
    }


    public function mediaDelete($id)
    {
        $data = Media::find($id)->delete();

        if($data)
        {
           return back()->with('success','Security file deleted successfully.');
        }

        return back()->with('error','Please try again after Sometime.');
    }


    /**
     *
     */
    public function securityTypes(Request $request)
    {
        if($request->ajax())
        {
            $data = SecurityType::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" _slug="' . $row->slug .'">Edit</a>
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" _slug="' . $row->slug .'">Delete</a>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('mis.contract.security.index');
    }


    /**
     *
     */
    public function securityTypeForms(Request $request)
    {
        if($request->ajax())
        {
            $data = SecurityForm::all();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" _slug="' . $row->slug .'">Edit</a>
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" _slug="' . $row->slug .'">Delete</a>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('mis.contract.security.types')->with(['form'=> 1]);
    }


    /**
     *
     */
    public function saveSecurityType(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('name'))
        {
            $sec_typ = $request->filled('form');

            if($request->filled('slug'))
            {
                $security = $sec_typ ? SecurityForm::query() : SecurityType::query();
                $security = $security->where('slug', $request->slug)->first();

                if($security)
                {
                    $security->name = $request->name;

                    if($security->save())
                    {
                        $return['ok'] = 1;
                    }
                }
            }
            else
            {
                $slug   = Str::slug($request->name);
                $exists = $sec_typ ? SecurityForm::query() : SecurityType::query();
                $exists = $exists->where('slug', $slug)->first();

                if(!$exists)
                {
                    if($sec_typ)
                    {
                        SecurityForm::create([
                            'slug'=> $slug,
                            'name'=> $request->name,
                        ]);
                    }
                    else
                    {
                        SecurityType::create([
                            'slug'=> $slug,
                            'name'=> $request->name,
                        ]);
                    }

                    $return['ok'] = 1;
                }
                else
                {
                    $return['msg'] = 'A ' . ($sec_typ ? 'Form of ' : '') . 'Security already exists with the same name!';
                }
            }

            if($return['ok'])
            {
                $return['url'] = route($sec_typ ? 'contract.security.type.forms' : 'contract.security.types');
                $return['msg'] = ($sec_typ ? 'Form of ' : '' ) .  'Security ' . ($request->filled('slug') ? 'Updated' : 'Created') . ' Successfully!';
            }
        }

        return $return;
    }


    /**
     *
     */
    public function deleteSecurityType(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        if($request->filled('slug'))
        {
            $sec_typ  = $request->filled('form');
            $security = $sec_typ ? SecurityForm::query() : SecurityType::query();
            $security = $security->where('slug', $request->slug)->first();

            if($security)
            {
                $security->delete();

                $return['ok']  = 1;
                $return['url'] = route($sec_typ ? 'contract.security.type.forms' : 'contract.security.types');
                $return['msg'] = ($sec_typ ? 'Form of ' : '') . 'Security Deleted Successfully!';
            }
        }

        return $return;
    }
}

