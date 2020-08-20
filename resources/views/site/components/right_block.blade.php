<!--Reed me for out (https://laravel.com/docs/5.7/queries#ordering-grouping-limit-and-offset {inRandomOrder})-->
    <div class="col-sm-3 col-sm-offset-1 blog-sidebar display-none-720px">
      <div class="sidebar-module sidebar-module-inset">

        <h4>About</h4>
        {!!$site -> short_description!!}
        <a href="{{route('about_us_page')}}">More</a>
          
      </div>
      
      <div class="sidebar-module">
        <h4>Climbing In Georgia</h4>
        <ol class="list-unstyled">
            <li> <a href="{{route('indoor_list')}}">Indoor climbing</a> </li>
            <li> <a href="{{route('outdoor_list')}}">Outdoor climbing</a> </li>
            <li> <a href="{{route('mount_list')}}">Mountainering</a> </li>
            <li> <a href="{{route('ice_list')}}">Ice and mix</a> </li>
            <li> <a href="{{route('other_list')}}">Other</a> </li>
            <li> <a href="{{route('about_us_page')}}">About as</a> </li>
        </ol>
      </div>
      
        <nav class="navbar fading-side-menu" data-spy="affix" data-offset-bottom="900" data-offset-top="900">
            
            <ul class="list-unstyled">

            <li><h4>Navigation Menu</h4></li>
                
          	@if(isset($mounts_system))
                <li>
                    <a href="#">
                        <span class="fa fa-angle-double-right text-primary"></span>Mount System Description
                    </a>
                </li>
            @endif            
            
            @if($article->text)
                <li>
                    <a href="#">
                        <span class="fa fa-angle-double-right text-primary"></span>Area Description
                    </a>
                </li>
            @endif

            @if($article->weather)
            
                <li>
                    <a href="#best_time_to_climb">
                        <span class="fa fa-angle-double-right text-primary"></span>Best time to climb
                    </a>
                </li>
                
            @endif

            @if($article->how_get)
            
                <li>
                    <a href="#how_to_get_there">
                        <span class="fa fa-angle-double-right text-primary"></span>How to get there
                    </a>
                </li>
                
            @endif
                
            @if($article->what_need)
            
                <li>
                    <a href="#what_you_need">
                        <span class="fa fa-angle-double-right text-primary"></span>What you need
                    </a>
                </li>
                
            @endif

            @if($article -> route || $article -> route_text)
            
                <li>
                    <a href="#routes">
                        <span class="fa fa-angle-double-right text-primary"></span>Routes
                    </a>
                </li>
                
            @endif              
            
            @if(isset($articles_gallery))
            
                <li>
                    <a href="#gallery">
                        <span class="fa fa-angle-double-right text-primary"></span>Gallery
                    </a>
                </li>
                
            @endif   
            
                <li>
                    <a href="#comments">
                        <span class="fa fa-angle-double-right text-primary"></span>Comments
                    </a>
                </li>
                
            @if (isset($other_article))
            
                <li>
                    <a href="#other">
                        <span class="fa fa-angle-double-right text-primary"></span>Other
                    </a>
                </li>
            @endif
            
            </ul>
            
        <!--</div>-->
        </nav>
      
    </div><!-- /.blog-sidebar -->
    
    <style>
        .right_navbar_sectors{margin-left: 1em;}
    </style>