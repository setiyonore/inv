@extends('layouts.app')
@section('title-page','Dashboard')
@section('content-header')
    <div class="row mb-2">
        <input type="hidden" id="url">
        <input type="hidden" id="token" value="{{csrf_token()}}">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
@section('main-content')
    <!-- Small boxes (Stat box) -->
    @if(auth()->user()->can('dashboard'))
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$order}}</h3>

                    <p>Total Order</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$paketBuku}}</h3>

                    <p>Paket Buku</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$paketJurnal}}</h3>

                    <p>Paket Jurnal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-journal-whills"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$pelanggan}}</h3>

                    <p>Pelanggan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <!-- BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Chart Order</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    @else
        hallo {{ Auth::user()->name }}
    @endif

    <!-- /.row (main row) -->
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            var APP_URL = {!! json_encode(url('/')) !!}
            $('#url').val(APP_URL);
            getOrderPerMonth();
        });
        $(function () {
            //-------------
            //- BAR CHART -
            //-------------

        });
        function getOrderPerMonth(){
            let baseurl = $('#url').val();
            $.ajax({
                url: baseurl+'/home/getOrderPerMonth',
                method: 'GET',
                success: function (data) {
                    var areaChartData = {
                        labels  : [],
                        datasets: [
                            {
                                label               : 'Paket Buku',
                                backgroundColor     : 'rgba(60,141,188,0.9)',
                                borderColor         : 'rgba(60,141,188,0.8)',
                                pointRadius          : false,
                                pointColor          : '#3b8bba',
                                pointStrokeColor    : 'rgba(60,141,188,1)',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(60,141,188,1)',
                                data                : []
                            },
                            {
                                label               : 'Paket Jurnal',
                                backgroundColor     : 'rgba(210, 214, 222, 1)',
                                borderColor         : 'rgba(210, 214, 222, 1)',
                                pointRadius         : false,
                                pointColor          : 'rgba(210, 214, 222, 1)',
                                pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data                : []
                            },
                        ]
                    }

                    for (i = 0;i<data.data.length;i++){
                        areaChartData.labels.push(data.data[i].bulan);
                    }
                    for(i = 0;i<data.paketBuku.length;i++){
                        areaChartData.datasets[0].data.push(data.paketBuku[i].jml);
                    }
                    for(i=0;i<data.paketJurnal.length;i++){
                        areaChartData.datasets[1].data.push(data.paketJurnal[i].jml);
                    }
                    console.log(areaChartData.datasets[0].data);
                    var barChartCanvas = $('#barChart').get(0).getContext('2d')
                    var barChartData = $.extend(true, {}, areaChartData)
                    var temp0 = areaChartData.datasets[0]
                    var temp1 = areaChartData.datasets[1]
                    barChartData.datasets[0] = temp1
                    barChartData.datasets[1] = temp0

                    var barChartOptions = {
                        responsive              : true,
                        maintainAspectRatio     : false,
                        datasetFill             : false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }

                    new Chart(barChartCanvas, {
                        type: 'bar',
                        data: barChartData,
                        options: barChartOptions
                    })
                }
            });
        }
    </script>
@endsection
