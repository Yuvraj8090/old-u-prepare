<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">PROCUREMENT PROGRAM  </h5>
            <div class="clearfix"></div>
    </div>

    <div class="x_content">
            
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
                    @if(isset($params))
                        @if(count($params) > 0 )

                            @foreach($params as $key => $param)
                            @php
                                $plannedDate  = $param->planned_date ? date('d-m-Y',strtotime($param->planned_date)) : '';
                                $actualDate  = $param->actual_date ? date('d-m-Y',strtotime($param->actual_date)) : '';
                            @endphp
                            
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <th> <input type="text"  name="data[{{$key}}][name]"  class="form-control" value="{{ $param->name ?? '' }}" disabled /> </th>
                                <td> <input type="text"  class="form-control days" value="{{ $param->days ?? '' }}"  disabled /> </td>
                                <th> <input type="text"   class="number-input form-control" value="{{ $param->weight ?? '' }}"  disabled />   </th>
                                <td>  <input type="text"  class="form-control" value="{{ $plannedDate }}"  disabled /> </td>
                                <td>  <input type="text"  class="form-control" value="{{ $actualDate }}"  disabled /> </td>
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
                    @endif
                </tbody>
            </table>
            
            
            <h5 style="font-weight:550;"> Procurement Bid Document </h5><hr>
            <br>
            
            
            @if(isset($defineProject->media->name))
            <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ url('images/bid_document/'.$defineProject->media->name ?? '') }}')" href="javascript:void(0)">View Document</a>
            <a download class="btn btn-md btn-danger" href="{{ url('images/bid_document/'.$defineProject->media->name ?? '') }}">Download Document</a>
            @elseif(isset($data->defineProject->media->name))
            <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ url('images/bid_document/'.$data->defineProject->media->name ?? '') }}')" 
            href="javascript:void(0)">View Document</a>
            <a download class="btn btn-md btn-danger" href="{{ url('images/bid_document/'.$data->defineProject->media->name ?? '') }}">Download Document</a>
            @elseif(isset($data->project->defineProject->media->name))
            <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ url('images/bid_document/'.$data->project->defineProject->media->name ?? '') }}')"; href="javascript:void(0)">View Document</a>
            <a download class="btn btn-md btn-danger" href="{{ url('images/bid_document/'.$data->project->defineProject->media->name ?? '') }}">Download Document</a>
            @else
            <h5 class="text-center"><b>Procurement Bid Document upload in progress..</b></h5>
            @endif
            
            <h5 style="font-weight:550;">   Pre Bid Minutes Document </h5><hr>
            @if(isset($defineProject->media2->name))
                <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ url('images/pre_bid_document/'.$defineProject->media2->name ?? '') }}')" href="javascript:void(0)">View Document</a>
                <a download class="btn btn-md btn-danger" href="{{ url('images/pre_bid_document/'.$defineProject->media2->name ?? '') }}">Download Document</a>
            @elseif(isset($data->defineProject->media2->name))

               <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ url('images/pre_bid_document/'.$data->defineProject->media2->name ?? "") }}')" 
               href="javascript:void(0)">View Document</a>
               <a download class="btn btn-md btn-danger" href="{{ url('images/pre_bid_document/'.$data->defineProject->media2->name ?? '') }}">Download Document</a>

            @elseif(isset($data->project->defineProject->media2->name))
                <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ url('images/pre_bid_document/'.$data->project->defineProject->media2->name ?? "") }}')" 
                    href="javascript:void(0)">View Document</a>
                <a download class="btn btn-md btn-danger" href="{{ url('images/pre_bid_document/'.$data->project->defineProject->media2->name ?? "") }}">Download Document</a>
            @else
            <h5 class="text-center"><b>Procurement Pre Bid Minutes Document upload in progress..</b></h5>
            @endif
            
            
            
                
        <br>
    </div>
</div>