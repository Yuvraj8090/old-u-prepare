@extends('layouts.admin')

@section('content')
    <style>
        .custom-form-control {
            width: 350px;
            height: 40px;
            padding: 10px;
            margin-right: 5px;
            border-radius: 5px;
        }

        #datatable_wrapper .dt-layout-row:first-child {
            zoom: 125%;
        }

        .col-md-2,
        .col-md-4 {
            margin-bottom: 10px;
        }

        table .prona {
            display: inline-block;
            overflow: hidden;
            max-width: 320px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>
                Edit Component: <span class="cen">{{ isset($comp) ? $comp->name : $cpiu->name }}</span>
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"></ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="x_title">
                    <h4>Edit Component</h4>
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <form class="col-md-6 ajax-form" data-method="POST" data-action="{{ route(isset($cpiu) ? 'mis.dashboard.pd.component.piu.save' : 'mis.dashboard.pd.component.save') }}" autocomplete="off">
                            @csrf
                            @isset($pius)
                                <div class="form-group">
                                    <label>PIU Name</label>
                                    <select name="piu" class="form-control">
                                        @foreach ($pius as $piu)
                                            <option value="{{ $piu->id }}" @selected($piu->id == $cpiu->id) _inr="{{ $piu->amt_inr }}" _usd="{{ $piu->amt_usd }}">{{ $piu->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="comp_id" value="{{ $comp->id }}">
                                <div class="form-group">
                                    <label>Component Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $comp->name }}">
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Amount in USD</label>
                                    <input type="text" class="form-control text-right" name="amt_usd" value="{{ isset($comp) ? $comp->amt_usd : $cpiu->amt_usd }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Amount in INR</label>
                                    <input type="text" class="form-control text-right" name="amt_inr" value="{{ isset($comp) ? $comp->amt_inr : $cpiu->amt_inr }}">
                                </div>
                            </div>

                            <div class="form-group mt-4 text-right">
                                <button class="btn btn-success">Update Component{{ isset($cpiu) ? ' PIU' : '' }}</button>
                            </div>
                        </form>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        @isset($cpiu)
            $('select[name="piu"]').on('change', function(e) {
                $('input[name="amt_inr"]').val($('option:selected', this).attr('_inr'));
                $('input[name="amt_usd"]').val($('option:selected', this).attr('_usd'));

                $('.cen').text($('option:selected', this).text());
            });
        @endisset
    </script>
@endsection
