@extends('frontend.layout.main')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('frontend/admin/assets/libs/dropzone/dropzone.min.css')}}"> 
    <script src="{{asset('frontend/admin/assets/libs/dropzone/dropzone.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
@endsection

@section('content')
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="pb-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Edit Page</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Edit Page</li>
                    </ol>
                </div>
            </div>

            <!-- General Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit Page</h5>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <form data-action="{{ url('admin/page/' . $page->id) }}"  data-method="PATCH" class="needs-validation" id="ajax-form" novalidate>
                                <div class="row">
                                    <div class="col-lg-6">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Menu Title (English) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="eng_title" placeholder="Enter Menu Title in English" value="{{ old('eng_title', $page->eng_title) }}" required>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Link / Slug <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="slug" placeholder="Link / Slug" value="{{ old('slug', $page->slug) }}" required readonly>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Banner Title (English) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="banner_eng_title" placeholder="Enter Banner Title in English" value="{{ old('banner_eng_title', $page->banner_eng_title) }}" required>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="english-content" class="form-label">Banner Description (English) <span class="text-danger">*</span></label>
                                            <div id="banner-english-editor">{!! old('banner_eng_description', $page->banner_eng_description) !!}</div>
                                            <textarea id="banner-english-editor-content" class="d-none" name="banner_eng_description" required>{!! old('banner_eng_description', $page->banner_eng_description) !!}</textarea>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Page Title (English) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="page_eng_title" placeholder="Enter Page Title in English" value="{{ old('page_eng_title', $page->page_eng_title) }}" required>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="english-content" class="form-label">Page Content (English) <span class="text-danger">*</span></label>
                                            <div id="english-editor">{!! old('eng_content', $page->eng_content) !!}</div>
                                            <textarea id="english-editor-content" class="d-none" name="eng_content" required>{!! old('eng_content', $page->eng_content) !!}</textarea>
                                        </div>
                            
                                        <div class="mb-2">
                                            <label for="fileUpload" class="form-label">Page Image <span class="text-danger">*</span></label>
                                            <!-- Page Image Upload -->
                                            <div id="pageImageUpload" class="dropzone"></div>
                                            <div id="pageImageError" class="invalid-feedback" style="display: none;">Page image is required.</div>
                                        </div>
                            
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Show on Menu</legend>
                                            <div class="col-sm-10 d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="show" id="gridRadios1" value="1" checked>
                                                    <label class="form-check-label" for="gridRadios1">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="show" id="gridRadios2" value="0">
                                                    <label class="form-check-label" for="gridRadios2">No</label>
                                                </div>
                                            </div>
                                        </fieldset>
                            
                                        <div>
                                            <button class="btn btn-primary">Page Update</button>
                                        </div>
                                    </div>
                            
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="example-title" class="form-label">Title (Hindi) <span class="text-danger">*</span></label>
                                            <input type="text" id="example-title" name="hin_title" class="form-control" placeholder="Enter Menu Title in Hindi" value="{{ old('hin_title', $page->hin_title) }}" required>
                                        </div>
                            
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="parent-link" class="form-label">Parent Link</label>
                                                <select id="parent-link" class="form-select">
                                                    <option value="" selected>None</option>
                                                    @if(isset($navigation))
                                                    @foreach($navigation as $nav)
                                                    <option value="{{ $nav->id }}" @if($page->parent_menu == $nav->id) selected @endif>{{ $nav->eng_title }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                            
                                            <div class="col-6 mb-3">
                                                <label for="order" class="form-label">Order</label>
                                                <input type="number" id="order" name="order" class="form-control" placeholder="Order" value="{{ old('order', $page->order) }}">
                                            </div>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Banner Title (Hindi) <span class="text-danger">*</span></label>
                                            <input type="text" id="simpleinput" class="form-control" name="banner_hin_title" placeholder="Enter Banner Title in Hindi" value="{{ old('banner_hin_title', $page->banner_hin_title) }}" required>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="hindi-content" class="form-label">Banner Description (Hindi) <span class="text-danger">*</span></label>
                                            <div id="banner-hindi-editor">{!! old('banner_hin_description', $page->banner_hin_description) !!}</div>
                                            <textarea id="banner-hindi-editor-content" class="d-none" name="banner_hin_description" required>{!! old('banner_hin_description', $page->banner_hin_description) !!}</textarea>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Page Title (Hindi) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="page_hin_title" placeholder="Enter Page Title in Hindi" value="{{ old('page_hin_title', $page->page_hin_title) }}" required>
                                        </div>
                            
                                        <div class="mb-3">
                                            <label for="hindi-content" class="form-label">Page Content (Hindi) <span class="text-danger">*</span></label>
                                            <div id="hindi-editor">{!! old('hin_content', $page->hin_content) !!}</div>
                                            <textarea id="hindi-editor-content" class="d-none" name="hin_content" required>{!! old('hin_content', $page->hin_content) !!}</textarea>
                                        </div>
                            
                                        <div class="mb-2">
                                            <label for="bannerImageUpload" class="form-label">Banners Image <span class="text-danger">*</span></label>
                                            <!-- Banner Image Upload -->
                                            <div id="bannerImageUpload" class="dropzone"></div>
                                            <div id="bannerImageError" class="invalid-feedback" style="display: none;">Banner image is required.</div>
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
@endsection

@section('js')
    <script>
        const englishEditor = new Quill('#english-editor', {
            theme: 'snow'
        });
        const hindiEditor = new Quill('#hindi-editor', {
            theme: 'snow'
        });
        const bannerEnglishEditor = new Quill('#banner-english-editor', {
            theme: 'snow'
        });
        const bannerHindiEditor = new Quill('#banner-hindi-editor', {
            theme: 'snow'
        });

        englishEditor.on('text-change', function () {
            document.querySelector('#english-editor-content').value = englishEditor.root.innerHTML;
        });
        hindiEditor.on('text-change', function () {
            document.querySelector('#hindi-editor-content').value = hindiEditor.root.innerHTML;
        });
        bannerEnglishEditor.on('text-change', function () {
            document.querySelector('#banner-english-editor-content').value = bannerEnglishEditor.root.innerHTML;
        });
        bannerHindiEditor.on('text-change', function () {
            document.querySelector('#banner-hindi-editor-content').value = bannerHindiEditor.root.innerHTML;
        });
    </script>


<script>
    Dropzone.autoDiscover = false;

    let uploadedPagePath = "{{ $page->image ? $page->image : null }}"; 
    let uploadedBannerPaths = "{{ $page->banner ? $page->banner : null }}";
    
    // Page Image Dropzone (allows only one file)
    const pageImageDropzone = new Dropzone("#pageImageUpload", {
        url: "{{ route('upload.file') }}",
        maxFiles: 1,  // Only one file for page image
        maxFilesize: 2,  // Max file size 2MB
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictRemoveFile: "Remove",
        dictDefaultMessage: "Drop page image here to upload",
        paramName: "page_image",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

        init: function () {
            const dzInstance = this;
            
            // If there's an uploaded page image, show it in the dropzone
            if (uploadedPagePath) {
                const mockFile = { name: "Current Page Image", size: 12345 };
                dzInstance.emit("addedfile", mockFile);
                dzInstance.emit("thumbnail", mockFile, `/${uploadedPagePath}`);
                dzInstance.emit("complete", mockFile);
                dzInstance.files.push(mockFile);
            }

            this.on("success", function (file, response) {
                if (uploadedPagePath) {
                    deleteFile(uploadedPagePath);  // Delete old page image
                }
                uploadedPagePath = 'storage/' + response.filePaths.page_image;
                    console.log(uploadedPagePath); // Save new page image path
                $('#pageImageUpload').removeClass('is-invalid');
                $('#pageImageError').hide();
            });

            this.on("removedfile", function (file) {
                const index = uploadedPagePath.indexOf(file.upload.path);
                if (index !== -1) {
                    deleteFile(uploadedPagePath);
                    uploadedPagePath = '';
                }
                $('#pageImageUpload').removeClass('is-invalid');
                $('#pageImageError').show();
            });

            this.on("addedfile", function (file) {
                if (dzInstance.files.length > 1) {
                    dzInstance.removeFile(file);  // Remove the file if more than 1 is added
                    toastr.warning("You can only upload one page image.");
                }
            });
        }
    });

    // Banner Image Dropzone (allows only one file)
    const bannerImageDropzone = new Dropzone("#bannerImageUpload", {
        url: "{{ route('upload.file') }}",
        maxFiles: 1,  // Only one file for banner image
        maxFilesize: 2,  // Max file size 2MB
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictRemoveFile: "Remove",
        dictDefaultMessage: "Drop banner image here to upload",
        paramName: "banner_images",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

        init: function () {
            const dzInstance = this;

            // If there are uploaded banner images, show them in the dropzone
            if (uploadedBannerPaths) {
                const mockFile = { name: "Current Banner Image", size: 12345 };
                dzInstance.emit("addedfile", mockFile);
                dzInstance.emit("thumbnail", mockFile, `/${uploadedBannerPaths}`);
                dzInstance.emit("complete", mockFile);
                dzInstance.files.push(mockFile);
            }

            this.on("success", function (file, response) {
                // If there was a previously uploaded banner image, delete it
                if (uploadedBannerPaths) {
                    deleteFile(uploadedBannerPaths);  // Delete old banner image
                }

                uploadedBannerPaths = 'storage/' + response.filePaths.banner_images;  // Save new banner image path
                console.log(uploadedBannerPaths);
                $('#bannerImageUpload').removeClass('is-invalid');
                $('#bannerImageError').hide();
            });

            this.on("removedfile", function (file) {
                if (uploadedBannerPaths && uploadedBannerPaths.indexOf(file.upload.path) !== -1) {
                    deleteFile(uploadedBannerPaths);  // Delete banner image on remove
                    uploadedBannerPaths = '';
                }
                $('#bannerImageUpload').removeClass('is-invalid');
                $('#bannerImageError').show();
            });

            // Restrict to a single banner image, remove the last one if more than one is added
            this.on("addedfile", function (file) {
                if (dzInstance.files.length > 1) {
                    let lastFile = dzInstance.files[dzInstance.files.length - 2]; // Get last file
                    dzInstance.removeFile(lastFile);  // Remove the previous banner image
                    toastr.warning("You can only upload one banner image.");
                }
            });
        }
    });

    // Form Submission
    $(document).on('submit', '#ajax-form', function (e) {
        e.preventDefault();

        console.log(uploadedBannerPaths);

        if (!uploadedBannerPaths) {
            toastr.error("Please upload at least one banner image.");
            return;
        }
        if (!uploadedPagePath) {
            toastr.error("Please upload a page image.");
            return;
        }

        const form = $(this);
        const actionUrl = form.data('action');
        const method = form.data('method');
        const formData = form.serializeArray();

        formData.push({ name: "banner", value: uploadedBannerPaths });
        formData.push({ name: "image", value: uploadedPagePath });

        $.ajax({
            url: actionUrl,
            type: method,
            data: formData,
            success: function (response) {
                toastr.success(response.message, "Success");
                location.reload();
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
                    toastr.error("Validation Error. Check fields.", "Error");
                } else {
                    toastr.error("Something went wrong.", "Error");
                }
            }
        });
    });
</script>


@endsection
