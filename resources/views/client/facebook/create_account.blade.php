@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Create Account</h1>
@stop

@section('content')
    
    @foreach ($fb_pages as $page)
        {{$page->name}}
    @endforeach
    <hr>
    @foreach ($fb_groups as $group)
        {{$group->name}}
    @endforeach
@stop

@section('css')
    
@stop

@section('js')
       
@stop