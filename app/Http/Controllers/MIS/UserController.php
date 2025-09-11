<?php

namespace App\Http\Controllers\MIS;


use App\Models\User;
use App\Models\Projects;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * List All Users Based on Logged in User
     *
     */
    public function index(Request $request)
    {
        $users     = User::query();
        $is_active = $request->filled('status') ? intval($request->status) : 1;

        if(auth()->user()->role_id !== 1)
        {
            $users->where('user_id', auth()->user()->id);
        }

        if ($request->search)
        {
            $users->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('username','LIKE','%'.$request->search.'%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone_no', 'LIKE', '%' . $request->search . '%');
            });
        }

        if($request->department)
        {
            $users->whereHas('role', function($query) use ($request) {
                $query->where('id', $request->department);
            });
        }

        $users->where('status', $is_active);

        // $users = $users->orderBy('id')->paginate('20');
        $users = $users->orderBy('id')->get();

        $users->load('role', 'permissions');

        $department = null;
        // $department = Role::where('affilaited', auth()->user()->role_id)->get();

        $years = Projects::distinct()
            ->selectRaw('YEAR(hpc_approval_date) as year')
            ->pluck('year');

        return view('mis.user.index', compact('users', 'department'));
    }
}
