@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Create Account</h1>
@stop

@section('content')
    <div class="card">
        <img src="https://picsum.photos/300/50" class="card-img-top" alt="...">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <label>Select Your Page</label>
                        @foreach ($fb_pages as $item)
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check ml-2">
                                    <input class="form-check-input rd_page" type="radio" value="{{$item->id}}" name="page" id="radio{{$item->id}}">
                                    <label class="form-check-label" for="radio{{$item->id}}">
                                        <img src="{{$item->image}}" width="21px"  style="border-radius: 50%;"> {{$item->name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-8">
                        @foreach ($fb_pages as $item)
                        <div id = "divId{{$item->id}}" class="row" style="display:none">
                            <form action="/save_account" method="POST">
                                @csrf
                                <img src="{{$item->image}}" width="100px"><br>
                                <label for="Inp{{$item->id}}">Account Name</label>
                                <input type="text" class="form-control" id="Inp{{$item->id}}" value = "{{$item->name}}" name="name"aria-describedby="emailHelp" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">You can Give a new name.</small>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    
@stop

@section('js')
    <script>
        $( document ).ready(function() {
            console.log( "ready!" );
            var itemclicked = $(".rd_page:checked").val();
            $(".rd_page").click(function(){
                $('#divId'+itemclicked).hide();
                // alert($(".rd_page:checked").val());
                itemclicked = $(".rd_page:checked").val();
                $('#divId'+itemclicked).show();
            });
        });
    </script>    
    
@stop