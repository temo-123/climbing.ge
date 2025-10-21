<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Selected Articles Export</title> -->
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 5px;
            line-height: 1.4;
            font-size: 12px;
        }
        .article {
            /* margin-bottom: 15px; */
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .article-title {
            font-size: 14px;
            font-weight: bold;
            /* margin-bottom: 5px; */
            color: #333;
        }
        .article-content {
            /* margin-bottom: 5px; */
            text-align: justify;
        }
        .sector {
            margin-top: 10px;
            padding: 5px;
            background-color: #f0f0f0;
            border-left: 3px solid #007bff;
        }
        .sector-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        h1 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 21px;
            color: #484848;;
        }
        h2 {
            font-size: 18px;
            margin-bottom: 3px;
            color: #484848;;
        }
        h3 {
            font-size: 16px;
            margin-bottom: 2px;
            color: #484848;;
        }
    </style>
</head>
<body>
    <h1>
        @if($locale === 'all')
            Export (Georgian / English)
        @else
            {{ $translations[$locale]['export_title'] }}
        @endif
    </h1>

    @foreach($localized_articles as $item)
        <div class="article">
            @php
                $globalData = $item['global_data'];
                $localeData = $item['locale_data'];
                $articleLocale = $item['locale'];
                $trans = $translations[$articleLocale];
            @endphp

            <div class="article-title">
                <h1>
                    @if($localeData && $localeData->title)
                        {!! $localeData->title !!}
                    @else
                        {{ $globalData->url_title }}
                    @endif
                </h1>
            </div>

            @if($localeData)
                @if($localeData->text)
                    <div class="article-content">{!! $localeData->text !!}</div>
                @endif
                @if($localeData->short_description)
                    <div class="article-content"><h2>{{ $trans['description'] }}:</h2> {!! $localeData->short_description !!}</div>
                @endif
                @if($localeData->route)
                    <div><h2>{{ $trans['route'] }}:</h2> {!! $localeData->route !!}</div>
                @endif
                @if($localeData->how_get)
                    <div><h2>{{ $trans['how_get'] }}:</h2> {!! $localeData->how_get !!}</div>
                @endif
                @if($localeData->best_time)
                    <div><h2>{{ $trans['best_time'] }}:</h2> {!! $localeData->best_time !!}</div>
                @endif
                @if($localeData->what_need)
                    <div><h2>{{ $trans['what_need'] }}:</h2> {!! $localeData->what_need !!}</div>
                @endif
                @if($localeData->info)
                    <div><h2>{{ $trans['info'] }}:</h2> {!! $localeData->info !!}</div>
                @endif
                @if($localeData->price_text)
                    <div><h2>{{ $trans['price'] }}:</h2> {!! $localeData->price_text !!}</div>
                @endif
                @if($localeData->address)
                    <div><h2>{{ $trans['address'] }}:</h2> {!! $localeData->address !!}</div>
                @endif
            @endif

            <div><h3>{{ $trans['url_title'] }}:</h3> {{ $globalData->url_title }}</div>
            <div><h3>{{ $trans['category'] }}:</h3> {{ $globalData->category }}</div>
            <div><h3>{{ $trans['published'] }}:</h3> {{ $globalData->published ? $trans['yes'] : $trans['no'] }}</div>
        </div>
    @endforeach

    @if(isset($sectors) && $sectors->count() > 0)
        <h2>{{ $translations['ka']['sectors'] }}</h2>
        @foreach($sectors as $sector)
            <div class="sector">
                <div class="sector-title">{{ $sector->name }}</div>
                @if(isset($sector->description))
                    <div>{{ $sector->description }}</div>
                @endif
            </div>
        @endforeach
    @endif
</body>
</html>
