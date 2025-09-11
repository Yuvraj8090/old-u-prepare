<?php

namespace App\Http\Controllers\Frontend\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\Admin\Navigation;
use App\Http\Controllers\Controller;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function navigationData()
    {
        $navigation = Navigation::all();

        return DataTables::of($navigation)
            ->addColumn('action', function ($item) {
                return '<div class="dropdown dropstart">
                    <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                         <i class="ti ti-dots-vertical fs-6"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bg-primary-header-modal">
                                <i class="ti ti-eye fs-4"></i> View
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="' . route('navigation.edit', $item->id) . '">
                                <i class="fs-4 ti ti-edit"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)" onclick="DeleteUser(\'' . route('navigation.destroy', $item->id) . '\')">
                                <i class="fs-4 ti ti-trash"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>';
            })
            ->editColumn('show', function ($item) {
                return $item->show ? 'Yes' : 'No';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function index()
    {
        $navigations = Navigation::all();

        return view('frontend.admin.navigation.index',compact('navigations'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'eng_title' => 'required|string|max:255',
            'hin_title' => 'required|string|max:255',
            'link'      => 'nullable|string|max:255',
            'order'     => 'numeric',
            'show'      =>'required|numeric'
        ]);

        try
        {
            Navigation::create($request->all());

            return response()->json(['message' => 'Menu Add successfully!'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => $e], 500);
        }
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
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
