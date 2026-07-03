<?php

namespace App\Services;

/**
 * Builds real orthographic 2D technical drawings (front elevation, side
 * profile, top/plan view) for the wall calculator's PDF export — rendered
 * with GD as raster PNGs (returned as base64 data URIs), computed straight
 * from the same width/height/depth/sides values the 3D viewer uses.
 *
 * This used to be built as inline <svg> markup, but dompdf does not paint
 * raw inline <svg>...</svg> at all — its SVG support only runs through the
 * normal image-loading pipeline (an <img src="file.svg">), and even routed
 * that way its text positioning is unreliable. GD sidesteps both problems:
 * every shape AND every label (dimensions, "foundation", "overhang", etc.)
 * is drawn directly onto one raster image at exact pixel coordinates, so
 * there's no separate HTML label-overlay step and nothing for dompdf's own
 * layout engine to get wrong.
 */
class WallDrawingService
{
    // Real single-side bracing switches style at this overhang depth: a flat
    // or barely-overhanging wall just needs a simple angled push-pull prop
    // (like single-side formwork bracing); past this point the top is
    // pulling forward and down hard enough that real builds switch to a
    // proper braced truss instead — same threshold the calculator's own
    // stand-free construction logic uses.
    const BRACE_OVERHANG_THRESHOLD = 0.5;

    const COLOR_LINE = '#1a1a2e';
    const COLOR_DIM = '#4a90d9';
    const COLOR_PANEL_FILL = '#f4f7fa';
    const COLOR_GROUND = '#999999';
    const COLOR_BRACE = '#4a5560';
    const COLOR_ROOF = '#9c3b30';
    const COLOR_FOOTING_FILL = '#d8d8d8';
    const COLOR_LABEL = '#888888';
    const COLOR_STUD = '#c7ccd1';
    const COLOR_SEAM = '#9aa2ab';

    // Real stud-wall climbing panel construction: studs 16" (0.4m) on-center,
    // standard 1.5m-square plywood sheets — the exact same values the 3D
    // viewer's own wallConstructionConfig.js (WALL_CONSTRUCTION) is built
    // from, kept here as their own constants since this PHP service has no
    // access to that JS module.
    const STUD_SPACING = 0.4;
    const SHEET_SIZE = 1.5;

    /**
     * English fallbacks for every translatable annotation this service
     * draws — merged under whatever a caller passes in `$labels`, so a
     * locale that's missing a key (or no locale at all) still gets a
     * legible drawing instead of a blank spot.
     */
    protected function defaultLabels()
    {
        return [
            'support_truss' => 'support truss',
            'support_prop' => 'support prop',
            'foundation' => 'foundation',
            'roof' => 'roof',
            'overhang' => 'overhang',
            'overhang_direction' => 'overhang direction',
            'side' => 'Side',
        ];
    }

    protected function fmt($meters)
    {
        return rtrim(rtrim(number_format($meters, 2), '0'), '.') . 'm';
    }

    protected function fontRegular()
    {
        return base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf');
    }

    protected function fontBold()
    {
        return base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
    }

    /** Allocates (or reuses) a GD color from a "#rrggbb" hex string. */
    protected function color($im, $hex)
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return imagecolorallocate($im, $r, $g, $b);
    }

    /** A new true-color canvas, filled white — the shared starting point for every drawing here. */
    protected function newCanvas($w, $h)
    {
        $im = imagecreatetruecolor(max((int) ceil($w), 1), max((int) ceil($h), 1));
        imageantialias($im, true);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefilledrectangle($im, 0, 0, $w, $h, $white);
        return $im;
    }

    /** GD's imagettftext y-coordinate is the text BASELINE, not the top — callers here all think in "top of text". */
    protected function text($im, $x, $y, $text, $size = 13, $hex = self::COLOR_LABEL, $bold = false, $align = 'left', $bg = null)
    {
        $font = $bold ? $this->fontBold() : $this->fontRegular();
        $box = imagettfbbox($size, 0, $font, $text);
        $textW = abs($box[4] - $box[0]);
        $textH = abs($box[5] - $box[1]);

        $drawX = $x;
        if ($align === 'middle') {
            $drawX = $x - $textW / 2;
        } elseif ($align === 'end') {
            $drawX = $x - $textW;
        }
        $baselineY = $y + $textH;

        if ($bg !== null) {
            $pad = 2;
            imagefilledrectangle($im, $drawX - $pad, $y - $pad, $drawX + $textW + $pad, $baselineY + $pad, $this->color($im, $bg));
        }

        imagettftext($im, $size, 0, (int) round($drawX), (int) round($baselineY), $this->color($im, $hex), $font, $text);
        return $textH;
    }

    /**
     * A dimension line: a straight line between two points with perpendicular
     * tick marks at each end, and a centered, white-backed label — the
     * standard CAD convention for annotating a measurement.
     */
    protected function dimensionLine($im, $x1, $y1, $x2, $y2, $label)
    {
        $tick = 4;
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $len = sqrt($dx * $dx + $dy * $dy) ?: 1;
        $px = -$dy / $len * $tick;
        $py = $dx / $len * $tick;
        $midX = ($x1 + $x2) / 2;
        $midY = ($y1 + $y2) / 2;

        $blue = $this->color($im, self::COLOR_DIM);
        imageline($im, (int) $x1, (int) $y1, (int) $x2, (int) $y2, $blue);
        imageline($im, (int) ($x1 - $px), (int) ($y1 - $py), (int) ($x1 + $px), (int) ($y1 + $py), $blue);
        imageline($im, (int) ($x2 - $px), (int) ($y2 - $py), (int) ($x2 + $px), (int) ($y2 + $py), $blue);

        $this->text($im, $midX, $midY - 7, $label, 13, self::COLOR_LINE, false, 'middle', '#ffffff');
    }

    /** A horizontally dashed line (GD has no native dash primitive for imageline). */
    protected function dashedHLine($im, $x1, $x2, $y, $hex)
    {
        $color = $this->color($im, $hex);
        $dash = 6;
        $gap = 4;
        for ($x = $x1; $x < $x2; $x += $dash + $gap) {
            imageline($im, (int) $x, (int) $y, (int) min($x + $dash, $x2), (int) $y, $color);
        }
    }

    /** The standard architectural cross-section symbol for ground/earth: short diagonal ticks below the ground line. */
    protected function groundHatching($im, $x1, $x2, $y, $hex)
    {
        $color = $this->color($im, $hex);
        $spacing = 10;
        $len = 7;
        for ($x = $x1; $x < $x2; $x += $spacing) {
            imageline($im, (int) $x, (int) ($y + $len), (int) ($x + $len), (int) $y, $color);
        }
    }

    protected function toDataUri($im)
    {
        ob_start();
        imagepng($im);
        $binary = ob_get_clean();
        imagedestroy($im);
        return 'data:image/png;base64,' . base64_encode($binary);
    }

    /**
     * Front elevation: the wall face as a climber standing in front of it
     * would see it (width x height), with both dimensions annotated — and,
     * so this reads as real construction rather than a bare rectangle, the
     * actual stud grid (0.4m on-center) and plywood sheet seams (1.5m
     * squares) the panel is really built from, same spacing the 3D viewer's
     * own frame/panel geometry uses.
     */
    public function buildFrontView($width, $height, $scale = 40)
    {
        $w = $width * $scale;
        $h = $height * $scale;
        $pad = 45;
        $canvasW = $w + $pad * 2;
        $canvasH = $h + $pad * 2 + 20;
        $x0 = $pad;
        $y0 = $pad;

        $im = $this->newCanvas($canvasW, $canvasH);
        imagefilledrectangle($im, (int) $x0, (int) $y0, (int) ($x0 + $w), (int) ($y0 + $h), $this->color($im, self::COLOR_PANEL_FILL));

        $studColor = $this->color($im, self::COLOR_STUD);
        foreach ($this->gridPositions($width, self::STUD_SPACING) as $pos) {
            if ($pos <= 0 || $pos >= $width) continue; // the outer rect below already draws the edges
            $x = $x0 + $pos * $scale;
            imageline($im, (int) $x, (int) $y0, (int) $x, (int) ($y0 + $h), $studColor);
        }

        $seamColor = $this->color($im, self::COLOR_SEAM);
        foreach ($this->gridPositions($height, self::SHEET_SIZE) as $pos) {
            if ($pos <= 0 || $pos >= $height) continue;
            $y = $y0 + $pos * $scale;
            imageline($im, (int) $x0, (int) $y, (int) ($x0 + $w), (int) $y, $seamColor);
        }

        imagesetthickness($im, 3); // the panel's real outer edge, heavier than the stud/seam grid inside it
        imagerectangle($im, (int) $x0, (int) $y0, (int) ($x0 + $w), (int) ($y0 + $h), $this->color($im, self::COLOR_LINE));
        imagesetthickness($im, 1);

        $this->dimensionLine($im, $x0, $y0 + $h + 20, $x0 + $w, $y0 + $h + 20, $this->fmt($width));
        $this->dimensionLine($im, $x0 - 22, $y0, $x0 - 22, $y0 + $h, $this->fmt($height));

        return $this->toDataUri($im);
    }

    /** Every position from 0 up to `total`, `step` apart, always including `total` itself as the last entry. */
    protected function gridPositions($total, $step)
    {
        $positions = [0.0];
        for ($v = $step; $v < $total - 1e-6; $v += $step) $positions[] = $v;
        $positions[] = $total;
        return $positions;
    }

    /**
     * Side profile: the height climbing straight up, then leaning out by the
     * overhang depth at the top — the same tiltAngle = atan2(depth, height)
     * convention the 3D viewer uses. `vertHeight` (bouldering only) marks
     * where the wall's own straight base section ends and the overhang
     * begins, matching buildBoulderingModel's own kink point. `isStandFree`
     * adds the support bracing + foundation footing behind the wall (this
     * drawing is the PDF's only rendering of that construction detail — it's
     * intentionally not duplicated in the live web calculator); `isOutdoor`
     * adds a simple roof indicator above the top. `$labels` supplies the
     * locale's own strings for every annotation drawn here (see
     * defaultLabels() for the keys and English fallback text).
     */
    public function buildSideView($height, $depth, $vertHeight = null, $isStandFree = false, $isOutdoor = false, $foundationDepth = 0, array $labels = [], $scale = 40)
    {
        $t = array_merge($this->defaultLabels(), $labels);
        $h = $height * $scale;
        $d = $depth * $scale;
        $pad = 50;

        // The brace reach itself, plus a flat buffer for the "support
        // truss"/"support prop" label centered out there — some locales'
        // translated text runs wider than English and was clipping past the
        // canvas edge with no buffer at all.
        $leftMargin = $isStandFree ? $this->braceReach($height, $depth, $scale) + 45 : 0;
        $footingPx = ($isStandFree && $foundationDepth > 0) ? $foundationDepth * $scale : 0;
        $topMargin = $isOutdoor ? 95 : 0; // room for the sloped roof indicator + its pitch label, clear of the depth dimension

        // Extra right-side room for the tilt-angle label text (e.g. "18.4°
        // overhang"), which starts at the wall base and runs right — a fixed
        // small margin clipped it for shallower/narrower walls where that
        // text is wider than the wall's own overhang reach.
        $canvasW = max($d, 10) + $pad * 2 + 110 + $leftMargin;
        $canvasH = $h + $pad * 2 + max(0, $footingPx - $pad + 45) + $topMargin;
        $x0 = $pad + $leftMargin;
        $y0 = $pad + $h + $topMargin; // ground level

        $topX = $x0 + $d;
        $topY = $y0 - $h;

        $im = $this->newCanvas($canvasW, $canvasH);

        if ($isStandFree) {
            $this->drawStandFreeBracing($im, $x0, $y0, $topX, $topY, $height, $depth, $scale, $t);
        }

        // The wall itself is the primary structure this drawing exists to
        // show — drawn noticeably thicker than the reference/dimension
        // lines around it, the same "heavy line = real structure, thin
        // line = annotation" convention any real technical drawing uses.
        $lineColor = $this->color($im, self::COLOR_LINE);
        imagesetthickness($im, 3);
        if ($vertHeight !== null && $vertHeight > 0 && $vertHeight < $height) {
            $kinkY = $y0 - ($vertHeight * $scale);
            imageline($im, (int) $x0, (int) $y0, (int) $x0, (int) $kinkY, $lineColor);
            imageline($im, (int) $x0, (int) $kinkY, (int) $topX, (int) $topY, $lineColor);
        } else {
            imageline($im, (int) $x0, (int) $y0, (int) $topX, (int) $topY, $lineColor);
        }
        imagesetthickness($im, 1);

        if ($isOutdoor) {
            $this->drawRoofIndicator($im, $x0, $topX, $topY, $scale, $isStandFree, $t);
        }

        $this->dashedHLine($im, $x0 - 15, $topX + 15, $y0, self::COLOR_GROUND);
        $this->groundHatching($im, $x0 - 15, $topX + 15, $y0, self::COLOR_GROUND);

        if ($isStandFree && $foundationDepth > 0) {
            $this->drawFoundationFooting($im, $x0, $y0, $foundationDepth, $scale, $t);
        }

        $this->dimensionLine($im, $x0 - 28, $y0, $x0 - 28, $topY, $this->fmt($height));

        if ($depth > 0) {
            $this->dimensionLine($im, $x0, $topY - 18, $topX, $topY - 18, $this->fmt($depth));
            $tiltDeg = round(rad2deg(atan2($depth, $height)), 1);
            $this->text($im, $x0 + 10, $topY + 22, $tiltDeg . '° ' . $t['overhang'], 13, self::COLOR_LABEL);
        }

        return $this->toDataUri($im);
    }

    // How far back (in pixels, away from the overhang direction) the
    // stand-free bracing reaches — shared between the canvas-margin
    // calculation above and the actual bracing draw below so the two can
    // never drift apart into a margin that clips the drawing it's sized for.
    private function braceReach($height, $depth, $scale)
    {
        if ($depth > self::BRACE_OVERHANG_THRESHOLD) {
            return 0.4 * $scale; // truss rear post setback
        }
        $attachY = min($height * 0.6, 2.2);
        return min($attachY * 0.6, 1.6) * $scale; // prop ground-anchor setback
    }

    /**
     * A freestanding wall has no building wall behind it to lean on — drawn
     * as one of two real single-side bracing styles depending on how hard
     * the overhang is actually pulling: a flat/barely-overhanging wall gets a
     * simple angled push-pull prop (single-side formwork bracing); a genuine
     * overhang gets a braced truss (vertical rear post, top tie, and two
     * diagonals triangulating the wedge that opens up because the post stays
     * vertical while the wall leans forward).
     */
    private function drawStandFreeBracing($im, $x0, $y0, $topX, $topY, $height, $depth, $scale, array $t)
    {
        $stroke = $this->color($im, self::COLOR_BRACE);
        imagesetthickness($im, 3); // a real structural member, drawn as heavy as the wall itself

        if ($depth > self::BRACE_OVERHANG_THRESHOLD) {
            $postX = $x0 - $this->braceReach($height, $depth, $scale);
            $postTopY = $topY; // full height, matching the wall's own top

            imageline($im, (int) $postX, (int) $y0, (int) $postX, (int) $postTopY, $stroke);
            imageline($im, (int) $postX, (int) $postTopY, (int) $topX, (int) $topY, $stroke);
            imageline($im, (int) $postX, (int) $y0, (int) $topX, (int) $topY, $stroke);

            $braceX1 = $postX;
            $braceY1 = $y0 + ($postTopY - $y0) * 0.4;
            $braceX2 = $x0 + ($topX - $x0) * 0.7;
            $braceY2 = $y0 + ($topY - $y0) * 0.7;
            imageline($im, (int) $braceX1, (int) $braceY1, (int) $braceX2, (int) $braceY2, $stroke);
            imagesetthickness($im, 1);

            imagefilledrectangle($im, (int) ($postX - 7), (int) ($y0 - 4), (int) ($postX + 7), (int) ($y0 + 4), $stroke);
            // At 25% up the post rather than dead-center — the height
            // dimension label sits at the post's own vertical midpoint, and
            // this and that were landing on top of each other.
            $this->text($im, $postX, $y0 + ($postTopY - $y0) * 0.25, $t['support_truss'], 12, self::COLOR_LABEL, false, 'middle', '#ffffff');
            return;
        }

        // Simple push-pull prop: one angled strut from partway up the back
        // down to a ground anchor set back from the wall.
        $attachY = min($height * 0.6, 2.2);
        $frac = $height > 0 ? $attachY / $height : 0;
        $mountX = $x0 + ($topX - $x0) * $frac;
        $mountY = $y0 + ($topY - $y0) * $frac;
        $anchorX = $mountX - $this->braceReach($height, $depth, $scale);

        imageline($im, (int) $mountX, (int) $mountY, (int) $anchorX, (int) $y0, $stroke);
        imagesetthickness($im, 1);
        imagefilledrectangle($im, (int) ($anchorX - 7), (int) ($y0 - 4), (int) ($anchorX + 7), (int) ($y0 + 4), $stroke);
        imagefilledrectangle($im, (int) ($mountX - 5), (int) ($mountY - 5), (int) ($mountX + 5), (int) ($mountY + 5), $stroke);
        $this->text($im, $anchorX, $y0 - 18, $t['support_prop'], 12, self::COLOR_LABEL, false, 'middle', '#ffffff');
    }

    /**
     * The footing the stand-free bracing above actually anchors into — a
     * schematic concrete block reaching `foundationDepth` meters into the
     * ground at the wall's own base, dimensioned the same way every other
     * measurement on this drawing is.
     */
    private function drawFoundationFooting($im, $x0, $y0, $foundationDepth, $scale, array $t)
    {
        $fd = $foundationDepth * $scale;
        $footingWidth = 44; // schematic width — not a real measured dimension

        imagefilledrectangle($im, (int) ($x0 - $footingWidth / 2), (int) $y0, (int) ($x0 + $footingWidth / 2), (int) ($y0 + $fd), $this->color($im, self::COLOR_FOOTING_FILL));
        imagerectangle($im, (int) ($x0 - $footingWidth / 2), (int) $y0, (int) ($x0 + $footingWidth / 2), (int) ($y0 + $fd), $this->color($im, self::COLOR_LINE));

        $this->text($im, $x0, $y0 + $fd + 6, $t['foundation'], 12, self::COLOR_LABEL, false, 'middle');
        $this->dimensionLine($im, $x0 + $footingWidth / 2 + 14, $y0, $x0 + $footingWidth / 2 + 14, $y0 + $fd, $this->fmt($foundationDepth));
    }

    /**
     * A real lean-to canopy roof — one continuous slope, high at the back
     * (the building-wall/post side) and low at the front eave (the overhang
     * direction), not a flat cap. The rise uses the same formula the 3D
     * canopy (wallGeometryBuilder.js's addSiteFeatures) is built from: a
     * fixed fraction of the span, capped tighter for stand-free roofs so a
     * very deep overhang still gets a contained canopy instead of one
     * ballooning right along with it. The resulting pitch is labeled in
     * degrees, since that's the actual buildable spec a roofer needs.
     */
    private function drawRoofIndicator($im, $x0, $topX, $topY, $scale, $isStandFree, array $t)
    {
        $eave = 22; // how far the roof projects past the wall on each side
        $baseGap = 20; // clearance above the wall's own top edge (and the depth-dimension line above that)
        $span = ($topX - $x0) + $eave * 2;

        $riseMin = 0.2 * $scale;
        $riseMax = 1.1 * $scale;
        $rise = $isStandFree
            ? min(max($span * 0.15, $riseMin), $riseMax)
            : max($span * 0.22, $riseMin);

        $frontY = $topY - $baseGap; // low eave, directly above the wall's own top
        $backY = $frontY - $rise; // high eave, over the building-wall/post side
        $pitchDeg = round(rad2deg(atan2($rise, $span)), 1);

        $roofColor = $this->color($im, self::COLOR_ROOF);
        imagesetthickness($im, 4);
        imageline($im, (int) ($x0 - $eave), (int) $backY, (int) ($topX + $eave), (int) $frontY, $roofColor);
        imagesetthickness($im, 1);

        $this->text($im, ($x0 + $topX) / 2, min($backY, $frontY) - 20, $t['roof'] . ' · ' . $pitchDeg . '°', 12, self::COLOR_ROOF, false, 'middle');
    }

    /**
     * Top/plan view: for a single wall, a simple line showing width with a
     * short arrow indicating which way the overhang leans. For a multi-side
     * stand-free tower, the ACTUAL N-sided footprint polygon — the exact same
     * apothem/corner math the 3D viewer's own computeTowerRadius/towerCorner
     * use, so this plan view matches the real rendered shape, not a
     * generic circle.
     */
    public function buildTopView(array $sides, array $labels = [], $scale = 30)
    {
        $t = array_merge($this->defaultLabels(), $labels);
        $n = count($sides);
        $pad = 45;

        if ($n <= 1) {
            $width = $sides[0]['width'] ?? 0;
            $depth = $sides[0]['depth'] ?? 0;
            $w = $width * $scale;
            $canvasW = $w + $pad * 2;
            $canvasH = 110;
            $x0 = $pad;
            $y0 = $canvasH / 2 + 10;

            $im = $this->newCanvas($canvasW, $canvasH);
            imageline($im, (int) $x0, (int) $y0, (int) ($x0 + $w), (int) $y0, $this->color($im, self::COLOR_LINE));

            if ($depth > 0) {
                $arrowX = $x0 + $w / 2;
                $arrowTopY = $y0 - 22;
                $gray = $this->color($im, self::COLOR_LABEL);
                imageline($im, (int) $arrowX, (int) $y0, (int) $arrowX, (int) $arrowTopY, $gray);
                imageline($im, (int) $arrowX, (int) $arrowTopY, (int) ($arrowX - 5), (int) ($arrowTopY + 7), $gray);
                imageline($im, (int) $arrowX, (int) $arrowTopY, (int) ($arrowX + 5), (int) ($arrowTopY + 7), $gray);
                $this->text($im, $arrowX + 8, $y0 - 18, $t['overhang_direction'], 12, self::COLOR_LABEL);
            }
            $this->dimensionLine($im, $x0, $y0 + 22, $x0 + $w, $y0 + 22, $this->fmt($width));

            return $this->toDataUri($im);
        }

        // Regular N-gon footprint: same formula as WallViewer3D.vue's
        // computeTowerRadius (apothem for N faces of average width W).
        $avgWidth = array_sum(array_column($sides, 'width')) / $n;
        $radius = $n === 2 ? max($avgWidth * 0.04, 0.14) : $avgWidth / (2 * tan(M_PI / $n));
        $step = 2 * M_PI / $n;

        $points = [];
        foreach ($sides as $i => $side) {
            $halfW = ($side['width'] ?? $avgWidth) / 2;
            $theta = $i * $step;
            foreach ([-$halfW, $halfW] as $localX) {
                $wx = $localX * cos($theta) + $radius * sin($theta);
                $wz = -$localX * sin($theta) + $radius * cos($theta);
                $points[] = [$wx, $wz];
            }
        }
        $xs = array_column($points, 0);
        $zs = array_column($points, 1);
        $minX = min($xs);
        $maxX = max($xs);
        $minZ = min($zs);
        $maxZ = max($zs);
        // Extra room beyond the polygon itself for the side labels, which are
        // pushed outward from the centroid below (a small polygon otherwise
        // crowds every label toward the same central point, unreadable).
        $labelMargin = 24;
        $canvasW = ($maxX - $minX) * $scale + $pad * 2 + $labelMargin * 2;
        $canvasH = ($maxZ - $minZ) * $scale + $pad * 2 + $labelMargin * 2;
        $originX = $pad + $labelMargin; // pixel-space position of local (0,0), the polygon's own rotation center
        $originY = $pad + $labelMargin;

        $im = $this->newCanvas($canvasW, $canvasH);

        $poly = [];
        foreach ($points as $p) {
            $poly[] = ($p[0] - $minX) * $scale + $originX;
            $poly[] = ($p[1] - $minZ) * $scale + $originY;
        }
        imagefilledpolygon($im, $poly, $this->color($im, self::COLOR_PANEL_FILL));
        imagepolygon($im, $poly, $this->color($im, self::COLOR_LINE));

        $centerXpx = (0 - $minX) * $scale + $originX;
        $centerYpx = (0 - $minZ) * $scale + $originY;

        foreach ($sides as $i => $side) {
            $p1 = $points[$i * 2];
            $p2 = $points[$i * 2 + 1];
            $labelX = (($p1[0] + $p2[0]) / 2 - $minX) * $scale + $originX;
            $labelY = (($p1[1] + $p2[1]) / 2 - $minZ) * $scale + $originY;

            // Pushed outward from the polygon's own center, so labels spread
            // apart instead of all crowding toward the same edge midpoints —
            // which visibly overlapped on a small (e.g. 3-sided) tower.
            $dx = $labelX - $centerXpx;
            $dy = $labelY - $centerYpx;
            $dist = sqrt($dx * $dx + $dy * $dy) ?: 1;
            $labelX += ($dx / $dist) * $labelMargin;
            $labelY += ($dy / $dist) * $labelMargin;

            $this->text($im, $labelX, $labelY - 6, $t['side'] . ' ' . ($i + 1), 12, self::COLOR_DIM, false, 'middle');
        }

        return $this->toDataUri($im);
    }
}
