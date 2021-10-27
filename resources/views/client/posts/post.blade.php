@extends('adminlte::page')

@section('title', 'Post')
@section('plugins.Summernote', true)
@section('content_header')
    <h1>Create a Post</h1>
@stop

@section('content')
@if(Session::has('message'))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-{{ Session::get('type') }} alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-{{ Session::get('icon') }}"></i> Alert!</h5>
                        {{ Session::get('message') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
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

                        <div class="col-12">
                            <x-adminlte-text-editor name="teConfig" label="Type Your Post" label-class="text-danger"
                            igroup-size="sm" placeholder="Write some text..." :config="$config"/>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shorturl" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                  Shorten Url
                                </label>
                              </div>
                        </div>
                        
                        <div class="col-6">
                            <x-adminlte-input-file name="file" igroup-size="sm" placeholder="Choose a file..." label="Image" label-class="text-danger">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-lightblue">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-file>
                        </div>    
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Post Now</button>
                            <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalPurple">Schedule</button>
                        </div>
                        
                    </form>
               </div>
           </div>
       </div>
   </div>

            {{-- Model Schedule --}}
    <form action="/tt" method="POST" enctype="multipart/form-data">
        <x-adminlte-modal id="modalPurple" title="Schedule Posts" theme="purple" icon="fas fa-bolt">
            
                @csrf
                <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <label>Select Time</label>
                                <input class="form-control" type="datetime-local" name="time" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Select Time Zone</label>
                            <select name="timezone_offset" id="timezone-offset" class="form-control" >
                                <option value="-12:00">(GMT -12:00) Eniwetok, Kwajalein</option>
                                <option value="-11:00">(GMT -11:00) Midway Island, Samoa</option>
                                <option value="-10:00">(GMT -10:00) Hawaii</option>
                                <option value="-09:50">(GMT -9:30) Taiohae</option>
                                <option value="-09:00">(GMT -9:00) Alaska</option>
                                <option value="-08:00">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                <option value="-07:00">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                <option value="-06:00">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                <option value="-05:00">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                <option value="-04:50">(GMT -4:30) Caracas</option>
                                <option value="-04:00">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                <option value="-03:50">(GMT -3:30) Newfoundland</option>
                                <option value="-03:00">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                <option value="-02:00">(GMT -2:00) Mid-Atlantic</option>
                                <option value="-01:00">(GMT -1:00) Azores, Cape Verde Islands</option>
                                <option value="+00:00">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                <option value="+01:00">(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
                                <option value="+02:00">(GMT +2:00) Kaliningrad, South Africa</option>
                                <option value="+03:00">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                <option value="+03:50">(GMT +3:30) Tehran</option>
                                <option value="+04:00">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                <option value="+04:50">(GMT +4:30) Kabul</option>
                                <option value="+05:00">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                <option value="+05:50" selected="selected">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                                <option value="+05:75">(GMT +5:45) Kathmandu, Pokhara</option>
                                <option value="+06:00">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                <option value="+06:50">(GMT +6:30) Yangon, Mandalay</option>
                                <option value="+07:00">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                <option value="+08:00">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                <option value="+08:75">(GMT +8:45) Eucla</option>
                                <option value="+09:00">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                <option value="+09:50">(GMT +9:30) Adelaide, Darwin</option>
                                <option value="+10:00">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                <option value="+10:50">(GMT +10:30) Lord Howe Island</option>
                                <option value="+11:00">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                <option value="+11:50">(GMT +11:30) Norfolk Island</option>
                                <option value="+12:00">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                <option value="+12:75">(GMT +12:45) Chatham Islands</option>
                                <option value="+13:00">(GMT +13:00) Apia, Nukualofa</option>
                                <option value="+14:00">(GMT +14:00) Line Islands, Tokelau</option>
                            </select>
                            </div>
                        </div>
                    
                        <div class="row text-center text-danger">
                            *Posts with past dates will be immediately sent.
                        </div>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
                </x-slot>
           
        </x-adminlte-modal>
    </form>
   <h3>Time Line</h3>
   <div class="row">
       <div class="col-12">
            <!-- Main node for this component -->
            <div class="timeline">
                <!-- Timeline time label -->
            @foreach($post as $data)
            @php
                $json = $data->response;
                $obj = json_decode($json);
                $content = $obj->post;
                $status = $obj->status;
            @endphp
                <div class="time-label">
                    @if ($status == 'success')
                        <span class="bg-green">{{$data->updated_at->format('d-m-Y')}}</span>
                    @else
                        <span class="bg-red">{{$data->updated_at->format('d-m-Y')}}</span>
                    @endif
                
                </div>
                <div>
                <!-- Before each timeline item corresponds to one icon on the left scale -->
                <i class="fas fa-envelope bg-blue"></i>
                <!-- Timeline item -->
                <div class="timeline-item">
                <!-- Time -->
                    <span class="time"><i class="fas fa-clock"></i>{{$data->updated_at->format('g:i A')}}</span>
                    <!-- Header. Optional -->
                    <h3 class="timeline-header"><a href="#">New Post</a>
                        @if ($status == 'success')
                        Was Sucessfull
                        @else
                        Was Not Sucessfull
                        @endif
                    </h3>
                    <!-- Body -->
                    <div class="timeline-body">
                        @if ($status == 'success')
                            {{$content}}
                        @else
                            {{$content}}<br>
                            @php
                                print_r($obj->errors[0]->details)
                            @endphp
                        @endif

                        @isset($data->file)
                            <div class="text-center">
                                <img src="{{$data->file}}" width="200px">
                            </div>
                        @endisset
                    </div>
                    <!-- Placement of additional controls. Optional -->
                    <div class="timeline-footer">
                    <small class="text-info">{{$data->updated_at->diffForHumans()}}</small><br>    
                    <a class="btn btn-primary btn-sm">Read more</a>
                    <a class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </div>
                </div>
            @endforeach
                <!-- The last icon means the story is complete -->
                <div>
                <i class="fas fa-clock bg-gray"></i>
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