<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Finance,Role};
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    public function index(Request $request){

        $data = Finance::query();
        
        $data->with('department');
        $data->where('agency_id',auth()->user()->role->id);
        
        if($request->quarter){
            $data->where('quarter',$request->quarter);
        }
        
        if($request->year){
            $data->where('year',$request->year);
            $data = $data->orderBy('id','asc')->paginate('20');
           
        }else{
            $data->where('year',date('Y'));
            $data = $data->orderBy('id','desc')->paginate('20'); 
        }
        
        $department = Role::whereNotIn('id',['1'])->get();

        $years = Finance::orderBy('year','desc')->distinct()->pluck('year')->toArray();

        $year = "2023";
        $currentYear = date('Y');

        $years = [];
        for($i=$year;$i<=$currentYear;$i++){
            $years[] = $i;
        }

        return view('admin.finance.index',compact('data','department','years')); 
    }

    public function create(){
        return view('admin.finance.create');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'year' => 'required|date_format:Y|before_or_equal:now',
            'quarter' => 'required|numeric',
            'office_equipment_exp' => 'required|numeric|min:0',
            'rent_expense' =>  'required|numeric|min:0',
            'electricity' => 'required|numeric|min:0',
            'transport' => 'required|numeric|min:0',
            'salaries' => 'required|numeric|min:0',
            'miscellaneous' => 'required|numeric|min:0',
        ]);
  
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $check = Finance::where('user_id',auth()->user()->id)->where('year',$request->year)->where('quarter',$request->quarter)->first();

        if($check){
            return response()->json(['errors' => ['error' => 'On this year & month entry already exist.']]);
        }

        $data = $request->all();

       
        $data = $request->only(['office_equipment_exp','electricity', 'transport', 'salaries', 'miscellaneous','rent_expense']);
        $data += [
                'year' => $request->year,
                'quarter' => $request->quarter,
                'budget' => $request->budget,
                'rent_exp' => $request->rent_expense,
                'agency_id' => auth()->user()->role->id,
                'user_id' => auth()->user()->id,
                'electricty_exp' => $request->electricity,
                'transport_exp' => $request->transport,
                'salaries_exp' => $request->salaries,
                'miscelleneous_exp' => $request->miscellaneous,
                'total_exp' => array_sum($data)
        ];

        $response = Finance::create($data);

        if($response){
            $url = url('finance/index');
            return $this->success('created','Expenditure ',$url);
        }

        return $this->success('error','Added ');     
    }

    public function edit($id){
        
        $data = Finance::findorfail($id);
        $array = [
            '1' => 'JANUARY - MARCH',
            '2' => 'APRIL - JUNE',
            '3' => 'JULY - SEPEMBER',
            '4' => 'OCTOMBER - DECEMBER'
        ];
        
        $quarterName = $array[$data->quarter] ?? '';
        $data['QuaterName'] = $quarterName;
        
        return response()->json($data);
    }

    public function update(Request $request,$id){

        $validator = Validator::make($request->all(),[
            // 'year' => 'required|date_format:Y|before_or_equal:now',
            // 'quarter' => 'required|numeric',
            'office_equipment_exp' => 'required|numeric|min:0',
            'electricity' => 'required|numeric|min:0',
            'transport' => 'required|numeric|min:0',
            'rent_expense' =>  'required|numeric|min:0',
            'salaries' => 'required|numeric|min:0',
            'miscellaneous' => 'required|numeric|min:0',
        ]);
  
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        $data['electricty_exp'] = $request->electricity;
        $data['transport_exp'] = $request->transport;
        $data['salaries_exp'] = $request->salaries;
        $data['miscelleneous_exp'] = $request->miscellaneous;
        $data['rent_exp'] = $request->rent_expense;

        $data['total_exp'] = $request->office_equipment_exp + $request->electricity + $request->rent_expense
                             + $request->transport + $request->salaries 
                             + $request->miscellaneous;

        $response = Finance::find($id)->update($data);

        if($response){
            $url = url('finance/index');
            return $this->success('updated','Expenditure ',$url);
        }
        return $this->success('error','Project Defined ');   
    }

    public function destroy($id){

    }

}
