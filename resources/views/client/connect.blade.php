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
        
       // print_r ($fb_id);
    @endphp
<h4 class="text-success">Active Package : {{$ap}}</h4>

@if($ap !== "Invalid package" )   
    <div class="row">
        @foreach ($services as $data)
        @php
            switch ($data->id) {
            case 1:
                $fa_fa = "fab fa-facebook-square";
                break;
            case 2:
                $fa_fa = "fab fa-facebook-square";
                break;
            case 3:
                $fa_fa = "fab fa-instagram";
                break;
            case 4:
                $fa_fa = "fab fa-twitter";
                break;
            case 5:
                $fa_fa = "fab fa-pinterest";
                break;

            default:
                $fa_fa = "fa fa-question-circle";
            }

        @endphp
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow mb-3" >
                <div class="card-header d-flex align-items-center" style="height: 2.5rem"><i class="{{$fa_fa}}"></i>&nbsp;{{$data->name}}</div>
                <div class="card-body bg-white text-center">
                    <img src="{{$data->logo}}" class="img-round mb-2" width="75px"><br>
                    <!-- Facebook Page and Group -->
                    @if ($data->id == 1 or $data->id ==2)
                        @foreach ($fb_id as $key => $fb)
                            <a  href = "./fbp_refresh?id={{$fb}}" class="btn btn-outline-info btn-sm mb-1 " style = "width:100%"><i class="fas fa-undo"></i> &nbsp; {{"$key"}}</a>
                        @endforeach
                        <a  href="./auth/facebook" class="btn btn-outline-primary btn-sm mb-1 " style = "width:100%" ><i class="fas fa-plug"></i>  &nbsp; Connect New</a>
                    @endif
                    <!--Twitter -->
                    @if ($data->id == 4)
                        @isset($twitter_all)
                            @foreach ($twitter_all as $tw)
                                <a  href = "#" class="btn btn-outline-info btn-sm mb-1 " style = "width:100%"><i class="fas fa-undo"></i> &nbsp; {{"$tw->screen_name"}}</a>
                            @endforeach    
                        @endisset
                        <a  href="./twitter/login" class="btn btn-outline-primary btn-sm mb-1 " style = "width:100%" ><i class="fas fa-plug"></i>  &nbsp; Connect New</a>
                    @endif
                    <!--Instagram-->
                    @if ($data->id == 3)
                        @isset($instagrams)
                            @foreach ($instagrams as $instagram)
                                <a  href = "#" class="btn btn-outline-info btn-sm mb-1 " style = "width:100%"><i class="fas fa-undo"></i> &nbsp; blah</a>
                            @endforeach    
                        @endisset
                        <a  href="./instagram_connect" class="btn btn-outline-primary btn-sm mb-1 " style = "width:100%" ><i class="fas fa-plug"></i>  &nbsp; Connect New</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif  
@stop

@section('css')
   
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop