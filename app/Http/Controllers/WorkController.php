<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects, ProjectCategory, Role, Districts, SubCategory};

class WorkController extends Controller
{
    public function index(Request $request)
    {

        $data = Projects::query();
        $data->with('department', 'defineProject','milestones');

        if ($request->department) {
            $data->where('assign_to', $request->department);
        }

        if ($request->category) {
            $data->where('category_id', $request->category);
        }

        if ($request->status) {
            $data->where('status', $request->status);
        }

        if ($request->year) {
            $data->whereYear('hpc_approval_date', $request->year);
        }

        if (auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT') {
            $data->where('assign_to', auth()->user()->role->id);
            $data->whereNotIn('stage',[0,1]);
        }

        $data = $data->orderBy('id', 'desc')->paginate('20');
        $data = $this->calculateWeightPercentage($data);

        $category   = ProjectCategory::all();
        $subcategory= SubCategory::orderBy('name')->get()->unique('name');
        $department = Role::where('affilaited', auth()->user()->role_id)->where('id', '!=', '6')->get();
        $years      = Projects::distinct()->selectRaw('YEAR(hpc_approval_date) as year')->pluck('year');
        $districts  = Districts::orderBy('name', 'asc')->get();

        return view('admin.project.index', compact('data', 'category', 'districts', 'years', 'department', 'subcategory'));
    }
}
