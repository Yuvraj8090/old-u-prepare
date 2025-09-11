<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Models\Admin\Announcement;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function announcementData()
    {
        $annoucement = Announcement::all();

        return DataTables::of($annoucement)
            ->addColumn('action', function ($annoucement) {
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
                            <a class="dropdown-item d-flex align-items-center gap-3" href="' . route('announcement.edit', $annoucement->id) . '">
                                <i class="fs-4 ti ti-edit"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)" onclick="DeleteUser(\'' . route('announcement.destroy', $annoucement->id) . '\')">
                                <i class="fs-4 ti ti-trash"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>';
            })
            ->editColumn('show', function ($annoucement) {
                return $annoucement->show ? 'Yes' : 'No';
            })
            ->editColumn('created_at', function ($annoucement) {
                return $annoucement->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     *
     */
    public function index()
    {
        return view('frontend.admin.announcement.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend.admin.announcement.create');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'eng_title'  => 'required|string|max:255',
            'hin_title'  => 'required|string|max:255',
            'eng_content'=> 'required|string',
            'hin_content'=> 'required|string',
            'show'       => 'required|numeric',
        ]);

        try {
            $slug            = Str::slug($request->eng_title);
            $request['slug'] = $slug;

            Announcement::create($request->all());

            return response()->json(['message' => 'Successfully Add Annoucement !'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Failed to Add Annoucement !'], 500);
            // return response()->json(['message' => $e], 500);
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
        $announcement = Announcement::find($id);

        return view('frontend.admin.announcement.edit', compact('announcement'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'eng_title'  => 'required|string|max:255',
            'hin_title'  => 'required|string|max:255',
            'eng_content'=> 'required|string',
            'hin_content'=> 'required|string',
            'show'       => 'required|numeric',
        ]);

        try {
            $announcement    = Announcement::find($id);
            $slug            = Str::slug($request->eng_title);
            $request['slug'] = $slug;
            $announcement->update($request->all());

            return response()->json(['message' => 'Successfully Update Annoucement !'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Failed to Update Annoucement !'], 500);
            // return response()->json(['message' => $e], 500);
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
