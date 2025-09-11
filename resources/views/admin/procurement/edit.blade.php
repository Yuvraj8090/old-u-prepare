
<!-- updated at 28/07/2025-->
@extends('layouts.admin')

@section('content')
<div style="width:100%;" class="page-title">
            <div style="width:100%;" class="title_left">
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <h4>Edit Project Procurement {{ $project->name }} | Project Id- {{ $project->number }} </h4>
            </div>
        </div>

        <div class="clearfix"></div>

        <nav aria-label="breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/procurement') }}">Procurement</a></li>
                    <li class="breadcrumb-item active">Edit Procurement</li>
                </ol>
          
        </nav>
   

    <div class="row">
        <div class="col-md-8">
            @include('admin.project.components.new.project-view-details', ['data' => $project])
            
                

        </div>
<div  class="col-md-4">
    @include('admin.project.components.new.approve-dec-hpc-details', ['data' => $project])
</div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">PROCUREMENT DETAILS</h5>
                </div>
                
                <div class="card-body">
                    <form autocomplete="off" data-method="POST" 
                          data-action="{{ url('procurement/update/'.$data->id) }}" 
                          id="ajax-form" class="form-horizontal">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Method of procurement</label>
                                    <select class="form-control" name="method_of_procurement" disabled>
                                        <option value="">SELECT</option>
                                        @if(count($project->category->methods_of_procurement) > 0)
                                            @foreach($project->category->methods_of_procurement as $pre)
                                                <option value="{{ $pre }}" @if($pre == $data->method_of_procurement) selected @endif>
                                                    {{ $pre }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-danger" id="error-method_of_procurement"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Type of procurement</label>
                                    <select class="form-control" name="type_of_procurement" disabled>
                                        <option value="{{ $data->type_of_procurement }}" selected>
                                            {{ !is_null($data->type_of_procurement) ? App\Helpers\Assistant::procureTypes($data->type_of_procurement) : 'N/A' }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Date of Publication of Bid (NIT)</label>
                                    <input type="date" name="bid_pub_date" class="form-control" 
                                           value="{{ $data->bid_pub_date ? date('Y-m-d', strtotime($data->bid_pub_date)) : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Attach Document of Publication</label>
                                    <input type="file" name="bid_pub_doc" class="form-control-file">
                                    @if($data->bid_pub_doc)
                                        <div class="mt-2">
                                            <a class="btn btn-sm btn-outline-success mr-2" 
                                               href="{{ asset('images/project/bid_pub_docs/' . $data->bid_pub_doc->name) }}" 
                                               target="_blank">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <a class="btn btn-sm btn-outline-primary" 
                                               href="{{ asset('images/project/bid_pub_docs/' . $data->bid_pub_doc->name) }}" 
                                               download>
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tender Fee <span class="text-dark">(<span id="ten-price">₹{{ number_format($data->bid_fee, 2) }}</span>)</span></label>
                                    <input type="number" class="form-control" name="bid_fee" 
                                           value="{{ $data->bid_fee }}" placeholder="Enter bid fee">
                                    <small class="text-danger" id="error-bid_fee"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Earnest Money Deposit <span class="text-dark">(<span id="emd-price">₹{{ number_format($data->earnest_money_deposit, 2) }}</span>)</span></label>
                                    <input type="number" class="form-control" name="earnest_money_deposit" 
                                           value="{{ $data->earnest_money_deposit }}" placeholder="Enter EMD amount">
                                    <small class="text-danger" id="error-earnest_money_deposit"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Bid Validity (In Days)</label>
                                    <input type="number" class="form-control" min="0" 
                                           value="{{ $data->bid_validity }}" name="bid_validity">
                                    <small class="text-danger" id="error-bid_validity"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Validity of EMD (In Days)</label>
                                    <input type="number" class="form-control" min="0" 
                                           value="{{ $data->bid_completion_days }}" name="bid_completion_days">
                                    <small class="text-danger" id="error-bid_completion_days"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <span class="loader" id="loader" style="display: none;"></span>
                                Update Procurement
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(count($params) > 0)
                @include('admin.procurement.params-edit')
            @else
                <div class="card mt-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">PROCUREMENT PROGRAM</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="mb-4">Work Program not created! Please add one.</p>
                        <a href="{{ url('procurement/program/'.$data->id) }}" class="btn btn-danger">
                            <i class="fa fa-plus mr-2"></i> Create Work Program
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        // Format currency display
        const currencyFormatter = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 2
        });

        // Update displayed amounts when inputs change
        $('input[name="bid_fee"]').on('input', function() {
            $('#ten-price').text(currencyFormatter.format($(this).val() || 0));
        });

        $('input[name="earnest_money_deposit"]').on('input', function() {
            $('#emd-price').text(currencyFormatter.format($(this).val() || 0));
        });

        // Form submission handling
        $('#ajax-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('[type="submit"]');
            const loader = form.find('#loader');
            
            submitBtn.prop('disabled', true);
            loader.show();
            
            $.ajax({
                url: form.data('action'),
                type: form.data('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.errors) {
                        $.each(response.errors, function(field, message) {
                            $('#error-' + field).text(message[0]);
                        });
                    } else if (response.success) {
                        toastr.success(response.message);
                        if(response.url) {
                            setTimeout(() => window.location = response.url, 1000);
                        }
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false);
                    loader.hide();
                }
            });
        });

        // Previous button functionality
        $('.previousButton').on('click', function() {
            window.history.back();
        });
    });
</script>
@stop
