@extends('layouts.admin')

@section('header_styles')
    <style>
        table th,
        table td {
            vertical-align: middle !important;
        }

        table a.site-images > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        table a.site-images {
            width: 40%;
            display: block;
        }
    </style>
@endsection

@section('content')
    <!-- code added 7 Feb 2024 -->
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>Site Progress Images of Project Milestone </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Site Progress Images of Project Milestone</a></li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="x_panel">
        <div class="x_title d-flex align-items-center justify-content-between">
            <h5 style="font-weight:550;">All SITE IMAGES</h5>

            @php $is_smv = request()->segment(4) && request()->segment(4) == 'site-images'; @endphp
 <a class="btn btn-md btn-danger milestone_id" data-toggle="modal" data-target=".addSiteImages" data-id="" href="javascript:(0)">Add Site Images</a>
      
        </div>

        <div class="x_content">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>S. No #</th>
                        <th style="width: 20%">Image</th>
                        <th>Activity Name</th>
                        <th>Stage Name</th>
                        <th>Remark</th>
                        <th class="text-center">Created At</th>
                        <th class="text-center">Updated At</th>
                        @if(auth()->user()->role->level == "THREE" || $is_smv)
                            <th style="width: 15%;">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @if(count( ($data->media ?? [])) > 0)
                        @foreach($data->media as $k => $d)
                            <tr>
                                <td>{{ ++$k }}</td>
                                <td>
                                    <a href="{{ asset('images/milestone/site/' . $d->name) }}" data-lightbox="site-images" class="site-images mx-auto">
                                        <img src="{{ asset('images/milestone/site/' . $d->name) }}" />
                                    </a>
                                </td>
                                <th>{{ $d->activity_name }}</th>
                                <th>{{ $d->stage_name }}</th>
                                <th>{{ $d->remark }}</th>
                                <td class="text-center">{{ date('d-m-Y h:i A', strtotime($d->created_at)) }}</td>
                                <td class="text-center">{{ date('d-m-Y h:i A', strtotime($d->updated_at)) }}</td>
                                @if(auth()->user()->role->level == "THREE" || $is_smv)
                                    <td class="text-center">
                                        <a class="btn btn-md btn-info media_id" data-id="{{ $d->id }}" data-toggle="modal" data-target=".updateSiteImages" href="javascript:void(0)" data-info='@json($d)'>Update Image</a>
                                        @if($is_smv)
                                        <br>
                                        <a class="btn btn-del btn-danger" href="#" data-id="{{ $d->id }}">Delete Image</a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>

                <tfoot></tfoot>
            </table>
        </div>
    </div>

    <!-- New code 8 feb 2024 -->
    <form autocomplete="off" data-method="POST" data-action="{{ url('site/image/update') }}" class="form-horizontal form-label-left ajax-form">
        @csrf

        <!-- Modal -->
        <div class="modal updateSiteImages" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Update Site Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="media_id" name="id" value="" />
                        <input type="hidden" name="milestoneId" value="{{ $id }}" />
                        @if(isset($data->project) && $data->project->contract->ms_type)
                        <input type="hidden" name="ms_img" value="Yes">
                        @endif

                        <div class="form-group">
                            <label class="control-label">
                                Update Site Images (Note:- Only JPG,JPEG,PNG file allowed.)
                            </label>
                            <div class="">
                                <input type="file" class="form-control" name="siteimage" required multiple />
                                <p class="error" id="error-siteimage"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Activity Name</label>
                            <div>
                                <input type="text" class="form-control" name="activity_name" required>
                                <p id="error-activity_name" class="error"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Stage Name</label>
                            <div>
                                <input type="text" class="form-control" name="stage_name" required>
                                <p id="error-stage_name" class="error"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Remark</label>
                            <div>
                                <textarea name="remark" rows="4" class="form-control"></textarea>
                                <p id="error-stage_name" class="error"></p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Image</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    {{-- @if(isset($data->project) && $data->project->contract->ms_type) --}}
    {{-- Add Site Images Popup --}}
    <form autocomplete="off"  data-method="POST" data-action="{{ url('/site/images/add') }}" class="form-horizontal form-label-left ajax-form">
        @csrf

        <!-- Modal -->
        <div class="modal addSiteImages" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Site Images</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="mileStone_id" name="id" value="" />
                        <input type="hidden" name="ms_img" value="Yes" />

                        <div class="form-group">
                            <label class="control-label">MileStones Name</label>
                            <div class="">
                                <input type="text" class="form-control" value="" readonly>
                                <p class="error" id="editerror-name"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Site Images (Note:- Only JPG, JPEG, PNG file allowed.)</label>
                            <div class="">
                                <input type="file" class="form-control" name="siteimages[]" required multiple />
                                <p class="error" id="error-siteimages"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Activity Name</label>
                            <div>
                                <input type="text" class="form-control" name="activity_name" required>
                                <p class="error" id="error-activity_name"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Stage Name</label>
                            <div>
                                <input type="text" class="form-control" name="stage_name" required>
                                <p class="error" id="error-stage_name"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Add Remark</label>
                            <div>
                                <textarea name="remark" rows="4" class="form-control"></textarea>
                                <p class="error" id="error-stage_name"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Images</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- @endif --}}

@stop

<!-- New code 8 feb 2024 -->
@section('script')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css" integrity="sha512-xtV3HfYNbQXS/1R1jP53KbFcU9WXiSA1RFKzl5hRlJgdOJm4OxHCWYpskm6lN0xp0XtKGpAfVShpbvlFH3MDAA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js" integrity="sha512-KbRFbjA5bwNan6DvPl1ODUolvTTZ/vckssnFhka5cG80JVa5zSlRPCr055xSgU/q6oMIGhZWLhcbgIC0fyw3RQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).on('click', '.media_id', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var data = $(this).data('info');
            // console.log(id);

            $('#media_id').val(id);

            $('.modal.updateSiteImages input[name="stage_name"]').val(data.stage_name);
            $('.modal.updateSiteImages input[name="activity_name"]').val(data.activity_name);
            $('.modal.updateSiteImages textarea').text(data.remark);
        });

        $('.btn-del').on('click', function(e) {
            let cbtn = $(this);
            alertBox('err', {heading: 'Danger', text: 'Are you sure to delete this image!'}, ()=> {
                let fd = newFormData();
                    fd.append('media_id', cbtn.data('id'));

                let pm = {
                    url: '{{ route("project.define.milestone.site.image.delete") }}',
                    data: fd
                }

                let bs = () => {}

                let cb = (resp) => {
                    cbtn.closest('tr').slideUp('normal', function() {
                        $(this).remove();
                    });
                }

                ajaxify(pm, bs, cb)
            })
        });
    </script>
@stop
