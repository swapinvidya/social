@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)

@section('content_header')
    <h1>Manage Accounts</h1>
@stop
@php
    $per =($Ac_qouta_used / $Ac_qouta_total) * 100;
    $bal = $Ac_qouta_total - $Ac_qouta_used;    
@endphp
{{-- Setup data for datatables --}}
@php
$heads = [
    ['label' => 'Id','no-export' => true, 'width' =>2],
    ['label' => 'Name','no-export' => true, 'width' => 5],
    ['label' => 'Actions', 'no-export' => true, 'width' => 3],
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
@section('content')
    <x-adminlte-modal id="modalCustom" title="Connect New Account" size="md" theme="primary" icon="fas fa-bell" v-centered static-backdrop scrollable>
        <div style="height:800px;">
            <ion-icon name="heart"></ion-icon>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
            <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>

    <div class="row">
        <a class="btn btn-app" href = "/create_account_fb">
            <span class="badge bg-purple">{{$bal}}</span>
            <i class="fab fa-facebook-square text-blue"></i> Add page
        </a>
        <a class="btn btn-app" href = "/create_account_fb">
            <span class="badge bg-purple">{{$bal}}</span>
            <i class="fab fa-facebook-square text-blue"></i> Add Group
        </a>
        <a class="btn btn-app" href = "/create_account_fb">
            <span class="badge bg-purple">{{$bal}}</span>
            <i class="fab fa-instagram text-danger"></i> Instagram
        </a>
        <a class="btn btn-app" href = "/create_account_fb">
            <span class="badge bg-purple">{{$bal}}</span>
            <i class="fab fa-linkedin"></i> Linkedin
        </a>
        <a class="btn btn-app bg-secondary">
            <span class="badge bg-success">300</span>
            <i class="fas fa-barcode"></i> Task
        </a>
        <a class="btn btn-app bg-success">
            <span class="badge bg-purple">891</span>
            <i class="fas fa-users"></i> Users
        </a>
        <a class="btn btn-app bg-danger">
            <span class="badge bg-teal">67</span>
            <i class="fas fa-inbox"></i> Orders
        </a>
     <!--<a class="btn btn-app">
            <i class="fas fa-edit"></i> Edit
          </a>
          <a class="btn btn-app">
            <i class="fas fa-play"></i> Play
          </a>
          <a class="btn btn-app">
            <i class="fas fa-pause"></i> Pause
          </a>
          <a class="btn btn-app">
            <i class="fas fa-save"></i> Save
          </a>
          <a class="btn btn-app">
            <span class="badge bg-warning">3</span>
            <i class="fas fa-bullhorn"></i> Notifications
          </a>
          <a class="btn btn-app">
            <span class="badge bg-success">300</span>
            <i class="fas fa-barcode"></i> Products
          </a>
          <a class="btn btn-app">
            <span class="badge bg-purple">891</span>
            <i class="fas fa-users"></i> Users
          </a>
          <a class="btn btn-app">
            <span class="badge bg-teal">67</span>
            <i class="fas fa-inbox"></i> Orders
          </a>
          <a class="btn btn-app">
            <span class="badge bg-info">12</span>
            <i class="fas fa-envelope"></i> Inbox
          </a>
          <a class="btn btn-app">
            <span class="badge bg-danger">531</span>
            <i class="fas fa-heart"></i> Likes
          </a>
          <a class="btn btn-app bg-secondary">
            <span class="badge bg-success">300</span>
            <i class="fas fa-barcode"></i> Products
          </a>
          <a class="btn btn-app bg-success">
            <span class="badge bg-purple">891</span>
            <i class="fas fa-users"></i> Users
          </a>
          <a class="btn btn-app bg-danger">
            <span class="badge bg-teal">67</span>
            <i class="fas fa-inbox"></i> Orders
          </a> -->
    </div>
    <div class="row">
        <div class="card card-primary card-outline mr-2 ml-2" style="width: 100%">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-edit"></i>
                Enabled Accounts
              </h3>
            </div>
            <div class="card-body">
              <h4>{{Auth::User()->name}}</h4>
              <div class="row">
                <div class="col-3 col-sm-3">
                  <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">All Accounts</a>
                    <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Facebook Page</a>
                    <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Facebook Group</a>
                    <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Instagram</a>
                  </div>
                </div>
                <div class="col-9 col-sm-9">
                  <div class="tab-content" id="vert-tabs-tabContent">
                    <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                        <x-adminlte-datatable id="table1" :heads="$heads" bordered >
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
                    </div>
                    <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                        <x-adminlte-datatable id="table2" :heads="$heads" bordered >
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
                        </x-adminlte-datatable> </div>
                    <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                        <x-adminlte-datatable id="table3" :heads="$heads" bordered >
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
                        </x-adminlte-datatable></div>
                    <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel" aria-labelledby="vert-tabs-settings-tab">
                        <x-adminlte-datatable id="table4" :heads="$heads" bordered >
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
                        </x-adminlte-datatable></div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.card -->
    </div>


@stop

@section('css')
   
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
@stop