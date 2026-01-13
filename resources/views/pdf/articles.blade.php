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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .sector-section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .sector-info {
            margin-bottom: 10px;
        }
        .route-table, .mtp-table {
            margin-top: 10px;
        }
        .sector-images {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 10px 0;
        }
        .sector-images img {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>


    <h1>
        @if($locale === 'all')
            Export (Georgian / English)
        @else
            {{ isset($translations[$locale]) && isset($translations[$locale]['export_title']) ? $translations[$locale]['export_title'] : 'Export' }}
        @endif
    </h1>

    @foreach($localized_articles as $item)
        <div class="article">



            @php
                $globalData = $item['global_data'] ?? null;
                $localeData = $item['locale_data'] ?? null;
                $articleLocale = $item['locale'] ?? '';
                $trans = !empty($articleLocale) && isset($translations[$articleLocale]) ? $translations[$articleLocale] : [];
            @endphp

            <div class="article-title">
                <h1>
                    @if($localeData && $localeData->title)
                        {!! $localeData->title !!}
                    @elseif($globalData && $globalData->url_title)
                        {{ $globalData->url_title }}
                    @else
                        {{ 'Untitled Article' }}
                    @endif
                </h1>
            </div>



            @if($localeData)
                @if($localeData->text)
                    <div class="article-content">{!! $localeData->text !!}</div>
                @endif
                @if($localeData->short_description)
                    <div class="article-content"><h2>{{ isset($trans['description']) ? $trans['description'] : 'Description' }}:</h2> {!! $localeData->short_description !!}</div>
                @endif
                @if($localeData->route)
                    <div><h2>{{ isset($trans['route']) ? $trans['route'] : 'Routes' }}:</h2> {!! $localeData->route !!}</div>
                @endif
                @if($localeData->how_get)
                    <div><h2>{{ isset($trans['how_get']) ? $trans['how_get'] : 'How to Get There' }}:</h2> {!! $localeData->how_get !!}</div>
                @endif
                @if($localeData->best_time)
                    <div><h2>{{ isset($trans['best_time']) ? $trans['best_time'] : 'Best Time to Climb' }}:</h2> {!! $localeData->best_time !!}</div>
                @endif
                @if($localeData->what_need)
                    <div><h2>{{ isset($trans['what_need']) ? $trans['what_need'] : 'What You Need' }}:</h2> {!! $localeData->what_need !!}</div>
                @endif
                @if($localeData->info)
                    <div><h2>{{ isset($trans['info']) ? $trans['info'] : 'Info' }}:</h2> {!! $localeData->info !!}</div>
                @endif
                @if($localeData->price_text)
                    <div><h2>{{ isset($trans['price']) ? $trans['price'] : 'Price' }}:</h2> {!! $localeData->price_text !!}</div>
                @endif
                @if($localeData->address)
                    <div><h2>{{ isset($trans['address']) ? $trans['address'] : 'Address' }}:</h2> {!! $localeData->address !!}</div>
                @endif
            @endif


            <div><h3>{{ isset($trans['url_title']) ? $trans['url_title'] : 'URL Title' }}:</h3> {{ $globalData && $globalData->url_title ? $globalData->url_title : 'N/A' }}</div>
            <div><h3>{{ isset($trans['category']) ? $trans['category'] : 'Category' }}:</h3> {{ $globalData && $globalData->category ? $globalData->category : 'N/A' }}</div>
            <div><h3>{{ isset($trans['published']) ? $trans['published'] : 'Published' }}:</h3> {{ $globalData && $globalData->published ? (isset($trans['yes']) ? $trans['yes'] : 'Yes') : (isset($trans['no']) ? $trans['no'] : 'No') }}</div>
        </div>
    @endforeach



    @if(isset($sectors_data) && count($sectors_data) > 0)
        <h2>Sectors and Routes Export</h2>
        @foreach($sectors_data as $sectorData)
            <div class="sector-section">
                <h3>{{ isset($sectorData['article']) && isset($sectorData['article']['title']) ? $sectorData['article']['title'] : 'Unknown Article' }} ({{ isset($sectorData['article']) && isset($sectorData['article']['category']) ? $sectorData['article']['category'] : 'Unknown Category' }})</h3>
                
                <div class="sector-info">
                    <h4>Sector: {{ isset($sectorData['sector']) && isset($sectorData['sector']['name']) ? $sectorData['sector']['name'] : 'Unknown Sector' }}</h4>
                    @if(isset($sectorData['sector']) && isset($sectorData['sector']['text']) && $sectorData['sector']['text'])
                        <p><strong>Description:</strong> {{ $sectorData['sector']['text'] }}</p>
                    @endif
                    
                    @if(isset($sectorData['sector']) && (
                        $sectorData['sector']['all_day_in_shade'] ?? false || 
                        $sectorData['sector']['all_day_in_sun'] ?? false || 
                        $sectorData['sector']['in_the_shade_afternoon'] ?? false || 
                        $sectorData['sector']['slabby'] ?? false || 
                        $sectorData['sector']['vertical'] ?? false || 
                        $sectorData['sector']['overhang'] ?? false || 
                        $sectorData['sector']['roof'] ?? false || 
                        $sectorData['sector']['for_family'] ?? false || 
                        $sectorData['sector']['for_kids'] ?? false || 
                        $sectorData['sector']['wolking_time'] ?? false || 
                        $sectorData['sector']['is_helmet'] ?? false))
                        <p><strong>Characteristics:</strong></p>
                        <ul>
                            @if(isset($sectorData['sector']['all_day_in_shade']) && $sectorData['sector']['all_day_in_shade']) <li>All day in shade</li> @endif
                            @if(isset($sectorData['sector']['all_day_in_sun']) && $sectorData['sector']['all_day_in_sun']) <li>All day in sun</li> @endif
                            @if(isset($sectorData['sector']['in_the_shade_afternoon']) && $sectorData['sector']['in_the_shade_afternoon']) <li>In shade in afternoon</li> @endif
                            @if(isset($sectorData['sector']['in_the_shade_befornoon']) && $sectorData['sector']['in_the_shade_befornoon']) <li>In shade in forenoon</li> @endif
                            @if(isset($sectorData['sector']['in_shade_after_10']) && $sectorData['sector']['in_shade_after_10']) <li>In shade after 10:00</li> @endif
                            @if(isset($sectorData['sector']['in_shade_after_15']) && $sectorData['sector']['in_shade_after_15']) <li>In shade after 15:00</li> @endif
                            @if(isset($sectorData['sector']['slabby']) && $sectorData['sector']['slabby']) <li>Slabby</li> @endif
                            @if(isset($sectorData['sector']['vertical']) && $sectorData['sector']['vertical']) <li>Vertical</li> @endif
                            @if(isset($sectorData['sector']['overhang']) && $sectorData['sector']['overhang']) <li>Overhang</li> @endif
                            @if(isset($sectorData['sector']['roof']) && $sectorData['sector']['roof']) <li>Roof</li> @endif
                            @if(isset($sectorData['sector']['for_family']) && $sectorData['sector']['for_family']) <li>For family</li> @endif
                            @if(isset($sectorData['sector']['for_kids']) && $sectorData['sector']['for_kids']) <li>For kids</li> @endif
                            @if(isset($sectorData['sector']['wolking_time']) && $sectorData['sector']['wolking_time']) <li>Walking time: {{ $sectorData['sector']['wolking_time'] }}</li> @endif
                            @if(isset($sectorData['sector']['is_helmet']) && $sectorData['sector']['is_helmet']) <li>Helmet required</li> @endif
                        </ul>
                    @endif
                </div>

                @if(isset($sectorData['images']) && count($sectorData['images']) > 0)
                    <div class="sector-images">
                        @foreach($sectorData['images'] as $image)
                            <img src="{{ public_path('images/sector_img/' . $image->image) }}" alt="{{ $image->title ?? 'Sector image' }}">
                        @endforeach
                    </div>
                @endif

                @if(isset($sectorData['routes']) && count($sectorData['routes']) > 0)
                    <div class="route-table">
                        <h4>Routes</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Grade</th>
                                    <th>Original Grade</th>
                                    <th>Height</th>
                                    <th>Bolts</th>
                                    <th>Anchor Type</th>
                                    <th>Author</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectorData['routes'] as $route)
                                    <tr>
                                        <td>{{ ucfirst($route['type'] ?? 'Unknown') }}</td>
                                        <td>{{ $route['title'] ?? 'N/A' }}</td>
                                        <td>{{ $route['grade'] ?? 'N/A' }}</td>
                                        <td>{{ $route['or_grade'] ?? 'N/A' }}</td>
                                        <td>{{ $route['height'] ?? 'N/A' }}</td>
                                        <td>{{ $route['bolts'] ?? 'N/A' }}</td>
                                        <td>{{ $route['anchor_type'] ?? 'N/A' }}</td>
                                        <td>{{ $route['author'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(isset($sectorData['mtps']) && count($sectorData['mtps']) > 0)
                    <div class="mtp-table">
                        <h4>MTPs (Mountaineering Routes)</h4>
                        @foreach($sectorData['mtps'] as $mtp)
                            <div style="margin-bottom: 15px; border: 1px solid #eee; padding: 10px;">
                                <h5>{{ $mtp['title'] ?? 'Unknown MTP' }}</h5>
                                @if(isset($mtp['text']) && $mtp['text'])
                                    <p><strong>Description:</strong> {{ $mtp['text'] }}</p>
                                @endif
                                <p><strong>Height:</strong> {{ $mtp['height'] ?? 'N/A' }} | <strong>Last Carabiner:</strong> {{ $mtp['last_carabin'] ?? 'N/A' }}</p>
                                
                                @if(isset($mtp['pitchs']) && count($mtp['pitchs']) > 0)
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Pitch</th>
                                                <th>Grade</th>
                                                <th>Height</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mtp['pitchs'] as $pitch)
                                                <tr>
                                                    <td>{{ $pitch['num'] ?? 'N/A' }}</td>
                                                    <td>{{ $pitch['grade'] ?? 'N/A' }}</td>
                                                    <td>{{ $pitch['height'] ?? 'N/A' }}</td>
                                                    <td>{{ $pitch['description'] ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</body>
</html>
