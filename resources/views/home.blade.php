@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('title', 'AppName')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                  <h4>Daily Usage Chart</h4>
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
                  <div>
                      <canvas id="myChart" width="400" height="200"></canvas>
                  </div>    
              </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
          <div class="card">
            <div class="card-body">
                <h4>Posting History</h4>
                @php
                $heads = [
                    'ID',
                    'Account Name',
                    ['label' => 'Queued', 'width' => 40],
                    ['label' => 'Error', 'no-export' => true, 'width' => 5],
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
                        [22, 'John Bender', 0, 0],
                        [19, 'Sophia Clemens', 0, 0],
                        [3, 'Peter Sousa', 0, 0],
                    ],
                    'order' => [[1, 'asc']],
                    'columns' => [null, null, null, ['orderable' => false]],
                ];
                @endphp
                
                {{-- Minimal example / fill data using the component slot --}}
                <x-adminlte-datatable id="table1" :heads="$heads">
                    @foreach($config['data'] as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{!! $cell !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </x-adminlte-datatable>         
            </div>
          </div>
      </div>
  </div>
@stop
@section('js')
   
   
    <script>
      var text_count = @json($text_count);
      var image_count = @json($image_count);
      var video_count = @json($video_count);
      var date_array = @json($date_array);

        $(document).ready(function() {
            $("select").change(function(){
                if($(this).text() != "Chef")
                    {
                        alert('GMT '+$(this).val()+' time offset applied');
                    }
              });
        });       
            
            const labels = date_array;

            const data = {
            labels: labels,
            datasets: [{
                label: 'Text',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: text_count,
            },{
                label: 'Image',
                backgroundColor: 'rgb(60, 50, 132)',
                borderColor: 'rgb(60, 50, 132)',
                data: image_count,
            },{
                label: 'Video',
                backgroundColor: 'rgb(30, 150, 72)',
                borderColor: 'rgb(30, 150, 72)',
                data: video_count,
            }]
            };
            const config = {
            type: 'bar',
            data: data,
            options: {}
            };

            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );

       
    </script>    
@stop
