@extends('site.layouts.app')

@section('content')

@section('title',  $article[0]['title'] )
@section('meta_description',  $article[0]['short_description'] )
@section('meta_keyword',  $article[0]['title'] )
@section('meta_img',  asset('images/'.$image))

@section('created_at',  $article[0]['created_at'])
@section('updated_at',  $article[0]['updated_at'])

<div class="container top_menu_margin">
    {{-- <div class="container"> --}}
    <div class='row'>
            
        <div class="col-md-6">
            @component('site.components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') {{route('index')}} @endslot
                @slot('active') article @endslot
                @slot('article') {{ $article[0]['title'] }} @endslot
            @endcomponent
            <p class="calendar">
                {{ $article ['start_data_day'] }}<em>{!!$article['start_data_month']!!}</em>
            </p>
            {{ $event_countdown }}
            <hr>
            {!!$article[0]['text']!!}
        </div>
        
        <div class="col-md-6">
            <div style="text-align: center;">
                {!!$article['map']!!}
            </div>
            {{-- {{ dd($article['id']) }} --}}
            @if(Auth::guest())
                <div style="text-align: center; margin: 4% 0 4% 0;">
                    <a href="{{ route('events_interes', array('events_id'=>$article['id'], 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                </div>
            @else
                @auth
                @if(isset($interested))
                    @forelse($interested as $iteres)
                        @if(Auth::user()['id'] == $iteres['user_id'])
                            <div style="text-align: center; margin: 4% 0 4% 0;">
                                <a href="{{ route('events_interes', array('events_id'=>$article['id'], 'actions'=>'del')) }}" type="button" class="btn btn-info" style="width: 100%;">No Interested</a>
                            </div>
                        @else
                            <div style="text-align: center; margin: 4% 0 4% 0;">
                                <a href="{{ route('events_interes', array('events_id'=>$article['id'], 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                            </div>
                        @endif
                    @empty
                    <div style="text-align: center; margin: 4% 0 4% 0;">
                        <a href="{{ route('events_interes', array('events_id'=>$article['id'], 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                    </div>
                    @endforelse
                @endif
                @endauth
            @endif


            @if($article[0]['info'] != NULL)
            <h2>Contact Information</h2>
            {!!$article[0]['info']!!}
            @endif
            
            <div class="col-md-12" style='margin: 20px;'> 
                <ul class="social-network social-circle" style="text-align: center;">
                    
                    @if(($article['fb_link']) != NULL)       
                      <li>
                        <a target="_blank" href="{{$article['fb_link']}}" class="icoFacebook ico_color" title="Facebook">
                          <i class="fa fa-facebook"></i>
                        </a>
                      </li>
                    @endif
        
                    @if(($article['twit_link']) != NULL) 
                    <li>
                      <a target="_blank" href="{{$article['twit_link']}}" class="icoTwitter ico_color" title="Twitter">
                        <i class="fa fa-twitter"></i>
                      </a>
                    </li>
                    @endif
        
                    @if(($article['google_link']) != NULL) 
                    <li>
                      <a target="_blank" href="{{$article['google_link']}}" class="icoGoogle ico_color" title="Google +">
                        <i class="fa fa-google-plus"></i>
                      </a>
                    </li>
                    @endif
        
                    @if(($article['inst_link']) != NULL) 
                    <li>
                      <a target="_blank" href="{{$article['inst_link']}}" class="icoLinkedin ico_color" title="Instagram">
                        <i class="fa fa-instagram"></i>
                      </a>
                    </li>
                    @endif
                    
                </ul>
            </div>
            
        </div>

    </div>

    @if(isset($comments))
        @if (Auth::guest())
            <comment-form-component :article_id="{{$article['id']}}"></comment-form-component>
        @else
            <comment-form-component 
                    :article_id="{{$article['id']}}" 
                    :user_name="name" 
                    :user_surname="surname" 
                    :is_login="true"
            ></comment-form-component>
        @endif
    @endif
</div>
@endsection