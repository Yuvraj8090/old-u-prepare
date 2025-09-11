<?php

namespace App\Http\Controllers\MIS;

use App\Models\Role;
use App\Models\MIS\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::where('status',1)->get();

        return view('admin.department.index',compact('departments') );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $departments = Department::where('status',1)->get();
        $roles = Role::all();

        return view('admin.department.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $return = ['ok', 'msg'=> 'An invalid request is detected'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $department = new Department();
        $department->name = $request->input('name');

        $department->save();
        $department->roles()->sync($request->input('role'));

        return $this->success('created', 'Department', route('department.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $department = Department::findorfail($id);
        return view('admin.department.edit',compact('department','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|numeric',
            'name'=> 'required|string',
            'role'=> 'required|array',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $department = Department::findorfail($request->id);
            $department->name = $request->input('name');
            $department->save();

            $department->roles()->sync($request->input('role'));

            return $this->success('updated', 'Department', route('department.index'));
        }
        catch(\Exception $e)
        {
            return ['ok' => 0, 'msg'=> 'Error'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try{
            $department = Department::findorfail($request->department);
            $department->roles()->detach();
            $department->delete();
            return ['ok' => 1, 'msg'=> 'Department Delete Successfully!'];
        }catch(\Exception $e){
            return ['ok' => 0, 'msg'=> 'Error'];
        }

    }


}
