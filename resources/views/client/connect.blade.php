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
        <div class="col-md-2">
            <div class="card text-center shadow p-3 mb-5 bg-white rounded">
                <div class="card-body">
                    @if($data->status)
                        @if($ayr_cnt)
                            <img src="{{$data->logo}}" width="100px" alt="Card image cap">
                            <h5 class="card-text">{{$data->name}}</h5>
                            <p class="card-text">{{$data->note}}</p>
                            <button type="button" class="btn btn-outline-success" disabled>Connected</button>
                        @else
                            <img src="{{$data->logo}}" width="100px" alt="Card image cap">
                            <h5 class="card-text">{{$data->name}}</h5>
                            <p class="card-text">{{$data->note}}</p>
                            <a href="/connect/{{$data->id}}" class="btn btn-primary">Connect</a>
                        @endif
                    @else
                        <img src="{{$data->logo}}" width="100px" alt="Card image cap"  style="-webkit-filter: grayscale(1); filter: grayscale(1);" >
                        <h5 class="card-text text-muted">{{$data->name}}</h5>
                        <p class="card-text text-muted">{{$data->note}}</p>
                        <button type="button" class="btn btn-secondary" disabled>Disabled</button>
                    @endif
                </div>
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