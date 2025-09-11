@extends('layouts.admin')

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>Grievance Details</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('mis.grievance.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('mis.grievance.manage') }}">Manage Grievances</a></li>
                        <li class="breadcrumb-item active"><a href="#">Grievance Details</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;">Grievance Details</h5>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" value="{{ $grievance->name ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Address</label>
                        <input type="text" class="form-control" value="{{ $grievance->address ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email ID</label>
                        <input type="text" class="form-control" value="{{ $grievance->email ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Mobile Number</label>
                        <input type="text" class="form-control" value="{{ $grievance->phone ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">District</label>
                        <input type="text" class="form-control" value="{{ $grievance->district->name ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Block</label>
                        <input type="text" class="form-control" value="{{ $grievance->block->name ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Village</label>
                        <input type="text" class="form-control" value="{{ $grievance->village ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Grievance Related To</label>
                        <input type="text" class="form-control" value="{{ App\Helpers\DummyData::typology($grievance->typology) ?? '—' }}" readonly>
                    </div>
                </div>
                @if($grievance->typology == 'other')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Related To Other Value</label>
                            <input type="text" class="form-control" value="{{ $grievance->typo_other ?? '—' }}" readonly>
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Grievance Category</label>
                            <input type="text" class="form-control" value="{{ $grievance->category_id ? $grievance->category->name : 'Other' }}" readonly>
                        </div>
                    </div>

                    @if($grievance->category_id)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Grievance Subcategory</label>
                                <input type="text" class="form-control" value="{{ $grievance->subcategory_id ? $grievance->subcategory->name : 'Other' }}" readonly>
                            </div>
                        </div>
                    @endif

                    @if(!$grievance->category_id || !$grievance->subcategory_id)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Grievance {{ $grievance->category_id ? 'Subcategory' : 'Category' }} Other Value</label>
                                <input type="text" class="form-control" value="{{ $grievance->category->name ?? '—' }}" readonly>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Grievance Description</label>
                        <textarea rows="5" class="form-control" readonly>{{ $grievance->description }}</textarea>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Filed on behalf of someone else: <b>{{ $grievance->on_behalf }}</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Have consent from survivor to share this information?: <b>{{ $grievance->consent }}</b></label>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
