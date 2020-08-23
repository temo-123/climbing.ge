<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://www.example.com/</loc>
        <changefreq>weekly</changefreq>
        <lastmod>2005-01-01</lastmod>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{route('indoor_list')}}</loc>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{route('outdoor_list')}}</loc>
        <changefreq>weekly</changefreq>
    </url>

    @if(isset($outdoors))
    @foreach($outdoors as $outdoor)
    <url>
        <loc> {{$outdoor -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$outdoor -> updated_at}} </lastmod>
    </url>
    @endforeach 
    @endif

    <url>
        <loc>{{route('ice_list')}}</loc>
        <changefreq>weekly</changefreq>
    </url>
    @if(isset($ice_and_mix))
    @foreach($ice_and_mix as $ice)
    <url>
        <loc> {{$ice -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$ice -> updated_at}} </lastmod>
    </url>
    @endforeach  
    @endif

    <url>
        <loc>{{route('other_list')}}</loc>
        <changefreq>weekly</changefreq>
    </url>
    @if(isset($others))
    @foreach($others as $other)
    <url>
        <loc> {{$other -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$other -> updated_at}} </lastmod>
    </url>
    @endforeach  
    @endif

    <url>
        <loc>{{route('mount_list')}}</loc>
        <changefreq>weekly</changefreq>
    </url>
    @if(isset($mountains))
    @foreach($mountains as $mountain)
    <url>
        <loc> {{$mountain -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$mountain -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif 



    @if(isset($news))
    @foreach($news as $new)
    <url>
        <loc> {{$new -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$new -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif  

    @if(isset($events))
    @foreach($events as $event)
    <url>
        <loc> {{$event -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$event -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif  

    @if(isset($partners))
    @foreach($partners as $partner)
    <url>
        <loc> {{$partner -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$partner -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif  

    @if(isset($securitys))
    @foreach($securitys as $security)
    <url>
        <loc> {{$security -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$security -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif 

    @if(isset($products))
    @foreach($products as $product)
    <url>
        <loc> {{$product -> title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$product -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif

</urlset>