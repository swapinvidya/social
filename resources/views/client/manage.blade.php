@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)

@section('content_header')
    <h1>Manage Accounts</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <x-adminlte-card title="Connected Accounts" theme="lightblue" theme-mode="outline"
            icon="fas fa-lg fa-envelope" removable>
            {{-- Setup data for datatables --}}
            @php
            $heads = [
                ['label' => 'Id','no-export' => true, 'width' =>2],
                ['label' => 'Name','no-export' => true, 'width' => 5],
                ['label' => 'Actions', 'no-export' => true, 'width' => 5],
            ];

            $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>';
            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>';
            $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                            <i class="fa fa-lg fa-fw fa-eye"></i>
                        </button>';

            $config = [
                'data' => [
                    [22, 'John Bender', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
                    [19, 'Sophia Clemens', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
                    [3, 'Peter Sousa', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
                ],
                'order' => [[1, 'asc']],
                'columns' => [null, null, null, ['orderable' => false]],
            ];
            @endphp

            {{-- Minimal example / fill data using the component slot --}}
            <x-adminlte-datatable id="table1" :heads="$heads" bordered compressed beautify>
                @foreach($Account as $row)
                    <tr>
                        <td>{{$row->id}}</td>
                        <td>
                            <img src="{{$FacebookPage->where('page_token',$row->page_token)->first()->image}}" width="25px">&nbsp;{{$row->name}}
                        </td>
                        <td>
                        @php
                            echo '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>';
                        @endphp 
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </div>
    <div class="col-md-4">
        //
    </div>
</div>

     
@stop

@section('css')
   
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop