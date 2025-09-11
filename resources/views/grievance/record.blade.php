@extends('layouts.admin')

@section('header_styles')
<style>
    .head h1 {
        font-size: 1.8rem;
    }

    .head+hr {
        border: 2px solid var(--color-tblue);
        opacity: 1;
    }

    label sup {
        color: rgba(var(--bs-danger-rgb));
    }

    .lh-1 * {
        line-height: 1;
    }

    .form-control.disabled {
        background-color: var(--bs-secondary-bg);
    }

    .form-control:focus,
    .form-control:focus {
        box-shadow: 0 0 0 .05rem rgba(13, 110, 253, .25)
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance:textfield;
    }
</style>
@endsection

@section('content')
    <section class="breadcrumbs">
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Record Grievance</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mis.grievance.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Record Grievance</a></li>
                </ol>
            </nav>
        </div>
    </section>

    @error('success')
        <section class="x_panel fssm">
            <div class="x_content">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <span>
                                    {!! $message !!}
                                </span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @enderror

    <section class="grievance-register p-0">
        <div class="x_panel">
            <div class="x_title">
                <h6>Fill the form below to record grievance</h6>
            </div>

            <div class="x_content">
                <div class="container">
                    <form method="POST" action="{{ route('mis.grievance.record.save') }}" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="full-name">Full Name</label>
                                <input type="text" id="full-name" class="form-control @error('full_name'){{ __('is-invalid') }}@enderror" name="full_name" value="{{ old('full_name') }}" placeholder="Grievance can be filed anonymously also">
                                @error('full_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address">Address</label>
                                <input type="text" id="address" class="form-control @error('address'){{ __('is-invalid') }}@enderror" name="address" value="{{ old('address') }}">
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">E-Mail ID</label>
                                <input type="email" name="email" id="email" class="form-control @error('email'){{ __('is-invalid') }}@enderror" value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone"><sup>*</sup>Mobile No.</label>
                                <input type="phone" name="phone" id="phone" class="form-control @error('phone'){{ __('is-invalid') }}@enderror" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district"><sup>*</sup>District</label>
                                <select name="district" id="district" class="form-control @error('district'){{ __('is-invalid') }}@enderror" required>
                                    <option value="">Kindly Choose...</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->slug }}" @selected(old('district') == $district->slug)>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="block"><sup>*</sup>Block</label>
                                <select name="block" id="block" class="form-control @error('block'){{ __('is-invalid') }}@enderror" _ov="{{ old('block') }}" required>
                                    <option value="">Kindly Choose...</option>
                                </select>
                                @error('block')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div class="form-control disabled d-none lin">
                                    <small>Loading Blocks for District...</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="village">Village</label>
                                <input name="village" id="village" class="form-control @error('village'){{ __('is-invalid') }}@enderror" value="{{ old('village') }}">
                                @error('village')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="mb-2 fw-bold">Grievance related to</label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label><sup>*</sup>Typology</label>
                                        <select name="typology" class="form-control @error('typology'){{ __('is-invalid') }}@enderror">
                                            <option value="">Kindly Choose...</option>
                                            @foreach($typology as $typo)
                                                <option value="{{ $typo->slug }}" @selected($typo->slug == old('typology'))>{{ $typo->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('typology')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div @class(['col-md-4', 'mb-3', 'd-none'=> old('typology') == 'other'])>
                                        <label><sup>*</sup>Category</label>
                                        <select name="category" class="form-control @error('category'){{ __('is-invalid') }}@enderror" {{ old('typology') !== 'other' ? 'required' : '' }}>
                                            <option value="">Kindly Choose...</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->slug }}" @selected(old('category') == $category->slug)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div @class(['col-md-4', 'mb-3', 'd-none'=> old('typology') == 'other'])>
                                        <label><sup>*</sup>Sub-Category</label>
                                        <select name="subcategory" class="form-control @error('subcategory'){{ __('is-invalid') }}@enderror" _ov="{{ old('subcategory') }}" {{ old('typology') !== 'other' ? 'required' : '' }}>
                                            <option value="">Kindly Choose...</option>
                                        </select>
                                        @error('subcategory')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <div class="form-control disabled d-none lin">
                                            <small>Loading Subcategories...</small>
                                        </div>
                                    </div>
                                    <div @class(['col-md-8', 'mb-3', 'd-none'=> old('typology') !== 'other'])>
                                        <label for="typoth"><sup>*</sup><small>Please Specify</small></label>
                                        <input type="text" class="form-control @error('typo_other'){{ __('is-invalid') }}@enderror" id="typoth" name="typo_other" value="{{ old('typo_other') }}" {{ old('typology') == 'other' ? 'required' : '' }}>
                                        @error('typo_other')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div @class(['row', 'mb-3', 'd-none'=> (old('subcategory') !== 'other')])>
                                    <div class="col-12">
                                        <label><sup>*</sup>Please Specify</label>
                                        <input type="text" class="form-control @error('scat_other'){{ __('is-invalid') }}@enderror" value="{{ old('scat_other') }}" name="scat_other" @if(old('subcategory') == 'other'){{ __('required') }}@endif />
                                        @error('scat_other')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <label for="desc">Description</label>
                                <textarea name="description" id="desc" rows="5" class="form-control @error('description'){{ __('is-invalid') }}@enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <div class="row">
                                    <label for="doc" class="col-2 lh-1">
                                        <span class="d-block">Upload Document (If Any)</span>
                                        <span><small>(PDF, JPG, JPEG, Video)</small></span>
                                    </label>

                                    <div class="col-10">
                                        <input type="file" name="file" id="doc" class="form-control @error('file'){{ __('is-invalid') }}@enderror" accept="image/jpg,image/jpeg,application/pdf,video/mp4">
                                        @error('file')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 py-4">
                                <h5>
                                    <span class="fw-bold d-block">For Safety & Security Complaints</span>
                                    <span>
                                        <small>(e.g. verbal & physical harrassment, etc.)</small>
                                    </span>
                                </h5>
                            </div>

                            <div class="col-12 mb-3">
                                <span class="me-3">Are you filing on behalf of someone else?</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="behalf" value="yes" id="behalf1" required>
                                    <label class="form-check-label" for="behalf1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="behalf" value="no" id="behalf2" required>
                                    <label class="form-check-label" for="behalf2">No</label>
                                </div>
                            </div>

                            <div class="col-12 mb-5">
                                <span class="me-3">Do you have consent from survivor to share this information?</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="consent" value="yes" id="consent1" required>
                                    <label class="form-check-label" for="consent1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="consent" value="no" id="consent2" required>
                                    <label class="form-check-label" for="consent2">No</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-lg btn-primary">Record Grievance</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        (function() {
            let $cat  = $('select[name="category"]');
            let $otvi = $('input[name="scat_other"]');
            let $scat = $('select[name="subcategory"]');
            let $blks = $('select[name="block"]');
            let $dist = $('select[name="district"]');
            let $typo = $('select[name="typology"]');
            let $tyot = $('input[name="typo_other"]');

            function newFormData() {
                let fd = new FormData();
                    fd.append('_token', '{{ csrf_token() }}');

                return fd;
            }

            $dist.on('change', function() {
                updateSDDD($(this), $blks, '{{ route("grievance.get.blocks") }}', 0)
            });

            $cat.on('change', function() {
                updateSDDD($(this), $scat, '{{ route("grievance.get.scats") }}', 1)
            });

            $scat.on('change', function() {
                if($(this).val() == 'other') {
                    $otvi.closest('.row').removeClass('d-none');
                    $otvi.attr('required', 'required')
                }else {
                    $otvi.closest('.row').addClass('d-none');
                    $otvi.removeAttr('required');
                }
            });

            $typo.on('change', function() {
                if($(this).val() == 'other') {
                    $cat.removeAttr('required');
                    $scat.removeAttr('required');

                    // Set other field;
                    $cat.closest('div').addClass('d-none');
                    $scat.closest('div').addClass('d-none');

                    $tyot.closest('div').removeClass('d-none');
                    $tyot.attr('required', 'required');
                } else {
                    $cat.attr('required', 'required');
                    $scat.attr('required', 'required');

                    // Set other field;
                    $cat.closest('div').removeClass('d-none');
                    $scat.closest('div').removeClass('d-none');

                    $tyot.closest('div').addClass('d-none');
                    $tyot.removeAttr('required');
                }
            });

            if($cat.val().length) {
                $cat.trigger('change');
            }

            if($dist.val().length) {
                $dist.trigger('change');
            }

            function updateSDDD(el, tel, purl, act) {
                let fd = newFormData();
                fd.append('slug', el.val());

                let bs = () => {
                    tel.addClass('d-none');
                    tel.closest('div.mb-3').find('.lin').removeClass('d-none');
                }
                let pm = {
                    url: purl,
                    type: 'POST',
                    data: fd
                }
                let cb = (resp) => {
                    if(resp.data.length) {
                        let bshtm = '<option value="">Please Choose...</option>';
                        tel.html(bshtm);

                        resp.data.forEach(function(item, indx, arr) {
                            tel.append(`<option value="${item.slug}">${item.name}</option>`);
                        });

                        if(act) {
                            tel.append(`<option value="other">Any Other</option>`);
                        }

                        if(tel.attr('_ov').length) {
                            tel.val(tel.attr('_ov'));
                            tel.trigger('change');
                        }
                    }
                }
                let al = () => {
                    tel.removeClass('d-none');
                    tel.closest('div.mb-3').find('.lin').addClass('d-none');
                }

                ajaxify(pm, bs, cb, al)
            }

            function ajaxify(pm, bs, cb, al) {
                let err = 1;
                let msg = '';
                let rsp = null;

                $.ajax({
                    url: pm.url,
                    type: pm.type,
                    data: pm.data,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        bs()
                    },
                    success: function(resp) {
                        if(Number(resp.ok)) {
                            err = 0;
                            rsp = resp;
                        }

                        msg = resp.msg;
                    },
                    error: function(jqXHR, err) {
                        switch(jqXHR.status) {
                            case 0:
                                msg = 'Please check you network connection.';
                                break;
                            case 500:
                                msg = 'An error occurred on server.';
                                break;
                            default:
                                break;
                        }
                    }
                }).always(function() {
                    al()

                    if(err) {
                        alert(msg);
                    }else {
                        cb(rsp);
                    }
                })
            }
        })()
    </script>
@endsection
