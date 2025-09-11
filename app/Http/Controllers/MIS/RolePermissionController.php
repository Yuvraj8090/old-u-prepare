<?php

namespace App\Http\Controllers\MIS;

use App\Models\Role;
use App\Models\MIS\Role as MISRole;
use App\Models\MIS\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $roles = Role::all();
        $roles = MISRole::all();

        return view('admin.role-permission.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::all();

        return view('admin.role-permission.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'permission'=> 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $role = new MISRole();
        $role->name = $request->input('name');
        $role->save();

        $role->permissions()->sync($request->input('permission'));

        return $this->success('created', 'Role', route('roles-permission.index'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role       = MISRole::findorfail($id);
        $permission = Permission::all();

        return view('admin.role-permission.edit', compact('permission', 'role'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|numeric',
            'name'      => 'required|string',
            'permission'=> 'required|array',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $role = MISRole::findorfail($request->id);
            $role->name = $request->input('name');
            $role->save();

            $role->permissions()->sync($request->input('permission'));

            return $this->success('updated', 'Role', route('roles-permission.index'));
        }
        catch(\Exception $e) {
            return ['ok' => 0, 'msg'=> $e->getMessage()];
        }
    }


    /**
     *
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roles_permission' => 'required|numeric',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $role = MISRole::findorfail($request->roles_permission);
            $role->permissions()->detach();
            $role->delete();

            return ['ok' => 1, 'msg'=> 'Role Delete Successfully!'];
        }
        catch(\Exception $e) {
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
}
