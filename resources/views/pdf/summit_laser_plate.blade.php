<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { size: 210mm 297mm; margin: 0; }
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; }

    /* A4 portrait sheet (210mm x 297mm); the plate drawing itself stays
       horizontal (200mm x 120mm) and is centered on the page. */
    .plate {
        position: absolute;
        top: 88.5mm;
        left: 5mm;
        width: 200mm;
        height: 120mm;
        border: 0.6mm solid #000;
        border-radius: 4mm;
        background: #fff;
    }

    .hole {
        position: absolute;
        width: 5mm;
        height: 5mm;
        border: 0.4mm solid #000;
        border-radius: 50%;
    }
    .hole-tl { top: 7mm; left: 7mm; }
    .hole-tr { top: 7mm; right: 7mm; }
    .hole-bl { bottom: 7mm; left: 7mm; }
    .hole-br { bottom: 7mm; right: 7mm; }

    .text-block {
        position: absolute;
        top: 30mm;
        left: 22mm;
        width: 100mm;
    }
    .title-ka {
        font-size: 30pt;
        font-weight: bold;
        margin: 0;
        line-height: 1.15;
        color: #111;
    }
    .title-en {
        font-size: 12pt;
        letter-spacing: 1pt;
        text-transform: uppercase;
        color: #555;
        margin: 2mm 0 0 0;
    }
    .divider {
        width: 32mm;
        height: 0.5mm;
        background: #bbb;
        margin: 6mm 0;
    }
    .height-value {
        font-size: 20pt;
        font-weight: bold;
        color: #222;
        margin: 0;
    }
    .coords-value {
        font-size: 11pt;
        color: #666;
        margin: 2.5mm 0 0 0;
        letter-spacing: 0.3pt;
    }

    .qr-frame {
        position: absolute;
        top: 22mm;
        right: 14mm;
        width: 72mm;
        padding: 4mm;
        border: 0.4mm solid #ccc;
        border-radius: 2mm;
        text-align: center;
    }
    .qr-frame img {
        width: 64mm;
        height: 64mm;
    }
    .qr-caption {
        font-size: 8pt;
        color: #888;
        margin-top: 3mm;
    }

    .brand {
        position: absolute;
        bottom: 8mm;
        left: 22mm;
        font-size: 8pt;
        font-style: italic;
        color: #999;
    }
</style>
</head>
<body>
@php
    $gTitle = $summit->ka_title ?: $summit->title;
    $eTitle = ($summit->title && $summit->title !== $gTitle) ? $summit->title : null;
    $height = $summit->height ? number_format($summit->height) . ' m' : '—';

    $coords = '—';
    if ($summit->latitude !== null && $summit->longitude !== null) {
        $latDir = $summit->latitude >= 0 ? 'N' : 'S';
        $lngDir = $summit->longitude >= 0 ? 'E' : 'W';
        $coords = number_format(abs($summit->latitude), 5) . '° ' . $latDir
            . '  ·  ' . number_format(abs($summit->longitude), 5) . '° ' . $lngDir;
    }
@endphp

<div class="plate">
    <div class="hole hole-tl"></div>
    <div class="hole hole-tr"></div>
    <div class="hole hole-bl"></div>
    <div class="hole hole-br"></div>

    <div class="text-block">
        <div class="title-ka">{{ $gTitle }}</div>
        @if($eTitle)
            <div class="title-en">{{ $eTitle }}</div>
        @endif

        <div class="divider"></div>

        <div class="height-value">{{ $height }}</div>
        <div class="coords-value">{{ $coords }}</div>
    </div>

    <div class="qr-frame">
        <img src="{{ $qr_data_uri }}" alt="QR" />
        <div class="qr-caption">Scan to log your ascent</div>
    </div>

    <div class="brand">powered by climbing.ge</div>
</div>
</body>
</html>
