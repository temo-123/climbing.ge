<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Wall Configuration</title>
<style>
    @page { margin: 14mm; }
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; color: #222; font-size: 9.5pt; }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 0.6mm solid #1a1a2e;
        padding-bottom: 5mm;
        margin-bottom: 6mm;
    }
    .brand-title { font-size: 18pt; font-weight: bold; margin: 0; color: #1a1a2e; }
    .brand-sub { font-size: 9pt; color: #777; margin-top: 1mm; }
    .doc-meta { text-align: right; font-size: 9pt; color: #444; }
    .doc-meta h2 { font-size: 14pt; margin: 0 0 2mm 0; color: #4a90d9; }

    .section-title {
        font-size: 11pt;
        font-weight: bold;
        color: #1a1a2e;
        border-bottom: 0.4mm solid #e8ecf0;
        padding-bottom: 1.5mm;
        margin: 6mm 0 3mm;
    }

    table { width: 100%; border-collapse: collapse; margin-bottom: 3mm; }
    th, td { padding: 2mm 2.5mm; text-align: left; border-bottom: 0.2mm solid #e8ecf0; font-size: 9pt; }
    th { background: #f4f7fa; color: #555; font-weight: bold; }
    tr.total-row td { font-weight: bold; font-size: 10.5pt; border-top: 0.4mm solid #1a1a2e; border-bottom: none; color: #1a1a2e; }
    td.num { text-align: right; }

    .renders-grid { display: flex; flex-wrap: wrap; gap: 3mm; margin-bottom: 3mm; }
    .render-box { width: 47%; border: 0.2mm solid #e8ecf0; border-radius: 2mm; padding: 2mm; text-align: center; }
    .render-box img { width: 100%; height: auto; }
    .render-label { font-size: 8pt; color: #888; margin-top: 1mm; }

    .drawings-grid { display: flex; flex-wrap: wrap; gap: 3mm; margin-bottom: 3mm; }
    .drawing-box { width: 100%; border: 0.2mm solid #e8ecf0; border-radius: 2mm; padding: 3mm; margin-bottom: 3mm; }
    .drawing-box.half { width: 47%; }
    .drawing-title { font-size: 9pt; font-weight: bold; color: #4a90d9; margin-bottom: 1.5mm; }

    .info-columns { display: flex; justify-content: space-between; gap: 6mm; }
    .info-box { width: 48%; }
    .info-box h6 { font-size: 8.5pt; font-weight: bold; color: #888; margin: 0 0 1.5mm 0; text-transform: uppercase; }
    .info-box p { margin: 0; line-height: 1.6; }

    .standfree-note {
        background: #fff8ec;
        border: 0.2mm solid #f0d9a8;
        border-radius: 2mm;
        padding: 3mm;
        font-size: 8.5pt;
        color: #8a6a2a;
        margin-bottom: 3mm;
    }

    .footer-note { font-size: 7.5pt; color: #999; margin-top: 8mm; border-top: 0.2mm solid #e8ecf0; padding-top: 2mm; }
</style>
</head>
<body>

    <div class="header">
        <div>
            <p class="brand-title">climbing.ge</p>
            <p class="brand-sub">Wall Construction &amp; Pricing Export</p>
        </div>
        <div class="doc-meta">
            <h2>Wall Configuration</h2>
            <div>Generated: {{ $generatedAt }}</div>
        </div>
    </div>

    <div class="info-columns">
        <div class="info-box">
            <h6>Discipline &amp; Structure</h6>
            <p>
                {{ ucfirst(str_replace('_', ' ', $input['discipline'] ?? '—')) }}<br>
                {{ ucfirst(str_replace('_', ' ', $input['structure'] ?? '—')) }}
                @if($sidesCount > 1) &mdash; {{ $sidesCount }}-sided tower @endif
            </p>
        </div>
        <div class="info-box">
            <h6>Overall Size</h6>
            <p>
                Height: {{ $input['height'] }}m &nbsp;|&nbsp;
                Width: {{ $input['width'] }}m &nbsp;|&nbsp;
                Overhang depth: {{ $input['depth'] ?? 0 }}m
            </p>
        </div>
    </div>

    @if($isStandFree)
        <div class="standfree-note">
            <strong>Stand-free structure.</strong> This wall is fully self-supporting (primary frame + diagonal
            subframe truss carrying the panel load to the ground) rather than attached to an existing building
            wall — see the detailed construction &amp; materials section below for the full breakdown.
        </div>
    @endif

    @if(count($snapshots))
        <div class="section-title">3D Renders</div>
        <div class="renders-grid">
            @foreach($snapshots as $shot)
                @if(!empty($shot['data_url']))
                    <div class="render-box">
                        <img src="{{ $shot['data_url'] }}" alt="{{ $shot['label'] ?? '' }}">
                        <div class="render-label">{{ $shot['label'] ?? '' }}</div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div class="section-title">2D Technical Drawings</div>
    <div class="drawings-grid">
        <div class="drawing-box half">
            <div class="drawing-title">Front Elevation</div>
            {!! $frontViewSvg !!}
        </div>
        <div class="drawing-box half">
            <div class="drawing-title">Side Profile</div>
            {!! $sideViewSvg !!}
        </div>
        <div class="drawing-box">
            <div class="drawing-title">{{ $sidesCount > 1 ? 'Plan View (Tower Footprint)' : 'Plan View' }}</div>
            {!! $topViewSvg !!}
        </div>
    </div>

    <div class="section-title">Dimensions</div>
    <table>
        <thead>
            <tr><th>Side</th><th class="num">Width</th><th class="num">Overhang Depth</th></tr>
        </thead>
        <tbody>
            @foreach($sides as $i => $side)
                <tr>
                    <td>Side {{ $i + 1 }}</td>
                    <td class="num">{{ $side['width'] }}m</td>
                    <td class="num">{{ $side['depth'] }}m</td>
                </tr>
            @endforeach
            @if(!empty($input['mat']['width']))
                <tr>
                    <td>Safety Mat</td>
                    <td class="num" colspan="2">
                        {{ round($input['mat']['width'], 2) }}m &times;
                        {{ round($input['mat']['depth'] ?? 0, 2) }}m,
                        {{ round(($input['mat']['height'] ?? 0) * 100, 0) }}cm thick
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    @if(count($price))
        <div class="section-title">Price Summary</div>
        <table>
            <tbody>
                @if(isset($price['wall_price_sum']))
                    <tr><td>Base Wall Price</td><td class="num">${{ number_format($price['wall_price_sum'], 2) }}</td></tr>
                @endif
                @if(!empty($price['mat_price_sum']))
                    <tr><td>Safety Mat</td><td class="num">${{ number_format($price['mat_price_sum'], 2) }}</td></tr>
                @endif
                @if(!empty($price['holds_total_price']))
                    <tr><td>Climbing Holds</td><td class="num">${{ number_format($price['holds_total_price'], 2) }}</td></tr>
                @endif
                @if(!empty($price['vat_price']))
                    <tr><td>VAT ({{ $price['vat_percent'] ?? 20 }}%)</td><td class="num">${{ number_format($price['vat_price'], 2) }}</td></tr>
                @endif
                <tr class="total-row">
                    <td>Total Price</td>
                    <td class="num">${{ number_format($price['total_price_sum'] ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if(count($billOfMaterials))
        <div class="section-title">
            {{ $isStandFree ? 'Detailed Construction & Bill of Materials' : 'Materials & Quantities' }}
        </div>
        <table>
            <thead>
                <tr><th>Item</th><th class="num">Quantity</th><th>Unit</th></tr>
            </thead>
            <tbody>
                @foreach($billOfMaterials as $row)
                    <tr>
                        <td>{{ $row['item'] ?? '' }}</td>
                        <td class="num">{{ $row['quantity'] ?? '' }}</td>
                        <td>{{ $row['unit'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer-note">
        Prices, dimensions and material quantities are approximate estimates generated by the climbing.ge wall
        calculator, based on the exact configuration shown above. Final construction figures should be confirmed
        with a structural professional before building.
    </div>

</body>
</html>
