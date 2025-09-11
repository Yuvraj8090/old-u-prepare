@extends('layouts.admin')

@section('header_styles')
    <style>
        table td,
        table th {
            vertical-align: middle !important;
        }

        table .btn-vsim {
            min-width: 174px;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                Physical Progress of Project Milestone | Milestone : {{ $data->name ?? '' }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Physical Progress of Project Milestone</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <div class="row align-items-center">
                <div class="col-10">
                    <h2 style="white-space: unset;">{{ $data->name ?? '' }} </h2>
                </div>
                <div class="col-2">
                   
                </div>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <table class="table table-striped projects table-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 8%">S. No #</th>
                        <th class="text-center">Progress (In %)</th>
                        <th>Activity</th>
                        <th>Stage</th>
                        <th>Submit Date</th>
                        {{-- <th style="width: 12%"> Documents</th> --}}
                        <th style="width: 190px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if($data->contract->physical_progress->count())
                        @foreach($data->contract->physical_progress as $k => $prog)
                            <tr>
                                <td>{{ ++$k }}</td>
                                <th class="text-center">{{ $prog->progress }}</th>
                                <th>{{ $prog->activity->name }}</th>
                                <th>{{ $prog->stage->name }}</th>
                                <td>{{ \Carbon\Carbon::parse($prog->date)->format('d-m-Y') }}</td>
                                {{-- <td></td> --}}
                                <td>
                                    @if(auth()->user()->role->level == "THREE")
                                       
                                        {{--
                                        <a class="btn btn-md btn-danger milestone_id" data-toggle="modal" data-target=".addSiteImages" data-id="{{ $prog->id }}" href="javascript:(0)" data-an="{{ $prog->activity_name }}" data-as="{{ $prog->stage_name }}">Add Site Images</a>
                                        --}}
                                        <br>
                                    @endif

                                    @if(auth()->user()->role->level == "THREE")
                                        <a class="btn btn-vsim btn-block btn-info" href="{{ url('/site/index/' . $prog->id) }}">
                                            <i class="fa fa-eye"></i>
                                            View Site Images
                                        </a>
                                    @elseif(auth()->user()->role->level == "ONE")
                                        <a class="btn btn-vsim btn-block btn-info" href="{{ url('admin/milestone/site/images/' . $prog->id) }}">
                                            <i class="fa fa-eye"></i>
                                            View Site Images
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <h4 class="h3">No Records Found</h4>
                            </td>
                        </tr>
                    @endif
                    {{--
                    @if(count($mileStones) > 0)
                        @foreach($mileStones as $k => $d)
                            <tr>
                                <td>{{ ++$k }}</td>
                                <th class="text-center">{{ $d->percentage }}</th>
                                <th>{{ $d->activity_name }}</th>
                                <th>{{ $d->stage_name }}</th>
                                <td>{{ $d->date }}</td>
                                <td>
                                    @if(count($d->milestoneDocs) > 0)
                                        @foreach($d->milestoneDocs as $doc)
                                            <a target="_blank" href="{{ asset('/images/physical_progress/' . $doc->file) }}" class="btn btn-sm btn-success">{{ $doc->document_name }}: View</a>
                                            <br>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->role->level == "THREE")
                                        <a class="btn btn-md btn-danger milestone_id" data-toggle="modal" data-target=".addSiteImages" data-id="{{ $d->id }}" href="javascript:(0)" data-an="{{ $d->activity_name }}" data-as="{{ $d->stage_name }}">Add Site Images</a>
                                        <br>
                                    @endif

                                    @if(auth()->user()->role->level == "THREE")
                                        <a class="btn btn-md btn-info"  href="{{ url('/site/index/' . $d->id) }}">View Site Images</a>
                                    @elseif(auth()->user()->role->level == "ONE")
                                        <a class="btn btn-md btn-info"  href="{{ url('admin/milestone/site/images/' . $d->id) }}">View Site Images</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    --}}
                </tbody>

                <tfoot></tfoot>
            </table>
        </div>
    </div>

    {{-- <form autocomplete="off" id="editform" data-method="POST" data-action="{{ route('projectLevel.physical', $data->id) }}" class="form-horizontal form-label-left ajax-form-edit"> --}}
    <form autocomplete="off" id="addform" data-method="POST" data-action="{{ route('contract.physical.save') }}" class="form-horizontal form-label-left ajax-form">
        @csrf
        <input type="hidden" name="contract_id" value="{{ $data->contract->id }}">
        <!-- Modal -->
        <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 style="font-weight:600;" class="modal-title" id="exampleModalLabel">Update Physical Progress: {{ $data->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {{--
                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4 ">MileStones Name</label>
                            <div class="col-md-8 col-sm-8 ">
                                <input type="text" class="form-control" value="{{ $data->name }}" readonly>
                                <p class="error" id="editerror-name"></p>
                            </div>
                        </div>
                        --}}

                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4">Activity Name</label>
                            <div class="col-md-8 col-sm-8">
                                <select name="activity" class="form-control" _e="0">
                                    <option value="">PLEASE CHOOSE</option>
                                    @foreach ($activities as $activity)
                                        <option value="{{ $activity->slug }}">{{ $activity->name }}</option>
                                    @endforeach
                                </select>
                                {{-- <input type="text" class="form-control" name="activity_name" data-key="activity_name" placeholder="Activity Name.."> --}}
                                <p class="error" id="editerror-activity_name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4">Stage Name</label>
                            <div class="col-md-8 col-sm-8">
                                <select name="stage" class="form-control d-none"></select>
                                <input class="form-control aph" disabled placeholder="Please Select an activity first">
                                {{-- <input type="text" class="form-control" name="stage_name" data-key="stage_name" placeholder="Stage Name.."> --}}
                                <p class="error" id="editerror-stage_name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4">Items</label>
                            <div class="col-md-8 col-sm-8">
                                <textarea name="items" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4">Physical Progress (In %)</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" class="form-control" name="progress" data-key="physical_progress" placeholder="Physical progress..">
                                <p class="error" id="editerror-progress"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4">Submit Date</label>
                            <div class="col-md-8 col-sm-8 ">
                                <input type="date" class="form-control datepicker" name="date" data-key="date"  placeholder="Enter Submit Date">
                                <p class="error" id="editerror-date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-4 col-sm-4">Images</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="file" name="images[]" class="form-control" multiple>
                                <p class="error" id="editerror-images"></p>
                            </div>
                        </div>

                        @if(isset($data->document) && count($data->document))
                            <h2 style="font-weight:550;">Upload Documents</h2>
                            <br>

                            @foreach($data->document as $doc)
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-3 ">
                                        {{ ucfirst($doc->name) }}
                                        <br>
                                        (Upload document)
                                        <br>
                                        (Note:- Only PDF Doc allowed.)
                                    </label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="file" class="form-control" name="{{ str_replace(' ', '-', $doc->name) }}" required  />
                                        <p class="error" id="editerror-{{ str_replace(' ', '-', $doc->name) }}"></p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a type="reset" class="btn btn-primary pull-left" href="">Reset</a>
                        <button id="submit-btn" type="submit" class="btn btn-success">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Update Progress
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Edit Progres Modal --}}
   @stop

@section('script')
    <script>
        let cprog = parseInt({{ $progress }});
        let stage = $('select[name="stage"]');
        $(document).on('click','.milestone_id', function(e) {
            e.preventDefault();

            var id= $(this).data('id');

            $('#mileStone_id').val(id);
            $('.addSiteImages input[name="activity_name"]').val($(this).data('an'));
            $('.addSiteImages input[name="stage_name"]').val($(this).data('as'));
        });

        $('select[name="activity"]').on('change', function() {
            let edt = Number($(this).attr('_e'));
            let fom = edt ? 'editform' : 'addform';
            let tph = $('input.aph');
            let tar = $(`#${fom} select[name="stage"]`);

            if($(this).val().length) {
                let fd  = newFormData();
                    fd.append('activity', $(this).val())

                let pm = {
                    url: '{{ route("contract.activity.stages") }}',
                    data: fd
                }

                let bs = ()=> {
                    tar.addClass('d-none');
                    tph.removeClass('d-none');
                    tph.attr('placeholder', 'Fetching Stages...');
                }

                let cb = (resp) => {
                    tar.html('<option value="">Please Choose</option>');

                    if(resp.data && resp.data.length) {
                        tph.addClass('d-none');
                        tar.removeClass('d-none');

                        resp.data.forEach(function(item, dx, data) {
                            tar.append(`<option value="${item.slug}" _w="${item.weightage}">${item.name}</option>`);
                        });

                        if(edt) {
                            tar.val(tar.attr('_cv'));
                        }
                    } else {
                        tar.addClass('d-none');
                        tph.removeClass('d-none');
                    }
                }

                let al = ()=> {
                    tph.attr('placeholder', 'Kindly select an activity.');
                }

                ajaxify(pm, bs, cb, al);
            } else {
                tar.addClass('d-none');
                tph.removeClass('d-none');
            }
        });

        //
        $('input[name="progress"]').on('keyup', function() {
            let pi = $(this);
            let wt = parseInt(stage.find('option:selected').attr('_w'));

            if(parseInt($(this).val()) <= wt) {
                let fd = newFormData();
                    fd.append('stage', $(this).val());
                    fd.append('contract_id', {{ $data->contract->id }});

                let pm = {
                    url: '{{ route('contract.physical.progress.validate') }}',
                    data: fd
                }

                let bs = () => {}

                let cb = (resp) => {
                    pi.val(resp.val);
                }

                let al = () => {}
            }
        })

        $('.btn-edit').on('click', function() {
            let $form = $('form#editform');
            let $data = $(this).data('info');

            $form.find('input[name="progress_id"]').val($data.id);
            $form.find('select[name="stage"]').attr('_cv', $(this).data('stage'));
            $form.find('select[name="activity"]').val($(this).data('activity'));
            $form.find('input[name="activity"]').val($(this).data('activity'));
            $form.find('input[name="stage"]').val($(this).data('stage'));
            $form.find('select[name="activity"]').trigger('change');
            $form.find('input[name="progress"]').val($data.progress);
            $form.find('input[name="date"]').val($data.date);
            $form.find('textarea').val($data.items);
        })

        $('table .btn-delete').on('click', function(e) {
            e.preventDefault();

            if(confirm('Are you sure to delete this entry?')) {
                let fd = newFormData();
                    fd.append('progress_id', $(this).data('id'));

                let pm = {
                    url: '{{ route('contract.physical.delete') }}',
                    data: fd
                }

                let bs = ()=> {}
                let cb = (resp) => {
                    if(resp.url) {
                        redirect(resp.url);
                    }
                }

                ajaxify(pm, bs, cb);
            }
        })
    </script>
@stop
