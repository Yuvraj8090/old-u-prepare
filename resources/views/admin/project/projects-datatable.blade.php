@props([
    'projects' => [],
    'filters' => [],
    'breadcrumbs' => [],
    'showActions' => false,
    'showProgress' => false
])

<style>
    .projects-datatable {
        width: 100% !important;
    }
    .projects-datatable .prona {
        display: inline-block;
        overflow: hidden;
        max-width: 320px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .projects-datatable .progress {
        height: 20px;
        background-color: #f1f1f1;
        border-radius: 4px;
    }
    .projects-datatable .progress-bar {
        transition: none;
    }
    .projects-datatable .badge {
        font-size: 0.8em;
        padding: 4px 8px;
        margin: 2px 0;
    }
    .projects-datatable .table-responsive {
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>

<div class="x_panel">
    <div class="row">
        <div class="col-md-12">
            <div class="x_content">
                <div class="table-responsive">
                    <div class="x_content">
                        <table id="datatable" class="table table-striped table-bordered w-100">
                            <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 20%">Project Name</th>
                                <th style="width: 20%">Details</th>
                                <th style="width:50px;">Status</th>

                                @if($showProgress)
                                    <th style="width:200px;">Physical %</th>
                                    <th style="width:200px;">Financial %</th>
                                @endif

                                @if($showActions)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($projects as $key => $project)
                                <tr>
                                    <th>{{ $key + 1 }}.</th>
                                    <td>
                                        <a data-toggle="tooltip" data-placement="top" title="{{ $project->name }}" style="color:blue;" href="{{ route('project.details', $project->id) }}">
                                            <span class="prona">{{ $project->name }}</span>
                                        </a>
                                        <br>
                                        <span class="badge text-white bg-success">Package Number: {{ $project->number}}</span>
                                        <br>
                                       <span class="badge text-white bg-secondary">
    Project Type: 
    {{ $project->contract && $project->contract->ms_type ? 'Item Rate' : 'EPC' }}
</span>



                                        <small class="d-block">
                                            <b>Created On: </b> {{ date('d M, Y', strtotime($project->created_at)) }}
                                        </small>
                                        <small class="d-blcok">
                                            Contract Value: <span @class(['curf'=> $project->contract])>{{ $project->contract ? $project->contract->procurement_contract : 'N/A' }}</span>
                                        </small>
                                    </td>
                                    <td style="font-size:17px !important;">
                                        <b>Category:</b> {{ $project->category->name }}
                                        <br>
                                        <b>Sub-category:</b> {{ $project->subcategory ?? 'N/A' }}
                                        <br>
                                        <b>Department:</b> <span class="badge bg-info text-white">{{ $project->department->department ?? 'N/A' }}</span>
                                        <br>
                                        <b>HPC Approval Date:</b> {{ date('d-m-Y', strtotime($project->hpc_approval_date)) }}
                                        <br>
                                        <b>District:</b> <span class="badge bg-info text-white">{{ $project->district_name ?? 'N/A' }}</span>
                                    </td>
                                    <td style="text-align:center;width:50px;vertical-align:middle;">
                                        <span @class(['badge', 'text-white'=> $project->stage == 5, 'bg-danger'=> $project->stage == 5, 'bg-warning'=> $project->stage != 5])>{{ $project->projectStatus }}</span>
                                    </td>
                                    
                                    @if($showProgress)
                                        <td class="project_progress">
                                            <div class="progress progress_sm">
                                                <div class="progress-bar bg-green" role="progressbar" style="width:{{ $project->ProjectTotalphysicalProgress ?? 0 }}%;" data-transitiongoal="{{ $project->ProjectTotalphysicalProgress ?? 0 }}"></div>
                                            </div>
                                            <small>{{ $project->ProjectTotalphysicalProgress  }}% Complete</small>
                                        </td>
                                        <td class="project_progress">
        <div class="progress progress_sm">
            <div class="progress-bar bg-green" role="progressbar" 
                 style="width:{{ $project->financial_completion_percent }}%"></div>
        </div>
        <small>{{ $project->financial_completion_percent }}% Complete</small>
        <hr>
        <small>Value: ₹{{ number_format($project->financial_amount_spent) }}</small>
        
    </td>
                                    @endif

                                    @if($showActions)
                                        <td class="text-center" _lid="{{ $project->id }}">
                                            @if($project->stage !== 5)
                                            <a class="canp btn btn-sm btn-info" href="{{ route('admin.project.cancel') }}">
                                                Cancel
                                            </a>
                                            @endif
                                            <a class="delp btn btn-danger" href="{{ route('admin.project.delete') }}">
                                                Delete
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $showProgress ? ($showActions ? 8 : 6) : ($showActions ? 5 : 4) }}">
                                        <center>
                                            <b> NO PROJECTS FOUND </b>
                                        </center>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#projectsDatatable').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 10,
            responsive: true,
            autoWidth: false,
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-info btn-sm'
                }
            ],
            initComplete: function() {
                // Apply currency formatting
                $('.curf').each(function(i, el) {
                    $(el).text(currencyFormatterFraction.format($(el).text()));
                });
                
                // Add buttons to DOM
                table.buttons().container().appendTo('#projectsDatatable_wrapper .col-md-6:eq(0)');
            }
        });

        // Handle delete button clicks
        $('#projectsDatatable').on('click', '.btn.delp', function(e) {
            e.preventDefault();
            const $btn = $(this);
            
            alertBox('err', {
                text: 'Are you sure to delete this project?',
                heading: 'Danger!'
            }, () => {
                const fd = new FormData();
                fd.append('project_id', Number($btn.closest('td').attr('_lid')));
                
                const pm = {
                    url: '{{ route("admin.project.delete") }}',
                    data: fd
                };
                
                const bs = () => busy(1);
                
                const cb = (resp) => {
                    table.row($btn.closest('tr')).remove().draw();
                };
                
                ajaxify(pm, bs, cb);
            });
        });

        // Handle cancel button clicks
        $('#projectsDatatable').on('click', '.btn.canp', function(e) {
            e.preventDefault();
            const $btn = $(this);
            
            alertBox('warn', {
                text: 'Are you sure to cancel this project?',
                heading: 'Warning!'
            }, () => {
                const fd = new FormData();
                fd.append('project_id', Number($btn.closest('td').attr('_lid')));
                
                const pm = {
                    url: '{{ route("admin.project.cancel") }}',
                    data: fd
                };
                
                const bs = () => busy(1);
                
                const cb = (resp) => {
                    window.location.reload();
                };
                
                ajaxify(pm, bs, cb);
            });
        });
    });

    // Category filter change handler
    $("#category_id").on("change", function(event) {
        event.preventDefault();
        const id = $(this).val();

        $.ajax({
            url: "{{ url('getSubCategory') }}/"+id,
            type: "GET",
            success: function(response) {
                if (response) {
                    populateSelect('#subcategory', response);
                }
            },
            error: function(err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
    });

    function populateSelect(selector, data) {
        $(selector).removeAttr('readonly').removeAttr('disabled').empty();
        $(selector).append($('<option>', { value: '', text: 'Select' }));

        $.each(data, function(index, item) {
            $(selector).append($('<option>', {
                value: item.name,
                text: item.name
            }));
        });
    }
</script>
@endpush