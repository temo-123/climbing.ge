<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{route('shop_list')}}</loc>
        <changefreq>weekly</changefreq>
    </url>
    @if(isset($products))
    @foreach($products as $product)
    <url>
        <loc> {{$product -> url_title}} </loc>
        <changefreq>weekly</changefreq>
        <lastmod> {{$product -> updated_at}} </lastmod>
    </url>
    @endforeach
    @endif 

</urlset>