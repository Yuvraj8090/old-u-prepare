<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">PROCUREMENT</h5>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-md-12">
                <table class=" table tbl table-striped  ">
                    <tr>
                        <td>
                            <h5 style="font-weight:550;">
                                Procurement Method:
                                <span class="h3">{{ $defineProject->method_of_procurement?? '' }}</span>
                            </h5>
                            <h5 style="font-weight:550;">
                                Earnest Money Deposit (EMD):
                                <span class="h3">{{ $defineProject->earnest_money_deposit ?? '' }}</span>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                Tender Fee:
                                <span class="h3">{{ $defineProject->bid_fee ?? '' }}</span>
                            </h5>
                        </td>
                        <td>
                            @if(isset($defineProject->media->name))
                                <a target="_blank" onClick="openPDF('{{ url('images/bid_document/'.$defineProject->media->name ?? '') }}')" href="javascript:void(0)" class=" btn btn-md btn-success h3">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    View Bid Document
                                </a>
                            @else
                                <h5 class="text-center">
                                    <b>Procurement Bid Document upload in progress..</b>
                                </h5>
                            @endif
                        </td>
                        <td>
                            @if(isset($defineProject->media->name))
                                <a download href="{{ url('images/bid_document/'.$defineProject->media->name ?? '') }}" class="btn btn-md btn-primary h3">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    Download
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5 style="font-weight:550;">
                                Bid Validity (In Days)
                                <span class="h3">{{ $defineProject->bid_validity ?? '' }}</span>
                            </h5>

                            <h5 style="font-weight:550;">
                                Bid Completion Period  (In Days)
                                <span class="h3">{{ $defineProject->bid_completion_days ?? '' }}</span>
                            </h5>
                        </td>
                        <td>
                            @if(isset($defineProject->media2->name))
                                <a target="_blank" onClick="openPDF('{{ url('images/pre_bid_document/'.$defineProject->media2->name ?? '') }}')" href="javascript:void(0)"class=" btn btn-md btn-success h3">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    View Pre Bid Minutes
                                </a>
                            @else
                                <h5 class="text-center">
                                    <b>Procurement Bid Document upload in progress..</b>
                                </h5>
                            @endif
                        </td>
                        <td>
                            @if(isset($defineProject->media2->name))
                                <a download href="{{ url('images/pre_bid_document/'.$defineProject->media2->name ?? '') }}" class="btn btn-md btn-primary h3">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    Download
                                </a>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class=" table tbl table-striped ">

                    <tr>
                        <th># S.No.</th>
                        <th style="width: 25%">Work Program</th>
                        <th>Days</th>
                        <th>Weightage</th>
                        <th>Planned Date</th>
                        <th>Actual Date</th>
                    </tr>

                    @if(isset($params))
                        @if(count($params) > 0 )
                            @foreach($params as $key => $param)
                                @php
                                    $plannedDate = $param->planned_date ? date('d-m-Y', strtotime($param->planned_date)) : '';
                                    $actualDate  = $param->actual_date ? date('d-m-Y', strtotime($param->actual_date)) : '';
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <th>
                                        <span style="font-weight:550;" class="h3">  {{ $param->name ?? '' }} </span>
                                    </th>
                                    <td>
                                        <span class="h3">  {{ $param->days ?? '' }} </span>
                                    </td>
                                    <th>
                                        <span class="h3">  {{ $param->weight ?? '' }} </span>
                                    </th>
                                    <td>
                                        <span class="h3">  {{ $plannedDate ?? '' }} </span>
                                    </td>
                                    <td>
                                        <span style="font-weight:550;"  class="h3">  {{ $actualDate ?? '' }} </span>
                                    </td>
                                </tr>
                                @endforeach
                                <tfooter>

                                </tfooter>
                                @else
                                <tr>
                                    <td colspan="9">
                                        <center>NO DATA FOUND</center>
                                    </td>
                                </tr>
                            @endif
                        @endif
                </table>
            </div>
        </div>
    </div>
</div>
