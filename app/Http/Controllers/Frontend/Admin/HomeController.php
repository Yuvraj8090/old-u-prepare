<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Models\Feedback;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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



    public function administration(Request $request)
    {
        if($request->isMethod('get'))
        {
            $data = DB::table('administration')->first();

            return view('frontend.admin.administration',compact('data'));
        }
        else
        {
            dd($request->all());
            $request->validate([
                'cm_name'                =>'required',
                'governor_name'          =>'required',
                'chief_secretary_name'   =>'required',
                'secretary_name'         =>'required',
                'cm_profile'             => 'required',
                'governor_profile'       => 'required',
                'chief_secretary_profile'=> 'required',
                'secretary_profile'      => 'required',
            ]);

            try {
                $data = $request->all();
                DB::table('administration')->update($data);

                return response()->json(['message' => 'Administration Update successfully!'], 200);
            }
            catch(\Exception $e)
            {
                return response()->json(['message' => 'Administration  Updation failed!'], 400);
            }
        }
    }


    public function upload(Request $request)
    {
        $request->validate([
            'cm_profile'             => 'nullable|image|mimes:jpg,png,webp,jpeg|max:2048',
            'governor_profile'       => 'nullable|image|mimes:jpg,png,webp,jpeg|max:2048',
            'chief_secretary_profile'=> 'nullable|image|mimes:jpg,png,webp,jpeg|max:2048',
            'secretary_profile'      => 'nullable|image|mimes:jpg,png,webp,jpeg|max:2048',
        ]);

        $filePaths = [];

        if ($request->hasFile('cm_profile'))
        {
            $cmImage                 = $request->file('cm_profile');
            $cmImagePath             = $cmImage->store('uploads/administration', 'public');
            $filePaths['cm_profile'] = $cmImagePath;
        }

        if ($request->hasFile('governor_profile'))
        {
            $governorImage                 = $request->file('governor_profile');
            $governorImagePath             = $governorImage->store('uploads/administration', 'public');
            $filePaths['governor_profile'] = $governorImagePath;
        }

        if ($request->hasFile('chief_secretary_profile'))
        {
            $chiefSecretaryImage                  = $request->file('chief_secretary_profile');
            $chiefSecretaryImagePath              = $chiefSecretaryImage->store('uploads/administration', 'public');
            $filePaths['chief_secretary_profile'] = $chiefSecretaryImagePath;
        }

        if ($request->hasFile('secretary_profile'))
        {
            $secretaryImage                 = $request->file('secretary_profile');
            $secretaryImagePath             = $secretaryImage->store('uploads/administration', 'public');
            $filePaths['secretary_profile'] = $secretaryImagePath;
        }

        return response()->json(['success' => true, 'filePaths' => $filePaths], 200);
    }


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


    public function feedback(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'   => 'required|string|max:200',
            'email'  => 'required|email',
            'type'   => 'required',
            'subject'=> 'required|string|max:200',
            'message'=> 'required|string|max:500',
        ]);

        try
        {
            Feedback::create($request->all());

            return response()->json(['success' => true, 'message' => 'Feedback sent successfully'],200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message' => $e], 500);
            // return response()->json(['message' => 'Failed to Submit Feedback !'], 500);
        }
    }


    public function feedbackGet()
    {
        return view('frontend.admin.feedback');
    }


    public function feedbackDate()
    {
        $feedbacks = Feedback::all();

        return DataTables::of($feedbacks)
            ->editColumn('created_at', function ($annoucement) {
                return $annoucement->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('message', function ($feedbacks) {
                return '<div data-bs-toggle="tooltip" data-bs-original-title="' . $feedbacks->message . '" >' . substr($feedbacks->message, 0, 30) . ' </div>';
            })
            ->rawColumns(['message','created_at'])
            ->make(true);
    }
}
