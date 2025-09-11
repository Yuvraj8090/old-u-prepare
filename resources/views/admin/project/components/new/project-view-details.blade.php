<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">PROJECT DETAILS</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
            <div class="row">
                <div class="col-md-12">

                <table class=" table tbl table-striped  ">
                    <tr>
                        <td colspan="2">
                            <h5 style="font-weight:550;">
                                {{ $data->name ?? '' }}
                                <span class="h3">   </span>
                            </h5>
                           
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="2">
                            <h5 style="font-weight:550;">
                                 Sub Project Number:
                                <span class="h3">{{ $data->number }}</span>
                            </h5>
                            <h5 style="font-weight:550;">
                                Estimate Budget:
                                <span class="h3">{{ $data->estimate_budget }}</span>
                            </h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5 style="font-weight:550;">
                                Department:
                                <span class="h3">{{ $data->department->department }}</span>
                            </h5>
                        </td>
                        <td>
                        <h5 style="font-weight:550;">
                            Category:
                                <span class="h3">{{ $data->category->name }}</span>
                                &nbsp;&nbsp;
                                (<span class="h3">{{ $data->subcategory }}</span>)
                            </h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5 style="font-weight:550;">
                                District:
                                <span class="h3">{{ $data->district_name ?? 'N/A' }}</span>
                            </h5>
                        </td>
                        <td>
                            <h5 style="font-weight:550;">
                                Block:
                                <span class="h3">{{ $data->block ?? 'N/A' }}</span>
                            </h5>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <h5 style="font-weight:550;">
                                Vidhan Sabha:
                                <span class="h3">{{ $data->assembly ?? 'N/A' }}</span>
                            </h5>
                        </td>
                        <td>
                            <h5 style="font-weight:550;">
                                Lok Sabha:
                                <span class="h3"> {{ $data->constituencie ?? 'N/A' }} </span>
                            </h5>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!--<div class="ln_solid"></div>-->
    </div>
</div>
