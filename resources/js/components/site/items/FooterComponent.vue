<template>
    <div class="footer"> 
        <div class="container"> 
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about" style='color: #fff;'>
                        
                        <h4>@lang('site.about us')</h4>
                        
                        <!-- {!!$site -> short_description!!}
                        <a href="{{route('about_us_page')}}">@lang('site.more')</a> -->
                        
                    </div>
                </div>

                <div class="col-md-4 display-none-720px">
                    <div class="f-links">
                        <h4>@lang('site.links')</h4>
                        <ul>
                        <!-- <li> <a href="{{route('indoor_list')}}">@lang('site.menu indoor climbing')</a> </li>
                        <li> <a href="{{route('outdoor_list')}}">@lang('site.menu outdoor climbing')</a> </li>
                        <li> <a href="{{route('mount_list')}}">@lang('site.menu mountaineering')</a> </li>
                        <li> <a href="{{route('ice_list')}}">@lang('site.menu ice climbing')</a> </li>
                        <li> <a href="{{route('other_list')}}">@lang('site.menu other')</a> </li>
                        <li> <a href="{{route('about_us_page')}}">@lang('site.menu about us')</a> </li>
                        <li> <a href="{{route('login')}}">@lang('site.menu login')</a> </li>
                        <li> <a href="{{url('/sitemap')}}">@lang('site.menu sitemap')</a> </li> -->
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="flick">
                        <h4>@lang('site.links')</h4>
                        <!-- <share-component route={{ URL::current() }}></share-component> -->
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>

</script>