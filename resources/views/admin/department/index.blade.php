@extends('layouts.admin')

@section('header_styles')
    <style>
        .btn-custom{
            padding:10px 3% !important;
        }
        .outerbox{
            margin:10px;
            padding:20px 35px;
            border-radius:10px;
            max-width:23% !important;
        }
        table thead th {
            vertical-align: middle !important;
        }
        tbody tr td.gr-no {
            width: 100px;
        }
        tbody tr td.action {
            width: 200px;
        }
    </style>
@endsection

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>Grievances</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Role & Permission</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="data x_panel">
        <div class="x_content">
            <a class="btn btn-success float-right" href="{{ route('department.create') }}">Add New Department</a>
            <table id="datatable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if($departments)
                        @forelse($departments as $key => $department)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $department->name}}</td>
                                <td>
                                    @if($department->roles)
                                        @foreach($department->roles as $perm)
                                        <span class="badge badge-success">{{ $perm->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('department.edit', $department->id) }}">Edit</a>

                                    <button class="btn btn-sm btn-danger delp" data-id="{{ $department->id }}">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Data Found</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>

            {{-- $data->links() --}}
        </div>
    </section>
@stop

<!-- code changes added on 9 feb  -->
@section('script')
    <script>
        $(document).ready(function() {
            $('.btn.delp').on('click', function(e) {
                let $btn = $(this);
                e.preventDefault();

                // On Click Callback
                let occb = ()=> {
                    let fd = newFormData();
                        fd.append('department', Number($btn.attr('data-id')));

                    let pm = {
                        url:  '{{ route('department.delete') }}',
                        data: fd
                    }

                    let bs = () => busy(1);

                    let cb = (resp)=> {
                        $btn.closest('tr').slideUp('normal', function() {
                            $btn.remove();
                        });
                    }

                    ajaxify(pm, bs, cb, 'PATCH');
                }

                alertBox('err', {
                    text: 'Are you sure to delete this Role?',
                    heading: 'Danger!'
                }, occb);
            })
        });
    </script>
@stop
