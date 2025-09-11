<?php

namespace App\Http\Controllers\MIS;

use App\Models\Media;
use App\Models\Projects;
use App\Models\MilestoneValueUpdated;
use App\Models\MIS\SafeGuardRule;
use App\Models\MIS\SafeguardEntry;
use App\Models\MIS\ReportProgressType;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProjectTrackingController extends Controller
{
    /**
     *
     */
    public function index() {}

    public function getSafeguardReport(Request $request)
    {
        $return = ['ok' => 0, 'msg' => 'An invalid request detected!'];

        // Only require project_id
        if (!$request->filled(['project_id'])) {
            return response()->json($return);
        }

        $project = Projects::find($request->project_id);
        $period = $request->filled('period') ? $request->period : 'monthly';
        $year = $request->filled('year') ? $request->year : date('Y');

        if (!$project) {
            $return['msg'] = 'Project not found!';
            return response()->json($return);
        }

        // Get all safeguard types (environmental and social)
        $types = ['environment', 'social'];
        $allReports = [];

        foreach ($types as $type) {
            // Get all steps for this type
            $steps = ReportProgressType::whereHas('safeguard_rules', function ($q) use ($type) {
                $q->where('safeguard_type', $type);
            })
                ->orderBy('id')
                ->get();

            foreach ($steps as $step) {
                $allRules = $step->safeguard_rules()->where('safeguard_type', $type)->orderBy('indx')->get();

                if ($allRules->isEmpty()) {
                    continue;
                }

                $entries = SafeguardEntry::where('project_id', $project->id)
    ->whereIn('rule_id', $allRules->pluck('id'))
    ->whereYear('entry_date', $year)
    ->get();

                $dateRanges = $this->getDateRangesForPeriod($period, $year);
                $html = $this->buildDataTableHTML($allRules, $entries, $dateRanges, $period, $year, $type, $step->slug);

                $allReports[] = [
                    'type' => $type,
                    'step' => $step->slug,
                    'html' => $html,
                    'title' => ucfirst($type) . ' Safeguards - ' . $step->name . ' (' . ucfirst($period) . ' ' . $year . ')',
                ];
            }
        }

        if (empty($allReports)) {
            $return['msg'] = 'No safeguard data found for this project!';
            return response()->json($return);
        }

        $return['ok'] = 1;
        $return['msg'] = 'Reports generated successfully!';
        $return['data'] = $allReports;

        return response()->json($return);
    }

  protected function buildDataTableHTML($rules, $entries, $dateRanges, $period, $year, $type, $step)
{
    $tableId = 'safeguardReport-' . $type . '-' . $step;
    $html = '<div class="report-section mb-5">';
    $html .= '<h4 class="fw-bold mb-2">' . ucfirst($type) . ' Safeguards - ' . ucfirst($step) . ' (' . ucfirst($period) . ' ' . $year . ')</h4>';
    $html .= '<div class="table-responsive">';
    $html .= '<table id="' . $tableId . '" class="table table-bordered table-striped table-hover w-100">';
    $html .= $this->buildReportTableHeader($period, $dateRanges);
    $html .= '<tbody>';

    foreach ($rules as $rule) {
        $html .= '<tr' . ($rule->is_heading ? ' class="table-primary"' : '') . '>';
        $html .= '<td>' . ($rule->indx ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($rule->name) . '</td>';

        foreach ($dateRanges as $range) {
            $isCompliant = $this->checkPeriodCompliance($rule->id, $entries, $range['start'], $range['end']);
            $html .= '<td class="text-center ' . ($isCompliant ? 'bg-success text-white' : 'bg-danger text-white') . '">';
            $html .= $isCompliant ? 'Yes' : 'No';
            $html .= '</td>';
        }

        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div>';
    return $html;
}


    protected function getDateRangesForPeriod($period, $year)
    {
        $ranges = [];

        switch ($period) {
            case 'yearly':
                $ranges[] = [
                    'label' => $year,
                    'start' => $year . '-01-01',
                    'end' => $year . '-12-31',
                ];
                break;

            case 'quarterly':
                for ($q = 1; $q <= 4; $q++) {
                    $startMonth = ($q - 1) * 3 + 1;
                    $endMonth = $startMonth + 2;

                    $ranges[] = [
                        'label' => 'Q' . $q,
                        'start' => $year . '-' . str_pad($startMonth, 2, '0', STR_PAD_LEFT) . '-01',
                        'end' => $year . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-' . cal_days_in_month(CAL_GREGORIAN, $endMonth, $year),
                    ];
                }
                break;

            case 'monthly':
            default:
                for ($m = 1; $m <= 12; $m++) {
                    $monthName = date('F', mktime(0, 0, 0, $m, 1, $year));
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $year);

                    $ranges[] = [
                        'label' => $monthName,
                        'start' => $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-01',
                        'end' => $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-' . $daysInMonth,
                    ];
                }
                break;
        }

        return $ranges;
    }

    protected function buildReportTableHeader($period, $dateRanges)
    {
        $html = '<thead class="thead-dark">';
        $html .= '<tr>';
        $html .= '<th rowspan="2">Index</th>';
        $html .= '<th rowspan="2">Safeguard Item</th>';
        $html .= '<th colspan="' . count($dateRanges) . '" class="text-center">' . ucfirst($period) . ' Compliance</th>';
        $html .= '</tr><tr>';

        foreach ($dateRanges as $range) {
            $html .= '<th class="text-center">' . $range['label'] . '</th>';
        }

        $html .= '</tr></thead>';

        return $html;
    }

   protected function checkPeriodCompliance($ruleId, $entries, $startDate, $endDate)
{
    return $entries
        ->where('rule_id', $ruleId)
        ->filter(function ($entry) use ($startDate, $endDate) {
            return $entry->entry_date >= $startDate
                && $entry->entry_date <= $endDate
                && (!isset($entry->applicable) || $entry->applicable == 1);
        })
        ->isNotEmpty();
}


    // Keep the existing helper methods (getDateRangesForPeriod, checkPeriodCompliance) the same

    /**
     *
     */
    public function qualityTests()
    {
        return $this->projectProgress(request(), null);
    }

  public function projectProgress(Request $request, $type = null)
{
    $q_tests = $type ? 0 : 1;
    $sort = $request->get('sort', 'desc'); // 'asc' or 'desc'

    // Load all relevant projects (Level 2 assigned user and stage 3 or 4)
    $projects = Projects::with(['contract', 'defineProject'])
        ->where('assign_level_2', auth()->user()->id)
        ->whereIn('stage', [3, 4])
        ->orderBy('id', 'desc')
        ->paginate(100); // fetch more for in-memory sorting

    // Calculate financial progress using milestone_values_updated
    foreach ($projects as $project) {
        $totalSubmitted = MilestoneValueUpdated::where('project_id', $project->id)
            ->where('type', 2) // financial progress only
            ->sum('amount');

        $totalBudget = $project->contract->procurement_contract ?? 0;

        $project->milestone_amount_total = $totalSubmitted;

        $project->progress_percentage = $totalBudget > 0
            ? round(($totalSubmitted / $totalBudget) * 100, 2)
            : 0;
    }

    // Sort in-memory by progress percentage
    $projects = $projects->sortBy(
        fn ($project) => $project->progress_percentage,
        SORT_REGULAR,
        $sort === 'desc'
    );

    // Manual pagination (20 per page)
    $perPage = 20;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $pagedProjects = new LengthAwarePaginator(
        $projects->forPage($currentPage, $perPage),
        $projects->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('admin.projectLevel3.index', [
        'data' => $pagedProjects,
        'q_tests' => $q_tests,
        'sort' => $sort,
    ]);
}


    /**
     *
     */
    public function overview($type, $project_id)
    {
        if (in_array($type, ['social', 'environment'])) {
            $types = $type == 'environment' ? [1, 2, 3] : [1, 2];
            $project = Projects::find($project_id);

            $progress = ReportProgressType::with([
                'safeguard_rules' => function ($q) use ($type, $project) {
                    $q->with([
                        'entries' => function ($query) use ($project) {
                            $query->where('env_soc_safeguard_entries.project_id', $project->id);
                        },
                    ])
                        ->where('env_soc_safeguard_rules.safeguard_type', $type)
                        ->where('env_soc_safeguard_rules.is_heading', 0);
                },
            ])
                ->whereIn('id', $types)
                ->get();

            foreach ($progress as &$phase) {
                $count = 0;
                foreach ($phase->safeguard_rules as $rule) {
                    if ($rule->entries->count()) {
                        $count++;
                    }
                }

                $phase->entries_count = $count;
            }

            return view('mis.project.tracking.overview', compact('type', 'progress', 'project'));
        }

        abort(404);
    }

    /**
     *
     */

    /**
     *
     */
    public function entrySheet($entryType, $project_id)
    {
        $phase = request('phase') ?? null;
        $project = Projects::findOrFail($project_id);

        return view('mis.project.tracking.sheet', compact('entryType', 'project', 'phase'));
    }

    /**
     *
     */
    public function saveSheetEntry(Request $request)
    {
        $abort = 0;
        $return = ['ok' => 0, 'msg' => 'An invalid request detected!'];

        $msgs = [
            // 'date.required'      => $return['msg'],
            // 'rule_id.integer'    => $return['msg'],
            // 'rule_id.required'   => $return['msg'],
            // 'applicable.integer' => $return['msg'],
            // 'project_id.integer' => $return['msg'],
            // 'project_id.required'=> $return['msg'],
            'validity.integer' => 'Kindly select Yes/No to tell if this rule is applicable',
            'applicable.required' => 'Kindly select Yes/No to tell if this rule is applicable',
        ];

        $rules = [
            'date' => 'required|date',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:7048',
            'remark' => 'nullable|string',
            'rule_id' => 'required|integer',
            'validity' => 'required|integer',
            'project_id' => 'required|integer',
            'applicable' => 'required|integer',
        ];

        if (intval($request->validity)) {
            $rules['validity_date'] = 'required|date|after_or_equal:today';
            $msgs['validity_date.required_if'] = 'Kindly enter a valid document validity date!';
        }

        $validator = Validator::make($request->all(), $rules, $msgs);

        if (!$validator->fails()) {
            if ($request->filled('project_id', 'rule_id', 'applicable', 'date')) {
                $rule = SafeGuardRule::find($request->rule_id);
                $project = Projects::find($request->project_id);

                if ($project && $rule) {
                    $request->applicable = intval($request->applicable);

                    if (!$request->applicable || ($request->applicable && $request->filled('remark'))) {
                        $entry = SafeguardEntry::where('project_id', $project->id)->where('rule_id', $rule->id)->where('entry_date', $request->date)->first();
                        $entry = $entry ?: new SafeguardEntry();

                        if ($entry) {
                            if (($request->applicable == 1) & !$request->hasFile('files')) {
                                $abort = 1;
                            }

                            if (!$abort) {
                                $entry->user_id = auth()->id();
                                $entry->rule_id = $rule->id;
                                $entry->project_id = $project->id;
                                $entry->entry_date = $request->date;
                                $entry->applicable = intval($request->applicable);
                                $entry->label_2 = $request->number;
                                $entry->label_4 = $request->remark;
                                $entry->validity_date = $request->validity ? $request->validity_date : null;

                                if ($entry->save()) {
                                    if ($request->hasFile('files')) {
                                        $this->uploadRuleDocs($request->file('files'), $rule, $project);

                                        $return['url'] = 'reload';
                                    }

                                    $return['ok'] = 1;
                                    $return['msg'] = 'Entry Saved Successfully!';
                                } else {
                                    $return['msg'] = 'Failed to save the entry this time. Please try agian!';
                                }
                            } else {
                                $return['msg'] = 'Document is mandatory if Compliance Rule is applicable.';
                            }
                        }
                    } else {
                        $return['msg'] = 'Please fill the remark input.';
                    }
                }
            }
        } else {
            $return['msg'] = $validator->errors()->first();
        }

        return $return;
    }

    public function getEntrySheet(Request $request)
    {
        $return = ['ok' => 0, 'msg' => 'An invalid request detected!'];

        if (!$request->filled(['project_id', 'type', 'step'])) {
            return $return;
        }

        $viewOnly = intval($request->view);
        $project = Projects::find($request->project_id);
        $p_step = ReportProgressType::where('slug', $request->step)->first();
        $type = $request->type;
        $date = $request->filled('date') ? $request->date : date('Y-m-d');

        if (!$p_step || !$project || !in_array($type, ['social', 'environment'])) {
            return $return;
        }

        if ($date > date('Y-m-d')) {
            $return['msg'] = 'Entries cannot be made for future dates';
            return $return;
        }

        // 🔁 Load all rules
        $allRules = $p_step
            ->safeguard_rules()
            ->with([
                'media' => fn($q) => $q->where('project_id', $project->id),
                'entries' => fn($q) => $q->where('project_id', $project->id)->where('entry_date', '<=', $date),
            ])
            ->where('safeguard_type', $type)
            ->get();

        if ($allRules->isEmpty()) {
            $return['msg'] = 'No safeguard rules found for this type!';
            return $return;
        }

        // ➕ Build Table
        $html = '<table class="table table-bordered">';
        $html .= $this->buildTableHead($type, $date);
        $html .= '<tbody>';

        // Split by indx format
        $headings = $allRules->filter(fn($r) => preg_match('/^[A-Z]+$/i', $r->indx))->sortBy('indx');
        $children = $allRules->filter(fn($r) => preg_match('/^\d+$/', $r->indx))->groupBy('parent_id');

        // Render headings with their children
        foreach ($headings as $heading) {
            $html .= '<tr _rid="' . $heading->id . '" _pid="' . $project->id . '">';
            $html .= $this->buildHeadingRow($heading, $type, $viewOnly);
            $html .= '</tr>';

            foreach ($children[$heading->id] ?? [] as $child) {
                $entry = $child->entries->firstWhere('entry_date', $date) ?? $child->entries->last();
                $html .= '<tr _rid="' . $child->id . '" _pid="' . $project->id . '">';
                $html .= $this->buildDataRow($child, $entry, $type, $viewOnly);
                $html .= '</tr>';
            }
        }

        // If no headings matched, fallback to raw list
        if ($headings->isEmpty()) {
            foreach ($allRules->sortBy('indx') as $rule) {
                $entry = $rule->entries->firstWhere('entry_date', $date) ?? $rule->entries->last();

                $html .= '<tr _rid="' . $rule->id . '" _pid="' . $project->id . '">';
                if (preg_match('/^[A-Z]+$/i', $rule->indx)) {
                    $html .= $this->buildHeadingRow($rule, $type, $viewOnly);
                } else {
                    $html .= $this->buildDataRow($rule, $entry, $type, $viewOnly);
                }
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table>';

        $return['ok'] = 1;
        $return['msg'] = 'Sheet Fetched Successfully!';
        $return['data'] = $html;

        return $return;
    }

    protected function buildHeadingRow($rule, $type, $viewOnly)
    {
        $html = '<th>' . ($rule->indx ?? '') . '</th>';
        $html .= '<th>' . $rule->name . '</th>';
        $html .= '<th>' . ($rule->label_1 ?? '') . '</th>';

        if ($type === 'social') {
            $html .= '<th>' . ($rule->label_2 ?? '') . '</th>';
        }

        $html .= '<th>' . ($rule->label_3 ?? '') . '</th>';
        $html .= '<th>' . ($rule->label_5 ?? '') . '</th>';
        $html .= '<th>' . ($rule->label_4 ?? '') . '</th>';

        if (!$viewOnly) {
            $html .= '<th>Action</th>';
        }

        return $html;
    }
    protected function buildDataRow($rule, $entry, $type, $viewOnly)
    {
        $html = '<td>' . ($rule->indx ?? '') . '</td>';
        $html .= '<td>' . ($rule->name ?? '') . '</td>';

        // Applicable Select
        $html .= '<td><select class="form-control form-control-sm" name="applicable"' . ($viewOnly ? ' disabled' : '') . '>';
        $html .= '<option value=""' . (!$entry ? ' selected' : '') . '>SELECT</option>';
        $html .= '<option value="1"' . ($entry && $entry->applicable == 1 ? ' selected' : '') . '>Yes</option>';
        $html .= '<option value="0"' . ($entry && $entry->applicable == 0 ? ' selected' : '') . '>No</option>';
        $html .= '<option value="2"' . ($entry && $entry->applicable == 2 ? ' selected' : '') . '>N/A</option>';
        $html .= '</select></td>';

        // Label 2 (only for social)
        if ($type === 'social') {
            $html .= '<td><input class="form-control form-control-sm" type="text" name="number" value="' . ($entry->label_2 ?? '') . '"' . ($viewOnly ? ' disabled' : '') . '></td>';
        }

        // Media or File
        $html .= '<td>';
        if ($rule->media->count()) {
            $html .= '<a href="#" class="btn btn-sm btn-info btn-mud">Manage Docs</a>';
        } else {
            $html .= '<input type="file" name="docs" class="form-control form-control-sm" multiple' . ($viewOnly ? ' disabled' : '') . '>';
        }
        $html .= '</td>';

        // Validity
        $validity = $entry && $entry->validity_date;
        $html .= '<td>';
        $html .= '<select class="form-control form-control-sm" name="validity"' . ($viewOnly ? ' disabled' : '') . '>';
        $html .= '<option value="0"' . (!$validity ? ' selected' : '') . '>N/A</option>';
        $html .= '<option value="1"' . ($validity ? ' selected' : '') . '>Yes</option>';
        $html .= '</select>';
        $html .= '<input type="date" name="validity-date" class="form-control mt-2' . (!$validity ? ' d-none' : '') . '" value="' . ($entry->validity_date ?? '') . '"' . ($viewOnly ? ' disabled' : '') . '>';
        $html .= '</td>';

        // Remark
        $html .= '<td><textarea name="remark" rows="1" class="form-control"' . ($viewOnly ? ' disabled' : '' ) . '>' . ($entry->label_4 ?? '') . '</textarea></td>';

        // Save button
        if (!$viewOnly) {
            $html .= '<td>';
            $html .= '<a href="#" class="btn btn-ssr btn-sm btn-success">Save</a>';
            $html .= '</td>';
        }

        return $html;
    }

    /**
     *
     */
    public function getEntryImages(Request $request)
    {
        $return = ['ok' => 0, 'msg' => 'An invalid request is detected'];

        if ($request->filled(['rule_id', 'project_id'])) {
            $view = intval($request->view) ? 1 : 0;
            $proj = Projects::find($request->project_id);

            if ($proj) {
                $rule = SafeGuardRule::with([
                    'media' => function ($q) use ($proj) {
                        $q->where('project_id', $proj->id);
                    },
                ])->find($request->rule_id);

                if ($rule) {
                    $return['ok'] = 1;
                    $return['msg'] = 'Media records fetched successfully!';

                    if ($rule->count()) {
                        $key = 1;
                        $html = '';

                        foreach ($rule->media as $media) {
                            $file_path = asset($media->path . $media->name);

                            $html .= '<tr _mid="' . $media->id . '">';
                            $html .= '<td>' . $key . '</td>';
                            $html .= '<td>';
                            if ($media->type == 'image') {
                                $html .= '<a class="img-thumb" href="' . $file_path . '" data-lightbox="sr-img">';
                                $html .= '<img src="' . $file_path . '" >';
                                $html .= '</a>';
                            } else {
                                $html .= '<div class="d-flex align-items-end">';
                                $html .= '<i class="fa fa-file fico"></i>';
                                $html .= '<a href="' . $file_path . '" target="_blank" class="btn ml-3 btn-info">View File</a>';
                                $html .= '</div>';
                            }
                            $html .= '</td>';
                            $html .= '<td>' . $media->created_at->format('d M, Y') . '</td>';
                            if (!$view) {
                                $html .= '<td>';
                                // $html .= '<a href="" class="btn btn-sm btn-success">Update</a>';
                                // $html .= '<br>';
                                $html .= '<a href="" class="btn btn-sm btn-danger">Delete</a>';
                                $html .= '</td>';
                            }

                            $html .= '</tr>';

                            $key++;
                        }

                        $return['data'] = $html;
                    }
                }
            }
        }

        return $return;
    }

    /**
     *
     */
    public function saveEntryImages(Request $request)
    {
        $return = ['ok' => 0, 'msg' => 'An invalid request is detected!'];

        if ($request->filled(['rule_id', 'project_id'])) {
            $rule = SafeGuardRule::find($request->rule_id);
            $proj = Projects::find($request->project_id);

            if ($rule && $proj) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'files.*' => 'required|mimes:jpg,jpeg,png,pdf|max:7048',
                    ],
                    [
                        'files.*.max' => 'Maximum 2MB files are allowed',
                        'files.*.mimes' => 'Only JPEG, JPG, PNG & PDF Files are allowed!',
                        'files.*.required' => 'Kindly provide atleast one file to upload',
                    ],
                );

                if (!$validator->fails()) {
                    $this->uploadRuleDocs($request->file('files'), $rule, $proj);

                    $return['ok'] = 1;
                    $return['mud'] = 1;
                    $return['msg'] = 'Files uploaded successfully!';
                } else {
                    $return['msg'] = $validator->errors()->first();
                }
            }
        }

        return $return;
    }

    /**
     *
     */
    public function deleteEntryImages(Request $request)
    {
        $return = ['ok' => 0, 'msg' => 'An invalid request is detected!'];

        if ($request->filled('media_id')) {
            $media = Media::find($request->media_id);

            if ($media && $media->delete()) {
                $return['ok'] = 1;
                $return['msg'] = 'File deleted successfully!';
            } else {
                $return['msg'] = 'Unable to delete the file this time. Please try again!';
            }
        }

        return $return;
    }

    /**
     *
     */
    private function buildTableHead($type, $date)
    {
        $html = '';

        $html .= '<thead>';
        if ($type == 'social') {
            $html .= '<tr>';
            $html .= '<th colspan="8">';
            $html .= '<i>Social Safeguards Assessment Sheet</i>';
            $html .= '</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th colspan="2">Monthly Progress Report</th>';
            $html .= '<th colspan="6">Date: (Site Visit / Reporting Date)</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th rowspan="2">#</th>';
            $html .= '<th>Particular of Social Safeguards</th>';
            $html .= '<th rowspan="2" colspan="7">';
            $html .= '<input type="date" name="entry-date" class="form-control" value="' . $date . '">';
            $html .= '</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th>(One time Compliance / Renewal of License or certificate  once in a year or as per the validity )</th>';
            $html .= '</tr>';
        } elseif ($type == 'environment') {
            $html .= '<tr>';
            $html .= '<th colspan="7">Environmental &amp; OHS Safeguards Assessment Sheet</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            // $html .= '<th>Monthly Progress Report for December 2024</th>';
            $html .= '<th>Particular of Environmental and OHS Safeguards</th>';
            $html .= '<th colspan="6">';
            $html .= '<input type="date" name="entry-date" class="form-control" value="' . $date . '">';
            $html .= '</th>';
            $html .= '</tr>';
            // $html .= '<tr>';
            // $html .= '<th>Particular of Environmental and OHS Safeguards</th>';
            // $html .= '</tr>';
        }

        $html .= '</thead>';

        return $html;
    }

    /**
     *
     */
    private function uploadRuleDocs($files, SafeGuardRule $rule, Projects $project)
    {
        foreach ($files as $file) {
            $file_path = 'images/' . $rule->safeguard_type . '-safeguard/' . date('Y') . '/' . date('m') . '/';
            $filename = str_replace(' ', '-', time() . rand(1, 9999) . $file->getClientOriginalName());

            $file->move($file_path, $filename);

            $response = Media::create([
                'name' => $filename,
                'path' => $file_path,
                'type' => $this->getFileType($file->getClientOriginalExtension()),
                'project_id' => $project->id,
                'mediable_id' => $rule->id,
                'mediable_type' => SafeGuardRule::class,
            ]);
        }
    }

    /**
     *
     */
    private function getFileType($extn)
    {
        $format = null;

        if (in_array($extn, ['jpg', 'jpeg', 'png', 'webp'])) {
            $format = 'image';
        } elseif (in_array($extn, ['pdf', 'doc', 'docs', 'xls', 'xlsx'])) {
            $format = 'file';
        }

        return $format;
    }
}
