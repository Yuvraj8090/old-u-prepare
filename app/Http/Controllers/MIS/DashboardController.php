<?php

namespace App\Http\Controllers\MIS;

use Illuminate\Http\Request;

use App\Models\MIS\Dashboard\PD\PIU;
use App\Models\MIS\Dashboard\PD\Component;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function(Request $request, $next) {
            if(auth()->check() && auth()->user()->hasRole('Admin'))
            {
                return $next($request);
            }

            abort(403);
        });
    }

    /**
     * 
     */
    public function components()
    {
        $pius  = PIU::all();
        $comps = Component::all();

        return view('mis.dashboard.pd.component.index', compact('comps', 'pius'));
    }


    /**
     * 
     */
    public function editComponent($comp_id)
    {
        $comp = Component::findOrFail($comp_id);

        return view('mis.dashboard.pd.component.edit', compact('comp'));
    }


    /**
     * 
     */
    public function editPIU($piu_id)
    {
        $pius = PIU::all();
        $cpiu = PIU::findOrFail($piu_id);

        return view('mis.dashboard.pd.component.edit', compact('pius', 'cpiu'));
    }


    /**
     * 
     */
    public function saveComponent(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:200',
            'comp_id'=> 'required|numeric',
            'amt_inr'=> 'required|numeric',
            'amt_usd'=> 'required|numeric',
        ], [
            'comp_id.numeric' => 'An Invalid request is detected',
            'comp_id.required'=> 'An Invalid request is detected',
        ]);


        if(!$validator->fails())
        {
            $component = Component::find($request->comp_id);

            if($component)
            {
                $component->name    = $request->name;
                $component->amt_inr = $request->amt_inr;
                $component->amt_usd = $request->amt_usd;

                if($component->save())
                {
                    $return['ok']  = 1;
                    $return['msg'] = 'Component update successfully!';
                    $return['url'] = route('mis.dashboard.pd.components');
                }
                else
                {
                    $return['msg'] = 'Failed to save component. Please try again!';
                }
            }
        }
        else
        {
            $return['msg'] = $validator->errors()->first();
        }

        return $return;
    }


    /**
     * 
     */
    public function savePIU(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            'piu'    => 'required|numeric',
            'amt_inr'=> 'required|numeric',
            'amt_usd'=> 'required|numeric',
        ], [
            'id.numeric' => 'An Invalid request is detected',
            'id.required'=> 'An Invalid request is detected',
        ]);


        if(!$validator->fails())
        {
            $piu = PIU::find($request->piu);

            if($piu)
            {
                $piu->amt_inr = $request->amt_inr;
                $piu->amt_usd = $request->amt_usd;

                if($piu->save())
                {
                    $return['ok']  = 1;
                    $return['msg'] = 'Component PIU update successfully!';
                    $return['url'] = route('mis.dashboard.pd.components');
                }
                else
                {
                    $return['msg'] = 'Failed to save component PIU. Please try again!';
                }
            }
        }
        else
        {
            $return['msg'] = $validator->errors()->first();
        }

        return $return;
    }
}
