@extends('layouts.admin')

@section('content')
    <style>
        .custom-form-control{
            height: 40px;
            border-radius: 5px;
            margin-right: 5px;
            width: 400px;
            padding: 10px;
        }
        .col-md-3,.col-md-4{
            margin-top:10px;
        }

        table th,
        table td {
            vertical-align: middle !important;
        }

        table tbody th:not(:first-child) {
            background-color: #BDD7EE;
        }

        .modal.mud {
            background: rgba(0, 0, 0, 0.56);
        }

        .modal.mud table thead th:nth-child(1) {
            width: 56px;
        }

        .modal.mud table thead th:nth-child(3) {
            width: 140px;
        }

        .modal.mud table thead th:nth-child(4) {
            width: 72px;
        }

        .modal.mud table tbody td a.img-thumb {
            width: 96px;
            height: 96px;
            display: inline-block;
        }

        .modal.mud table tbody td a.img-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal.mud table tbody td .fico {
	        width: 96px;
	        color: #333;
	        border: 1px solid #ccc;
	        padding: 5px 0;
	        font-size: 96px;
	        text-align: center;
	        border-radius: 4px;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h3>{{ ucfirst($entryType) }} Compliances || Project: {{ $project->name ?? "N/A" }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Update Project</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="x_title d-flex align-items-center justify-content-between">
                    <h5 class="m-0">{{ ucfirst($entryType) }} Safeguard Entry Sheet</h5>

                    <select name="entry-phase" class="form-control w-auto" autocomplete="off">
                        <option value="">Select Phase</option>
                        <option value="pre-construction" @selected($phase == 'pre-construction')>Pre-Construction</option>
                        <option value="during-construction" @selected($phase == 'during-construction')>During Construction</option>
                        @if($entryType == 'environment')
                        <option value="post-construction" @selected($phase == 'post-construction')>Post-Construction</option>
                        @endif
                    </select>
                </div>

                <div class="x_content sres"></div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    <div class="modal mud" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Manage Safeguard Rule Documents</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>


                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="mediaTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="view_docs-tab" data-toggle="tab" href="#home" role="tab">View Docs</a>
                            </li>
                            @if(!isset($view))
                            <li class="nav-item">
                                <a class="nav-link" id="add_docs-tab" data-toggle="tab" href="#upload" role="tab">Add Docs</a>
                            </li>
                            @endif
                        </ul>

                        <div class="tab-content" id="mediaTabContent">
                            <div class="tab-pane fade active show" id="home" role="tabpanel">
                                <div class="table-responsive" style="max-height: 420px;">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Media File</th>
                                                <th>Date Added</th>
                                                @if(!isset($view))
                                                <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <img src="{{ asset('assets/img/svg/img_loader.svg') }}" width="32px" />
                                                    <br>
                                                    Updating Records
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if(!isset($view))
                            <div class="tab-pane fade" id="upload" role="tabpanel">
                                <form id="ajax-form" autocomplete="off" data-method="POST" data-action="{{ route('mis.project.tracking.safeguard.entry.image.save') }}">
                                    @csrf
                                    <input type="hidden" name="rule_id" value="">
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                                    <div class="card border-top-none" style="border-top: none;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="control-label">Select Files</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input class="form-control" name="files[]" type="file" multiple>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mt-3 text-right">
                                                    <button class="btn btn-primary" type="submit">Add Docs</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js" integrity="sha512-KbRFbjA5bwNan6DvPl1ODUolvTTZ/vckssnFhka5cG80JVa5zSlRPCr055xSgU/q6oMIGhZWLhcbgIC0fyw3RQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css" integrity="sha512-xtV3HfYNbQXS/1R1jP53KbFcU9WXiSA1RFKzl5hRlJgdOJm4OxHCWYpskm6lN0xp0XtKGpAfVShpbvlFH3MDAA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        let $data = {
            view: {{ isset($view) ? 1 : 0 }},
            date: '{{ date("Y-m-d") }}',
            sgtyp: '{{ $entryType }}',
            sgptyp: '{{ $phase ?? "pre-construction" }}',
            project: {{ $project->id }}
        }

        let $valid = $('select[name="validity"]');
        let $phase = $('select[name="entry-phase"]');

        $phase.on('change', function(e) {
            e.preventDefault();

            if($(this).val()) {
                $data.sgptyp = $(this).val();

                loadSafeGuardSheet($data);
            }
        });

        if(!$phase.val()) {
            $phase.val('pre-construction');
            $data.sgptyp = 'pre-construction';
        }

        loadSafeGuardSheet($data);

        $('.modal.mud button.close').on('click', function(e) {
            e.preventDefault();
            let $modal = $(this).closest('.modal');
                $modal.removeClass('show d-block');
                $modal.find('input[name="rule_id"]').val('');
        });

        $('.modal.mud table tbody').on('click', '.btn-danger', function(e) {
            e.preventDefault();

            let $btn = $(this);
            let $tbd = $btn.closest('tbody');
            let $mid = parseInt($btn.closest('tr').attr('_mid'));

            if($mid) {
                if(confirm('Are you sure to delete this?')) {
                    let fd = newFormData();
                        fd.append('media_id', $mid);

                    let pm = {
                        url: '{{ route("mis.project.tracking.safeguard.entry.image.delete") }}',
                        data: fd
                    }

                    let bs = () => {}
                    let cb = (resp) => {
                        $btn.closest('tr').slideUp('normal', function() {
                            $(this).remove();

                            // if(!$tbd.find('tr').length) {
                            //     $tbd.html('<tr><td colspan="4" class="text-center">No Records Found</td></tr>');
                            // }
                        });
                    }

                    ajaxify(pm, bs, cb);
                }
            }
        })
    </script>
@stop
