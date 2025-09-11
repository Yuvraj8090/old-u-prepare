@extends('layouts.admin')

@section('header_styles')
    <style>
        table thead th {
            vertical-align: middle !important;
        }
        tbody tr td.gr-no {
            width: 100px;
        }
        tbody tr td.action {
            width: 200px;
        }
        .popup {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: fixed;
            background-color: rgba(0, 0, 0, 0.25);
        }
        .popup > .card {
            min-width: 420px !important;
        }

        .popup.flex {
            display: flex;
        }

        .popup.none {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>Manage Grievances</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('mis.grievance.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Manage Grievances</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="filters">
        <form action="" method="GET" autocomplete="off">
            <div class="row">
                <div class="col-lg-10 col-md-8">
                    <div class="col-md-3 mb-3">
                        <input type="text" placeholder="Search by name..." name="name" value="{{ request()->name ?? '' }}" class="form-control" />
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="district" class="form-control">
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option  value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="typology" class="form-control">
                            <option value="">Related to</option>
                            @foreach($typology as $typo)
                                <option  value="{{ $typo->slug }}" @selected(request('typology') == $typo->slug)>{{ $typo->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="resolved" @selected(request('status') == 'resolved')>Resolved</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="year" class="form-control">
                            <option value="">Select Year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->year }}" @selected(request('year') == $year->year)>{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 mb-3">
                        <select name="month" class="form-control">
                            <option value="">Select Month</option>
                            @foreach ($months as $key => $month)
                                <option value="{{ $key + 1 }}" @selected(request('month') == ($key + 1))>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning pull-right">
                        <i class="fa fa-search" ></i>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </section>

    <section class="data x_panel">
        <div class="x_content">
            <table id="datatable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Grievance No.</th>
                        <th>Related To</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Resolved At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($grievances->count())
                        @foreach($grievances as $key => $grievance)
                            <tr _id="{{ $grievance->id }}" _grid={{ $grievance->ref_id }}>
                                <th>{{ $key + 1 }}.</th>
                                <td class="text-center gr-no">
                                    <a class="_gdl" href="{{ route('mis.grievance.details', $grievance->ref_id) }}">{{ $grievance->ref_id ?? '—' }}</a>
                                </td>
                                <td>{{ $grievance->typology }}</td>
                                <td>{{ $grievance->department ?? '—' }}</td>
                                <td>{{ ucwords($grievance->status) }}</td>
                                <td>{{ $grievance->resolved_at ? $grievance->resolved_at->format('d M, Y') : '' }}</td>
                                <td class="text-center action">
                                    <a href="#" class="btn btn-primary btn-fto">Forward To</a>
                                    <a href="#" class="btn btn-success">Complaint Log</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">
                                <center>
                                    <b> No Grievance Found </b>
                                </center>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{-- $data->links() --}}
        </div>
    </section>

    {{-- Popup for Assign To --}}
    <div class="popup none flex align-items-center justify-content-center">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    <span>Forward Grievance:</span>
                    <span class="_grid"></span>
                    to
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('mis.grievance.assign.department') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Department</label>
                        <select class="form-control" name="dept_id">
                            <option value="">Select Department</option>
                        </select>
                    </div>
                    <div class="form-group mb-0 mt-4">
                        <a href="#" class="btn btn-success">Forward</a>
                        <a href="#" class="btn btn-danger">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function() {
            let $popup = $('.popup');

            $('a[href="#"]').on('click', function(e) {
                e.preventDefault();
            });

            $('tbody a.btn-fto').on('click', function() {
                $grid = $(this).closest('tr').attr('_grid');

                $popup.find('._grid').text($grid);

                $popup.removeClass('none');
            });

            $popup.find('form .btn-danger').on('click', function() {
                $popup.addClass('none');
                $popup.find('._grid').text('');
            });

            $popup.find('form .btn-success').on('click', function () {
                $form = $popup.find('form');

                var url = $form.attr('action');
                var method = $form.attr('method');
                var formData = new FormData($form.get(0)); // Use FormData for file upload

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        toastr.options = {
                            timeOut: 3000,
                            iconClass: 'toast-success-icon',
                            toastClass: 'custom-toast-width',
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            extendedTimeOut: 2000,
                        }

                        if (Number(response.ok)) {
                            toastr.success("Success! "+ response.msg);

                            if(response.url){
                                setTimeout(function() {
                                    window.location = response.url;
                                }, 1000);
                            }
                        } else {
                            toastr.error(response.msg)
                        }
                    },
                    error: function (err) {
                        toastr.info("Error! Please Contact Admin.");
                    },
                });
            });
        })()
    </script>
@endsection
