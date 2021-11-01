@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Connect Accounts</h1>
@stop

@section('content')
    <p>Connect your social media Accounts.</p>
    @php
        try {
        // Try something
            $ap = $packages->find(Auth::user()->package_type)->name;
        } catch (\Exception $e) {
            // Do something exceptional
            $ap = "Invalid package";
        }   
    @endphp
    <h4 class="text-success">Active Package : {{$ap}}</h4>

    @if($ap !== "Invalid package" )
    
    <div class="row">
        @foreach($services as $data)
            <div class="col-md-3">
            <!-- Widget: user widget style 1 -->
                <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                    @if($data->status)
                        @if($connection_status[$data->id])
                            <div class="widget-user-header text-white" style="background: url('/img/h_img/1.jpg') center center;">
                                <h3 class="widget-user-username text-right">{{$data->name}}</h3>
                                <h5 class="widget-user-desc text-right"><a href="{{$connection_url[$data->id]}}" class="btn btn-outline-success" disabled>Connected</a></h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{$data->logo}}" alt="User Avatar">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                    <h5 class="description-header">{{$Ac_qouta_total}}</h5>
                                    <span class="description-text">ACCOUNTS</span>
                                    </div>
                                    <!-- /.description-block
                                    Ac_qouta_total
                                    Ac_qouta_used
                                    Ac_qouta_avilable
                                    -->
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="description-block">
                                    <h5 class="description-header">{{$Ac_qouta_used}}</h5>
                                    <span class="description-text">QUOTA</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        @else
                            <div class="widget-user-header text-white" style="background: url('/img/h_img/1.jpg') center center;">
                                <h3 class="widget-user-username text-right">{{$data->name}}</h3>
                                <h5 class="widget-user-desc text-right"><a href="{{$connection_url[$data->id]}}" class="btn btn-primary">Connect</a></h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{$data->logo}}" alt="User Avatar">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">ACCOUNTS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">QUOTA</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        @endif
                    @else
                        <div class="widget-user-header text-white" style="background: url('/img/h_img/1.jpg') center center;">
                            <h3 class="widget-user-username text-right">{{$data->name}}</h3>
                            <h5 class="widget-user-desc text-right"><a href="{{$connection_url[$data->id]}}" class="btn btn-outline-success">Refresh</a></h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="{{$data->logo}}" alt="User Avatar">
                        </div>
                        <div class="card-footer">
                            <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                <h5 class="description-header">3,200</h5>
                                <span class="description-text">ACCOUNTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">QUOTA</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    @endif
                </div>             
            </div>
        @endforeach
    </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="callout callout-danger">
                    <h5>I am a danger callout!</h5>
  
                    <p>There is a problem that we need to fix. A wonderful serenity has taken possession of my entire
                      soul,
                      like these sweet mornings of spring which I enjoy with my whole heart.</p>
                </div>
            </div>
        </div>
    @endif
    
@stop

@section('css')
   
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop