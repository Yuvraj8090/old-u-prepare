<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">CONTRACTOR DETAILS</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
            <table class="table tbl table-striped">
                <tr>
                    <td>
                        <h5 style="font-weight:550;"> Contractor Name :
                            <span class="h3"> {{ $contracts->company_name ?? '' }} </span>
                        </h5>

                        <h5 style="font-weight:550;"> Authorized Person :
                            <span class="h3"> {{ $contracts->authorized_personel ?? '' }} </span>
                        </h5>

                        <h5 style="font-weight:550;"> Phone No.  :
                            <span class="h3"> {{ $contracts->contact ?? '' }} </span>

                            &nbsp;&nbsp;&nbsp;  Email  :
                            <span class="h3"> {{ $contracts->email ?? '' }} </span>
                        </h5>


                        <h5 style="font-weight:550;">Address :
                            <span class="h3"> {{ $contracts->contractor_address ?? '' }} </span>
                        </h5>

                        <h5 style="font-weight:550;">Registered Number  :
                            <span class="h3"> {{ $contracts->company_resgistered_no ?? '' }} </span>

                            &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  Registration Type :
                            <span class="h3"> {{ $contracts->registration_type ?? '' }} </span>
                        </h5>

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
