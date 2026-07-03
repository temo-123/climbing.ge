<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}">
<head>
<meta charset="UTF-8">
<title>{{ $tr->t('doc_title') }}</title>
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

    /* Tables read as one bordered "card" (rounded corners via the wrapper,
       since border-radius doesn't apply cleanly to <table> itself once
       border-collapse is in play) with alternating row shading — much
       easier to scan a multi-row price/materials breakdown than a bare
       HTML table with only bottom-rules between rows. */
    .table-card { border: 0.2mm solid #e8ecf0; border-radius: 2mm; overflow: hidden; margin-bottom: 4mm; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 2.5mm 3mm; text-align: left; font-size: 9pt; }
    /* No text-transform: uppercase here — dompdf's uppercasing corrupts
       Georgian (and likely other non-Latin) glyphs entirely, rendering them
       as blank boxes instead of letters. Letter-spacing alone still gives
       the header row a distinct, label-like look without that risk. */
    th { background: #eef2f7; color: #46505c; font-weight: bold; font-size: 8.5pt; letter-spacing: 0.02em; }
    tbody tr:nth-child(even) td { background: #f8fafc; }
    tbody tr td { border-top: 0.2mm solid #eef1f4; }
    tbody tr:first-child td { border-top: none; }
    /* `table tbody tr.total-row td` (not just `tr.total-row td`) so this
       always outranks the nth-child zebra rule above by specificity —
       otherwise the total row's own highlight got silently overridden by
       the zebra shading whenever it happened to land on an even row. */
    table tbody tr.total-row td { font-weight: bold; font-size: 10.5pt; background: #eaf2fb; border-top: 0.4mm solid #4a90d9; color: #1a1a2e; }
    td.num { text-align: right; }

    .renders-grid { margin-bottom: 3mm; }
    .render-box { width: 100%; border: 0.2mm solid #e8ecf0; border-radius: 2mm; padding: 2mm; text-align: center; margin-bottom: 3mm; }
    .render-box img { width: 100%; height: auto; }
    .render-label { font-size: 8pt; color: #888; margin-top: 1mm; }

    .drawings-grid { margin-bottom: 3mm; }
    .drawing-box { width: 100%; border: 0.2mm solid #e8ecf0; border-radius: 2mm; padding: 3mm; margin-bottom: 3mm; }
    .drawing-title { font-size: 9pt; font-weight: bold; color: #4a90d9; margin-bottom: 1.5mm; }

    /* dompdf does not paint raw inline <svg>...</svg> markup at all — its SVG
       support only runs through the normal image-loading pipeline (an
       <img src="...">), and even routed that way its text positioning is
       unreliable. So each drawing (shapes AND labels — dimensions,
       "foundation", "overhang", etc.) is rendered as one GD-drawn PNG
       (see WallDrawingService) and dropped in here as a plain image —
       nothing left for dompdf's layout/text engine to get wrong.
       `width: auto` + `max-width/max-height` (not `width: 100%`) is
       deliberate: a tall, narrow drawing (e.g. a steep side profile)
       stretched to the full page width at a fixed 100% would blow well past
       a sane page height — capping max-height to ~82% of the A4 paper
       height (297mm) lets a drawing use the full width when its own aspect
       ratio allows it, but shrinks proportionally instead of overflowing
       when it can't. */
    .cad-drawing { text-align: center; }
    .cad-drawing img { display: inline-block; width: auto; height: auto; max-width: 100%; max-height: 243mm; }

    .info-columns { display: flex; justify-content: space-between; gap: 6mm; }
    .info-box { width: 48%; }
    /* Same reason the table header rule above dropped text-transform:
       uppercase — it silently corrupts Georgian glyphs into blank boxes. */
    .info-box h6 { font-size: 8.5pt; font-weight: bold; color: #888; margin: 0 0 1.5mm 0; }
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
            <p class="brand-sub">{{ $tr->t('subtitle') }}</p>
        </div>
        <div class="doc-meta">
            <h2>{{ $tr->t('doc_title') }}</h2>
            <div>{{ $tr->t('generated') }}: {{ $generatedAt }}</div>
        </div>
    </div>

    <div class="info-columns">
        <div class="info-box">
            <h6>{{ $tr->t('discipline_structure') }}</h6>
            <p>
                {{ !empty($input['discipline']) ? $tr->t('discipline_name_' . $input['discipline']) : '—' }}<br>
                {{ !empty($input['structure']) ? $tr->t('structure_name_' . $input['structure']) : '—' }}
                @if($sidesCount > 1) &mdash; {{ $sidesCount }}-{{ $tr->t('tower_suffix') }} @endif
            </p>
        </div>
        <div class="info-box">
            <h6>{{ $tr->t('overall_size') }}</h6>
            <p>
                {{ $tr->t('height_label') }}: {{ $input['height'] }}m &nbsp;|&nbsp;
                {{ $tr->t('width_label') }}: {{ $input['width'] }}m &nbsp;|&nbsp;
                {{ $tr->t('overhang_depth_label') }}: {{ $input['depth'] ?? 0 }}m
            </p>
        </div>
    </div>

    @if($isStandFree)
        <div class="standfree-note">
            {{ $tr->t('standfree_note', [
                'style' => ($input['depth'] ?? 0) > \App\Services\WallDrawingService::BRACE_OVERHANG_THRESHOLD
                    ? $tr->t('brace_truss') : $tr->t('brace_prop'),
                'depth' => round($foundationDepth, 2),
            ]) }}
        </div>
    @endif

    @if($isOutdoor)
        <div class="standfree-note">
            {{ $tr->t('outdoor_note') }}
        </div>
    @endif

    @if(count($snapshots))
        <div class="section-title">{{ $tr->t('renders_title') }}</div>
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

    <div class="section-title">{{ $tr->t('drawings_title') }}</div>
    <div class="drawings-grid">
        <div class="drawing-box">
            <div class="drawing-title">{{ $tr->t('front_elevation') }}</div>
            <div class="cad-drawing">
                <img src="{{ $frontView }}" alt="{{ $tr->t('front_elevation') }}">
            </div>
        </div>
        <div class="drawing-box">
            <div class="drawing-title">{{ $tr->t('side_profile') }}</div>
            <div class="cad-drawing">
                <img src="{{ $sideView }}" alt="{{ $tr->t('side_profile') }}">
            </div>
        </div>
        <div class="drawing-box">
            <div class="drawing-title">{{ $sidesCount > 1 ? $tr->t('plan_view_tower') : $tr->t('plan_view') }}</div>
            <div class="cad-drawing">
                <img src="{{ $topView }}" alt="{{ $sidesCount > 1 ? $tr->t('plan_view_tower') : $tr->t('plan_view') }}">
            </div>
        </div>
    </div>

    <div class="section-title">{{ $tr->t('dimensions_title') }}</div>
    <div class="table-card">
    <table>
        <thead>
            <tr><th>{{ $tr->t('side_col') }}</th><th class="num">{{ $tr->t('width_col') }}</th><th class="num">{{ $tr->t('overhang_col') }}</th></tr>
        </thead>
        <tbody>
            @foreach($sides as $i => $side)
                <tr>
                    <td>{{ $tr->t('side_label') }} {{ $i + 1 }}</td>
                    <td class="num">{{ $side['width'] }}m</td>
                    <td class="num">{{ $side['depth'] }}m</td>
                </tr>
            @endforeach
            @if(!empty($input['mat']['width']))
                <tr>
                    <td>{{ $tr->t('safety_mat_row') }}</td>
                    <td class="num" colspan="2">
                        {{ round($input['mat']['width'], 2) }}m &times;
                        {{ round($input['mat']['depth'] ?? 0, 2) }}m,
                        {{ round(($input['mat']['height'] ?? 0) * 100, 0) }}cm thick
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    </div>

    @if(count($price))
        <div class="section-title">{{ $tr->t('price_summary') }}</div>
        <div class="table-card">
        <table>
            <tbody>
                @if(isset($price['wall_price_sum']))
                    <tr><td>{{ $tr->t('base_price') }}</td><td class="num">${{ number_format($price['wall_price_sum'], 2) }}</td></tr>
                @endif
                @if(!empty($price['mat_price_sum']))
                    <tr><td>{{ $tr->t('mat_price') }}</td><td class="num">${{ number_format($price['mat_price_sum'], 2) }}</td></tr>
                @endif
                @if(!empty($price['protection_price_sum']))
                    <tr>
                        <td>
                            {{ $tr->t('protection_price') }}
                            @if(!empty($price['protection_anchor_count']))
                                <br><span style="font-size:7.5pt;color:#999;">
                                    {{ $tr->t('protection_hint', [
                                        'anchors' => $price['protection_anchor_count'],
                                        'ropePart' => !empty($price['protection_rope_length'])
                                            ? $tr->t('protection_rope_part', ['rope' => $price['protection_rope_length']]) : '',
                                    ]) }}
                                </span>
                            @endif
                        </td>
                        <td class="num">${{ number_format($price['protection_price_sum'], 2) }}</td>
                    </tr>
                @endif
                @if(!empty($price['holds_total_price']))
                    <tr><td>{{ $tr->t('holds_price') }}</td><td class="num">${{ number_format($price['holds_total_price'], 2) }}</td></tr>
                @endif
                @if(!empty($price['foundation_price_sum']))
                    <tr>
                        <td>
                            {{ $tr->t('foundation_price') }}
                            @if(!empty($price['foundation_depth']))
                                <br><span style="font-size:7.5pt;color:#999;">{{ $tr->t('foundation_hint', ['depth' => $price['foundation_depth']]) }}</span>
                            @endif
                        </td>
                        <td class="num">${{ number_format($price['foundation_price_sum'], 2) }}</td>
                    </tr>
                @endif
                @if(!empty($price['roof_price_sum']))
                    <tr><td>{{ $tr->t('roof_price') }}</td><td class="num">${{ number_format($price['roof_price_sum'], 2) }}</td></tr>
                @endif
                @if(!empty($price['vat_price']))
                    <tr><td>{{ $tr->t('vat_label') }} ({{ $price['vat_percent'] ?? 20 }}%)</td><td class="num">${{ number_format($price['vat_price'], 2) }}</td></tr>
                @endif
                <tr class="total-row">
                    <td>{{ $tr->t('total_price') }}</td>
                    <td class="num">${{ number_format($price['total_price_sum'] ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
        </div>
    @endif

    @if(count($billOfMaterials))
        <div class="section-title">
            {{ $isStandFree ? $tr->t('detailed_construction') : $tr->t('materials_quantities') }}
        </div>
        <div class="table-card">
        <table>
            <thead>
                <tr><th>{{ $tr->t('item_col') }}</th><th class="num">{{ $tr->t('quantity_col') }}</th><th>{{ $tr->t('unit_col') }}</th></tr>
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
        </div>
    @endif

    <div class="footer-note">
        {{ $tr->t('footer_note') }}
    </div>

</body>
</html>
