<?php

namespace App\Http\Controllers;

use App\Models\{User, Role, Projects};
use App\Models\MIS\Department;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class ManageLoginController extends Controller
{
    public function index(Request $request)
    {
        $data      = User::query();
        $is_active = $request->filled('status') ? intval($request->status) : 1;

        if(auth()->user()->role_id !== 1)
        {
            $data->where('user_id', auth()->user()->id);
        }

        if ($request->search)
        {
            $data->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('username','LIKE','%'.$request->search.'%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone_no', 'LIKE', '%' . $request->search . '%');
            });
        }

        if($request->department)
        {
            $data->whereHas('role', function($query) use ($request) {
                $query->where('id', $request->department);
            });
        }

        $data->where('status', $is_active);

        $data = $data->orderBy('id')->get();

        $data->load('role');

        $department = null;
        // $department = Role::where('affilaited', auth()->user()->role_id)->get();

        $years = Projects::distinct()
            ->selectRaw('YEAR(hpc_approval_date) as year')
            ->pluck('year');

        return view('admin.logins.index', compact('data', 'department'));
    }


    public function create()
    {
        $department = Department::all();
        $designation = Role::get();

        return view('admin.logins.create', compact('department', 'designation'));

        if(auth()->user()->role_id !== 1)
        {
            $department = Role::where('affilaited', auth()->user()->role_id)->get();

            return view('admin.logins.create',compact('department'));
        }

        abort(404);
    }


    public function store(Request $request)
    {
        if(in_array(auth()->user()->role_id,[2, 6, 19, 25]))
        {
            $validator = Validator::make($request->all(),[
                // 'image' => 'required|mimes:jpeg,png,jpg|max:5000',
                'name'       => 'required',
                'username'   => 'required|unique:users',
                'phone_no'   => 'required|numeric|digits:10|unique:users,phone_no',
                'designation'=> 'required|max:255',
                'email'      => 'required|unique:users|email',
                'password'   => 'required|min:6|max:20',
                // 'department' => 'required|unique:users,role_id|exists:roles,id'
                'department' => 'required|exists:roles,id'
            ]);

        }
        else
        {
            $validator = Validator::make($request->all(),[
                'image'      => 'nullable|mimes:jpeg,png,jpg|max:5000',
                'name'       => 'required',
                'username'   => 'required|unique:users',
                'designation'=> 'required|max:255',
                'email'      => 'required|unique:users|email',
                'phone_no'   => 'required|numeric|digits:10|unique:users,phone_no',
                'password'   => 'required|min:6|max:20',
                'department' => 'required|exists:roles,id'
            ]);
        }

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();

        if($request->profile_image) {
            $file     = $request->file('profile_image');
            $filename = time().rand(1, 9999).'_'.$request->username.'_image.'.$file->extension();

            $file->move('images/profile/', $filename);
            $data['profile_image'] = $filename;
        }

        $role_to_assign   = Role::where('name', $request->designation)->first();

        $data['user_id']  = auth()->user()->id;
        $data['role_id']  = $role_to_assign ? $role_to_assign->id : $request->department;
        $data['password'] = bcrypt($request->password);

        $response = User::create($data);

        if($response)
        {
            $url = route('manage-logins.index');

            return $this->success('created','User ',$url);
        }

        return $this->success('error','login ');
    }


    /**
     *
     */
    public function show($id)
    {
        $data = User::find($id);

        return view('admin.project.view', compact('data'));
    }


    /**
     *
     */
    public function edit($id)
    {
        $data = User::query();
        $data->with('role');

        if(auth()->user()->role_id !== 1)
        {
            $data->where('user_id', auth()->user()->id);
        }

        $data        = $data->find($id);
        $department  = Department::all();
        $designation = Role::all();

        // dd($data->toArray());

        // $department = Role::where('affilaited',auth()->user()->role_id)->get();
	    $departments = Department::with('designations')->get();

        return view('admin.logins.edit', compact('department', 'data', 'designation', 'departments'));
    }


    /**
     *
     */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'image'        => 'nullable|mimes:jpeg,png,jpg|max:5000',
            'name'         => 'required',
            'username'     => 'required|unique:users,username,' . $id,
            'email'        => 'required|email|unique:users,email,' . $id,
            'designation'  => 'required|max:255',
            'phone_no'     => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users')->ignore($id)
            ],
            'user_password'=> 'nullable|min:6|max:20',
        ];

        if (in_array(auth()->user()->role_id, [2, 6, 19, 25])) {
            // $validationRules['department'] = 'required|unique:users,role_id,' . $id;
        } else {
            $validationRules['department'] = 'required|exists:mis_departments,id';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $role_to_assign   = Role::where('name', $request->designation)->first();

        $data                   = $request->only(['name', 'username', 'email', 'phone_no', 'designation', 'department']);
        $data['role_id']        = $role_to_assign ? $role_to_assign->id : $request->department;
        $data['department_id']  = $request->department;
	    //$data['designation_id'] = $request->designation;

        if ($request->user_password)
        {
            $data['password'] = bcrypt($request->user_password);
        }

        if ($request->hasFile('image'))
        {
            $file     = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/profile/'), $filename);
            $data['profile_image'] = 'images/profile/'.$filename;
        }

        $user    = User::findOrFail($id);
        $updated = $user->update($data);

        if ($updated)
        {
            // $role = Role::findOrFail($request->designation);

            // $user->syncRoles([]);
            // $user->assignRole($role->name);

            $url = route('manage-logins.index');

            return $this->success('updated', 'Department', $url);
        }

        return $this->success('error', 'Login');
    }


    /**
     *
     */
    public function changeStatus($id,$status)
    {
        $response = User::findOrfail($id)->update(['status' => $status]);

        if($response && $status == "1")
        {
            return back()->with('success', 'Login activated successfully.');
        }
        elseif($response && $status == "0")
        {
            return back()->with('success', 'Login De-activated successfully.');
        }

        return back()->with('error', 'Please try again after sometime.');
    }

    // public function destroy($id){
    //     User::findOrfail($id)->delete();
    //     return redirect()->route('manage-login.index')->with('success', 'Login deleted successfully');
    // }

    public function changePasswordView()
    {
        return view('admin.settings.change-password');
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'            => 'required',
            'new_password'        => 'required|min:6',
            'confirm_new_password'=> 'required|same:new_password'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::find(auth()->user()->id);

        if(!$user)
        {
            return response()->json(['errors' => ['error'  => 'User Not Found!']]);
        }

        if (!Hash::check($request->password, $user->password))
        {
            return response()->json(['errors' => ['error'  => 'Incorrect password!']]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if($user)
        {
            $url = url('change-password');

            return $this->success('updated','Password  ',$url);
        }

        return $this->success('error','Please try again later. ');
    }


    /**
     * View My Profile
     *
     * @return View
     */
    public function viewProfile()
    {
        $user = auth()->user();

        return view('admin.settings.profile', compact('user'));
    }


    /**
     *
     */
    public function saveProfile(Request $request)
    {
        $user = auth()->user();

        $validator = $request->validate([
            'name'=> 'required|string|min:2',
            'email'=> [
                'required',
                'email:filter',
                // Rule::unique('users')->ignore($user)
            ],
            'phone'=> 'numeric|digits:10',
            'image'=> [
                File::image()
                ->max(1024)
                ->dimensions(Rule::dimensions()->maxWidth(512)->maxHeight(512))
            ]
        ]);

        $user->name     = $request->name;
        $user->gender   = in_array($request->gender, ['male', 'female', 'other']) ? $request->gender : NULL;
        $user->email    = $request->email;
        $user->phone_no = $request->phone;

        if($request->hasFile('image'))
        {
            $file     = $request->file('image');
            $filename = str_replace(" ", "-", time() . rand(1, 9999) . '_' . $file->getClientOriginalName());

            $file->move('images/user/profile/', $filename);

            $user->profile_image = $filename;
        }

        $user->save();

        return redirect()->back()->withSuccess(['success'=> 'Your profile updated successfully']);
    }
}
