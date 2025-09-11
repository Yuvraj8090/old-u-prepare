<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 
     */
    public function index()
    {
        return ['msg'=> 'Welcome to UPREPARE API\'s!'];
    }


    /**
     * 
     */
    public function dashboard(Request $request)
    {
        $procurement = [];
        $contract    = [];
        $projects    = [];
        $departments = ['PROCUREMENT','USDMA-PROCUREMENT','FOREST-PROCUREMENT','RWD-PROCUREMENT','PWD-PROCUREMENT','PMU-PROCUREMENT'];

        if(in_array(auth()->user()->role->department, $departments))
        {
            $procurementData = $this->ProcurmentChartLogic($request);
            $contract        = $procurementData['contract'];
            $procurement     = $procurementData['procurement'];
        }

        $chartData = $this->physicalChartLogic($request);
        $data      = $chartData['data'];
        $financialData   = $this->financailProgressLogic($request);

        $data['expenses'] = formatIndianNumberLakh($financialData['Works'] + $financialData['Goods'] + $financialData['Consultancy'] + $financialData['others'] + $financialData['officeExense']);

        return $data;
    }
}
