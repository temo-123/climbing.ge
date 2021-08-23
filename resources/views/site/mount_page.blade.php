@extends('site.layouts.app')

@section('content')

@section('title',  $article[0]['title'] )
@section('meta_description',  $article[0]['short_description'] )
@section('meta_keyword',  $article[0]['title'] )
@section('meta_img',  asset('images/'.$image))

@section('created_at',  $article[0]['created_at'])
@section('updated_at',  $article[0]['updated_at'])

    <div class="container top_menu_margin">
        <div class="row">
            @if(isset($article))  
            {{-- @if($article->name)
            <h1 class="blog-title">{{ $article->name }}</h1> 
            @else
            <h1 class="blog-title">
                {{ $article->title }}
                @auth
                @if(Auth::user()->hasRole('admin'))
                @auth
                (<a href="#">Edit</a>)
                @endauth
                @endif
                @endauth
            </h1> 
            @endif --}}

            <div class="col-sm-8 blog-header">
                {{-- @component('site.components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') {{route($all_article_but)}} @endslot
                @slot('active') {{$article_map}} @endslot
                @if($article -> title)
                @slot('article') {{$article -> title}} @endslot
                @else
                @slot('article') {{$article -> name}} @endslot
                @endif
                @endcomponent --}}

                {{-- <p class="blog-post-meta">{{ $article->created_at }}</p> --}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-main">

                @if(isset($mounts_system))
                    @foreach($mounts_system as $mount_system)
                        <div class="mountain_masive_description">
                            <h2>Mountain Massive - <strong>{{$mount_system -> name}}</strong></h2>

                            {!!$mount_system -> text!!}

                            @if(($mount_system->what_need) != NUll)
                                <h2 id="what_you_need">What you need</h2>
                                {!!$mount_system->what_need!!}
                            @endif

                            @if(($mount_system->weather) != NULL)
                                <h2 id="best_time_to_climb">Best time to climb</h2>
                            @endif

                            @if(($mount_system->weather) == NULL)
                                {!!$mount_system->best_time!!}
                            @else 
                                <div class="row">
                                    <div class="col-md-6" style="margin-top: 5%;">
                                        {!!$mount_system->best_time!!}
                                    </div>
                                    <div class="col-md-6" style="text-align: center;">
                                        <div class="col-xs-12">
                                            {!!$mount_system->weather!!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(($mount_system->how_get) != NUll)
                                <h2 id="how_to_get_there">How to get there</h2>
                                {!!$mount_system->how_get!!}
                            @endif

                            @if(($mount_system->map) != NULL)
                                <div class="article_map">
                                    {!!$mount_system->map!!}
                                </div>
                            @endif
                            @break
                        </div>
                        
                    @endforeach
                @endif
            </div>
                    <h1>{{ $article[0]['title'] }}</h1>

                    @if($article[0]['text'] != NULL)
                            {!!$article [0]['text']!!}
                        @endif

                        @if($article['weather'] == NULL && $article[0]['best_time'] != NULL)
                            <h2 id="best_time_to_climb">Best time to climb</h2>
                            {!!$article[0]['best_time']!!}
                        @elseif($article['weather'] != NULL && $article[0]['best_time'] != NULL)
                            <h2 id="best_time_to_climb">Best time to climb</h2>

                            <div class="row">
                                <div class="col-md-6" style="margin-top: 5%;">
                                    {!!$article[0]['best_time']!!}
                                </div>
                                <div class="col-md-6" style="text-align: center;">
                                    {!!$article['weather']!!}
                                </div>
                            </div>
                        @endif

                        @if(($article[0]['how_get']) != NUll)
                            <h2 id="how_to_get_there">How to get there</h2>
                            {!!$article[0]['how_get']!!}
                        @endif

                        @if(($article['map']) != NULL)
                            <div class="article_map">
                                {!!$article['map']!!}
                            </div>
                        @endif


                        @if(($article[0]['info']) != NUll)
                            <h2 id="how_to_get_there">Info</h2>
                            {!!$article[0]['info']!!}
                        @endif

                        @if(($article[0]['what_need']) != NUll)
                            <h2 id="what_you_need">What you need</h2>
                            {!!$article[0]['what_need']!!}
                        @endif

                        @if($article [0]['route'])
                            <h2 id="routes">Route</h2>
                            {!!$article [0]['route']!!}
                        @endif  

            </div>
            
            @include('site.components.right_block')
            
        </div>

        @if(isset($comments))
        @if (Auth::guest())
            <comment-form-component :article_id="{{$article[0]['id']}}"></comment-form-component>
        @else
            <comment-form-component 
                    :article_id="{{$article[0]['id']}}" 
                    :user_name="name" 
                    :user_surname="surname" 
                    :is_login="true"
            ></comment-form-component>
        @endif
        @endif

        <a href="{{ URL::previous() }}" class='btn btn-primary'>Go Back</a>
        @if(isset($all_article_but))
        <a href="{{ route($all_article_but) }}" class="btn btn-primary">All Articles</a>
        @endif

        @if(isset($other_article))
        @include('site.components.other_article')
        @endif

        @else
        <h1>Variable Error 2! [Article block][$article]</h1>
        @endif
    </div>
</div>
@endsection