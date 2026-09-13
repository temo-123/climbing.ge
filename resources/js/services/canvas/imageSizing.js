// Encodes a <canvas> to a JPEG data URL, binary-searching the quality
// parameter so the final file size lands inside [minBytes, maxBytes] instead
// of using one fixed quality number — a fixed quality (e.g. 0.92) swings
// wildly in output size depending on how much detail/noise the underlying
// photo has, so the same setting can produce a crisp ~1.2MB file for one
// photo and a soft, over-compressed ~150KB file for another. This is the
// SAME algorithm used by every composite-bake save flow (sector local image,
// spot rock image, route/pitch drawings) — see canvasToJpegSized's callers.
//
// JPEG size is monotonically non-decreasing with quality for a given image,
// so a standard bisection converges in a handful of re-encodes.
function byteLengthOfDataUrl(dataUrl) {
    const idx = dataUrl.indexOf(',');
    const b64 = idx >= 0 ? dataUrl.slice(idx + 1) : dataUrl;
    let padding = 0;
    if (b64.endsWith('==')) padding = 2;
    else if (b64.endsWith('=')) padding = 1;
    return Math.floor((b64.length * 3) / 4) - padding;
}

function distanceFromRange(size, minBytes, maxBytes) {
    if (size < minBytes) return minBytes - size;
    if (size > maxBytes) return size - maxBytes;
    return 0;
}

function canvasToJpegSized(canvas, minBytes, maxBytes, maxIterations = 8) {
    let lo = 0.05, hi = 0.98;
    let bestUrl = null, bestDist = Infinity;

    const evaluate = (q) => {
        const url = canvas.toDataURL('image/jpeg', q);
        const size = byteLengthOfDataUrl(url);
        const dist = distanceFromRange(size, minBytes, maxBytes);
        if (dist < bestDist) { bestDist = dist; bestUrl = url; }
        return size;
    };

    for (let i = 0; i < maxIterations; i++) {
        const mid = (lo + hi) / 2;
        const size = evaluate(mid);
        if (bestDist === 0) break; // landed inside the target range already
        if (size > maxBytes) hi = mid; else lo = mid;
    }

    // A very simple/flat image (thin lines on a plain sky, say) may never
    // reach minBytes no matter how high the quality goes — in that case
    // bestUrl is whatever candidate got closest (typically the highest
    // quality tried), which is the honest best-effort result rather than an
    // error.
    return bestUrl || canvas.toDataURL('image/jpeg', 0.92);
}

// Shared target range for every composite-bake save flow in the canvas
// editor system (see canvasToJpegSized's callers) — chosen so saved sector/
// route photos keep enough real detail to be useful as climbing references
// without ballooning page weight.
const COMPOSITE_JPEG_MIN_BYTES = 0.8 * 1024 * 1024;
const COMPOSITE_JPEG_MAX_BYTES = 1.5 * 1024 * 1024;

export { canvasToJpegSized, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES };
