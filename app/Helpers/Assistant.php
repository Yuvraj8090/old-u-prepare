<?php

namespace App\Helpers;

use App\Models\Grievance;
use App\Models\MIS\Department;

use App\Models\Grievance\Log;
use App\Models\Geography\District;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * Assisting Function for Portal
 *
 * @author Robin Tomar <robintomr@icloud.com>
 */
class Assistant
{
    /**
     *
     */
    public static function filterGrievances(Request $request)
{
    $department = explode('-', auth()->user()->role->department)[0];
    $department = Department::where('name', $department)->first();

    $grievances = Grievance::query();

    if(auth()->user()->hasRole('Admin') || auth()->user()->hasPermission('grm_nodal'))
    {
        $grievances->where('skip', 0);
    }
    elseif(auth()->user()->hasPermission('resolve_grm'))
    {
        // Filter grievances assigned to this specific user
        $grievances->where('user_id', auth()->id());
    }
        elseif(!auth()->user()->hasPermission('grm_nodal') && $department)
        {
            $grievances->where('dept_id', $department->id);
        }
        else
        {
            $grievances->where('skip', 1);
        }
        $grieves  = clone $grievances;
        $grieves  = $grieves->get()->count();
        $pending  = clone $grievances;
        $pending  = $pending->where('status', 'pending')->get()->count();
         $rejected  = clone $grievances;
        $rejected  = $rejected->where('status', 'rejected')->get()->count();
        $resolved = clone $grievances;
        $resolved = $resolved->where('status', 'resolved')->get()->count();


        if($request->filled('district'))
        {
            $district = District::where('slug', $request->district)->first();

            if($district)
            {
                $grievances->where('district_id', $district->id);
            }
        }

        if($request->filled('typology'))
        {
            $grievances->where('typology', $request->typology);
        }

        if($request->filled('status'))
        {
            $grievances->where('status', $request->status);
        }

        if($request->filled('year'))
        {
            $grievances->whereYear('created_at', $request->year);
        }

        if($request->filled('month'))
        {
            $grievances->whereMonth('created_at', $request->month);
        }

        return (object) [
            'grieves'   => $grieves,
            'pending'   => $pending,
            'rejected' => $rejected,
            'resolved'  => $resolved,
            'grievances'=> $grievances->latest()->get()
        ];
    }


    /**
     *
     */
    public static function slugify($string)
    {
        $slugged = "{$string}-1";

        if(isset($string[-1]) && is_numeric($string[-1])) {
            $slugged = preg_replace_callback('/(\d+)$/', function($matches) {
                return $matches[1] + 1;
            }, $string);
        }

        return $slugged;
    }


    /**
     *
     */
    public static function upload($file, $folder, $fun, $small = 0)
    {
        $return = (object) ['done'=> 0, 'msg'=> NULL, 'file'=> NULL];

        $extn = '.' . $file->extension();
        $name = $fun ?: ( $fun . '_' . time() . '_' . $folder );
        $path = 'assets/uploads/' . date('Y') . '/' . date('m') . '/' . $folder;
        $ptos = public_path($path) . '/';

        // If keep file original name
        if($fun == 'get')
        {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name = str_replace(' ', '-', strtolower($name));
        }

        // Check if Directory to Save Image Exists otherwise Create that
        File::isDirectory($ptos) OR File::makeDirectory($ptos, 0755, true);

        while(File::exists($ptos . $name . $extn))
        {
            $name = Self::slugify($name);
        }

        if($file->move($ptos, $name . $extn))
        {
            $return->done = 1;
            $return->name = $name . $extn;
            $return->path = $path . '/';
            $return->file = $path . '/' . $name . $extn;
        }

        return $return;
    }


    /**
     *
     */
    public static function grievanceLogEntry(Grievance $grievance, $days)
    {
        foreach([7, 10, 14] as $day)
        {
            if($days >= $day)
            {
                $log_data = SELF::getGDLEM($days);

                if($log_data->msg && $log_data->type)
                {
                    $exists = Log::where('grievance_id', $grievance->id)->where('type', $log_data->type)->first();

                    if(!$exists)
                    {
                        Log::create([
                            'title'       => $log_data->msg,
                            'type'        => $log_data->type,
                            'user_id'     => auth()->id(),
                            'grievance_id'=> $grievance->id,
                        ]);
                    }

                }
            }
        }
    }


    /**
     *
     */
    private static function getGDLEM($days)
    {
        $msg  = NULL;
        $type = NULL;

        switch($days)
        {
            case $days > 14:
                $msg  = 'Grievance is pending at Nodal Officer Level.';
                $type = '14dl';
                break;
            case $days > 10:
                $msg  = 'Grievance status is pending at Action (EE) Level.';
                $type = '10dl';
                break;
            case $days > 7:
                $msg  = 'Grievance is pending at AE Level.';
                $type = '7dl';
                break;
            default:
                $days = 0;
                break;
        }

        return (object) [
            'msg' => $msg,
            'type'=> $type
        ];
    }



    /**
     *
     */
    public static function months($month = 0)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        if($month)
        {
            return $months[$month - 1];
        }

        return $months;
    }


    /**
     *
     */
    public static function getProjectStatus($stage, $all = 0)
    {
        $statuses = ['Pending for Procurement', 'Pending for Contract', 'Pending for Milestone', 'On Going', 'Completed', 'Cancelled'];

        if($all)
        {
            return $statuses;
        }

        $stage    = isset($statuses[$stage]) ? $statuses[$stage] : 'N/A';

        return $stage;
    }


    /**
     *
     */
    public static function procureTypes($type = 0)
    {
        $types = ['Itemwise', 'EPC', 'Others'];

        if($type && $type > 0 && $type <= 3)
        {
            return $types[$type - 1];
        }

        return $types;
    }



    /**
     * 
     */
    public static function getPWDashStats($data)
    {
        $return = (object) [
            'loai'  => 0,       // LOA Issued
            'signed'=> 0,       // Contract Signed
            'startd'=> 0,       // Contract Start Date Given
            'loatbi'=> 0,       // LOA to be Issued
            'signpn'=> 0,       // Contract Signing Pending
            'tbereb'=> 0,       // To be Rebidded
        ];

        foreach($data as $item)
        {
            // If Contract Signing Date is Filled
            if($item->contract->contract_signing_date)
            {
                // If Contractor is Added
                if($item->contractor_address)
                {
                    $return->loai    = $return->loai + 1;
                    $return->signing = $return->loai + 1;
                }
                else
                {
                    $return->loatbi = $return->loatbi + 1;
                }
            }

            // if Start Date is Filled
            if($item->contract->commencement_date)
            {
                $return->startd = $return->startd + 1;
            }
            else
            {
                $return->signpn = $return->signpn + 1;
            }

            // If Project is Cancelled
            if($item->stage = 5)
            {
                $return->tbereb = $return->tbereb + 1;
            }
        }

        return $return;
    }
}
