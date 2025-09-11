@extends('layouts.admin')

@section('content')
    <style>
        #datatable_wrapper .dt-layout-row:first-child {
            zoom: 125%;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>
                <b>Login Details</b>
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">All Login</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <div class="clearfix"></div>
        </div>

        <div class="x_content" >
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search"  placeholder="Search...." class="form-control" value="{{ $_GET['search'] ?? '' }}" />
                    </div>

                    <div class="col-md-3">
                        <select name="department" class="form-control">
                            <option value=""> Department </option>
                            @if(isset($department))
                                @if(count($department) > 0)
                                    @foreach($department as $d)
                                        <option value="{{ $d->id }}" @if( request()->department == $d->id) selected @endif >{{  $d->department }}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Status</option>
                            <option value="1" @if(request()->status === "1") selected @endif >Active</option>
                            <option value="0" @if(request()->status === "0") selected @endif >Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                            <i class="fa fa-search"></i>
                            Filter
                        </button>

                        <a  onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                            <i class="fa fa-refresh"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.error')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Login</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="datatable" class="table table-striped projects table-bordered text-center">
                        <thead>
                            <tr>
                                <th class="text-left">S.No</th>
                                <th>Photo</th>
                                <th>Username</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>User Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        <th>{{ $key + 1 }}.</th>
                                        <th>
                                            <img src="{{ $d->profile_image }}" width="100px;" alt="profile img">
                                        </th>
                                        <th>{{ $d->username }}</th>
                                        <th class="text-left">{{ $d->department->name ?? ''}}</th>
                                        <th class="text-left">{{ is_int($d->designation) ? $d->role->name : $d->designation }}</th>
                                        {{--<th class="text-left">{{ $d->role->name ?? ''}}</th>--}}
                                        {{-- <th class="text-left">{{ $d->role->department ?? ''}}</th> --}}
                                        <th style="text-align:left;">
                                            Name: {{ $d->name }}
                                            <br>
                                            {{-- Department : <span class="badge bg-secondary text-white">{{$d->role->department}} </span><br> --}}
                                            Email: {{ $d->email }}
                                            <br>
                                            Contact No: {{ $d->phone_no }}
                                        </th>
                                        <td>
                                            @if($d->status == 1)
                                                <span class="badge bg-success text-white">Active</span>
                                            @else
                                                <span class="badge bg-danger text-white">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('manage-logins.edit', $d->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-pencil"></i> &nbsp;  Edit
                                            </a>

                                            <!--<a type="button" data-userId="{{ $d->id }}" class="btn btn-primary text-white btn-sm dataView" >  -->
                                                <!--    <i class="fa fa-eye"></i> &nbsp; View details-->
                                            <!--</a>-->

                                            @if(request()->segment(1) == "manage-logins")
                                                @if($d->status == 1)
                                                    <a href="{{ url('login/status/'.$d->id.'/0') }}" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-close"></i> &nbsp;  Deactivate
                                                    </a>
                                                @else
                                                    <a href="{{ url('login/status/'.$d->id.'/1') }}" class="btn btn-success btn-sm">
                                                        <i class="fa fa-check"></i> &nbsp;  Activate
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    {{-- {{ $data->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@stop


@section('modal')
    <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://via.placeholder.com/300" style="width:100% !important;" class="img-fluid" alt="User Image">
                        </div>
                        <div class="col-md-6">
                            <table id="tableData" border="0" class="table"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">

    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#datatable', {
            pageLength: 5,
            lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
        });

        $(".dataView").on("click", function (event) {
            event.preventDefault();

            var url = "";
            var method = "POST";
            var userId = $(this).data("userId");

            $.ajax({
                url: url,
                type: method,
                data: { id:userId },
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.errors) {
                        var msg = Object.keys(response.errors)[0];
                            msg = response.errors[msg];

                        $.each(response.errors, function (field, message) {
                            var ff = field.replace(/\./g, "-");

                            $("#error-edit-" + ff).text(message[0]);
                            $("#error-" + ff).text(message[0]);
                        });

                        toastr.error(msg);
                    } else if (response.success) {
                        var msg = Object.keys(response.data)[0];

                        $.each(response.data, function (field, message) {
                            var ff = field.replace(/\./g, "-");

                            $("#error-edit-" + ff).text(message[0]);
                            $("#error-" + ff).text(message[0]);
                        });
                    }
                },
                error: function (err) {
                    toastr.info("Error! Please Contact Admin.");
                },
            });
        });
    </script>
@stop

