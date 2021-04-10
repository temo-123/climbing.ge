@if(isset($area_info))
@foreach($area_info as $area)

      @if($area['routes'] != 0 || $area['mtps'] != 0)
      <div class="row">
            <h2 style="font-size: 160%">Sector name <strong>{{$area['sectors']['name']}}</strong></h2>
      </div>
      <div class="row">
            <div class="col-md-6">
                  @if ($area['sectors']['in_shade_after_10']!=null)
                        <img class="sun_svg" src="{{asset('images/svg/in shade after 10.00 am.svg')}}" alt="In shade after 10.00 am" title="In shade after 10.00 am">
                  @endif
                  @if ($area['sectors']['in_shade_after_15']!=null)
                        <img class="sun_svg" src="{{asset('images/svg/in shade after 15.00 pm.svg')}}" alt="In shade after 15.00 pm" title="In shade after 15.00 pm">
                  @endif
                  @if ($area['sectors']['in_the_shade_befornoon']!=null)
                        <img class="sun_svg" src="{{asset('images/svg/in shade befor noon.svg')}}" alt="In shade befor noon" title="In shade befor noon">
                  @endif
                  @if ($area['sectors']['in_the_shade_afternoon']!=null)
                        <img class="sun_svg" src="{{asset('images/svg/in shade in the afternoon.svg')}}" alt="In shade in the afternoon" title="In shade in the afternoon">
                  @endif
                  @if ($area['sectors']['all_day_in_shade']!=null)
                        <img class="sun_svg" src="{{asset('images/svg/in the shade whole day.svg')}}" alt="In the shade whole day" title="In the shade whole day">
                  @endif
                  @if ($area['sectors']['all_day_in_sun']!=null)
                        <img class="sun_svg" src="{{asset('images/svg/in the sun the whole day.svg')}}" alt="In the sun the whole day" title="In the sun the whole day">
                  @endif
            </div>
            <div class="col-md-6">
                  @if ($area['sectors']['overhang']!=null)
                        <img class="relief_svg" src="{{asset('images/svg/overhang.svg')}}" alt="Overhang" title="Overhang">
                  @endif
                  @if ($area['sectors']['slabby']!=null)
                        <img class="relief_svg" src="{{asset('images/svg/slabby.svg')}}" alt="Slabby" title="Slabby">
                  @endif
                  @if ($area['sectors']['vertical']!=null)
                        <img class="relief_svg" src="{{asset('images/svg/vertical.svg')}}" alt="Vertical" title="Vertical">
                  @endif
            </div>
      </div>

      {!! $area['sectors']['text'] !!}
      
      @if($area['sectors']['sector_img'] != NULL)
      <div class="row">
            @foreach($area['sectors']['sector_img'] as $sector_img)
                  <img style="width: {{ $sector_img['sector_img_size'] }}%" src="{{ asset('images/sector_img/'.$sector_img['image']) }}" alt="">
            @endforeach
      </div>
      @endif

      @endif

      @if($area['routes'] != 0)              
            <table class="table col-md-12 table table-hover" >
              <tbody>
                  <tr>
                        <td>N</td>
                        <td>Name</td>
                        <td>Height</td>
                        <td>Bolts</td>
                        <td>Gread FR</td>
                        <td class="display-none-720px">Gread YDS</td> 
                  </tr>
              </tbody>
            @foreach($area['routes'] as $route_loop)
                  <tbody>
                        <tr> 
                              <td>{{$route_loop['num']}}</td>
                              <td>{{$route_loop['name']}}</td>
                              <td>{{$route_loop['height']}}</td>
                              <td>{{$route_loop['bolts']}}</td>
                              <td>{{$route_loop['grade_fr']}}</td>
                              <td>{{$route_loop['grade_yds']}}</td>
                        </tr>
                  </tbody>
            @endforeach
            </table>
      @endif
      @if($area['mtps'] != 0)
      <div style='display:none;'>{{$mtp_index = 0}}</div>
            @foreach($area['mtps'] as $mtp)
            <div style='display:none;'>{{$mtp_rope_index = 0}}</div>
            <div class ='row'>
                  <div class='col-xs-6 col-md-6 col-lg-6'>
                        <h3><strong>{{$area['mtps'][$mtp_index]['mtp name']}}</strong></h3>
                  </div>
                  
                  <div class='col-md-6'>
                        <a href="#" data-toggle="modal" data-target="#squarespaceModal_mtp_info_">
                        <h4><strong>More information</strong></h4>
                        </a>
                  </div>
            </div>

            <table class="table col-md-12 table table-hover" id="dev-table">
                  <tbody>
                        <tr>
                              <td>N</td>
                              <td>Name</td>
                              <td>Height</td>
                              <td>Bolts</td>
                              <td>Gread FR</td>
                              <td class="display-none-720px">Gread YDS</td>
                        </tr>
                  </tbody>
                  @foreach($area['mtps'][$mtp_index]['mtp pitchs'] as $mtp_pitchs)
                  <tbody>
                        <tr>  
                              <td>{{$mtp_pitchs['pitch num']}}</td>
                              <td>{{$mtp_pitchs['pitch name']}}</td>
                              <td>{{$mtp_pitchs['pitch height']}}</td>
                              <td>{{$mtp_pitchs['pitch bolts']}}</td>
                              <td>{{$mtp_pitchs['pitch grade fr']}}</td>
                              <td>{{$mtp_pitchs['pitch grade yds']}}</td>
                        </tr>
                  </tbody>
                  <div style='display:none;'>{{$mtp_rope_index++}}</div>
                  @endforeach
            </table>
            <div style='display:none;'>{{$mtp_index++}}</div>
      @endforeach
      @endif
@endforeach
@endif