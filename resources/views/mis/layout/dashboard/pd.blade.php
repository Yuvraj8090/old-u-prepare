@extends('layouts.admin')

@section('content')
    <style>
        .row .x_panel {
            height: 100%;
            margin-bottom: 0;
        }

        .row [class*="col-"] {
            margin-bottom: 15px;
        }
    </style>

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title text-center">
                    <h4>Component Wise Project Cost Allocation in Millian $</h4>
                </div>

                <div class="x_content">
                    <div id="canvas-holder" class="w-100">
                        <canvas id="chart-area"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title text-center">
                    <h4>PIU Wise</h4>
                </div>

                <div class="x_content">
                    <div id="piuwpc" class="w-100">
                        <canvas id="piuwpcc"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title text-center">
                    <h4>PIU / PMU - Expenditure and WB Disbursement to-date</h4>
                </div>

                <div class="x_content">
                    <div id="bcanvas" class="w-100">
                        <canvas id="bchart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title text-center">
                    {{-- <h4>Component Wise Project Cost Allocation in Millian $</h4> --}}
                </div>

                <div class="x_content">
                    <div class="responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Works - Targets</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>PWD</th>
                                    <td>45 Bridges & 8 Slope Works</td>
                                </tr>
                                <tr>
                                    <th>USDMA</th>
                                    <td>5 Disaster Shelters & 1 SEOC Interior</td>
                                </tr>
                                <tr>
                                    <th>SDRF</th>
                                    <td>1 Fire training Institute & 19 Fire Stations</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title text-center">
                    <h4>PIU Wise Works Implementation Status</h4>
                </div>

                <div class="x_content">
                    <div id="bpwcanvas" class="w-100">
                        <canvas id="bpiuw"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/1.0.0-beta.1/chartjs-plugin-datalabels.min.js"></script>

    <script>
        // window.onload = function() {
            Chart.plugins.register(ChartDataLabels);
            var ctxpa = document.getElementById("chart-area").getContext('2d');
            var ctxpb = document.querySelector('canvas#piuwpcc').getContext('2d');
            var ctxba = document.querySelector('canvas#bchart').getContext('2d');
            var ctxbb = document.querySelector('canvas#bpiuw').getContext('2d');

            window.myPie1 = new Chart(ctxpa, configpa);
            window.myPie2 = new Chart(ctxpb, configpb);
            window.myBar1 = new Chart(ctxba, configba);
            window.myBar2 = new Chart(ctxbb, configbb);
        // };

        var randomScalingFactor = function() {
            return Math.round(Math.random() * 100);
        };

        var configba = {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_data['pius_pie']['all']['labels']) !!},
                datasets: [
                    {
                        data: {!! json_encode($chart_data['pius_pie']['bcd']['total']) !!},
                        label: "Total Allocated",
                        backgroundColor: '#5b9bd5',
                    },
                    {
                        data: {!! json_encode($chart_data['pius_pie']['bcd']['contract']) !!},
                        label: "Contract Value",
                        backgroundColor: '#ed7d31',
                    },
                    {
                        data: {!! json_encode($chart_data['pius_pie']['bcd']['financial']) !!},
                        label: "Financial Value",
                        backgroundColor: '#a5a5a5',
                    },
                ]
            },
            options: {
                responsive: true,
                legend: {
                    labels: {
                        padding: 35,
                        boxWidth: 12,
                        boxHeight: 6,
                    },
                    display: true,
                    position: 'top',
                },
                barValueSpacing: 20,
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                min: 0,
                            }
                        }
                    ]
                },
                plugins: {
                    datalabels: {
                        font: {
                            weight: 'bold'
                        },
                        align: 'top',
                        anchor: 'end',
                        formatter: Math.round,
                    }
                }
            }
        }

        var configbb = {
            type: 'bar',
            data: {
                labels: ['In Progress', 'Contract Award Stage', 'DPR to be/being Prepared', 'Being Rebidded', 'On Hold due to RAP issue', 'To be Bidded'],
                datasets: [
                    {
                        data: [21, 4, 13, 2, 5, 0],
                        label: 'PWD Bridges',
                        backgroundColor: '#5b9bd5',
                    },
                    {
                        data: [0, 0, 8, 0, 0, 0],
                        label: 'PWD Slope Works',
                        backgroundColor: '#ed7d31',
                    },
                    {
                        data: [0, 0, 1, 0, 0, 0],
                        label: 'USDMA SEOC Interior',
                        backgroundColor: '#a5a5a5',
                    },
                    {
                        data: [0, 0, 0, 0, 0, 5],
                        label: 'USDMA Disaster Shelters',
                        backgroundColor: '#ffc000',
                    },
                    {
                        data: [0, 0, 0, 0, 0, 1],
                        label: 'SDRF Fire Training Institute',
                        backgroundColor: '#4472c4',
                    },
                    {
                        data: [0, 0, 0, 0, 0, 19],
                        label: 'SDRF Fire Stations',
                        backgroundColor: '#70ad47',
                    },
                ]
            },
            options: {
                responsive: true,
                barValueSpacing: 20,
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                min: 0,
                            }
                        }
                    ]
                },
                legend: {
                    labels: {
                        padding: 35,
                        boxWidth: 12,
                        boxHeight: 6,
                    },
                    display: true,
                    position: 'right',
                },
                plugins: {
                    datalabels: {
                        font: {
                            weight: 'bold'
                        },
                        align: 'top',
                        anchor: 'end',
                        formatter: Math.round,
                    }
                }
            }
        }

        var configpa = {
            type: 'pie',
            data: {
                datasets: [{
                    data: {!! json_encode($chart_data['comp_pie']['amt_usd']) !!},
                    backgroundColor: [
                        '#5b9bd5',
                        '#ed7d31',
                        '#a5a5a5',
                        '#ffc000',
                    ],
                    label: 'Dataset 1'
                }],
                labels: {!! json_encode($chart_data['comp_pie']['labels']) !!}
            },
            options: {
                legend: {
                    display: false
                },
                responsive: true,
                plugins: {
                    datalabels: {
                        color: 'white',
                        backgroundColor: function(context) {
                            return context.dataset.backgroundColor;
                        },
                        formatter: function(value, context) {
                            return formatUSDCurrency.format(value);
                        }
                    }
                }
            }
        };

        var configpb = {
            type: 'pie',
            data: {
                datasets: [{
                    data: {!! json_encode($chart_data['pius_pie']['all']['amt_inr']) !!},
                    backgroundColor: [
                        '#5b9bd5',
                        '#ed7d31',
                        '#a5a5a5',
                        '#ffc000',
                    ],
                    label: 'Dataset 1'
                }],
                labels: {!! json_encode($chart_data['pius_pie']['all']['labels']) !!}
            },
            options: {
                legend: {
                    display: false
                },
                responsive: true,
                plugins: {
                    datalabels: {
                        color: 'white',
                        backgroundColor: function(context) {
                            return context.dataset.backgroundColor;
                        },
                        formatter: function(value, context) {
                            return context.chart.data.labels[context.dataIndex] + ': ' + currencyFormatter.format(value);
                        }
                    }
                }
            }
        };
    </script>
@endsection
