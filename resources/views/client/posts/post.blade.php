@extends('adminlte::page')

@section('title', 'Post')
@section('plugins.Summernote', true)
@section('content_header')
    <h1>Create a Post</h1>
@stop

@section('content')
   <div class="row">
       <div class="col-12">
           <div class="card">
               <div class="card-body">
                    <form action="/dd" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- With placeholder, sm size, label and some configuration --}}
                        @php
                        $config = [
                            "height" => "150",
                            "toolbar" => [
                                // [groupName, [list of button]]
                                ['style', ['bold', 'italic', 'underline', 'clear']],
                                ['font', ['strikethrough', 'superscript', 'subscript']],
                                ['fontsize', ['fontsize']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['height', ['height']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture', 'video']],
                                ['view', ['fullscreen', 'codeview', 'help']],
                            ],
                        ]
                        @endphp
                        <x-adminlte-text-editor name="teConfig" label="Type Your Post" label-class="text-danger"
                            igroup-size="sm" placeholder="Write some text..." :config="$config"/>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
               </div>
           </div>
       </div>
   </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop