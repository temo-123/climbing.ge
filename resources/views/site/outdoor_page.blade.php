@extends('site.layouts.app')

@section('content')


    @section('meta_title',  $article->title )
    @if ($article->short_description != NULL)
        @section('meta_description',  $article->short_description )
    @else
        @section('meta_description',  $article->title )
    @endif
    @if ($article->meta_keyword != NULL)
        @section('meta_keyword',  $article->meta_keyword )
    @else
        @section('meta_keyword',  $article->title )
    @endif
    @if(isset($image_dir))
        @section('meta_img',  asset('images/'.$image_dir.'/'.$article->image))
    @endif

        <div class="container top_menu_margin">

            <div class="row">
                @if(isset($article))  
                @if($article->name)
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
                @endif

                <div class="col-sm-8 blog-header">
                    @component('site.components.breadcrumb')
                    @slot('parent') Home @endslot
                    @slot('link') {{route($all_article_but)}} @endslot
                    @slot('active') {{$article_map}} @endslot
                    @if($article -> title)
                    @slot('article') {{$article -> title}} @endslot
                    @else
                    @slot('article') {{$article -> name}} @endslot
                    @endif
                    @endcomponent

                    <p class="blog-post-meta">{{ $article->created_at }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 blog-main">

                    @if($article->text != NULL)
                        {!!$article -> text!!}
                    @endif

                    @if(($article->address) != NUll)
                        <h2 id="how_to_get_there">Address</h2>
                        {!!$article->address!!}
                    @endif

                    @if(($article->how_get) != NUll)
                        <h2 id="how_to_get_there">How to get there</h2>
                        {!!$article->how_get!!}
                    @endif

                    @if(($article->map) != NULL)
                        <div class="article_map">
                            {!!$article->map!!}
                        </div>
                    @endif

                    @if(($article->prices_text) != NUll)
                        <h2 id="how_to_get_there">Prices</h2>
                        {!!$article->prices_text!!}
                    @endif

                    @if(($article->info) != NUll)
                        <h2 id="how_to_get_there">Info</h2>
                        {!!$article->info!!}
                    @endif

                    @if(($article->what_need) != NUll)
                        <h2 id="what_you_need">What you need</h2>
                        {!!$article->what_need!!}
                    @endif

                    @if($article -> route)
                    <h2 id="routes">Route</h2>
                    {!!$article -> route!!}
                    @endif             


                    @if(isset($sport_route_sector))
                    @include('site.components.route')
                    @endif
                    
                    @if(isset($article_gallery_count))
                    @if($article_gallery_count > 0)
                    @include('site.components.article_gallery')
                    @endif
                    @endif
                    
                </div>
                
                @include('site.components.right_block')
                
            </div> 
      
            @if(isset($comments))
            @if (Auth::guest())
                <comment-form-component :article_id="{{$article->id}}"></comment-form-component>
            @else
                <comment-form-component 
                        :article_id="{{$article->id}}" 
                        :user_name="name" 
                        :user_surname="surname" 
                        :is_login="true"
                ></comment-form-component>
            @endif
            @endif

            <a href="{{ URL::previous() }}" class='btn'>Go Back</a>
            @if(isset($all_article_but))
            <a href="{{ route($all_article_but) }}" class="btn">All Articles</a>
            @endif

            @if(isset($other_article))
            @include('site.components.other_article')
            @endif

            @else
            <h1>Variable Error 2! [Article block][$article]</h1>
            @endif
        </div>

        @endsection