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
                    <h4 class="fs-18 fw-semibold m-0">Edit Administration</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home </a></li>
                        <li class="breadcrumb-item active">Edit Administration</li>
                    </ol>
                </div>
            </div>

            <!-- General Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit Administration !!</h5>
                        </div>
                        <div class="card-body">
                            <form data-action="{{route('administration')}}" data-method="POST"  class="needs-validation" id="ajax-form" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <div class="card-body p-2">
                                                <label for="uploadedCmProfile" class="form-label">Chief Minister Profile</label>
                                                <div id="uploadedCmProfile" class="dropzone"></div>
                                                <div class="invalid-feedback" id="cm_profile_error">The Chief Minister Profile is required.</div>
                                            </div>
                                            <div class="mb-3">                  
                                                <label for="simpleinput" class="form-label">Chief Minister Name</label>
                                                <input type="text" id="simpleinput" class="form-control" name="cm_name" placeholder="Enter Chief Minister Name.." value="{{old('cm_name',$data->cm_name)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">   
                                            <div class="card-body p-2">
                                                <label for="uploadedGovernorProfile" class="form-label">Governor Profile</label>
                                                <div id="uploadedGovernorProfile" class="dropzone"></div>
                                                <div class="invalid-feedback" id="governor_profile_error">The Governor Profile is required.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="example-email" class="form-label">Governor Name </label>
                                                <input type="text" id="example-title" name="governor_name" class="form-control" placeholder="Enter Chief Governor Name.." value="{{old('governor_name',$data->governor_name)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <div class="card-body p-2">
                                                <label for="uploadedChiefSecretaryProfile" class="form-label">Chief Secretary Profile</label>
                                                <div id="uploadedChiefSecretaryProfile" class="dropzone"></div>
                                                <div class="invalid-feedback" id="chief_secretary_profile_error">The Chief Secretary Profile is required.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Chief Secretary Name</label>
                                                <input type="text" id="simpleinput" class="form-control" name="chief_secretary_name" placeholder="Enter Chief Secretary Name.." value="{{old('chief_secretary_name',$data->chief_secretary_name)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <div class="card-body p-2">
                                                <label for="uploadedSecretaryProfile" class="form-label">Secretary Profile</label>
                                                <div id="uploadedSecretaryProfile" class="dropzone"></div>
                                                <div class="invalid-feedback" id="secretary_profile_error">The Secretary Profile is required.</div>
                                            </div>                   
                                            <div class="mb-3">
                                                <label for="example-email" class="form-label">Secretary Name (Disaster Management)</label>
                                                <input type="text" id="example-title" name="secretary_name" class="form-control" placeholder="Enter Secretary Name (Disaster Management).." value="{{old('secretary_name',$data->secretary_name)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary">Edit Administration</button>
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
    {{-- <script>

        let uploadedCmProfile = "{{ $data->cm_profile ? $data->cm_profile : null }}"; 
        let uploadedGovernorProfile = "{{ $data->governor_profile ? $data->governor_profile : null }}";
        let uploadedChiefSecretaryProfile = "{{ $data->chief_secretary_profile ? $data->chief_secretary_profile : null }}";
        let uploadedSecretaryProfile = "{{ $data->secretary_profile ? $data->secretary_profile : null }}";

        Dropzone.autoDiscover = false;
        initializeDropzone("uploadedCmProfile", "cm_profile");
        initializeDropzone("uploadedGovernorProfile", "governor_profile");
        initializeDropzone("uploadedChiefSecretaryProfile", "chief_secretary_profile");
        initializeDropzone("uploadedSecretaryProfile", "secretary_profile");

        function initializeDropzone(id, paramName) {
            return new Dropzone(`#${id}`, {
                url: "{{ route('upload.file') }}",
                maxFiles: 1,
                maxFilesize: 2, 
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                dictDefaultMessage: `Drop ${paramName} image here to upload`,
                paramName: paramName,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                init: function () {
                    const dzInstance = this;
                    this.on("success", function (file, response) {
                        console.log(`${paramName} uploaded:`, response);
                        $(`#${id}`).removeClass('is-invalid');
                        $(`#${id}_error`).hide();
                    });

                    this.on("removedfile", function (file) {
                        $.ajax({
                            url: "{{ route('file.delete') }}",
                            type: "POST",
                            data: { filePath: file.upload.path, _token: "{{ csrf_token() }}" },
                            success: function (response) {
                                console.log(`${paramName} file removed:`, response);
                            },
                            error: function (error) {
                                console.error(`Error removing ${paramName} file:`, error);
                            }
                        });
                    });
                }
            });
        }


        $(document).on('submit', '#ajax-form', function (e) {
            e.preventDefault();

            const form = $(this);
            const actionUrl = form.data('action');
            const method = form.data('method');
            const formData = form.serializeArray(); 

       
            formData.push({[
                name: "cm_profile", value: JSON.stringify(uploadedCmProfile) 
                name: "governor_profile", value: JSON.stringify(uploadedGovernorProfile) 
                name: "chief_secretary_profile", value: JSON.stringify(uploadedChiefSecretaryProfile) 
                name: "secretary_profile", value: JSON.stringify(uploadedSecretaryProfile) 

            ]});

            // Make AJAX request
            $.ajax({
                url: actionUrl,
                type: method,
                data: formData,
                success: function (response) {
                    // Display success message
                    toastr.success(response.message || "Form submitted successfully.", "Success");
                    // Redirect if a redirect URL is provided
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        // Optional: Refresh the page or reset the form if no redirect is provided
                        form[0].reset();
                        uploadedFilePaths = [];
                        $('#fileUpload').removeClass('is-invalid');
                        $('#featured_image_error').hide();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        // Validation error
                        const errors = xhr.responseJSON?.errors || {};
                        $('.is-invalid').removeClass('is-invalid'); // Clear previous error states
                        
                        // Loop through errors and display feedback
                        $.each(errors, function (key, value) {
                            const input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove(); // Remove old feedback
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`); // Add new feedback
                        });

                        // Show validation error message
                        toastr.error("Please check the highlighted fields and try again.", "Validation Error");
                    } else {
                        // General error handling
                        toastr.error("An error occurred. Please try again later.", "Error");
                        console.error(xhr.responseText || "Unknown error");
                    }
                }
            });
        });






    </script> --}}

    {{-- <script>
        let uploadedCmProfile = "{{ $data->cm_profile ? $data->cm_profile : null }}";
        let uploadedGovernorProfile = "{{ $data->governor_profile ? $data->governor_profile : null }}";
        let uploadedChiefSecretaryProfile = "{{ $data->chief_secretary_profile ? $data->chief_secretary_profile : null }}";
        let uploadedSecretaryProfile = "{{ $data->secretary_profile ? $data->secretary_profile : null }}";
    
        Dropzone.autoDiscover = false;
    
        initializeDropzone("uploadedCmProfile", "cm_profile", uploadedCmProfile);
        initializeDropzone("uploadedGovernorProfile", "governor_profile", uploadedGovernorProfile);
        initializeDropzone("uploadedChiefSecretaryProfile", "chief_secretary_profile", uploadedChiefSecretaryProfile);
        initializeDropzone("uploadedSecretaryProfile", "secretary_profile", uploadedSecretaryProfile);
    
        function initializeDropzone(id, paramName, existingFile) {
            const dropzoneElement = `#${id}`;
            new Dropzone(dropzoneElement, {
                url: "{{ route('administration.upload.file') }}",
                maxFiles: 1,
                maxFilesize: 2, // Maximum file size in MB
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                dictDefaultMessage: `Drop ${paramName} image here to upload`,
                paramName: paramName,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                init: function () {
                    const dropzone = this;
    
                    // Handle existing file
                    if (existingFile) {
                        const mockFile = { name: existingFile.split('/').pop(), size: 0 };
                        dropzone.emit("addedfile", mockFile);
                        dropzone.emit("thumbnail", mockFile, existingFile);
                        dropzone.emit("complete", mockFile);
                        dropzone.files.push(mockFile);
                    }
    
                    this.on("success", function (file, response) {
                        window[`uploaded${capitalizeFirstLetter(paramName)}Profile`] = response.filePaths[paramName];
                        existingFile = response.filePaths[paramName];
                        $(`#${id}`).removeClass('is-invalid');
                        $(`#${id}_error`).hide();
                    });
    
                    this.on("removedfile", function () {
                        $.ajax({
                            url: "{{ route('administration.file.delete') }}",
                            type: "POST",
                            data: {
                                filePath: window[`uploaded${capitalizeFirstLetter(paramName)}Profile`],
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                window[`uploaded${capitalizeFirstLetter(paramName)}Profile`] = null;
                            },
                            error: function (error) {
                                console.error(`Error removing ${paramName} file:`, error);
                            }
                        });
                    });
                },
            });
        }
    
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    
        $(document).on('submit', '#ajax-form', function (e) {
            e.preventDefault();
    
            const form = $(this);
            const actionUrl = form.data('action');
            const method = form.data('method');
            const formData = form.serializeArray();

            formData.push({ name: "cm_profile", value: uploadedCmProfile });
            formData.push({ name: "governor_profile", value: uploadedGovernorProfile });
            formData.push({ name: "chief_secretary_profile", value: uploadedChiefSecretaryProfile });
            formData.push({ name: "secretary_profile", value: uploadedSecretaryProfile });
    

            if (!uploadedCmProfile) $('#cm_profile_error').show();
            if (!uploadedGovernorProfile) $('#governor_profile_error').show();
            if (!uploadedChiefSecretaryProfile) $('#chief_secretary_profile_error').show();
            if (!uploadedSecretaryProfile) $('#secretary_profile_error').show();
 
            $.ajax({
                url: actionUrl,
                type: method,
                data: formData,
                success: function (response) {
                    toastr.success(response.message || "Form submitted successfully.", "Success");
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        form[0].reset();
                        $(".dropzone").each(function () {
                            Dropzone.forElement(this).removeAllFiles(true);
                        });
    
                        uploadedCmProfile = null;
                        uploadedGovernorProfile = null;
                        uploadedChiefSecretaryProfile = null;
                        uploadedSecretaryProfile = null;
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};
                        $('.is-invalid').removeClass('is-invalid');
                        $.each(errors, function (key, value) {
                            const input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove();
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        });
                        toastr.error("Please check the highlighted fields and try again.", "Validation Error");
                    } else {
                        toastr.error("An error occurred. Please try again later.", "Error");
                    }
                },
            });
        });
    </script> --}}
    

    <script>
        let uploadedCmProfile = "{{ $data->cm_profile ? $data->cm_profile : null }}";
        let uploadedGovernorProfile = "{{ $data->governor_profile ? $data->governor_profile : null }}";
        let uploadedChiefSecretaryProfile = "{{ $data->chief_secretary_profile ? $data->chief_secretary_profile : null }}";
        let uploadedSecretaryProfile = "{{ $data->secretary_profile ? $data->secretary_profile : null }}";
    
        Dropzone.autoDiscover = false;
    
        initializeDropzone("uploadedCmProfile", "cm_profile", uploadedCmProfile);
        initializeDropzone("uploadedGovernorProfile", "governor_profile", uploadedGovernorProfile);
        initializeDropzone("uploadedChiefSecretaryProfile", "chief_secretary_profile", uploadedChiefSecretaryProfile);
        initializeDropzone("uploadedSecretaryProfile", "secretary_profile", uploadedSecretaryProfile);
    
        function initializeDropzone(id, paramName, existingFile) {
            const dropzoneElement = `#${id}`;
            new Dropzone(dropzoneElement, {
                url: "{{ route('administration.upload.file') }}",
                maxFiles: 1,
                maxFilesize: 2, // Maximum file size in MB
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                dictDefaultMessage: `Drop ${paramName} image here to upload`,
                paramName: paramName,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                init: function () {
                    const dropzone = this;
    
                    // Handle existing file
                    if (existingFile) {
                        const mockFile = { name: existingFile.split('/').pop(), size: 0 };
                        dropzone.emit("addedfile", mockFile);
                        dropzone.emit("thumbnail", mockFile, existingFile);
                        dropzone.emit("complete", mockFile);
                        dropzone.files.push(mockFile);
                    }
    
                    this.on("success", function (file, response) {
                        const filePath = 'storage/' + response.filePaths[paramName];
                        existingFile = filePath;
                        $(`#${id}`).removeClass('is-invalid');
                        $(`#${id}_error`).hide();
                    });
    
                    this.on("removedfile", function () {
                        const filePath = window[`uploaded${capitalizeFirstLetter(paramName)}Profile`];
                        $.ajax({
                            url: "{{ route('administration.file.delete') }}",
                            type: "POST",
                            data: {
                                filePath: filePath,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function () {
                                window[`uploaded${capitalizeFirstLetter(paramName)}Profile`] = null;
                            },
                            error: function (error) {
                                console.error(`Error removing ${paramName} file:`, error);
                            },
                        });
                    });
                },
            });
        }
    
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    
        $(document).on('submit', '#ajax-form', function (e) {
            e.preventDefault();
    
            const form = $(this);
            const actionUrl = form.data('action');
            const method = form.data('method');
            const formData = new FormData(this);
    
            // Add uploaded file paths to form data
            formData.append("cm_profile", uploadedCmProfile || "");
            formData.append("governor_profile", uploadedGovernorProfile || "");
            formData.append("chief_secretary_profile", uploadedChiefSecretaryProfile || "");
            formData.append("secretary_profile", uploadedSecretaryProfile || "");
    
            // Make AJAX request
            $.ajax({
                url: actionUrl,
                type: method,
                data: formData,
                contentType: false, // Important for FormData
                processData: false, // Important for FormData
                success: function (response) {
                    toastr.success(response.message || "Form submitted successfully.", "Success");
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        // Reset only if form submission is successful
                        form[0].reset();
                        $(".dropzone").each(function () {
                            Dropzone.forElement(this).removeAllFiles(true);
                        });
    
                        // Reset uploaded file paths
                        uploadedCmProfile = null;
                        uploadedGovernorProfile = null;
                        uploadedChiefSecretaryProfile = null;
                        uploadedSecretaryProfile = null;
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};
                        $('.is-invalid').removeClass('is-invalid');
                        $.each(errors, function (key, value) {
                            const input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove();
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        });
                        toastr.error("Please check the highlighted fields and try again.", "Validation Error");
                    } else {
                        toastr.error("An error occurred. Please try again later.", "Error");
                    }
                },
            });
        });
    </script>
@endsection

