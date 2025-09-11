<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class GeneralController extends Controller
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
    public function versionOne()
    {
        return ['msg'=> 'Welcome to UPREPARE API\'s Version 1.'];
    }
}
