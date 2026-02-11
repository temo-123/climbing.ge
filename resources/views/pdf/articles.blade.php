<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pdf_title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pdf/style.css') }}">
</head>
<body>

    @foreach($localized_articles as $item)
        <div class="article">

            @php
                $globalData = $item['global_data'] ?? null;
                $localeData = $item['locale_data'] ?? null;
                $articleLocale = $item['locale'] ?? '';
                $trans = !empty($articleLocale) && isset($translations[$articleLocale]) ? $translations[$articleLocale] : [];
                
                $articleTitle = '';
                if ($localeData && !empty($localeData->title)) {
                    $articleTitle = $localeData->title;
                } elseif ($globalData && !empty($globalData->url_title)) {
                    $articleTitle = $globalData->url_title;
                } else {
                    $articleTitle = 'Untitled Article';
                }
                
                $hasText = $localeData && !empty($localeData->text);
                $hasShortDesc = $localeData && !empty($localeData->short_description);
                $hasRoute = $localeData && !empty($localeData->route);
                $hasHowGet = $localeData && !empty($localeData->how_get);
                $hasBestTime = $localeData && !empty($localeData->best_time);
                $hasWhatNeed = $localeData && !empty($localeData->what_need);
                $hasInfo = $localeData && !empty($localeData->info);
                $hasPriceText = $localeData && !empty($localeData->price_text);
                $hasAddress = $localeData && !empty($localeData->address);
                
                $hasGlobalData = $globalData !== null;
            @endphp

            <div class="article-title">
                <h1>{!! $articleTitle !!}</h1>
            </div>

            @if($hasGlobalData && !empty($globalData->image))
                @php
                    $articleImgPath = '';
                    $articleImgName = is_array($globalData) ? ($globalData['image'] ?? '') : ($globalData->image ?? '');
                    
                    if (!empty($articleImgName)) {
                        $articleImgPaths = [
                            public_path('images/' . $articleImgName),
                            public_path('images/' . strtolower($articleImgName)),
                            public_path('images/article_img/' . $articleImgName),
                            public_path('images/article_img/' . strtolower($articleImgName)),
                            public_path($articleImgName),
                        ];
                        foreach ($articleImgPaths as $path) {
                            if (file_exists($path) && is_readable($path)) {
                                $articleImgPath = $path;
                                break;
                            }
                        }
                    }
                @endphp
                @if(!empty($articleImgPath) && file_exists($articleImgPath))
                    @php
                        $articleImgExt = strtolower(pathinfo($articleImgPath, PATHINFO_EXTENSION));
                        $articleImgMime = 'image/jpeg';
                        if ($articleImgExt === 'png') $articleImgMime = 'image/png';
                        elseif ($articleImgExt === 'gif') $articleImgMime = 'image/gif';
                        elseif ($articleImgExt === 'webp') $articleImgMime = 'image/webp';
                        elseif ($articleImgExt === 'svg') $articleImgMime = 'image/svg+xml';
                    @endphp
                    <div class="article-main-image">
                        <img src="data:{{ $articleImgMime }};base64,{{ base64_encode(file_get_contents($articleImgPath)) }}" alt="Article image">
                    </div>
                @endif
            @endif

            @if($hasText)
                <div class="article-content">{!! $localeData->text !!}</div>
            @endif
            @if($hasShortDesc)
                <div class="article-content"><h2>{{ isset($trans['description']) ? $trans['description'] : 'Description' }}:</h2> {!! $localeData->short_description !!}</div>
            @endif
            @if($hasRoute)
                <div><h2>{{ isset($trans['route']) ? $trans['route'] : 'Routes' }}:</h2> {!! $localeData->route !!}</div>
            @endif
            @if($hasHowGet)
                <div><h2>{{ isset($trans['how_get']) ? $trans['how_get'] : 'How to Get There' }}:</h2> {!! $localeData->how_get !!}</div>
            @endif
            @if($hasBestTime)
                <div><h2>{{ isset($trans['best_time']) ? $trans['best_time'] : 'Best Time to Climb' }}:</h2> {!! $localeData->best_time !!}</div>
            @endif
            @if($hasWhatNeed)
                <div><h2>{{ isset($trans['what_need']) ? $trans['what_need'] : 'What You Need' }}:</h2> {!! $localeData->what_need !!}</div>
            @endif
            @if($hasInfo)
                <div><h2>{{ isset($trans['info']) ? $trans['info'] : 'Info' }}:</h2> {!! $localeData->info !!}</div>
            @endif
            @if($hasPriceText)
                <div><h2>{{ isset($trans['price']) ? $trans['price'] : 'Price' }}:</h2> {!! $localeData->price_text !!}</div>
            @endif
            @if($hasAddress)
                <div><h2>{{ isset($trans['address']) ? $trans['address'] : 'Address' }}:</h2> {!! $localeData->address !!}</div>
            @endif

            @if($hasGlobalData && !empty($globalData->category))
                <div class="article-meta"><strong>{{ isset($trans['category']) ? $trans['category'] : 'Category' }}:</strong> {{ $globalData->category }}</div>
            @endif
        </div>
    @endforeach

    @if(isset($sectors_data) && is_array($sectors_data) && count($sectors_data) > 0)
        <h2>Sectors and Routes Export</h2>
        @foreach($sectors_data as $sectorData)
            @php
                $sector = $sectorData['sector'] ?? [];
                $sectorImages = $sector['images'] ?? [];
                $sectorRoutes = $sector['routes'] ?? [];
                $sectorMtps = $sector['mtps'] ?? [];
                $sectorFields = is_array($sector) ? $sector : (array)$sector;
            @endphp
            <div class="sector-section">
                
                {{-- Line 1: Sector Name --}}
                <div class="sector-name">{{ $sector['name'] ?? 'Unknown Sector' }}</div>
                
                {{-- Line 2: Signs/Icons --}}
                <div class="sector-signs" style="margin-top: 15px;">
                    <div class="char-section" style="flex-wrap: nowrap;">
                        {{-- Sun/Shade icons --}}
                        <div class="char-row">
                            @php
                                $sunShadeIcons = [
                                    ['field' => 'in_shade_after_10', 'path' => 'images/svg/in shade after 10.00 am.svg', 'label' => '10:00'],
                                    ['field' => 'in_shade_after_15', 'path' => 'images/svg/in shade after 15.00 pm.svg', 'label' => '15:00'],
                                    ['field' => 'in_the_shade_befornoon', 'path' => 'images/svg/in shade befor noon.svg', 'label' => 'Morning'],
                                    ['field' => 'in_the_shade_afternoon', 'path' => 'images/svg/in shade in the afternoon.svg', 'label' => 'Afternoon'],
                                    ['field' => 'all_day_in_shade', 'path' => 'images/svg/in the shade whole day.svg', 'label' => 'All shade'],
                                    ['field' => 'all_day_in_sun', 'path' => 'images/svg/in the sun the whole day.svg', 'label' => 'All sun'],
                                ];
                            @endphp
                            @foreach($sunShadeIcons as $icon)
                                @php
                                    $fieldValue = $sectorFields[$icon['field']] ?? null;
                                    $iconPath = public_path($icon['path']);
                                    $iconExists = file_exists($iconPath);
                                @endphp
                                @if($fieldValue && $iconExists)
                                    <div class="char-container">
                                        @php $mimeType = 'image/svg+xml'; $iconContent = base64_encode(file_get_contents($iconPath)); @endphp
                                        <img class="char-icon" src="data:{{ $mimeType }};base64,{{ $iconContent }}" alt="{{ $icon['label'] }}">
                                        <div class="char-icon-label">{{ $icon['label'] }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        {{-- Relief icons --}}
                        <div class="char-row">
                            @php
                                $reliefIcons = [
                                    ['field' => 'roof', 'path' => 'images/svg/roof.svg', 'label' => 'Roof'],
                                    ['field' => 'overhang', 'path' => 'images/svg/overhang.svg', 'label' => 'Overhang'],
                                    ['field' => 'vertical', 'path' => 'images/svg/vertical.svg', 'label' => 'Vertical'],
                                    ['field' => 'slabby', 'path' => 'images/svg/slabby.svg', 'label' => 'Slabby'],
                                ];
                            @endphp
                            @foreach($reliefIcons as $icon)
                                @php
                                    $fieldValue = $sectorFields[$icon['field']] ?? null;
                                    $iconPath = public_path($icon['path']);
                                    $iconExists = file_exists($iconPath);
                                @endphp
                                @if($fieldValue && $iconExists)
                                    <div class="char-container">
                                        @php $mimeType = 'image/svg+xml'; $iconContent = base64_encode(file_get_contents($iconPath)); @endphp
                                        <img class="char-icon" src="data:{{ $mimeType }};base64,{{ $iconContent }}" alt="{{ $icon['label'] }}">
                                        <div class="char-icon-label">{{ $icon['label'] }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        {{-- Family/Kids icons --}}
                        <div class="char-row">
                            @php
                                $familyIcons = [
                                    ['field' => 'for_family', 'path' => 'images/svg/for family.svg', 'label' => 'Family'],
                                    ['field' => 'for_kids', 'path' => 'images/svg/for kids.svg', 'label' => 'Kids'],
                                ];
                            @endphp
                            @foreach($familyIcons as $icon)
                                @php
                                    $fieldValue = $sectorFields[$icon['field']] ?? null;
                                    $iconPath = public_path($icon['path']);
                                    $iconExists = file_exists($iconPath);
                                @endphp
                                @if($fieldValue && $iconExists)
                                    <div class="char-container">
                                        @php $mimeType = 'image/svg+xml'; $iconContent = base64_encode(file_get_contents($iconPath)); @endphp
                                        <img class="char-icon" src="data:{{ $mimeType }};base64,{{ $iconContent }}" alt="{{ $icon['label'] }}">
                                        <div class="char-icon-label">{{ $icon['label'] }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        {{-- Helmet icon --}}
                        @if(!empty($sectorFields['is_helmet']))
                            @php $helmetPath = public_path('images/svg/helmet.png'); $helmetExists = file_exists($helmetPath); @endphp
                            @if($helmetExists)
                                <div class="char-row">
                                    <div class="char-container">
                                        @php $helmetMime = 'image/png'; $helmetContent = base64_encode(file_get_contents($helmetPath)); @endphp
                                        <img class="char-icon" src="data:{{ $helmetMime }};base64,{{ $helmetContent }}" alt="Helmet">
                                        <div class="char-icon-label">Helmet</div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        {{-- Walking time --}}
                        @if(!empty($sectorFields['wolking_time']))
                            @php $walkingPath = public_path('images/svg/walking.svg'); $walkingExists = file_exists($walkingPath); @endphp
                            <div class="char-container">
                                @if($walkingExists)
                                    @php $walkingMime = 'image/svg+xml'; $walkingContent = base64_encode(file_get_contents($walkingPath)); @endphp
                                    <img class="char-icon" src="data:{{ $walkingMime }};base64,{{ $walkingContent }}" alt="Walking">
                                @endif
                                <div class="char-icon-label">~ {{ $sectorFields['wolking_time'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Line 3: Description --}}
                @if(!empty($sector['text']))
                    <p><strong>Description:</strong> {{ $sector['text'] }}</p>
                @endif
                
                {{-- Line 4: Sector Images --}}
                @if(is_array($sectorImages) && count($sectorImages) > 0)
                    @php $sectorImgCount = count($sectorImages); @endphp
                    <div class="sector-images-row" style="margin-top: 15px;">
                        <div class="sector-images sector_images_{{ $sectorImgCount }}">
                            @foreach($sectorImages as $image)
                                @php 
                                    $imgPath = '';
                                    $imgName = is_array($image) ? ($image['image'] ?? '') : ($image->image ?? '');
                                    $imgType = is_array($image) ? ($image['type'] ?? 'regular') : 'regular';
                                    
                                    if (!empty($imgName)) {
                                        $possiblePaths = [];
                                        if ($imgType === 'local') {
                                            $possiblePaths = [
                                                public_path('images/sector_local_img/' . $imgName),
                                                public_path('images/sector_local_img/' . strtolower($imgName)),
                                                public_path('images/sector_local_img/' . strtoupper($imgName)),
                                                public_path('images/sector_local_img/' . ucfirst(strtolower($imgName))),
                                                public_path('images/sector_local_img/' . ucwords(strtolower($imgName))),
                                            ];
                                        } else {
                                            $possiblePaths = [
                                                public_path('images/sector_img/' . $imgName),
                                                public_path('images/sector_img/' . strtolower($imgName)),
                                                public_path('images/sector_img/' . strtoupper($imgName)),
                                                public_path('images/sector_img/' . ucfirst(strtolower($imgName))),
                                                public_path('images/sector_img/' . ucwords(strtolower($imgName))),
                                            ];
                                        }
                                        $possiblePaths = array_merge($possiblePaths, [
                                            public_path('images/' . $imgName),
                                            public_path('images/' . strtolower($imgName)),
                                            public_path($imgName),
                                        ]);
                                        foreach ($possiblePaths as $path) {
                                            if (file_exists($path) && is_readable($path)) {
                                                $imgPath = $path;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                @if(!empty($imgName) && !empty($imgPath) && file_exists($imgPath))
                                    @php
                                        $extension = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
                                        $mimeType = 'image/jpeg';
                                        if ($extension === 'png') $mimeType = 'image/png';
                                        elseif ($extension === 'gif') $mimeType = 'image/gif';
                                        elseif ($extension === 'webp') $mimeType = 'image/webp';
                                        elseif ($extension === 'svg') $mimeType = 'image/svg+xml';
                                        elseif (in_array($extension, ['jpg', 'jpeg'])) $mimeType = 'image/jpeg';
                                    @endphp
                                    <img src="data:{{ $mimeType }};base64,{{ base64_encode(file_get_contents($imgPath)) }}" alt="Sector image {{ $imgName }}">
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Routes Table --}}
                @if(is_array($sectorRoutes) && count($sectorRoutes) > 0)
                    <div class="route-table">
                        <h4>Routes</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Grade</th>
                                    <th>Height</th>
                                    <th>Bolts</th>
                                    <th>Anchor Type</th>
                                    <th>Author</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectorRoutes as $route)
                                    <tr>
                                        <td>{{ ucfirst($route['type'] ?? 'Unknown') }}</td>
                                        <td>{{ $route['title'] ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $grade = $route['grade'] ?? '';
                                                $orGrade = $route['or_grade'] ?? '';
                                                if (!empty($grade) && !empty($orGrade) && $orGrade !== 'N/A') {
                                                    echo $grade . ' / ' . $orGrade;
                                                } elseif (!empty($grade)) {
                                                    echo $grade;
                                                } else {
                                                    echo $orGrade ?: 'N/A';
                                                }
                                            @endphp
                                        </td>
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

                {{-- MTPs --}}
                @if(is_array($sectorMtps) && count($sectorMtps) > 0)
                    <div class="mtp-table">
                        <h4>MTPs (Mountaineering Routes)</h4>
                        @foreach($sectorMtps as $mtp)
                            <div class="mtp-item">
                                <h5>{{ $mtp['title'] ?? 'Unknown MTP' }}</h5>
                                @if(!empty($mtp['text']))
                                    <p><strong>Description:</strong> {{ $mtp['text'] }}</p>
                                @endif
                                <p>
                                    <strong>Height:</strong> {{ $mtp['height'] ?? 'N/A' }} | 
                                    <strong>Last Carabiner:</strong> {{ $mtp['last_carabin'] ?? 'N/A' }}
                                </p>
                                
                                @if(isset($mtp['pitchs']) && is_array($mtp['pitchs']) && count($mtp['pitchs']) > 0)
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
