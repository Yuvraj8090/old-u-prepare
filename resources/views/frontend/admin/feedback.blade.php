@extends('frontend.layout.main')
@section('content')

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="pb-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Feedback</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item active">Feedback</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Feedback</h5>
                        </div>
                        <div class="card-body">
                            <table id="navigation-table" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
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
            ajax: '{{ route("feedback.data") }}',
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'type', name: 'type' },
                { data: 'subject', name: 'subject' },
                { data: 'message', name: 'message' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
    </script>
    <script>
        $(document).on('submit', '#ajax-form', function (e) {
        e.preventDefault();
    
        const form = $(this);
        const actionUrl = form.data('action');
        const method = form.data('method');
        const formData = form.serialize();
    
          $.ajax({
              url: actionUrl,
              type: method,
              data: formData,
              success: function (response) {
                  toastr.success(response.message, "Success");
                  window.location.href = "/admin/navigation";
              },
              error: function (xhr) {
                  if (xhr.status === 422) {
                      const errors = xhr.responseJSON.errors;
                      $('.is-invalid').removeClass('is-invalid'); 
                      $.each(errors, function (key, value) {
                          const input = $(`[name="${key}"]`);
                          input.addClass('is-invalid');
                          input.next('.invalid-feedback').remove();
                          input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                      });
                      toastr.error("Please check the highlighted fields and try again.", "Validation Error");
                  } else {
                      toastr.error("Something went wrong. Please try again.", "Error");
                  }
              }
          });
      });
    </script>
@endsection
