<?php

namespace App\Http\Controllers\MIS;

use App\Helpers\Assistant;
use Illuminate\Http\Request;

use App\Models\MIS\ActionTaskReport;
use App\Models\MIS\ATR\Status;
use App\Models\MIS\ATR\TaskType;
use App\Models\MIS\ATR\Component;
use App\Models\MIS\ATR\Organization;
use App\Models\MIS\ATR\SubComponent;

use App\Http\Controllers\Controller;

class ATRController extends Controller
{
    /**
     *
     */
    public function entryForm()
    {
        $orgs     = Organization::all();
        $comps    = Component::all();
        $tasks    = TaskType::all();
        $months   = Assistant::months();
        $statuses = Status::all();

        return view('mis.atr.create', compact('orgs', 'comps', 'tasks', 'months', 'statuses'));
    }


    /**
     *
     */
    public function viewReport()
    {
        $atrs = ActionTaskReport::all();

        return view('mis.atr.index', compact('atrs'));
    }


    /**
     *
     */
    public function editEntry($id)
    {
        $atr      = ActionTaskReport::findOrFail($id);
        $orgs     = Organization::all();
        $comps    = Component::all();
        $tasks    = TaskType::all();
        $months   = Assistant::months();
        $statuses = Status::all();

        return view('mis.atr.edit', compact('atr', 'orgs', 'comps', 'tasks', 'months', 'statuses'));
    }


    /**
     *
     */
    public function getSubComponents(Request $request)
    {
        $return = ['ok'=> 0, 'msg'=> 'An Invalid request is detected!'];

        if($request->filled('slug'))
        {
            $component = Component::with('sub_components')->where('slug', $request->slug)->first();

            if($component)
            {
                $data         = [];
                $scount       = $component->sub_components()->count();

                $return['ok']  = 1;
                $return['msg'] = $scount ? $scount . ' Sub Components Fetched Successfully' : 'No Sub Components are Available for the selected Component!';

                if($scount)
                {
                    foreach($component->sub_components as $scomp)
                    {
                        $data[] = [
                            'name'=> $scomp->name,
                            'slug'=> $scomp->slug
                        ];
                    }
                }

                $return['data'] = $data;
            }
        }

        return $return;
    }


    /**
     *
     */
    public function saveEntry(Request $request)
    {
        $return       = ['ok'=> 0, 'msg'=> 'An invalid request is detected!'];
        $update       = $request->filled('atr_id');

        $task         = TaskType::where('slug', $request->task)->first();
        $status       = Status::where('slug', $request->status)->first();
        $component    = Component::with('sub_components')->where('slug', $request->comp)->first();
        $organization = Organization::where('slug', $request->org)->first();
        $subComponent = NULL;

        if($request->filled('s_comp'))
        {
            $subComponent = SubComponent::where('slug', $request->s_comp)->first();
        }

        if($task && $status && $component && $organization)
        {
            $atr   = $update ? ActionTaskReport::find($request->atr_id) : new ActionTaskReport;
            $s_csc = $component->sub_components()->count();

            if($atr)
            {
                if(!$s_csc || ($s_csc && $subComponent) )
                {
                    $atr->year             = $request->year;
                    $atr->month            = ($request->month > 0 && $request->month < 13) ? $request->month : date('n');
                    $atr->remark           = $request->remark;
                    $atr->user_id          = auth()->id();
                    $atr->next_step        = $request->next;
                    $atr->status_id        = $status->id;
                    $atr->action_item      = $request->item;
                    $atr->date_actual      = $this->chnageDate($request->date_act);
                    $atr->date_revise      = $this->chnageDate($request->date_rev);
                    $atr->date_commit      = $this->chnageDate($request->date_com);
                    $atr->component_id     = $component->id;
                    $atr->task_type_id     = $task->id;
                    $atr->organization_id  = $organization->id;
                    $atr->sub_component_id = $subComponent ? $subComponent->id : 0;

                    if($atr->save())
                    {
                        $return['ok']  = 1;
                        $return['msg'] = 'Action Task Report ' . ($update ? 'Updated' : 'Saved') . ' Successfully!';
                        $return['url'] = $update ? route('atr.entry.edit', $atr->id) : route('atr.entry.form');
                    }
                    else
                    {
                        $return['msg'] = 'Failed to save the action task report record. Please try again!';
                    }
                }
                else
                {
                    $return['msg'] = 'Kindly select a sub-component for the selected component';
                }
            }
        }

        return $return;
    }
}
