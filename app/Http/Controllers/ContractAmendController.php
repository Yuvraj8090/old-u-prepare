<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Contracts,ContractAmendDetails};
use Illuminate\Support\Facades\Validator;


class ContractAmendController extends Controller
{
    public function index(Request $request,$id){
        $contract = Contracts::with('project:id,name')->find($id);
        $data = ContractAmendDetails::where('contract_id',$id)->orderBy('id','desc')->get();
        return view('admin.contract.contract-amends.index',compact('data','contract'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'contract_id' => 'required',
            'contrat_value' => 'required|numeric',
            'contrat_date' => 'required|date',
            'contract_amend_document' => 'required|mimes:pdf'
        ]);
  
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $date = date("Y-m-d",strtotime($request->contrat_date));

        $file = $request->file('contract_amend_document');
        $filename = time().rand(1, 9999).'_image.'.$file->extension();
        $file->move('images/contract/amend/', $filename);
        
        $response = ContractAmendDetails::create([
            'contract_id' => $request->contract_id,
            'cost' => $request->contrat_value,
            'amend_date' =>  $date,
            'document' => $filename
        ]);

        Contracts::find($request->contract_id)->update([
            'procurement_contract' =>  $request->contrat_value,
            'end_date' =>  $date 
        ]);

       if($response){
            $url = url('contract-amend/index/'.$request->contract_id);
            return $this->success('created','Contract Amended ',$url);
        }
        return $this->success('error','Contract ');        
    }

    public function edit(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'contract_id' => 'required',
            'cost' => 'required|numeric',
            'date' => 'required|date'
        ]);
  
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $response = ContractAmendDetails::find($id)->update($request->all());

        if($response){
            $url = route('contract.index');
            return $this->success('created','Contract ',$url);
        }
        return $this->success('error','Contract '); 
        
    }
}
