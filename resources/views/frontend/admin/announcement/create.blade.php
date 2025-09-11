@extends('frontend.layout.main')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}

    @endsection

@section('content')
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="pb-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Add Announcement</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item active">Add Announcement</li>
                    </ol>
                </div>
            </div>

            <!-- General Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Announcement !!</h5>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form data-action="{{route('announcement.store')}}" data-method="POST"  class="needs-validation" id="ajax-form" novalidate>
                                        @csrf
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Title (English)</label>
                                            <input type="text" id="simpleinput" class="form-control" name="eng_title" placeholder="Title (English)" value="{{old('eng_title')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="english-content" class="form-label">Content (English) <span class="text-danger">*</span></label>
                                            {{-- <div id="editor">{!! old('eng_content')!!}</div> --}}
                                            <textarea id="editor" class="" name="eng_content" required>{!! old('eng_content') !!}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-email" class="form-label">Title (Hindi)</label>
                                            <input type="text" id="example-title" name="hin_title" class="form-control" placeholder="Title (Hindi)" value="{{old('hin_title')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="english-content" class="form-label">Content (Hindi) <span class="text-danger">*</span></label>
                                            {{-- <div id="editor">{!! old('hin_content')!!}</div> --}}
                                            <textarea id="editor" class="" name="hin_content" required>{!! old('hin_content') !!}</textarea>
                                        </div>
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Show on View</legend>
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
                                            <button class="btn btn-primary" type="submit">Add Announcement</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
@endsection

@section('js')
<script src="{{ asset('assets/js/libs/tinymce/7.6.0/tinymce.min.js') }}"></script>
<script>
    // Initialize TinyMCE
    tinymce.init({
        selector: '#editor', // Target the textarea
        height: 300, // Set the height of the editor
        plugins: 'lists link image table code', // Include additional plugins
        branding: false,
        promotion: false,
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code', // Customize the toolbar
    });
</script>

    <script>
        //  Form Submit script
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
                setTimeout(() => {
                    window.location.href = "/admin/announcement";
                }, 3000);
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

