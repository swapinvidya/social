@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Manage Accounts</h1>
@stop

@section('content')
    <p>Your Connected Accounts Are</p>
    error!!
    <ol>
        @foreach ($services as $item)
            @if($item->status)
                <li class= text-success>{{$item->name}} <i class="fa fa-check"></i> </li>
            @else
                <li class="text-mute">{{$item->name}} <i class="fa fa-ban"></i></li>
            @endif
        @endforeach
    </ol>
     
@stop

@section('css')
   
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop