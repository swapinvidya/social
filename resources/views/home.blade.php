@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('title', 'AppName')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">You are logged in!</p>
                    <div>
                        <canvas id="myChart" width="400" height="200"></canvas>
                    </div>    
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Sales
                  </h3>
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                      <li class="nav-item">
                        <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                      </li>
                    </ul>
                  </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content p-0">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane">
                        <canvas id="myChartttt" width="400" height="200"></canvas>
                    </div>
                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                      <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                    </div>
                  </div>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
@stop
@section('js')
   
   
    <script>
        const labels = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            ];
            const data = {
            labels: labels,
            datasets: [{
                label: 'My First dataset',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [0, 10, 5, 2, 20, 30, 45],
            }]
            };
            const config = {
            type: 'line',
            data: data,
            options: {}
            };

            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
    </script>    
@stop
