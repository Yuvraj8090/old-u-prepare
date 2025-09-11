
<!--   // New Code added 7/feb/2024 -->
@extends('layouts.admin')

@section('content')

<div class="x_panel">
        <div class="x_title">
        <h5 style="font-weight:550;">PROJECT DETAILS</h5>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Project Name </label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $project->name }}" readonly />
                            <p class="error-data" id="error-name"></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Project Type </label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $project->category->name }}" readonly />
                            <p class="error-project" id="error-name"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label ">Project Number </label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $project->number }}" readonly />
                            <p class="error" id="error-number"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Estimate Value</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $project->estimate_budget }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">District</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $project->district->name }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Assign To</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $project->department->name }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</div>

<div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">APPROVAL DETAILS</h5>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label ">Project Approval Number </label>
                            <div class="">
                                <input type="text" class="form-control" value="{{ $project->approval_number }}" readonly >
                                <p class="error" id="error-approval_number"></p>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Project Approved By </label>
                            <div class=""> 
                                <input type="text" class="form-control" value="{{ $project->approved_by }}"  readonly >
                                <p class="error" id="error-approved_by"></p>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Govt. Approval Date </label>
                            <div class="">
                                <input type="text" class="form-control datepicker" value="{{ date('d-m-Y',strtotime($project->approval_date)) }}" readonly >
                                <p class="error" id="error-approval_date"></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
</div>

<div class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;">PROCUREMENT</h5>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br>

                <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label  ">Method of procurement </label>
                        <div class="">
                            <select class="form-control" name="method_of_procurement" readonly >
                                <option value="" >SELECT</option>
                            @if(count($project->category->methods_of_procurement) > 0)
                                @foreach($project->category->methods_of_procurement as $pre)
                                    <option value="{{$pre}}" @if($pre == $data->method_of_procurement ) selected @endif>{{ $pre  }}</option>
                                @endforeach
                            @endif
                            </select>
                            <p class="error" id="error-method_of_procurement"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                            <div class="form-group ">
                                <label class="control-label "> Assign To(PMU/PIU) </label>
                                <div class=" ">
                                    <select class="form-control" name="assign_project" readonly>
                                        <option value="">SELECT</option>
                                        @if(count($procurementLvlThree) > 0)
                                        @foreach($procurementLvlThree as $dd)
                                        <option value="{{$dd->id}}" @if($dd->id == $project->procure_level_3) selected @endif >{{ $dd->name }} ( UserName : {{ $dd->username }} )</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <p class="error" id="error-assign_project"></p>
                                </div>
                            </div>
                        </div>

                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label  ">Tender Fee </label>
                    <div class="">
                        <input type="number" class="form-control" name="bid_fee"  value="{{ $data->bid_fee }}" readonly  placeholder="BID fee..">
                        <p class="error" id="error-bid_fee"></p>
                    </div>
                </div>
                </div>

                <div class="col-md-6">

                <div class="form-group">
                    <label class="control-label  ">Earnest Money Deposit  </label>
                    <div class="">
                        <input type="number" class="form-control" name="earnest_money_deposit" value="{{ $data->earnest_money_deposit }}" readonly placeholder="Earnest money desposit...">
                        <p class="error" id="error-earnest_money_deposit"></p>
                    </div>
                </div>
                </div>

                    <div class="col-md-6">
                            <div class="form-group ">
                                <label class="control-label ">Bid Validity (In Days)</label>
                                <div class=" ">
                                    <input type="number" class="form-control" min="0" value="{{ $data->bid_validity }}" readonly name="bid_validity" >
                                    <p class="error" id="error-bid_validity"></p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="control-label ">Bid Completion Period  (In Days)</label>
                                <div class=" ">
                                    <input type="number" class="form-control" min="0"  value="{{ $data->bid_completion_days }}" readonly name="bid_completion_days">
                                    <p class="error" id="error-bid_completion_days"></p>
                                </div>
                            </div>
                        </div>
                
        </div>
</div>

</div>

<div class="x_panel">
        <div class="x_content">

            <div class="x_panel">
                <div class="x_title">
                    <h5 style="font-weight:550;">Work Program </h5>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <br>
                    <form autocomplete="off" data-method="POST" data-action="{{ url('procurement-project/update/'.$project->id) }}" class="ajax-form form-horizontal form-label-left">
                        @csrf

                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th># S.No.</th>
                                    <th style="width: 25%">Work Program</th>
                                    <th>Days</th>
                                    <th>Weightage</th>
                                    <th>Planned Date</th>
                                    <th>Actual Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($params) > 0 )

                                @foreach($params as $key => $param)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <th style="display:none;" ><input type="hiden" name="data[{{$key}}][id]" value="{{ $project->paramsValues[$key]['id'] ?? '' }}" /></th>
                                    <th> <input type="text" minlength="1" maxlength="200" name="data[{{$key}}][name]" class="form-control" value="{{ $param->name ?? '' }}" disabled /> </th>
                                    <td> <input type="number" min="1" name="data[{{$key}}][days]" data-key="{{ $key + 1 }}" id="days{{ $key + 1 }}" class="form-control days" value="{{ $param->days ?? '' }}" disabled  /> </td>
                                    <th> <input type="text" name="data[{{$key}}][weight]" value="{{ $project->paramsValues[$key]['weight'] ?? '' }}" class="number-input form-control" disabled /> </th>
                                    <td> <input type="text" name="data[{{$key}}][planned_date]" value="{{ $project->paramsValues[$key]['planned_date'] ?? '' }}" class="form-control" disabled /> </td>
                                    <td> <input type="text" name="data[{{$key}}][actual_date]" value="{{ $project->paramsValues[$key]['actual_date'] ?? '' }}" class="form-control datepicker" required /> </td>
                                </tr>
                                @endforeach
                                <tfooter>

                                </tfooter>
                                @else
                                <tr>
                                    <td colspan="9">
                                        <center> NO DATA FOUND </center>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12">
                                    <button id="submit-btn" type="submit" class="btn btn-success pull-right">
                                        <span class="loader" id="loader" style="display: none;"></span>
                                        Update Work Program
                                    </button>
                                </div>
                            </div>

                    </form>
                    <br>
                </div>
            </div>

        </div>
</div>
@stop


@section('script')
@if(false)
    <script>

            // $('#startDate').on('input',function(){

            //     var value = $(this).val();
            //     var plannedDate = $('#startDate').val();

            //     if(value != "" && plannedDate != ""){
            //         $('.days').removeAttr("readonly");
            //         $('.days').val('');
            //         console.log(value);
            //         console.log(plannedDate);
            //     }
                
            // });

            $('.days').on('input',function(){

                var value = $(this).val();
                var days = $(this).data('key');
                var previousDate =  '.date'+days;


                var previousDate = $(previousDate).val();
                var currentDate = new Date(previousDate).addDays(value);

                console

                var formattedDate = currentDate.getFullYear() + '-' + 
                    ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + 
                    ('0' + currentDate.getDate()).slice(-2);

                    
                console.log(value);
                console.log(days);
                console.log(previousDate);
                console.log(currentDate);

                
                // $(previousDate).val(formattedDate);

            });

            function weight(){

                    var total = 0;
                    var start = 1;
                    var end = $('#end').text();

                    $('.days').each(function() {
                        var value = $(this).val();
                        if (value) {
                            total += parseFloat(value);
                        }
                    });

                    for (var i = start; i <= end; i++) {
                        var days =  $('#days'+i).val();
                        var percentage = (days/total) * 100;
                        percentage = percentage.toFixed(2)
                        console.log(percentage);
                        $('#weight'+i).val(percentage);
                    }  
            }

    </script>
@endif
@stop

<!--   // New Code added 7/feb/2024 -->
