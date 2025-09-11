@extends('layouts.admin')

@section('content')
    <style>
        table.table tr th:last-child,
        table.table tr td:last-child {
            text-align: center;
        }


    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>
                All Components
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"></ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="x_title">
                    <h3 class="m-0">Components</h3>
                </div>

                <div class="x_content">
                    <table class="table comps table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th style="width: 50px;">S.No.</th>
                                <th>Component Name</th>
                                <th>Amount (INR)</th>
                                <th>Amount (USD)</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($comps as $key => $comp)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $comp->name }}</td>
                                    <td class="amt_inr">{{ $comp->amt_inr }}</td>
                                    <td class="amt_usd">{{ $comp->amt_usd }}</td>
                                    <td>
                                        <a href="{{ route('mis.dashboard.pd.component.edit', $comp->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-pencil"></i>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="x_title">
                    <h3 class="m-0">Component PIU's</h3>
                </div>

                <div class="x_content">
                    <table class="table pius table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th style="width: 50px;">S.No.</th>
                                <th>PIU Name</th>
                                <th>Amount (INR)</th>
                                <th>Amount (USD)</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pius as $key => $piu)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $piu->name }}</td>
                                    <td class="amt_inr">{{ $piu->amt_inr }}</td>
                                    <td class="amt_usd">{{ $piu->amt_usd }}</td>
                                    <td>
                                        <a href="{{ route('mis.dashboard.pd.component.piu.edit', $piu->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-pencil"></i>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

        @if($comps->count())
            $('.curf').each(function(i, el) {
                $(el).text(currencyFormatterFraction.format($(el).text()));
            });

            let $tableComps = new DataTable('table.comps', {
                pageLength: 5,
                lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
            });

            let $tablePIUS = new DataTable('table.pius', {
                pageLength: 5,
                lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
            });

            document.querySelectorAll('table .amt_usd').forEach(function(el) {
                el.innerText = formatUSDCurrency.format(el.innerText);
            });

            document.querySelectorAll('table .amt_inr').forEach(function(el) {
                el.innerText = currencyFormatterFraction.format(el.innerText);
            });
        @endif
    </script>
@stop

