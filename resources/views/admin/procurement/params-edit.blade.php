<div class="x_panel">
    <div class="x_title">
        <!--<button id="addData" class="btn btn-success btn-md pull-right" > + Add More</button>-->
        <h5 style="font-weight:550;">Procurement Program</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <br>
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th style="width:8%;"># S.No.</th>
                    <th style="width: 25%">Work Program</th>
                    <th>Weightage</th>
                    <th>Days</th>
                    <th>Planned Date</th>
                    <th>Actual Date</th>
                </tr>
            </thead>

            <tbody id="tableData">
                @if(count($params) > 0 )
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    @php
                        $actualdateHas = "readonly";
                    @endphp

                    @foreach($params as $key => $param)
                        @php
                            $actualDate  = $project->paramsValues[$key]['actual_date'] ? date('Y-m-d', strtotime($project->paramsValues[$key]['actual_date'])) : '';
                            $plannedDate = $project->paramsValues[$key]['planned_date'] ? date('Y-m-d', strtotime($project->paramsValues[$key]['planned_date'])) : '';
                            $readonly    = $actualDate ? 'readonly ' : ' ';
                        @endphp

                        <tr id="tr{{ $key }}" >
                            <td>{{ $key + 1 }}</td>
                            <th>
                                <input type='hidden' name='procurement_id' value='{{ $data->id }}' required />
                                <input type="hidden" name="project_id" value="{{ $project->id }}" />
                                <input type="hidden" name="id" value="{{ $param->id }}" />
                                <input type="text"  minlength="1"  maxlength="200" name="name"  class="form-control input-readonly" {{ $actualdateHas }} value="{{ $param->name ?? '' }}"  />
                            </th>
                            <th>
                                <input type="text" name="weight"  value="{{ $project->paramsValues[$key]['weight'] ?? '' }}" class="number-input form-control" {{ $readonly }} required />
                            </th>
                            <td>
                                <input type="number" min="1" name="days" data-key="{{ $key + 1 }}" id="days{{ $key + 1 }}" class="form-control days" value="{{ $param->days ?? '' }}" readonly />
                            </td>
                            <td>
                                <input type="date" name="planned_date"  value="{{ $plannedDate ?? '' }}" class="form-control" readonly required />
                            </td>
                            <td>
                                <input type="date" name="actual_date"  value="{{ $actualDate ?? '' }}" class="form-control"{{ $readonly }}required />
                            </td>
                        </tr>
                    @endforeach

                    <span id="addInputs"></span>

                    <tfoot></tfoot>
                @else
                    <tr class="tr-remove" >
                        <td colspan="9">
                            <center>NO DATA FOUND</center>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <br>

        <h5 style="font-weight:550;">Procurement Bid Document</h5>

        @if(isset($data->media))
            <a onClick="openPDF('{{ url('images/bid_document/'.$data->media->name ?? '') }}')"  class="btn btn-md btn-primary" href="#">View Document</a>
            <a download class="btn btn-md btn-danger" href="{{ url('images/bid_document/'.$data->media->name ?? '') }}">Download Document</a>
        @else
            <h5 class="text-center">
                <b>Procurement Bid Document upload in progress..</b>
            </h5>
        @endif

        <br>

        <h5 style="font-weight:550;">Procurement Pre Bid Document</h5>
        @if(isset($data->media2))
            <a onClick="openPDF('{{ url('images/pre_bid_document/'.$data->media2->name ?? '') }}')"  class="btn btn-md btn-primary" href="#">View Document</a>
            <a download class="btn btn-md btn-danger" href="{{ url('images/pre_bid_document/'.$data->media2->name ?? '') }}">Download Document</a>
        @else
            <h5 class="text-center"><b>Procurement Pre Bid Document upload in progress..</b></h5>
        @endif
        <br>

        <div style="display:none;">
            <input type="hidden" id="Number" name="number" value="1" />
        </div>
    </div>
</div>
