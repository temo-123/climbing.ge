<div class="footer__graphic"></div>

<div class="footer"> 
    <div class="container"> 
        <div class="row">

            <div class="col-md-4">
                <div class="f-about" style='color: #fff;'>
                    
                    <h4>About as</h4>
                    
                    {!!$site -> short_description!!}
                    <a href="{{route('about_us_page')}}">More</a>
                    
                </div>
            </div>

            <div class="col-md-4">
                <div class="f-links">
                    <h4>Links</h4>
                    <ul>
                    <li> <a href="{{route('indoor_list')}}">Indoor climbing</a> </li>
                    <li> <a href="{{route('outdoor_list')}}">Outdoor climbing</a> </li>
                    <li> <a href="{{route('mount_list')}}">Mountainering</a> </li>
                    <li> <a href="{{route('ice_list')}}">Ice and mix</a> </li>
                    <li> <a href="{{route('other_list')}}">Other</a> </li>
                    <li> <a href="{{route('about_us_page')}}">About as</a> </li>
                    <li> <a href="{{route('login')}}">Login</a> </li>
                    <li> <a href="{{url('sitemap')}}">Sitemap</a> </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="flick">
                    <h4>Share</h4>
                </div>
            </div>
            
        </div>
    </div>
</div>