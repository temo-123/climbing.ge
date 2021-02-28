<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>http://shop.{{ config('app.url') }}/</loc>
        <lastmod>2004-10-01</lastmod>
        <url>
            {{-- <loc>{{route('product_list')}}</loc>
            <changefreq>weekly</changefreq> --}}
            @if(isset($products))
            @foreach($products as $product)
            <url>
                <loc> http://shop.{{ config('app.url') }}/product/{{ $product->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $product->updated_at }} </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('shop_about_us/') }}</loc>
            <changefreq>weekly</changefreq>
        </url>
    </sitemap>
    <sitemap>
        <loc>http://shop.{{ config('app.url') }}/ru/</loc>
        <lastmod>2005-01-01</lastmod>
        <url>
            {{-- <loc>{{ url('ru/product/') }}</loc>
            <changefreq>weekly</changefreq> --}}
            @if(isset($products))
            @foreach($products as $product)
            <url>
                <loc> http://shop.{{ config('app.url') }}/ru/product/{{ $product->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $product->updated_at }} </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ru/shop_about_us/') }}</loc>
            <changefreq>weekly</changefreq>
        </url>
    </sitemap>
    <sitemap>
        <loc>http://shop.{{ config('app.url') }}/ka/</loc>
        <lastmod>2005-01-01</lastmod>
        <url>
            {{-- <loc>{{ url('ka/product/') }}</loc>
            <changefreq>weekly</changefreq> --}}
            @if(isset($products))
            @foreach($products as $product)
            <url>
                <loc> http://shop.{{ config('app.url') }}/ka/product/{{ $product->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $product->updated_at }} </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ka/shop_about_us/') }}</loc>
            <changefreq>weekly</changefreq>
        </url>
    </sitemap>
</sitemapindex>