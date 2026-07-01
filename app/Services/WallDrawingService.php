<?php

namespace App\Services;

/**
 * Builds real orthographic 2D technical drawings (front elevation, side
 * profile, top/plan view) for the wall calculator's PDF export — plain SVG
 * strings with dimension lines and labels, computed straight from the same
 * width/height/depth/sides values the 3D viewer uses, not a screenshot or an
 * isometric sketch. Kept as its own service (not inline in the Blade view)
 * since the projection trig is substantial enough to want unit-testable,
 * readable methods rather than a wall of @php blocks.
 */
class WallDrawingService
{
    /**
     * A dimension line: a straight line between two points with perpendicular
     * tick marks at each end and a centered text label — the standard CAD
     * convention for annotating a measurement, not just a bare arrow.
     */
    protected function dimensionLine($x1, $y1, $x2, $y2, $label)
    {
        $tick = 4;
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $len = sqrt($dx * $dx + $dy * $dy) ?: 1;
        // Perpendicular unit vector, for the tick marks at each end.
        $px = -$dy / $len * $tick;
        $py = $dx / $len * $tick;
        $midX = ($x1 + $x2) / 2;
        $midY = ($y1 + $y2) / 2;

        return '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="#4a90d9" stroke-width="1"/>'
            . '<line x1="' . ($x1 - $px) . '" y1="' . ($y1 - $py) . '" x2="' . ($x1 + $px) . '" y2="' . ($y1 + $py) . '" stroke="#4a90d9" stroke-width="1"/>'
            . '<line x1="' . ($x2 - $px) . '" y1="' . ($y2 - $py) . '" x2="' . ($x2 + $px) . '" y2="' . ($y2 + $py) . '" stroke="#4a90d9" stroke-width="1"/>'
            . '<rect x="' . ($midX - strlen($label) * 3 - 3) . '" y="' . ($midY - 7) . '" width="' . (strlen($label) * 6 + 6) . '" height="12" fill="#fff"/>'
            . '<text x="' . $midX . '" y="' . ($midY + 3) . '" font-size="10" fill="#1a1a2e" text-anchor="middle" font-family="Helvetica, Arial, sans-serif">' . $label . '</text>';
    }

    protected function fmt($meters)
    {
        return rtrim(rtrim(number_format($meters, 2), '0'), '.') . 'm';
    }

    /**
     * Front elevation: the plain rectangular footprint of one wall face as a
     * climber standing in front of it would see it (width x height), with
     * both dimensions annotated.
     */
    public function buildFrontView($width, $height, $scale = 40)
    {
        $w = $width * $scale;
        $h = $height * $scale;
        $pad = 40;
        $svgW = $w + $pad * 2;
        $svgH = $h + $pad * 2;
        $x0 = $pad;
        $y0 = $pad;

        $svg = '<svg viewBox="0 0 ' . $svgW . ' ' . $svgH . '" width="100%" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect x="' . $x0 . '" y="' . $y0 . '" width="' . $w . '" height="' . $h . '" fill="#f4f7fa" stroke="#1a1a2e" stroke-width="1.5"/>';
        $svg .= $this->dimensionLine($x0, $y0 + $h + 18, $x0 + $w, $y0 + $h + 18, $this->fmt($width));
        $svg .= $this->dimensionLine($x0 - 18, $y0, $x0 - 18, $y0 + $h, $this->fmt($height));
        $svg .= '</svg>';
        return $svg;
    }

    /**
     * Side profile: the height climbing straight up, then leaning out by the
     * overhang depth at the top — the same tiltAngle = atan2(depth, height)
     * convention the 3D viewer uses. `vertHeight` (bouldering only) marks
     * where the wall's own straight base section ends and the overhang
     * begins, matching buildBoulderingModel's own kink point.
     */
    public function buildSideView($height, $depth, $vertHeight = null, $scale = 40)
    {
        $h = $height * $scale;
        $d = $depth * $scale;
        $pad = 45;
        $svgW = max($d, 10) + $pad * 2 + 30;
        $svgH = $h + $pad * 2;
        $x0 = $pad;
        $y0 = $pad + $h; // ground level

        $topX = $x0 + $d;
        $topY = $y0 - $h;

        $svg = '<svg viewBox="0 0 ' . $svgW . ' ' . $svgH . '" width="100%" xmlns="http://www.w3.org/2000/svg">';

        if ($vertHeight !== null && $vertHeight > 0 && $vertHeight < $height) {
            // Bouldering: straight section, then the overhang kink.
            $kinkY = $y0 - ($vertHeight * $scale);
            $svg .= '<polyline points="' . $x0 . ',' . $y0 . ' ' . $x0 . ',' . $kinkY . ' ' . $topX . ',' . $topY . '" fill="none" stroke="#1a1a2e" stroke-width="2.5"/>';
        } else {
            $svg .= '<line x1="' . $x0 . '" y1="' . $y0 . '" x2="' . $topX . '" y2="' . $topY . '" stroke="#1a1a2e" stroke-width="2.5"/>';
        }
        // Ground line
        $svg .= '<line x1="' . ($x0 - 15) . '" y1="' . $y0 . '" x2="' . ($topX + 15) . '" y2="' . $y0 . '" stroke="#999" stroke-width="1" stroke-dasharray="4,3"/>';

        $svg .= $this->dimensionLine($x0 - 25, $y0, $x0 - 25, $topY, $this->fmt($height));
        if ($depth > 0) {
            $svg .= $this->dimensionLine($x0, $topY - 15, $topX, $topY - 15, $this->fmt($depth));
            $tiltDeg = round(rad2deg(atan2($depth, $height)), 1);
            $svg .= '<text x="' . ($x0 + 10) . '" y="' . ($topY + 20) . '" font-size="10" fill="#888" font-family="Helvetica, Arial, sans-serif">' . $tiltDeg . '&#176; overhang</text>';
        }
        $svg .= '</svg>';
        return $svg;
    }

    /**
     * Top/plan view: for a single wall, a simple line showing width with a
     * short arrow indicating which way the overhang leans. For a multi-side
     * stand-free tower, the ACTUAL N-sided footprint polygon — the exact same
     * apothem/corner math the 3D viewer's own computeTowerRadius/towerCorner
     * use, so this plan view matches the real rendered shape, not a
     * generic circle.
     */
    public function buildTopView(array $sides, $scale = 30)
    {
        $n = count($sides);
        $pad = 40;

        if ($n <= 1) {
            $width = $sides[0]['width'] ?? 0;
            $depth = $sides[0]['depth'] ?? 0;
            $w = $width * $scale;
            $svgW = $w + $pad * 2;
            $svgH = 90;
            $x0 = $pad;
            $y0 = $svgH / 2;
            $svg = '<svg viewBox="0 0 ' . $svgW . ' ' . $svgH . '" width="100%" xmlns="http://www.w3.org/2000/svg">';
            $svg .= '<line x1="' . $x0 . '" y1="' . $y0 . '" x2="' . ($x0 + $w) . '" y2="' . $y0 . '" stroke="#1a1a2e" stroke-width="2.5"/>';
            if ($depth > 0) {
                $svg .= '<line x1="' . ($x0 + $w / 2) . '" y1="' . $y0 . '" x2="' . ($x0 + $w / 2) . '" y2="' . ($y0 - 20) . '" stroke="#888" stroke-width="1" marker-end="url(#arrow)"/>';
                $svg .= '<text x="' . ($x0 + $w / 2 + 6) . '" y="' . ($y0 - 12) . '" font-size="9" fill="#888" font-family="Helvetica, Arial, sans-serif">overhang direction</text>';
            }
            $svg .= $this->dimensionLine($x0, $y0 + 20, $x0 + $w, $y0 + 20, $this->fmt($width));
            $svg .= '</svg>';
            return $svg;
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
        $svgW = ($maxX - $minX) * $scale + $pad * 2;
        $svgH = ($maxZ - $minZ) * $scale + $pad * 2;

        $poly = [];
        foreach ($points as $p) {
            $poly[] = (($p[0] - $minX) * $scale + $pad) . ',' . (($p[1] - $minZ) * $scale + $pad);
        }

        $svg = '<svg viewBox="0 0 ' . $svgW . ' ' . $svgH . '" width="100%" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<polygon points="' . implode(' ', $poly) . '" fill="#f4f7fa" stroke="#1a1a2e" stroke-width="1.5"/>';
        foreach ($sides as $i => $side) {
            $p1 = $points[$i * 2];
            $p2 = $points[$i * 2 + 1];
            $labelX = ((($p1[0] + $p2[0]) / 2 - $minX) * $scale + $pad);
            $labelY = ((($p1[1] + $p2[1]) / 2 - $minZ) * $scale + $pad);
            $svg .= '<text x="' . $labelX . '" y="' . $labelY . '" font-size="9" fill="#4a90d9" text-anchor="middle" font-family="Helvetica, Arial, sans-serif">Side ' . ($i + 1) . '</text>';
        }
        $svg .= '</svg>';
        return $svg;
    }
}
