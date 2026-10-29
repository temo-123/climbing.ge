{{--
    Plain-HTML version of the page for crawlers that don't run JavaScript
    (filled by SeoService — see App\Services\Seo\SeoPageBody). Printed inside
    #app, so Vue replaces it on mount; the `js` class set in partials/seo
    hides it for JS users until then, so there's no flash. Same title, text
    and links as the Vue page — never anything the page doesn't show.
--}}
@php
    $seoBody   = ($seo ?? [])['body'] ?? null;
    $seoTrail  = (($seo ?? [])['breadcrumbs'] ?? null)['trail'] ?? [];
@endphp
@if($seoBody)
<div class="cg-ssr">
    @if(count($seoTrail))
    <nav aria-label="breadcrumb">
        <ol>
            @foreach($seoTrail as $i => $crumb)
                @if($i < count($seoTrail) - 1)
                <li><a href="{{ $crumb[1] }}">{{ $crumb[0] }}</a></li>
                @else
                <li aria-current="page">{{ $crumb[0] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
    @endif

    <main>
        <h1>{{ $seoBody['h1'] }}</h1>

        @if($seoBody['html'] !== '')
            {!! $seoBody['html'] !!}
        @elseif(!empty($seo['description']))
            <p>{{ $seo['description'] }}</p>
        @endif

        @if(count($seoBody['links']))
        <ul>
            @foreach($seoBody['links'] as $link)
            <li><a href="{{ $link[1] }}">{{ $link[0] }}</a></li>
            @endforeach
        </ul>
        @endif
    </main>
</div>
@endif
