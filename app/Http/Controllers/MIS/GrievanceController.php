<?php

namespace App\Http\Controllers\MIS;

use App\Helpers\Assistant;

use App\Models\Role;
use App\Models\User;
use App\Models\Grievance;
use App\Models\Grievance\Log;
use App\Models\Grievance\Action;
use App\Models\Geography\District;

use Illuminate\Support\Carbon;
use App\Helpers\DummyData;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class GrievanceController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        $years      = Grievance::select(DB::raw('YEAR(created_at) as year'))->distinct()->get();
        $depts      = Role::select(DB::raw('department'))->distinct()->get();
        $months     = DummyData::months();

        $typology   = DummyData::typology();
        $districts  = District::orderBy('name')->get();

        $data       = Assistant::filterGrievances($request);

        $grieves    = $data->grieves;
        $pending    = $data->pending;
        $rejected    = $data->rejected;
        $resolved   = $data->resolved;
        $grievances = $data->grievances;

        return view('mis.grievance.index', compact('depts', 'typology', 'districts', 'years', 'months', 'grieves', 'grievances', 'pending', 'resolved','rejected'));
    }
  




public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,resolved,rejected',
        'remark' => 'required|string'
    ]);

    $grievance = Grievance::findOrFail($id);

    // Update status
    $grievance->status = $request->status;

    if ($request->status === 'resolved') {
        $grievance->resolved_at = Carbon::now();
    }

    $grievance->save();

    // Create log for status update
    $logTitle = 'Grievance status updated to "' . ucfirst($request->status) . '" by ';
    $logTitle .= auth()->user()->hasPermission('grm_nodal') ? 'Nodal Officer: ' : '';
    $logTitle .= auth()->user()->name;

    Log::create([
        'type'         => 'status_update',
        'title'        => $logTitle,
        'remark'       => $request->remark, // store as-is
        'user_id'      => auth()->id(),
        'grievance_id' => $grievance->id,
    ]);

    return response()->json([
        'message' => 'Grievance status updated successfully.',
        'data' => $grievance,
    ]);
}

public function addRemarkLog(Request $request, $id)
{
    $request->validate([
        'remark' => 'required|string',
    ]);

    $grievance = Grievance::findOrFail($id);

    $logTitle = 'Remark added by ';
    $logTitle .= auth()->user()->hasPermission('grm_nodal') ? 'Nodal Officer: ' : '';
    $logTitle .= auth()->user()->name;

    Log::create([
        'type'         => 'remark',
        'title'        => $logTitle,
        'remark'       => $request->remark, // store as-is
        'user_id'      => auth()->id(),
        'grievance_id' => $grievance->id,
    ]);

    return response()->json([
        'message' => 'Remark added to grievance log.',
    ]);
}




    /**
     *
     */
    public function forward(Request $request)
    {
        $forward = $request->filled('forward');
        $return  = ['ok'=> 0, 'msg'=> 'Invalid request is detected!'];

        $v_rules = [
            'remark'   => 'required|string',
            'grievance'=> 'required|string',
        ];

        $v_msgs = [
            'remark.required'   => 'Kindly provide a remark.',
            'grievance.string'  => 'An invalid request is detected.',
            'grievance.required'=> 'An invalid request is detected.',
        ];

        if($forward)
        {
            $v_rules['user']         = 'required|integer';
            $v_msgs['user.required'] = 'Kindly select a user first!';
        }

        $validator = Validator::make($request->all(), $v_rules, $v_msgs);

        if(!$validator->fails())
        {
            $user      = $forward ? User::find($request->user) : NULL;
            $grievance = Grievance::where('ref_id', $request->grievance)->first();

            if(!$forward && $grievance)
            {
                $grievance->user_id = 0;

                if($grievance->save())
                {
                    $return = $this->createLog($grievance, $user, $forward, [
                        'title'    => 'Grievance is Reverted to GRM Nodal Officer.',
                        'remark'   => $request->remark,
                        'user_id'  => auth()->id(),
                        'is_revert'=> 1,
                    ]);
                }
            }
            else if($user && $grievance)
            {
                if($user->id !== $grievance->user_id)
                {
                    $grievance->user_id = $user->id;

                    if($grievance->save())
                    {
                        $return = $this->createLog($grievance, $user, $forward, [
                            'title'     => 'Grievance Forwarded to ' . $user->name,
                            'user'      => auth()->id(),
                            'remark'    => $request->remark,
                            'forward_to'=> $user->id,
                        ]);
                    }
                    else
                    {
                        $return['msg'] = 'Failed to complete your request this time. Please try again.';
                    }
                }
                else
                {
                    $return['msg'] = 'The selected user is already an incharge for this grievance.';
                }
            }
            else
            {
                $return['msg'] = 'Failed to process your request this time. This might be because of an invalid request.';
            }
        }
        else
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        return $return;
    }


    /**
     *
     */
    public function details($ref_id)
    {
        $users     = User::where('status', 1)->orderBy('name')->get();
        $grievance = Grievance::with('logs')->where('ref_id', $ref_id)->first();

        if($grievance)
        {
            if(auth()->id() == $grievance->user_id || auth()->user()->hasPermission('grm_nodal') || auth()->user()->hasRole('Admin'))
            {
                $grievance->load(['logs', 'action', 'block', 'district', 'category', 'subcategory']);

                return view('mis.grievance.details', compact('users', 'grievance'));
            }
            else
            {
                abort(403);
            }
        }

        abort(404);
    }


    /**
     *
     */
    public function actionReport(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'Invalid request is detected!'];

        $validator = Validator::make($request->all(), [
            'grievance'=> ['required', 'string'],
            'action'   => ['required', 'numeric'],
            'comment'  => ['required', 'string'],
            'document' => ['required', File::types(['jpg', 'jpeg', 'pdf'])->max('5mb')],
        ], [
            'action.numeric'    => 'Data tampering is detected',
            'action.required'   => 'An invalid request is detected',
            'comment.required'  => 'The comment is required for action taken.',
            'document.required' => 'Kindly provide the document for Action Taken.',
            'grievance.required'=> 'An invalid request is detected',
        ]);

        if(!$validator->fails())
        {
            $grievance = Grievance::where('ref_id', $request->grievance)->first();

            if($grievance)
            {
                $act_type          = $request->action ? 'final' : 'preliminary';
                $request['action'] = intval($request->action);

                $action = Action::where('grievance_id', $grievance->id)->first() ?: new Action;

                $action->grievance_id = $grievance->id;

                $file_path = NULL;

                if($request->hasFile('document'))
                {
                    // Upload File
                    $file      = $request->file('document');
                    $file_name = 'grievance_' . $grievance->ref_id . '_' . $act_type .  '_action_doc_' . time() . '.' . $file->getClientOriginalExtension();
                    $file_path = 'uploads/' . date('Y') . '/' . date('m') . '/';

                    if($file->move($file_path, $file_name))
                    {
                        $file_path = $file_path . $file_name;
                    }
                }

                if(!$request['action'])
                {
                    $action->pact     = $request->comment;
                    $action->pact_doc = $file_path;
                }
                else
                {
                    $action->fact     = $request->comment;
                    $action->fact_doc = $file_path;
                }

                if($action->save())
                {
                    $log_title  = (!$request['action'] ? 'Preliminary' : 'Final');
                    $log_title .= ' action taken report is submitted by ';
                    $log_title .= (auth()->user()->hasPermission('grm_nodal') ? 'Nodal Officer: ' : '');
                    $log_title .= auth()->user()->name;

                    // Create a Log Entry
                    Log::create([
                        'type'        => !$request['action'] ? 'pact' : 'fact',
                        'title'       => $log_title,
                        'remark'      => $request->comment,
                        'user_id'     => auth()->id(),
                        'grievance_id'=> $grievance->id,
                    ]);

                    $return['ok']  = 1;
                    $return['url'] = route('mis.grievance.detail', $grievance->ref_id);
                    $return['msg'] = ucfirst($act_type) . ' Action Report is stored for Grievance Ref. No.: ' . $grievance->ref_id . ' Successfully!';
                }
                else
                {
                    $return['msg'] = 'Failed to store action report. Please try again!';
                }
            }
        }
        else
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        return $return;
    }


    /**
     *
     */
    private function createLog($grievance, $user, $forward, $log_data)
    {
        $return = [];

        $log_data['grievance_id'] = $grievance->id;

        Log::create($log_data);

        $return['ok']  = 1;
        $return['url'] = route('mis.grievance.detail', $grievance->ref_id);
        $return['msg'] = 'Grievance successfully ' . ($forward ? ('forwarded to ' . $user->name . '.') : 'reverted to GRM Nodal Officer.');

        return $return;
    }
}
