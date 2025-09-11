@extends('frontend.layout.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{asset('frontend/admin/assets/libs/dropzone/dropzone.min.css')}}">
<script src="{{asset('frontend/admin/assets/libs/dropzone/dropzone.min.js')}}"></script>

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="pb-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Add Page</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Add Page</li>
                    </ol>
                </div>
            </div>

            <!-- General Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Page</h5>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <form data-action="{{route('navigation.store')}}" data-method="POST"  class="needs-validation" id="ajax-form" novalidate>
                                <div class="row">
                                    <div class="col-lg-6">
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
                                            <label for="simpleinput" class="form-label">Link / Slug (If you leave it blank, it will be generated automatically.) </label>
                                            <input type="text" id="simpleinput" class="form-control" name="slug" placeholder="Link / Slug " value="{{old('slug')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="example-email" class="form-label">Parent Link</label>
                                            <select class="form-select">
                                                <option value="" selected >None</option>
                                                @if(isset($navigation))
                                                @foreach($navigation as $nav)
                                                <option value="{{$nav->id}}">{{$nav->eng_title}}</option>
                                                @endforeach
                                                @endif
                                            </select>
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
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-2">
                                             <!-- Dropzone Single File Upload -->
                                            <div class="card-body p-2">
                                                <label for="fileUpload" class="form-label">Banners Image</label>
                                                <div id="fileUpload" class="dropzone"></div>
                                                <div class="invalid-feedback" id="featured_image_error">The featured image is required.</div>
                                            </div>
                                        </div>
                                      </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <script>
        Dropzone.autoDiscover = false; // Prevent automatic Dropzone discovery

        let uploadedFilePaths = []; // To store the paths of uploaded files

        const myDropzone = new Dropzone("#fileUpload", {
            url: "{{ route('upload.file') }}", // Separate route for file upload
            maxFiles: 6, // Allow up to 6 files
            maxFilesize: 2, // Max file size in MB
            acceptedFiles: "image/*", // Accept only image files
            addRemoveLinks: true, // Add remove file link
            dictRemoveFile: "Remove", // Custom remove link text
            dictDefaultMessage: "Drop files here to upload (Max: 6)", // Custom message
            paramName: "images[]", // Name of the file input (array)

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token to request headers
            },

            init: function () {
                this.on("success", function (file, response) {
                    // Store the uploaded file path
                    if (response.filePath) {
                        uploadedFilePaths.push('storage/' + response.filePath);
                    }
                    console.log("File uploaded successfully:", response);

                    // Remove validation errors when a file is successfully uploaded
                    $('#fileUpload').removeClass('is-invalid');
                    $('#featured_image_error').hide();
                });

                this.on("error", function (file, response) {
                    console.error("File upload failed:", response);
                });

                this.on("maxfilesexceeded", function (file) {
                    this.removeFile(file); // Remove excess file
                    alert("You can upload a maximum of 6 files."); // Notify the user
                });
            },

            removedfile: function (file) {
                const filePath = file.upload && file.upload.filename
                    ? 'storage/' + file.upload.filename
                    : null;

                if (filePath) {
                    // Remove the file path from the uploaded file paths array
                    uploadedFilePaths = uploadedFilePaths.filter(path => path !== filePath);

                    // Send AJAX request to delete the file from the server
                    $.ajax({
                        url: "{{ route('file.delete') }}", // Endpoint to handle file deletion
                        type: "POST",
                        data: {
                            filePath: filePath,
                            _token: "{{ csrf_token() }}" // Include CSRF token
                        },
                        success: function (response) {
                            console.log("File removed successfully:", response);
                        },
                        error: function (error) {
                            console.error("Error removing file:", error);
                        }
                    });
                }

                // Remove the file preview from Dropzone
                const previewElement = file.previewElement;
                if (previewElement != null) {
                    previewElement.parentNode.removeChild(previewElement);
                }

                // Add validation error if no files are uploaded
                if (uploadedFilePaths.length === 0) {
                    $('#fileUpload').addClass('is-invalid');
                    $('#featured_image_error').show();
                }
            }
        });

        // Handle form submission
        $(document).on('submit', '#ajax-form', function (e) {
            e.preventDefault(); // Prevent default form submission

            const form = $(this);
            const actionUrl = form.data('action');
            const method = form.data('method');
            const formData = form.serializeArray(); // Collect form data

            // Check if files are uploaded before form submission
            if (uploadedFilePaths.length === 0) {
                $('#fileUpload').addClass('is-invalid'); // Add error class to Dropzone
                $('#featured_image_error').show(); // Show validation error message
                return; // Stop form submission if validation fails
            }

            // Append uploaded file paths to form data
            formData.push({ name: "images", value: JSON.stringify(uploadedFilePaths) });

            $.ajax({
                url: actionUrl,
                type: method,
                data: formData,
                success: function (response) {
                    // Show success toaster
                    toastr.success(response.message, "Success");
                    // Redirect or perform other actions
                    window.location.href = "/tournaments";
                },
                error: function (xhr) {
                    if (xhr.status === 422) { // Validation error
                        const errors = xhr.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid'); // Clear previous errors

                        // Loop through errors and display them
                        $.each(errors, function (key, value) {
                            const input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove();
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        });

                        // Show validation error toaster
                        toastr.error("Please check the highlighted fields and try again.", "Validation Error");
                    } else {
                        // Show general error toaster
                        toastr.error("Something went wrong. Please try again.", "Error");
                    }
                }
            });
        });
    </script>

@endsection
