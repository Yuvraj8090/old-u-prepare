@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            <h4>Action Task Report</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Action Task Report</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row w-100">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title">
                    <h4>Task Report Entry Form</h4>
                </div>

                <div class="x_content">
                    <form class="ajax-form" data-action="{{ route('atr.entry.save') }}" data-method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Entry Month</label>
                                <select name="month" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach($months as $key => $month)
                                        <option value="{{ $key + 1 }}" @selected(date('n') == $key + 1)>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Entry Year</label>
                                <select name="year" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @php $cy = intval(date('Y')); @endphp
                                    @for($i = $cy - 10;$i <= $cy;$i++)
                                        <option value="{{ $i }}" @selected($cy == $i)>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Responsible Organization</label>
                                <select name="org" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach($orgs as $org)
                                        <option value="{{ $org->slug }}">{{ $org->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Component</label>
                                <select name="comp" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach($comps as $comp)
                                        <option value="{{ $comp->slug }}">{{ $comp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sub-Component</label>
                                <select name="s_comp" class="form-control d-none" disabled>
                                    <option value="">Not Applicable</option>
                                </select>
                                <div class="form-control disabled lin">
                                    <small>Please Select a Component...</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Task Type</label>
                                <select name="task" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach($tasks as $task)
                                        <option vlaue="{{ $task->slug }}">{{ $task->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->slug }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Action Item</label>
                                <textarea name="action_item" rows="4" class="form-control" required></textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Commited Date</label>
                                <input type="date" name="date_com" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Revised Date</label>
                                <input type="date" name="date_rev" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Actual Completion Date</label>
                                <input type="date" name="date_act" class="form-control">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remark" rows="4" class="form-control"></textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Next Step</label>
                                <textarea name="next" rows="4" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button class="btn px-5 btn-lg btn-success">Submit ATR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-2"></div>
    </div>
@endsection

@section('script')
    <script>
        let $scsb = $('select[name="s_comp"]');

        $('select[name="comp"]').on('change', function(e) {
            let $cv = $(this).val();
            let $sl = $scsb.parent().find('.lin');

            if($cv.length) {
                let fd = newFormData();
                    fd.append('slug', $cv);

                let pm = {
                    url: '{{ route("atr.entry.component.sub") }}',
                    data: fd
                }

                let bs = ()=> {
                    $scsb.addClass('d-none');
                    $sl.removeClass('d-none');
                    $sl.html('Fetching Sub-Components...');
                    $scsb.attr('disabled', '');
                }

                let cb = (resp)=> {
                    $scsb.html(`<option value="">Please Select</option>`);

                    if(resp.data.length) {
                        resp.data.forEach((item) => {
                            $scsb.append(`<option value="${item.slug}">${item.name}</option>`)
                        });

                        $sl.addClass('d-none');
                        $scsb.removeClass('d-none');
                        $scsb.removeAttr('disabled');
                    } else {
                        $scsb.addClass('d-none');
                        $sl.removeClass('d-none');
                        $sl.html('Not Applicable for Selected Component.');
                        $scsb.attr('disabled', '');
                    }
                }

                ajaxify(pm, bs, cb);
            } else {
                $scsb.addClass('d-none');
                $sl.removeClass('d-none');
                $sl.html('Kindly Select a Component First...');
                $scsb.attr('disabled', '');
            }
        })
    </script>
@endsection
