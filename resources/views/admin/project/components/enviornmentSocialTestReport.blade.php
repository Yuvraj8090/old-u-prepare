<!-- Environment -->
<div class="col-md-12 col-sm-12  ">
    <div class="x_panel">
        <div class="x_title d-flex aic justify-content-between">
            <h5 style="font-weight:550;">{{ $headline }}</h5>
            <a class="btn btn-sm btn-info" href="{{ route('mis.project.detail.compliances.sheet', [$project->id, $module]) }}">View Details</a>
        </div>


        <div class="col-md-12">
            <div class="x_content">
                <table class="table text-center table-striped projects table-bordered">
                    <thead>
                        <tr>
                            <th>Phase</th>
                            <th>No. of activities</th>
                            <th>Activities Completed</th>
                            <th>Progress(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($env_soc as $s)
                            <tr>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->data->rules }}</td>
                                <td>{{ $s->data->completed }}</td>
                                <td>{{ number_format( ( ($s->data->completed / $s->data->rules) * 100 ), 2 ) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
