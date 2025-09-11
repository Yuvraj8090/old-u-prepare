<?php

namespace App\Http\Controllers\Grievance;

use App\Helpers\DummyData;

use App\Mail\EMail;
use App\Models\Projects;
use App\Models\Grievance;
use App\Models\MIS\Department;
use App\Models\Grievance\Log;
use App\Models\Geography\Block;
use App\Models\Geography\District;
use App\Models\Grievance\ComplaintDetail;
use App\Models\Grievance\ComplaintNature;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class GrievanceController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $pending    = Grievance::where('status', 'pending')->get()->count();
        $resolved   = Grievance::where('status', 'resolved')->get()->count();
        $grievances = Grievance::all()->count();

        return view('grievance.welcome', compact('grievances', 'pending', 'resolved'));
    }


    /**
     *
     */
    public function grievances(Request $request)
    {
        $grieves  = Grievance::all()->count();
        $pending  = Grievance::where('status', 'pending')->get()->count();
        $resolved = Grievance::where('status', 'resolved')->get()->count();

        $years      = Grievance::select(DB::raw('YEAR(created_at) as year'))->distinct()->get();
        $depts      = Role::select(DB::raw('department'))->distinct()->get();
        $months     = DummyData::months();

        $typology   = DummyData::typology();
        $districts  = District::orderBy('name')->get();

        $grievances = $this->filterGrievances($request);

        return view('grievance.grievances', compact('depts', 'typology', 'districts', 'grievances', 'years', 'months', 'grieves', 'grievances', 'pending', 'resolved'));
    }


    /**
     *
     */
    public function record()
    {
        $typology   = DummyData::typology();
        $districts  = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('grievance.record', compact('typology', 'districts', 'categories'));
    }


    /**
     *
     */
    public function manage(Request $request)
    {
        $years      = Grievance::select(DB::raw('YEAR(created_at) as year'))->distinct()->get();
        $depts      = Role::select(DB::raw('department'))->distinct()->get();
        $months     = DummyData::months();

        $typology   = DummyData::typology();
        $districts  = District::orderBy('name')->get();

        $grievances = Assistant::filterGrievances($request);

        return view('grievance.manage', compact('depts', 'typology', 'districts', 'grievances', 'years', 'months'));
    }


    /**
     *
     */
    public function details($ref_id)
    {
        $grievance = Grievance::where('ref_id', $ref_id)->first();

        if($grievance)
        {
            $grievance->load(['block', 'district', 'category', 'subcategory']);

            return view('grievance.details', compact('grievance'));
        }
    }


    /**
     *
     */
    public function assignDept(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'Testing Error'];

        if($request->filled('dept_id'))
        {

        }

        return $return;
    }


    /**
     *
     */
    public function save(Request $request)
    {
        $return = [];

        $request->validate([
            'full_name'  => ['nullable', 'string'],
            'address'    => ['nullable', 'string'],
            'email'      => ['nullable', 'email:filter'],
            'phone'      => ['required', 'digits:10'],
            'district'   => ['required'],
            'block'      => ['required'],
            'village'    => ['nullable', 'string'],
            'typology'   => ['required'],
            'typo_other' => [Rule::when($request->typology == 'other', 'required|string')],
            'category'   => [Rule::requiredIf($request->typology !== 'other')],
            'subcategory'=> [Rule::requiredIf($request->typology !== 'other')],
            'scat_other' => [Rule::when($request->subcategory == 'other', 'required|string')],
            'description'=> ['nullable', 'string'],
            'file'       => ['nullable', File::types(['jpg', 'jpeg', 'pdf', 'mp4'])],
        ], [
            'full_name.string'       => 'Full name should be a string value.',
            'address.string'         => 'Address should be a string value.',
            'village.string'         => 'Village value should be a string.',
            'phone.required'         => 'The mobile number is required.',
            'category.required_if'   => 'The category field is required.',
            'subcategory.required_if'=> 'The sub-category field is required.',
            'desc.required'          => 'The description field value should be a string.',
            'typo_other.required_if' => 'Please specify typology other value.',
            'scat_other.required_if' => 'Please specify subcategory other value.',
            'scat_other.string'      => 'Please specify other value for sub-category.',
        ]);

        $district = District::where('slug', $request->district)->first();

        if($district)
        {
            $block = Block::where('slug', $request->block)->first();

            if($block)
            {
                $category = Category::where('slug', $request->category)->first();

                if($request->typology == 'other' || $category)
                {
                    $subCat = SubCategory::where('slug', $request->subcategory)->first();

                    if($request->subcategory == 'other' || $subCat)
                    {
                        $file_path = NULL;

                        if($request->hasFile('file'))
                        {
                            $file      = $request->file('file');
                            $file_name = 'grievance_doc_' . time() . '.' . $file->getClientOriginalExtension();
                            $file_path = 'uploads/' . date('Y') . '/' . date('m') . '/';
                            // $doc_path  = $file->storeAs($file_path, $file_name);

                            if($file->move($file_path, $file_name))
                            {
                                $file_path = $file_path . $file_name;
                            }
                        }

                        $lgid = Grievance::latest()->first();
                        $lgid = $lgid ? ($lgid->id + 1) : 1;

                        $lgid = 'GR' . str_pad($lgid, 5, '0', STR_PAD_LEFT);

                        $grievance = Grievance::create([
                            'ref_id'        => $lgid,
                            'address'       => $request->filled('address') ? $request->address : NULL,
                            'email'         => $request->filled('email') ? $request->email : NULL,
                            'phone'         => $request->phone,
                            'village'       => $request->filled('village') ? $request->village : NULL,
                            'typology'      => $request->typology,
                            'block_id'      => $block->id,
                            'document'      => $file_path,
                            'consent'       => $request->consent == 'yes' ? 'Yes' : 'No',
                            'on_behalf'     => $request->behalf == 'yes' ? 'Yes' : 'No',
                            'registrant'    => $request->filled('full_name') ? $request->full_name : NULL,
                            'district_id'   => $district->id,
                            'category_id'   => $category ? $category->id : 0,
                            'subcategory_id'=> $subCat ? $subCat->id : 0,
                            'scat_other'    => $request->filled('scat_other') ? $request->scat_other : NULL,
                            'description'   => $request->filled('description') ? $request->description : NULL,
                        ]);

                        $message = '';

                        $message .= 'Grievance has been recorded.<br />The Grievance ID is ' . $grievance->ref_id . '.';

                        // Send SMS/EMAIL to User if (Phone/Email) Provided
                        if($grievance->email)
                        {
                            $subject  = 'Grievance Registration Confirmation - ' . config('app.name');
                            $details  = [
                                'user_name'=> $grievance->registrant,
                            ];
                            $template = 'mail.grievance.registered';

                            try {
                                Mail::to($grievance->email)->send(new EMail($details, $subject, $template));
                            }
                            catch (Exception $e)
                            {
                                dd($e);
                            }
                        }


                        return redirect()->back()->withErrors(['success'=> $message]);
                    }
                    else
                    {
                        $return['subcategory'] = 'Invalid sub-category value is detected.';
                    }
                }
                else
                {
                    $return['category'] = 'Invalid category value is detected.';
                }
            }
            else
            {
                $return['block'] = 'Invalid block value is detected.';
            }
        }
        else
        {
            $return['district'] = 'Invalid district value is detected.';
        }

        return redirect()->back()->withInput()->withErrors($return);
    }
}
