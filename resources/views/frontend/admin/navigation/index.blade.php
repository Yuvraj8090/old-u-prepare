@extends('frontend.layout.main')
@section('content')

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="pb-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Navigation</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Navigation</li>
                    </ol>
                </div>
            </div>

            <!-- General Form -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Menu Link</h5>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form data-action="{{route('navigation.store')}}" data-method="POST"  class="needs-validation" id="ajax-form" novalidate>
                                        @csrf
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Title (English)</label>
                                            <input type="text" id="simpleinput" class="form-control" name="eng_title" placeholder="Title (English)" value="{{old('eng_title')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="example-email" class="form-label">Title (Hindi)</label>
                                            <input type="text" id="example-title" name="hin_title" class="form-control" placeholder="Title (Hindi)" value="{{old('hin_title')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Link</label>
                                            <input type="text" id="simpleinput" class="form-control" name="link" placeholder="Link" value="{{old('link')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="example-email" class="form-label">Order</label>
                                            <input type="number" id="example-title" name="order" class="form-control" placeholder="Order" value="{{old('order')}}">
                                        </div>
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Show on Menu</legend>
                                            <div class="col-sm-10 d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="show" id="gridRadios1" value="1" checked="">
                                                    <label class="form-check-label" for="gridRadios1">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="show" id="gridRadios2" value="0">
                                                    <label class="form-check-label" for="gridRadios2">No</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div>
                                            <button class="btn btn-primary btn-sm">Add Menu Link</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Navigation</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="navigation-table" class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Title (English)</th>
                                            <th>Title (Hindi)</th>
                                            <th>Link</th>
                                            <th>Order</th>
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
            ajax: '{{ route("navigation.data") }}',
            columns: [
                { data: 'eng_title', name: 'eng_title' },
                { data: 'hin_title', name: 'hin_title' },
                { data: 'link', name: 'link' },
                { data: 'order', name: 'order' },
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
