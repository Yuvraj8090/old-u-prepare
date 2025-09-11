@extends('frontend.layout.main')
@section('content')

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="pb-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Announcements</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item active">Announcements</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Announcements</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="navigation-table" class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Title (English)</th>
                                            <th>Title (Hindi)</th>
                                            <th>Show</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>
    <script>
        let navigationTable = $('#navigation-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("announcement.data") }}',
            columns: [
                { data: 'eng_title', name: 'eng_title' },
                { data: 'hin_title', name: 'hin_title' },
                { data: 'show', name: 'show' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        function DeleteUser(url) {
            swal({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (response) {
                            navigationTable.ajax.reload(); // Reload the table
                            swal("Deleted!", "Record has been deleted.", "success");
                        },
                        error: function (error) {
                            swal("Error!", "Unable to delete the record.", "error");
                        }
                    });
                }
            });
        }
    </script>
@endsection
