@extends('public.layout.base')

@section('page_title'){{ __('Grievance Status') }}@endsection

@section('header_styles')
    <style>
        .head h1 {
            font-size: 1.8rem;
        }

        .head+hr {
            border: 2px solid var(--color-tyell);
            opacity: 1;
        }

        th {
            font-size: 0.8em;
            background: #95dfe6 !important;
        }
    </style>
@endsection

@section('page_content')
    <section class="grievance-register pt-5">
        <div class="head container-xxl">
            <div class="row">
                <div class="col">
                    <h1 class="text-uppercase fw-bold text-dark m-0">Grievance Status</h1>
                </div>
            </div>
        </div>
        <hr class="my-2" />

        <div class="container-xl pt-3">
            <form method="POST" action="{{ route('public.grievance.details') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-2">
                        <p class="m-0 fw-bold text-dark">Grievance No.</p>
                        <p class="m-0 fw-bold text-dark">(शिकायत क्रमांक)</p>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="grievance_no" class="form-control @error('grievance_no'){{ __('is-invalid') }}@enderror" />
                        @error('grievance_no')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <span class="info text-secondary">Please Enter Your Grievance No. starting with GR. e.g. GR00</span>
                    </div>
                    <div class="col-lg-4"></div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-primary px-5">Submit</button>
                    </div>
                </div>
            </form>

            @error('grm')
                @php $grievance = (object) $errors->all() @endphp
                @php $grievance = App\Models\Grievance::with(['logs', 'action'])->find($grievance->id) @endphp
                @if($grievance)
                <div class="row mt-3">
                    <div class="col-12">
                        <table class="table table-bordered text-center">
                            <thead>
                                <th>Grievance Id</th>
                                <th>Name</th>
                                <th>Mobile No.</th>
                                <th>District</th>
                                <th>Block</th>
                                <th>Office Unit</th>
                                <th>Grievance Current Status</th>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>{{ $grievance->ref_id }}</td>
                                    <td>{{ $grievance->registrant }}</td>
                                    <td>{{ $grievance->phone ?? '—' }}</td>
                                    <td>{{ $grievance->district ? $grievance->district->name : '—' }}</td>
                                    <td>{{ $grievance->block ? $grievance->block->name : '—' }}</td>
                                    <td>{{ $grievance->department ? $grievance->department->name : '—' }}</td>
                                    <td>{{ $grievance->status ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <style>
                    .py-8 {
                        padding-top: 4.5rem!important
                        padding-bottom: 4.5rem!important;
                    }

                    .bsb-timeline-1 {
                        --bsb-tl-color: #cfe3ff;
                        --bsb-tl-circle-size: 18px;
                        --bsb-tl-circle-color: #0d6ef6;
                        --bsb-tl-circle-offset: 9px
                    }

                    .bsb-timeline-1 .timeline {
                        margin: 0;
                        padding: 0;
                        position: relative;
                        list-style: none;
                    }

                    .bsb-timeline-1 .timeline::after {
                        top: 0;
                        left: 0;
                        width: 2px;
                        bottom: 0;
                        content: "";
                        position: absolute;
                        margin-left: -1px;
                        background-color: var(--bsb-tl-color);
                    }

                    .bsb-timeline-1 .timeline > .timeline-item {
                        margin: 0;
                        padding: 0;
                        position: relative
                    }

                    .bsb-timeline-1 .timeline > .timeline-item::before {
                        top: 0;
                        left: calc(var(--bsb-tl-circle-offset)*-1);
                        width: var(--bsb-tl-circle-size);
                        height: var(--bsb-tl-circle-size);
                        z-index: 1;
                        content: "";
                        position: absolute;
                        border-radius: 50%;
                        background-color: var(--bsb-tl-circle-color);
                    }

                    .bsb-timeline-1 .timeline > .timeline-item .timeline-body {
                        margin: 0;
                        padding: 0;
                        position: relative
                    }

                    .bsb-timeline-1 .timeline > .timeline-item .timeline-content {
                        padding: 0 0 2.5rem 2.5rem
                    }

                    .bsb-timeline-1 .timeline > .timeline-item:last-child .timeline-content {
                        padding-bottom: 0
                    }

                    @media(min-width: 576px) {
                        .py-sm-8 {
                            padding-top: 4.5rem!important
                            padding-bottom: 4.5rem!important;
                        }
                    }

                    @media(min-width: 768px) {
                        .py-md-8 {
                            padding-top: 4.5rem!important
                            padding-bottom: 4.5rem!important;
                        }

                        .bsb-timeline-1 .timeline > .timeline-item .timeline-content {
                            padding-bottom: 3rem
                        }
                    }

                    @media(min-width: 992px) {
                        .py-lg-8 {
                            padding-top: 4.5rem!important
                            padding-bottom: 4.5rem!important;
                        }
                    }

                    @media(min-width: 1200px) {
                        .py-xl-8 {
                            padding-top: 4.5rem!important
                            padding-bottom: 4.5rem!important;
                        }
                    }

                    @media(min-width: 1400px) {
                        .py-xxl-8 {
                            padding-top: 4.5rem!important
                            padding-bottom: 4.5rem!important;
                        }
                    }
                </style>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">Grievance Timeline</div>

                            <div class="card-body">
                                <section class="bsb-timeline-1 py-5 py-xl-8">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-10 col-md-8 col-xl-6">

                                                <ul class="timeline">
                                                    @foreach($grievance->logs as $key => $log)
                                                        <li class="timeline-item border-0">
                                                            <div class="timeline-body">
                                                                <div class="timeline-content">
                                                                    <div class="card border-0">
                                                                        <div class="card-body p-0">
                                                                            <h5 class="card-subtitle text-secondary mb-1">
                                                                                <span>
                                                                                    {{ $log->created_at->format('d M, Y') }} <small>{{ $log->created_at->format('H:m a') }}</small>
                                                                                </span>

                                                                                @if($log->type && $grievance->action && ($grievance->action->pact_doc || $grievance->action->fact_doc))
                                                                                    <a href="{{ asset($log->type == 'pact' ? $grievance->action->pact_doc : $grievance->action->fact_doc) }}" class="ml-3 btn btn-sm btn-info" target="_blank">View Report Document</a>
                                                                                @endif
                                                                            </h5>

                                                                            <h2 class="card-title mb-3">{{ $log->title }}</h2>

                                                                            {{-- @if($key) --}}
                                                                                {{-- @if($log->forward_to) --}}
                                                                                    {{-- <h2 class="card-title mb-3">Grievance Forwarded to {{ $log->forwarded_user->name }}</h2> --}}
                                                                                {{-- @elseif($log->is_revert) --}}
                                                                                    {{-- <h2 class="card-title mb-3">Grievance is Reverted to GRM Nodal Officer</h2> --}}
                                                                                {{-- @elseif($log->type && $grievance->action && ($grievance->action->pact || $grievance->action->fact)) --}}
                                                                                    {{-- <h2 class="card-title mb-3">{{ $log->type == 'pact' ? 'Preliminary' : 'Final' }} Action Taken Report Submitted</h2> --}}
                                                                                {{-- @endif --}}
                                                                            {{-- @elseif(!$key) --}}
                                                                                {{-- <h2 class="card-title mb-3">{{ $log->remark }}</h2> --}}
                                                                            {{-- @endif --}}

                                                                            @if($key)
                                                                            <p class="card-text m-0">{{ $log->remark }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @enderror
        </div>
    </section>
@endsection
