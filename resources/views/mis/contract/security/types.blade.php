@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            <h4>Contract {{ isset($form) ? 'Form of ' : '' }}Securities</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Contract {{ isset($form) ? 'Form of ' : '' }}Securities</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    @include('admin.error')

    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="x_title">
                    <a href="#" class="btn btn-primary btn-sm float-right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSModal">Add Form of Security</a>
                    <h2>Contract {{ isset($form) ? 'Form of ' : '' }}Securities</h2>

                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table class="table data-table table-striped projects table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th>Form of Security Name</th>
                                <th style="width: 200px">Action</th>
                            </tr>
                        </thead>

                        <tbody class="text-center"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    <!-- Modal -->
    <div id="addSModal" class="modal bd-example-modal-lg" tabindex="-1" role="dialog">
        <div class="w-100 modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="txt-aoe"></span> Form of Security
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form autocomplete="off" data-method="POST" data-action="{{ route('contract.security.type.save') }}" class="form-horizontal form-label-left ajax-form dt" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="slug" value="" />
                        <input type="hidden" name="form" value="yes" />
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Name</label>

                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" name="name" />
                                <p class="error-project" id="error-name"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <button id="submit-btn" type="submit" class="btn btn-success btn-sm">
                            <span class="loader" id="loader" style="display: none;"></span>
                            <span class="txt">Add</span> Form of Security
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        $form_edf     = $('form#edf');
        $gb_DataTable = null;

        $(function() {
            var i = 1;
            $gb_DataTable = $(".data-table").DataTable({
                ajax: "{{ route('contract.security.type.forms') }}",
                order: [0, "ASC"],
                paging: true,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                autoWidth: false,
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 15, 25, 50, 100],
                searchDelay: 2000,
                iDisplayLength: "5",
            });

            $('.data-table').on('click', '.btn-danger', function() {
                let $btn  = $(this);
                let $slug = $btn.attr('_slug');

                let occb = ()=> {
                    let fd = newFormData();
                        fd.append('slug', $slug);
                        fd.append('form', 'yes');

                    let pm = {
                        url: '{{ route("contract.security.type.delete") }}',
                        data: fd
                    }

                    let bs = () => busy(1);

                    let cb = (resp)=> {
                        // $btn.closest('tr').slideUp('normal', function() {
                        //     $btn.closest('tr').remove();
                        // });
                        $gb_DataTable.ajax.reload();
                    }

                    ajaxify(pm, bs, cb);
                }

                alertBox('err', {
                    text: 'Are you sure to delete this?',
                    heading: 'Danger!'
                }, occb);
            });

            $('.data-table').on('click', '.btn-success', function() {
                let $btn  = $(this);
                let $slug = $btn.attr('_slug');
                let $name = $btn.closest('td').prev().text();
                let $modal = $('#addSModal');


                $body.append('<div class="modal-backdrop show"></div>');
                $modal.addClass('show');
                $modal.attr('style', 'display: unset;');
                $modal.find('#submit-btn .txt').text('Update');
                $modal.find('input[name="slug"]').val($slug);
                $modal.find('input[name="name"]').val($name);
                $modal.find('.close').on('click', function() {
                    $modal.attr('style', 'display: none;');
                    $modal.removeClass('show');
                    $body.find('.modal-backdrop').remove();
                    $modal.find('.close').off('click');
                    $modal.find('input[name="slug"]').val('');
                    $modal.find('form').get(0).reset()
                    $modal.find('#submit-btn .txt').text('Add');
                })
            })
        });
    </script>
@endsection
