<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Models\Admin\Page;
use App\Models\Admin\Navigation;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pageData()
    {
        $page = Page::all();

        return DataTables::of($page)
            ->addColumn('action', function ($page) {
                return '
                <div class="dropdown dropstart">
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
                            <a class="dropdown-item d-flex align-items-center gap-3" href="' . route('page.edit', $page->id) . '">
                                <i class="fs-4 ti ti-edit"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)" onclick="DeleteUser(\'' . route('page.destroy', $page->id) . '\')">
                                <i class="fs-4 ti ti-trash"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>';
            })
            ->addColumn('parent_menu', function ($page) {
                return $page->navigation->eng_title;
            })
            ->editColumn('show', function ($page) {
                return $page->show ? 'Yes' : 'No';
            })
            ->editColumn('created_at', function ($page) {
                return $page->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action','parent_menu'])
            ->make(true);
    }


    public function index()
    {
        return view('frontend.admin.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $navigation = Navigation::orderBy('eng_title','asc')->get(['id','eng_title']);
        return view('frontend.admin.page.create',compact('navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $navigation = Navigation::with('children')->orderBy('eng_title','asc')->get(['id','eng_title']);
        $page = Page::findorfail($id);
        return view('frontend.admin.page.edit',compact('page','navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'eng_title'             => 'required|string|max:255',
            'hin_title'             => 'required|string|max:255',
            'slug'                  => 'nullable|string|max:255', // For slug input, nullable is used
            'order'                 => 'nullable|numeric', // If order is not always required, make it nullable
            'show'                  => 'required|numeric', // Show (Yes/No) radio buttons should be numeric (1 or 0)
            'banner_eng_title'      => 'required|string|max:255',
            'banner_hin_title'      => 'required|string|max:255',
            'banner_eng_description'=> 'required|string', // Banner description fields are required
            'banner_hin_description'=> 'required|string',
            'page_eng_title'        => 'required|string|max:255',
            'page_hin_title'        => 'required|string|max:255',
            'eng_content'           => 'required|string', // Content fields are required
            'hin_content'           => 'required|string',
            'image'                 => 'required', // Assuming featured image is uploaded
            'banner'                => 'required', // Assuming banner image is uploaded
        ]);

        try {
            $page = Page::find($id);
            $page->update($request->all());
        } catch (\Exception $e) {
            // return response()->json(['message' => 'Failed to update Page !'], 500);
            return response()->json(['message' => $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upload(Request $request)
    {
        // Validate the page image and banner image fields
        $request->validate([
            'page_image' => 'nullable|image|mimes:jpg,png,webp,jpeg|max:2048', // Validate page image
            'banner_images' => 'nullable|image|mimes:jpg,png,webp,jpeg|max:2048', // Validate single banner image
        ]);

        $filePaths = [];

        // Upload page image
        if ($request->hasFile('page_image'))
        {
            $pageImage               = $request->file('page_image');
            $pageImagePath           = $pageImage->store('uploads', 'public');
            $filePaths['page_image'] = $pageImagePath; // Save page image path
        }

        // Upload single banner image
        if ($request->hasFile('banner_images'))
        {
            $bannerImage = $request->file('banner_images');
            $bannerImagePath = $bannerImage->store('uploads', 'public');
            $filePaths['banner_images'] = $bannerImagePath; // Save banner image path
        }

        // Return response with file paths
        return response()->json(['success' => true, 'filePaths' => $filePaths], 200);
    }


    /**
     *
     */
    public function delete(Request $request)
    {
        $filePath = $request->input('filePath');

        if ($filePath && Storage::disk('public')->exists($filePath))
        {
            Storage::disk('public')->delete($filePath);

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }
}
