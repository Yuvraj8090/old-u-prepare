<?php

namespace App\Http\Controllers\MIS;

use App\Models\Role;
use App\Models\User;
use App\Models\MIS\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();

        // return view('mis.user.permission.index', compact('permissions'));
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

        $role = new Role();
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
    public function edit(String $username)
    {
        $user       = User::where('username', $username)->first();

        if($user)
        {
            $permissions = Permission::all();

            return view('mis.user.permission.edit', compact('user', 'permissions'));
        }

        return redirect()->back()->with(['errors'=> 'No User Found with that username']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|numeric',
            'permission'=> 'array',
        ]);

        if (!$validator->fails())
        {
            try
            {
                $user = User::findorfail($request->id);

                $user->permissions()->sync($request->input('permission'));

                return $this->success('updated', 'User', route('mis.users'));
            }
            catch(\Exception $e)
            {
                return ['ok' => 0, 'msg'=> $e->getMessage()];
            }
        }

        return response()->json(['errors' => $validator->errors()]);
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
            $role = Role::findorfail($request->roles_permission);
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
