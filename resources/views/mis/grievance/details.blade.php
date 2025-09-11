@extends('layouts.admin')

@section('header_styles')
    <style>
        .x_title .row {
            align-items: center;
        }

        .x_title h5 {
            font-weight: 550;
        }

        .x_title h4,
        .x_title h5 {
            margin-bottom: 0;
        }

        .h4 {
            font-size: 18px !important;
        }
    </style>
@endsection

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>Grievance Details</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('mis.grievances') }}">Grievances</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Grievance Details</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="x_panel">
        <div class="x_title">
            <h5>Applicant Details: </h5>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            Name:
                            <span class="h4"> {{ $grievance->registrant ?? '—' }} </span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            Mobile Number:
                            <span class="h4">{{ $grievance->phone ?? '' }}</span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            Email ID:
                            <span class="h4">{{ $grievance->email ?? '' }}</span>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            District:
                            <span class="h4">{{ $grievance->district->name ?? '' }}</span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            Block:
                            <span class="h4">{{ $grievance->block->name ?? '' }}</span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            Village:
                            <span class="h4">{{ $grievance->village ?? '' }}</span>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <h5 style="font-weight:550;">
                            Address:
                            <span class="h4">{{ $grievance->address ?? '' }}</span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <br /><br />

    <section class="x_panel">

        <div class="x_title">
            <h5 class="h4 col-md-6">Grievance Details: {{ $grievance->ref_id }}</h5>


            <h5 class="h4 col-md-6 text-right">Submitted At: {!! $grievance->date_full_format !!}</h5>
            </h4>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Related To :
                            <span class="h4">{{ App\Helpers\DummyData::typology($grievance->typology) ?? '—' }}</span>
                        </h5>
                    </div>
                </div>

                @if ($grievance->typology == 'other')
                    <div class="col-md-4">
                        <div class="form-group">
                            <h5 style="font-weight:550;">Related Details:
                                <span
                                    class="h4">{{ App\Helpers\DummyData::typology($grievance->typo_other) ?? '—' }}</span>
                            </h5>
                        </div>
                    </div>
                @else
                    <div class="col-md-8">
                        <div class="form-group">
                            <h5 style="font-weight:550;">Nature of Complaint:
                                <span
                                    class="h4">{{ $grievance->category_id ? $grievance->category->name : 'Other' }}</span>
                            </h5>
                        </div>
                    </div>


                    @if ($grievance->category_id)
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5 style="font-weight:550;">Category:
                                    <span
                                        class="h4">{{ $grievance->subcategory_id ? $grievance->subcategory_id->name : 'Other' }}</span>
                                </h5>
                            </div>
                        </div>

                        @if (!$grievance->subcat_id)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5 style="font-weight:550;">Sub - Category:
                                        <span
                                            class="h4">{{ $grievance->subcategory_id ? $grievance->subcategory_id->name : 'Other' }}</span>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5 style="font-weight:550;">Complaint Description:
                                        <span class="h4">{{ $grievance->scat_other }}</span>
                                    </h5>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>

            <div class="row">
                <div @class([
                    'col-md-4' => !$grievance->proj_id,
                    'col-md-12' => $grievance->proj_id,
                ])>
                    <div class="form-group">
                        <h5 style="font-weight:550;">Project related to Grievance:
                            <span class="h4">{{ $grievance->project ? $grievance->project->name : 'Other' }}</span>
                        </h5>
                    </div>
                </div>

                @if (!$grievance->proj_id)
                    <div class="col-md-4">
                        <div class="form-group">
                            <h5 style="font-weight:550;">Details :
                                <span class="h4"> {{ $grievance->proj_other }} </span>
                            </h5>
                        </div>
                    </div>
                @endif

                <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Grievance Description :
                            <span class="h4"> {{ $grievance->description }} </span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Document Provided:
                            <span class="h4">&nbsp;</span>
                            @if (isset($grievance->document))
                                <a download href="{{ asset($grievance->document) }}"
                                    class="btn text-white btn-md btn-success">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i>&nbsp;&nbsp; Download
                                </a>
                            @else
                                <h5> Document not provided !</h5>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Filed on behalf of someone else:
                            <span class="h4">{{ $grievance->on_behalf }}</span>
                        </h5>
                    </div>
                </div>

                @if ($grievance->on_behalf == 'Yes')
                    <div class="col-md-12">
                        <div class="form-group">
                            <h5 style="font-weight:550;">Have consent to share this information?
                                <span class="h4">{{ $grievance->consent }}</span>
                            </h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>


    <br /><br />

    <section class="x_panel">
        <div class="x_title">
            <div class="row">
                <div class="col-md-8">
                    <h5>Grievance Assigned To: {{ $grievance->incharge ? $grievance->incharge->name : 'GRM Nodal' }}</h5>
                </div>

                <div class="col-md-4 text-right">
                    @if (auth()->user()->hasPermission('grm_nodal') && !$grievance->user_id)
                        <a href="#" data-toggle="modal" data-target="#frg_modal"
                            class="btn btn-forward btn-primary" _put="ftu">Forward to User</a>
                    @elseif(auth()->id() == $grievance->user_id)
                        <a href="#" data-toggle="modal" data-target="#frg_modal"
                            class="btn btn-forward btn-primary" _put="rtn">Revert to Nodal Officer</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="x_content">
            <div class="row pb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Name:
                            <span
                                class="h4">{{ $grievance->incharge ? $grievance->incharge->name . ' (' . $grievance->incharge->username . ')' : 'N/A' }}</span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Designation:
                            <span
                                class="h4">{{ $grievance->incharge && $grievance->incharge->designatien ? $grievance->incharge->designatien->name : 'N/A' }}</span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Department:
                            <span
                                class="h4">{{ $grievance->incharge ? $grievance->incharge->role->department : 'Grievance Nodal' }}</span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <h5 style="font-weight:550;">Email:
                            <span class="h4">{{ $grievance->incharge ? $grievance->incharge->email : 'N/A' }}</span>
                        </h5>
                    </div>
                </div>
            </div>

            <hr>
        </div>
    </section>

    <div class="row">
        <div class="col-md-6">
            <section class="x_panel">
                <div class="x_title">
                    <h5>Preliminary Action Taken </h5>
                </div>
                <div class="x_content">
                    @if (!$grievance->action || ($grievance->action && !$grievance->action->pact))
                        <p class="form-control h-auto disabled text-center">N/A</p>
                        @if (
                            $grievance->user_id == auth()->id() ||
                                (auth()->user()->hasPermission('grm_nodal') && (int) $grievance->created_at->diffInDays() > 14))
                            <button class="btn btn-patr btn-primary" data-toggle="modal" data-target="#actt_modal">Click
                                here to Submit Preliminary Action Taken Report</button>
                        @else
                            {{-- <p class="form-control disabled text-center"></p> --}}
                        @endif
                    @else
                        <p class="form-control h-auto disabled">{{ $grievance->action->pact }}</p>

                        <a href="{{ asset($grievance->action->pact_doc) }}" class="btn btn-info" target="_blank">View
                            Document</a>
                        <a download href="{{ asset($grievance->action->pact_doc) }}" class="btn btn-primary">Download
                            Document</a>
                    @endif
                </div>
            </section>
        </div>
        <div class="col-md-6">
            <section class="x_panel">
                <div class="x_title">
                    <h5>Final Action Taken</h5>
                </div>
                <div class="x_content">
                    @if ($grievance->action && $grievance->action->pact)
                        @if (!$grievance->action->fact)
                            <p class="form-control h-auto disabled text-center">N/A</p>
                            @if (
                                $grievance->user_id == auth()->id() ||
                                    (auth()->user()->hasPermission('grm_nodal') && (int) $grievance->created_at->diffInDays() > 14))
                                <button class="btn btn-fatr btn-primary" data-toggle="modal"
                                    data-target="#actt_modal">Click here to Submit Final Action Taken Report</button>
                            @endif
                        @else
                            <p class="form-control h-auto disabled">{{ $grievance->action->fact }}</p>

                            <a href="{{ asset($grievance->action->fact_doc) }}" class="btn btn-info"
                                target="_blank">View Document</a>
                            <a download href="{{ asset($grievance->action->fact_doc) }}" class="btn btn-primary">Download
                                Document</a>
                        @endif
                    @else
                        <p class="form-control disabled text-center">Kindly Submit the Priliminary Action Taken Report
                            First</p>
                    @endif
                </div>
            </section>
        </div> @if ($grievance->status !== 'resolved' && $grievance->status !== 'rejected')
         <div class="col-md-6">
        <section class="x_panel">
            <div class="x_title">
                <h5>Mark grievance As Resolved/Rejected</h5>
            </div>
            <div class="x_content">

               <form id="send-remark">
    @csrf
    <textarea id="remark" name="remark" class="form-control" rows="3" placeholder="Enter your remark here..."></textarea>
    <br>
    <button type="submit" class="btn btn-success btn-sm">
        Send Remarks
    </button>
</form>





            </div>
        </section>
    </div>
       @endif

@if ($grievance->action && $grievance->action->pact && $grievance->action->pact_doc)
    @if ($grievance->status !== 'resolved' && $grievance->status !== 'rejected')
        <div class="col-md-6">
            <section class="x_panel">
                <div class="x_title">
                    <h5>Mark grievance As Resolved/Rejected</h5>
                </div>
                <div class="x_content">
                    <button class="btn btn-success btn-sm update-status" data-id="{{ $grievance->id }}"
                        data-status="resolved">
                        Resolve Grievance
                    </button>

                    <button class="btn btn-danger btn-sm update-status" data-id="{{ $grievance->id }}"
                        data-status="rejected">
                        Reject Grievance
                    </button>
                </div>
            </section>
        </div>
    @else
        <span class="form-control disabled text-center timeline">
            Status: {{ ucfirst($grievance->status) }}
        </span>
    @endif
@endif

   
    </div>

    <style>
        .timeline h2 {
            font-size: 2rem;
        }

        .timeline p {
            font-size: 1.25rem;
        }

        .py-8 {
            padding-top: 4.5rem !important padding-bottom: 4.5rem !important;
        }

        .bsb-timeline-1 {
            --bsb-tl-color: #cfe3ff;
            --bsb-tl-circle-size: 18px;
            --bsb-tl-circle-color: #0d6ef6;
            --bsb-tl-circle-offset: 9px
        }

        .bsb-timeline-1 .timeline {
            margin: 0;
            padding: 0;
            position: relative list-style: none;
        }

        .bsb-timeline-1 .timeline::after {
            top: 0;
            left: 0;
            width: 2px;
            bottom: 0;
            content: "";
            position: absolute;
            margin-left: -1px;
            background-color: var(--bsb-tl-color);
        }

        .bsb-timeline-1 .timeline>.timeline-item {
            margin: 0;
            padding: 0;
            position: relative
        }

        .bsb-timeline-1 .timeline>.timeline-item::before {
            top: 0;
            left: calc(var(--bsb-tl-circle-offset)*-2);
            width: var(--bsb-tl-circle-size);
            height: var(--bsb-tl-circle-size);
            z-index: 1;
            content: "";
            position: absolute;
            border-radius: 50%;
            background-color: var(--bsb-tl-circle-color);
        }

        .bsb-timeline-1 .timeline>.timeline-item .timeline-body {
            margin: 0;
            padding: 0;
            position: relative
        }

        .bsb-timeline-1 .timeline>.timeline-item .timeline-content {
            padding: 0 0 2.5rem 2.5rem
        }

        .bsb-timeline-1 .timeline>.timeline-item:last-child .timeline-content {
            padding-bottom: 0
        }

        @media(min-width: 576px) {
            .py-sm-8 {
                padding-top: 4.5rem !important padding-bottom: 4.5rem !important;
            }
        }

        @media(min-width: 768px) {
            .py-md-8 {
                padding-top: 4.5rem !important padding-bottom: 4.5rem !important;
            }

            .bsb-timeline-1 .timeline>.timeline-item .timeline-content {
                padding-bottom: 3rem
            }
        }

        @media(min-width: 992px) {
            .py-lg-8 {
                padding-top: 4.5rem !important padding-bottom: 4.5rem !important;
            }
        }

        @media(min-width: 1200px) {
            .py-xl-8 {
                padding-top: 4.5rem !important padding-bottom: 4.5rem !important;
            }
        }

        @media(min-width: 1400px) {
            .py-xxl-8 {
                padding-top: 4.5rem !important padding-bottom: 4.5rem !important;
            }
        }
    </style>

    <section class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h5 class="m-0">Grievance Log Timeline</h5>


                </div>

                <div class="x_content">
                    <div class="bsb-timeline-1 py-5 py-xl-8">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-10 col-md-8 col-xl-6">

                                    <ul class="timeline">
                                        @foreach ($grievance->logs as $key => $log)
                                            <li class="timeline-item border-0">
                                                <div class="timeline-body">
                                                    <div class="timeline-content">
                                                        <div class="card border-0">
                                                            <div class="card-body p-0">
                                                                <h5 class="card-subtitle text-secondary mb-1">
                                                                    <span>
                                                                        {{ $log->created_at->format('d M, Y') }}
                                                                        <small>{{ $log->created_at->format('H:m a') }}</small>
                                                                    </span>

                                                                    @if (in_array($log->type, ['pact', 'fact']) &&
                                                                            $grievance->action &&
                                                                            ($grievance->action->pact_doc || $grievance->action->fact_doc))
                                                                        <a href="{{ asset($log->type == 'pact' ? $grievance->action->pact_doc : $grievance->action->fact_doc) }}"
                                                                            class="ml-3 btn btn-sm btn-info"
                                                                            target="_blank">View Report Document</a>
                                                                    @endif
                                                                </h5>

                                                                <h2 class="card-title mb-3">{{ $log->title }}</h2>

                                                                {{-- @if ($key) --}}
                                                                {{-- @if ($log->forward_to) --}}
                                                                {{-- <h2 class="card-title mb-3">Grievance Forwarded to {{ $log->forwarded_user->name }}</h2> --}}
                                                                {{-- @elseif($log->is_revert) --}}
                                                                {{-- <h2 class="card-title mb-3">Grievance is Reverted to GRM Nodal Officer</h2> --}}
                                                                {{-- @elseif($log->type && $grievance->action && ($grievance->action->pact || $grievance->action->fact)) --}}
                                                                {{-- <h2 class="card-title mb-3">{{ $log->type == 'pact' ? 'Preliminary' : 'Final' }} Action Taken Report Submitted</h2> --}}
                                                                {{-- @endif --}}
                                                                {{-- @elseif(!$key) --}}
                                                                {{-- <h2 class="card-title mb-3">{{ $log->remark }}</h2> --}}
                                                                {{-- @endif --}}

                                                                @if ($key)
                                                                    <p class="card-text m-0">{{ $log->remark }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (false)
        <section class="x_panel">
            <div class="x_title">
                <h5>Grievance Log</h5>
            </div>

            <div class="x_content">

            </div>
        </section>
    @endif


    <div class="modal fade" id="actt_modal" tabindex="-1" role="dialog" aria-labelledby="actt_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actt_modal_title"><span>Priliminary</span> Action Taken Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="ajax-form" data-method="POST" data-action="{{ route('mis.grievance.action') }}"
                        data-method="POST" data-action="{{ route('mis.grievance.action') }}"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="grievance" value="{{ $grievance->ref_id }}">
                        <input type="hidden" name="action" value="">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="control-label">Action Taken Comment<sup>*</sup></label>
                                <textarea name="comment" rows="4" class="form-control"></textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="control-label">Document for Action Taken<sup>*</sup></label>
                                <input type="file" class="form-control" name="document">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit Report</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="frg_modal" tabindex="-1" role="dialog" aria-labelledby="frg_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="frg_modal_title"><span>Forward</span> Grievance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="ajax-form" data-method="POST" data-action="{{ route('mis.grievance.forward') }}"
                        data-method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="grievance" value="{{ $grievance->ref_id }}">
                        <input type="hidden" name="action" value="">
                        @if (!$grievance->user_id)
                            <input type="hidden" name="forward" value="yes">
                        @endif

                        <div class="row">
                            @if (auth()->id() !== $grievance->user_id)
                                <div class="col-12 mb-3">
                                    <label class="control-label">Select User<sup>*</sup></label>
                                    <select name="user" class="form-control">
                                        <option value="">SELECT</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-12 mb-3">
                                <label class="control-label">Add Remark<sup>*</sup></label>
                                <textarea name="remark" rows="4" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Forward to User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $(document).ready(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // 🌐 Send remark only
        $('#send-remark').on('submit', function (e) {
            e.preventDefault();

            const remark = $('#remark').val();
            const grievanceId = "{{ $grievance->id }}";

            if (!remark.trim()) {
                alert("Please enter a remark before submitting.");
                return;
            }

            $.ajax({
                url: '/mis/grievances/' + grievanceId + '/remark',
                type: 'POST',
                data: { remark: remark },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    alert(response.message || 'Remark submitted successfully.');
                    $('#remark').val(''); // Clear textarea
                },
                error: function (xhr) {
                    alert('Error: ' + (xhr.responseJSON.message || 'Something went wrong.'));
                }
            });
        });

        // 🌐 Update status (resolved / rejected)
        $('.update-status').on('click', function () {
            const grievanceId = $(this).data('id');
            const status = $(this).data('status');

            const remark = prompt("Enter remark for marking as " + status + ":");

            if (!remark || !remark.trim()) {
                alert("Remark is required to change status.");
                return;
            }

            if (!confirm("Are you sure you want to mark this grievance as " + status + "?")) return;

            $.ajax({
                url: '/mis/grievances/' + grievanceId + '/update-status',
                type: 'POST',
                data: {
                    status: status,
                    remark: remark
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    alert('Status updated to ' + status);
                    location.reload(); // Or update status live if needed
                },
                error: function (xhr) {
                    alert('Error: ' + (xhr.responseJSON.message || 'Failed to update.'));
                }
            });
        });
    });
</script>


@endsection

@section('script')
    <script>
        $atrm = $('#actt_modal');
        $atrt = $atrm.find('input[name="action"]');

        $('.btn-patr').on('click', function() {
            $atrt.val(0);
            $atrm.find('form').get(0).reset();
            $atrm.find('#actt_modal_title > span').text('Preliminary');
        });

        $('.btn-fatr').on('click', function() {
            $atrt.val(1);
            $atrm.find('form').get(0).reset();
            $atrm.find('#actt_modal_title > span').text('Final');
        });

        $('.btn-forward').on('click', function(e) {
            e.preventDefault();
            let title = 'Revert to GRM Nodal Officer';
            let btnla = 'Revert to GRM Nodal';
            if ($(this).attr('_put') == 'ftu') {
                title = btnla = 'Forward to User';
            }

            $('#frg_modal .modal-title').html(title);
            $('#frg_modal .btn-primary').text(btnla)
        })
    </script>
@endsection
