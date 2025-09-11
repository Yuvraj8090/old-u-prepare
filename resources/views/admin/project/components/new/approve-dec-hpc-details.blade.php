<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">APPROVAL DETAILS</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
            <div class="col-md-12">
                <table class="table tbl table-striped">
                    <tr>
                        <th>
                            <h5 style="font-weight:550;">
                                DEC Letter No:
                                <span class="h3">{{ $data->dec_approval_letter_number ?? '' }}</span>
                            </h6>
                            <h6 style="font-weight:550;">
                                On Dated:
                                <span class="h3">{{ date('d-m-Y', strtotime($data->dec_approval_date)) }}</span>
                            </h6>
                        </th>
                        <th>
                            @if(isset($data->dec_approval_doc))
                                <a onClick="openPDF('{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}')" href="#" class="btn btn-md btn-success h3">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    view
                                </a>
                            @else
                                <h5>Document not uploaded yet!</h5>
                            @endif
                        </th>
                        <th>
                            @if(isset($data->dec_approval_doc))
                                    <a download href="{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}" class="btn btn-md btn-primary h3">
                                        <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                        DEC
                                    </a>
                            @else
                                <h5>Document not uploaded yet!</h5>
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <h5 style="font-weight:550;">
                                HPC Letter No.
                                <span class="h3">{{ $data->hpc_approval_letter_number ?? '' }}</span>
                            </h5>
                            <h6 style="font-weight:550;">
                                On Dated:
                                <span class="h3">{{ date('d-m-Y', strtotime($data->hpc_approval_date)) }}</span>
                            </h5>
                        </th>
                        <th>
                            @if(isset($data->hpc_approval_doc))
                                <a onClick="openPDF('{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}')" href="#" class="btn btn-md btn-success h3">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    View
                                </a>
                            @else
                                <h5>Document not uploaded yet!</h5>
                            @endif
                        </th>
                        <th>
                            @if(isset($data->hpc_approval_doc))
                                <a download href="{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}" class="btn btn-md btn-primary h3">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    HPC
                                </a>
                            @else
                                <h5>Document not uploaded yet!</h5>
                            @endif
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
