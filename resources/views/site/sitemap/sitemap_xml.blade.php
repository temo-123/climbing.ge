<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>http://{{ config('app.url') }}/</loc>
        <lastmod>2004-10-01</lastmod>
        <url>
            <loc>{{route('indoor_list')}}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($indoors))
            @foreach($indoors as $indoor)
            <url>
                <loc> http://{{ config('app.url') }}/indoor/{{ $indoor->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $indoor->updated_at }} </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{route('outdoor_list')}}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($outdoors))
            @foreach($outdoors as $outdoor)
            <url>
                <loc> http://{{ config('app.url') }}/outdoor/{{ $outdoor->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $outdoor->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{route('ice_list')}}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($ice_and_mix))
            @foreach($ice_and_mix as $ice)
            <url>
                <loc> http://{{ config('app.url') }}/ice/{{ $ice->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $ice->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{route('other_list')}}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($others))
            @foreach($others as $other)
            <url>
                <loc> http://{{ config('app.url') }}/other/{{ $other->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $other->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{route('mount_list')}}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($mountains))
            @foreach($mountains as $mountain)
            <url>
                <loc> http://{{ config('app.url') }}/mountaineering/{{ $mountain->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $mountain->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif 
        </url>
        <url>
            <loc>{{ url('about_us/') }}</loc>
            <changefreq>weekly</changefreq>
        </url>
    </sitemap>
    <sitemap>
        <loc>http://{{ config('app.url') }}/ru/</loc>
        <lastmod>2005-01-01</lastmod>
        <url>
            <loc>{{ url('ru/indoor/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($indoors))
            @foreach($indoors as $indoor)
            <url>
                <loc> http://{{ config('app.url') }}/ru/indoor/{{ $indoor->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $indoor->updated_at }} </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ru/outdoor/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($outdoors))
            @foreach($outdoors as $outdoor)
            <url>
                <loc> http://{{ config('app.url') }}/ru/outdoor/{{ $outdoor->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $outdoor->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ru/ice_and_mix/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($ice_and_mix))
            @foreach($ice_and_mix as $ice)
            <url>
                <loc> http://{{ config('app.url') }}/ru/ice/{{ $ice->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $ice->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ru/other/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($others))
            @foreach($others as $other)
            <url>
                <loc> http://{{ config('app.url') }}/ru/other/{{ $other->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $other->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ru/mountaineering/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($mountains))
            @foreach($mountains as $mountain)
            <url>
                <loc> http://{{ config('app.url') }}/ru/mountaineering/{{ $mountain->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $mountain->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif 
        </url>
        <url>
            <loc>{{ url('ru/about_us/') }}</loc>
            <changefreq>weekly</changefreq>
        </url>
    </sitemap>
    <sitemap>
        <loc>http://{{ config('app.url') }}/ka/</loc>
        <lastmod>2005-01-01</lastmod>
        <url>
            <loc>{{ url('ka/indoor/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($indoors))
            @foreach($indoors as $indoor)
            <url>
                <loc> http://{{ config('app.url') }}/ka/indoor/{{ $indoor->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $indoor->updated_at }} </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ka/outdoor/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($outdoors))
            @foreach($outdoors as $outdoor)
            <url>
                <loc> http://{{ config('app.url') }}/ka/outdoor/{{ $outdoor->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $outdoor->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ka/ice_and_mix/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($ice_and_mix))
            @foreach($ice_and_mix as $ice)
            <url>
                <loc> http://{{ config('app.url') }}/ka/ice/{{ $ice->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $ice->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ka/other/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($others))
            @foreach($others as $other)
            <url>
                <loc> http://{{ config('app.url') }}/ka/other/{{ $other->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $other->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif
        </url>
        <url>
            <loc>{{ url('ka/mountaineering/') }}</loc>
            <changefreq>weekly</changefreq>
            @if(isset($mountains))
            @foreach($mountains as $mountain)
            <url>
                <loc> http://{{ config('app.url') }}/ka/mountaineering/{{ $mountain->url_title }} </loc>
                <changefreq>weekly</changefreq>
                <lastmod> {{ $mountain->updated_at }}  </lastmod>
            </url>
            @endforeach
            @endif 
        </url>
        <url>
            <loc>{{ url('ka/about_us/') }}</loc>
            <changefreq>weekly</changefreq>
        </url>
    </sitemap>
</sitemapindex>