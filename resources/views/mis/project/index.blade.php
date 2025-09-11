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

    @php
        $breads = [
            (object) [
                'url'   => url('dashboard'),
                'name'  => 'Home',
                'active'=> 0
            ],
        ];

        if(request()->segment('1') == "project")
        {
            $breads[] = (object) [
                'url'   => NULL,
                'name'  => 'Project Monitoring',
                'active'=> 1
            ];
        }
        elseif(request()->segment('2') == "project")
        {
            $breads[] = (object) [
                'url'   => NULL,
                'name'  => 'Manage Project',
                'active'=> 1
            ];

            $breads[] = (object) [
                'url'   => NULL,
                'name'  => 'Edit Project',
                'active'=> 1
            ];
        }
        elseif(request()->segment('1') == "define")
        {
            $breads[] = (object) [
                'url'   => NULL,
                'name'  => 'Assign Project',
                'active'=> 1
            ];
        }
        elseif(request()->segment('1') == 'work')
        {
            $breads[] = (object) [
                'url'   => NULL,
                'name'  => 'Work Progress',
                'active'=> 1
            ];
        }
    @endphp

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>
                All Projects
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @foreach($breads as $bread)
                        <li @class(['active'=> $bread->active, 'breadcrumb-item'])>
                            @if($bread->url)
                            <a href="{{ $bread->url }}">
                            @endif
                                {{ $bread->name }}
                            @if($bread->url)
                            </a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                {{--<div class="x_panel">--}}

                 <style>
    .col-md-3,.col-md-4,.col-md-2,.col-md-8{
        margin-top:10px;
    }

    .dropdown-toggle::after {
        top: 50%;
        right: 10px;
        position: absolute;
        transform: translateY(-50%);
    }

    .dropdown.districts .dropdown-menu {
        padding: 0;
    }

    .dropdown.districts .dropdown-menu li {
        display: flex;
        padding: 5px 10px;
        align-items: center;
    }

    .dropdown.districts .dropdown-menu li label {
        margin: 0 0 0 5px;
        cursor: pointer;
    }
</style>
<form action="" method="GET" autocomplete="off">
    <div class="row">
        <div class="col-lg-10 col-md-8">

            @if(in_array(auth()->user()->role->level, ['ADMIN', 'ONE']))
            <div class="col-md-2">
                <select name="department" class="form-control">
                    <option value="">Departments</option>
                    @if(count($department) > 0)
                        @foreach($department as $dp)
                            <option  value="{{ $dp->id }}" @if(isset($dept_tfltr) && $dept_tfltr == $dp->id)@elseif(request('department') == $dp->id)@endif >{{ $dp->department }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            @endif

            @if(isset($project_districts) && $project_districts->count())
            <div class="col-md-2">
                <div class="dropdown districts">
                    <button class="m-0 form-control dropdown-toggle text-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Districts
                    </button>
                    <ul class="dropdown-menu w-100">
                        @php $user_districts = auth()->user()->district ? json_decode(auth()->user()->district) : NULL; @endphp
                        @foreach($project_districts as $key => $district)
                        <li>
                            <input id="{{ Str::slug($district->name) }}" type="checkbox" name="project_districts[]" value="{{ $district->name }}" @if($user_districts && in_array($district->name, $user_districts)){{ __('checked') }}@endif />
                            <label for="{{ Str::slug($district->name) }}">{{ $district->name }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(isset($category))
                <div class="col-md-2">
                    <select name="category" id="category_id" class="form-control">
                        <option value=""> Category </option>
                        @if(count($category) > 0)
                            @foreach($category as $cat)
                                <option value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="subcategory" id="subcategory" class="form-control" >
                        <option value="">Sub-Category</option>
                        @if(isset($subcategory) && $subcategory->count())
                            @foreach ($subcategory as $scat)
                                <option value="{{ $scat->name }}" @selected(request('subcategory') == $scat->name)>{{ $scat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif

            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">Status</option>
                    @foreach (App\Helpers\Assistant::getProjectStatus(0, 1) as $key => $status)
                        <option value="{{ $key }}" @selected($key == (request('status') ?? ''))>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="year" class="form-control">
                    <option value="">HPC Approval Year</option>
                    @if(count($years) > 0)
                        @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <select name="completion_year" class="form-control">
                    <option value="">Completion Year</option>
                    @if(count($years) > 0)
                        @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}" @if(request('completion_year') == $ye) selected @endif>{{ $ye }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

        </div>

        <div class="col-md-2">
            <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;margin-to" class="btn btn-danger text-white pull-right">
                <i class="fa fa-refresh" ></i>
                Reset
            </a>

            <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning pull-right">
                <i class="fa fa-search" ></i>
                Filter
            </button>
        </div>
    </div>
</form>

                  @include('admin.project.projects-datatable', [
    'projects' => $projects,
    'breadcrumbs' => $breads,
    'showActions' => auth()->user()->hasRole('Admin'),
    'showProgress' => true
])
                {{--</div>--}}
            </div>
        </div>
    </div>
@stop


@section('script')
<link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">
<script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>

<script>
    let $body = $('body');


    @if($projects->count())
    $('.curf').each(function(i, el) {
        $(el).text(currencyFormatterFraction.format($(el).text()));
    });
    let $table = new DataTable('#datatable', {
        pageLength: 5,
        lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
    });
    @endif

    $("#category_id").on("change", function (event) {
        event.preventDefault();

        let id = $(this).val();

        $.ajax({
            url: "{{ url('getSubCategory') }}/"+id,
            type: "GET",
            success: function (response) {

                if (response) {
                    populateSelect('#subcategory', response);
                }

            },
            error: function (err) {
                toastr.info("Error! Please Contact Admin.");
            },
        });
    });

    $(document).ready(function() {
        $('table#datatable').on('click', '.btn.delp', function(e) {
            let $btn = $(this);
            e.preventDefault();

            // On Click Callback
            let occb = ()=> {
                let fd = newFormData();
                    fd.append('project_id', Number($btn.closest('td').attr('_lid')));

                let pm = {
                    url: '{{ route("admin.project.delete") }}',
                    data: fd
                }

                let bs = () => busy(1);

                let cb = (resp)=> {
                    $btn.closest('tr').slideUp('normal', function() {
                        $btn.remove();
                    });
                }

                ajaxify(pm, bs, cb);
            }

            alertBox('err', {
                text: 'Are you sure to delete this project?',
                heading: 'Danger!'
            }, occb);
        });

        $('.btn.canp').on('click', function(e) {
            let $btn = $(this);

            e.preventDefault();

            // On Click Callback
            let occb = ()=> {
                let fd = newFormData();
                    fd.append('project_id', Number($btn.closest('td').attr('_lid')));

                let pm = {
                    url: '{{ route("admin.project.cancel") }}',
                    data: fd
                }

                let bs = () => busy(1);

                let cb = (resp)=> {
                    // $btn.closest('tr').slideUp('normal', function() {
                    //     $btn.closest('tr').remove();
                    // });
                    window.location.reload();
                }

                ajaxify(pm, bs, cb);
            }

            alertBox('warn', {
                text: 'Are you sure to cancel this project?',
                heading: 'Danger!'
            }, occb);
        });

        $('.dropdown.districts').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            let $dd = $(this);

            $dd.toggleClass('show');
            $dd.find('ul').toggleClass('show');
        });

        $('.dropdown.districts ul').on('click', function(e) {
            e.stopImmediatePropagation();
        });


        $('body').on('click', function() {
            let $dd = $('.dropdown.districts');
                $dd.removeClass('show');
                $dd.find('ul').removeClass('show');
        })
    });

    function populateSelect(selector, data) {
        $(selector).removeAttr('readonly');
        $(selector).removeAttr('disabled');

        $(selector).empty(); // Clear existing options

        $(selector).append($('<option>', {
            value: '',
            text: 'Select'
        }));

        $.each(data, function(index, item) {
            $(selector).append($('<option>', {
                value: item.name,
                text: item.name
            }));
        });
    }
</script>
@stop
