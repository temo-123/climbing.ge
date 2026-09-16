// When an admin replaces a sector/local-image's background photo, every
// route/pitch/extra-drawing already saved on the OLD photo stays in the
// database untouched (correct — the geometry is unaffected by a same-framing
// re-shoot) but the physical composite file the public site serves is just
// the plain new upload, with nothing baked onto it — the drawings silently
// vanish from view until an admin happens to reopen and re-save each one
// individually. Reported September 2026 as "extra drawing is not saved [i.e.
// not visible] after image replace."
//
// Fix: right after a successful replace, automatically redo the exact same
// render-and-save every individual editor already does for a normal save —
// once per item, reusing each item's own unchanged json/geometry — so the
// shared composite file is refreshed immediately with no admin action
// needed. This deliberately reuses the SAME renderer used everywhere else
// (paperJsonRenderer/legendRenderer) rather than a server-side re-implementation,
// so a fix to how items render never has to be kept in sync in two places.
import axios from 'axios';
import { drawItem, drawItemScaled } from './paperJsonRenderer.js';
import { drawCombinedLegend } from './legendRenderer.js';
import { canvasToJpegSized, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES } from './imageSizing.js';

function parseDoubleEncoded(raw) {
    if (raw == null) return null;
    let j = raw;
    try {
        if (typeof j === 'string') j = JSON.parse(j);
        if (typeof j === 'string') j = JSON.parse(j);
    } catch (_) { return null; }
    return j;
}

// Same shape/behavior as every editor's own renderCompositeAtFullResolution:
// waits for the freshly-uploaded photo to actually finish loading before
// drawing anything (a bg that never loads/errors resolves null rather than
// baking a blank canvas), draws every meta's own json at full photo
// resolution, then bakes the one combined legend on top.
function renderCombinedComposite(bgUrl, metas, translate) {
    return new Promise((resolve) => {
        if (!bgUrl || !metas.length) { resolve(null); return; }
        let settled = false;
        const finish = (value) => { if (settled) return; settled = true; clearTimeout(timeoutId); resolve(value); };
        const timeoutId = setTimeout(() => finish(null), 15000);
        const bg = new Image();
        bg.onload = () => {
            try {
                const w = bg.naturalWidth, h = bg.naturalHeight;
                const canvas = document.createElement('canvas');
                canvas.width = w; canvas.height = h;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(bg, 0, 0, w, h);

                metas.forEach(meta => {
                    if (!meta || !meta.json) return;
                    try { drawItemScaled(ctx, meta, w, h, null, null, null, 1, 1); } catch (_) {}
                });

                try {
                    const refWidth = (metas[0] && (metas[0].bg_width || metas[0].canvas_width)) || w;
                    drawCombinedLegend(ctx, w, h, metas.map(m => m.json), refWidth, { drawItem, translate });
                } catch (e) { console.error('drawCombinedLegend failed:', e); }

                finish(canvasToJpegSized(canvas, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES));
            } catch (e) {
                console.error('renderCombinedComposite failed:', e);
                finish(null);
            }
        };
        bg.onerror = () => finish(null);
        bg.src = bgUrl;
    });
}

function metaFromRow(row) {
    return {
        json: parseDoubleEncoded(row.json),
        canvas_width: row.canvas_width, canvas_height: row.canvas_height,
        bg_left: row.bg_left, bg_top: row.bg_top,
        bg_width: row.bg_width, bg_height: row.bg_height,
    };
}

// sectorImageId: the sector_images.id whose photo was just replaced.
// newImageFilename: the filename `replace_sector_image` just returned.
// translate: this.$t bound to 'admin.articles.canvas_editor.' + key, same as
// every editor already passes to drawCombinedLegend.
// Returns { rebaked: boolean, count, failed } — never throws.
async function rebakeSectorImageAfterReplace(sectorImageId, newImageFilename, translate) {
    try {
        const [routesRes, pitchesRes, extraRes] = await Promise.all([
            axios.get('/get_route/get_related_routes_jsons', { params: { sector_image_id: sectorImageId } }).catch(() => ({ data: [] })),
            axios.get('/set_mtp/set_mtp_pitch/get_pitch_jsons_for_sector_image', { params: { sector_image_id: sectorImageId } }).catch(() => ({ data: [] })),
            axios.get('/set_sector_image_extra_drawing/get_for_editor/' + sectorImageId).catch(() => ({ data: {} })),
        ]);
        const routes = routesRes.data || [];
        const pitches = pitchesRes.data || [];
        const extra = extraRes.data && extraRes.data.extra_drawing;

        const metas = [];
        routes.forEach(r => metas.push(metaFromRow(r)));
        pitches.forEach(p => metas.push(metaFromRow(p)));
        if (extra && extra.json) metas.push(metaFromRow(extra));

        const drawable = metas.filter(m => m.json);
        if (!drawable.length) return { rebaked: false, count: 0 };

        const bgUrl = '/public/images/sector_img/' + newImageFilename;
        const jpeg = await renderCombinedComposite(bgUrl, drawable, translate);
        if (!jpeg) return { rebaked: false, count: 0, failed: true };

        const posts = [];
        routes.forEach(r => posts.push(axios.post('/set_route/save_route_drawing', {
            route_id: r.route_id, sector_image_id: sectorImageId, json: r.json, edited_image: jpeg,
            canvas_width: r.canvas_width, canvas_height: r.canvas_height,
            bg_left: r.bg_left, bg_top: r.bg_top, bg_width: r.bg_width, bg_height: r.bg_height,
        })));
        pitches.forEach(p => posts.push(axios.post('/set_mtp/set_mtp_pitch/save_pitch_drawing', {
            pitch_id: p.mtp_pitch_id, sector_image_id: sectorImageId, json: p.json, edited_image: jpeg,
            canvas_width: p.canvas_width, canvas_height: p.canvas_height,
            bg_left: p.bg_left, bg_top: p.bg_top, bg_width: p.bg_width, bg_height: p.bg_height,
        })));
        if (extra && extra.json) posts.push(axios.post('/set_sector_image_extra_drawing/save/' + sectorImageId, {
            json: extra.json, edited_image: jpeg,
            canvas_width: extra.canvas_width, canvas_height: extra.canvas_height,
            bg_left: extra.bg_left, bg_top: extra.bg_top, bg_width: extra.bg_width, bg_height: extra.bg_height,
        }));

        const results = await Promise.allSettled(posts);
        const failed = results.filter(r => r.status === 'rejected').length;
        return { rebaked: true, count: drawable.length, failed: failed || undefined };
    } catch (e) {
        console.error('rebakeSectorImageAfterReplace failed:', e);
        return { rebaked: false, count: 0, failed: true };
    }
}

// sectorLocalImageId: the sector_local_images.id whose photo was just replaced.
// newImageFilename: the filename `update_image` just returned.
async function rebakeSectorLocalImageAfterReplace(sectorLocalImageId, newImageFilename, translate) {
    try {
        const editorRes = await axios.get('/set_sector/set_sector_local_images/get_for_editor/' + sectorLocalImageId).catch(() => ({ data: {} }));
        const layouts = editorRes.data.layouts || [];
        const extraRes = await axios.get('/set_sector_local_image_extra_drawing/get_for_editor/' + sectorLocalImageId).catch(() => ({ data: {} }));
        const extra = extraRes.data && extraRes.data.extra_drawing;

        const metas = [];
        layouts.forEach(l => metas.push(metaFromRow(l)));
        if (extra && extra.json) metas.push(metaFromRow(extra));

        const drawable = metas.filter(m => m.json);
        if (!drawable.length) return { rebaked: false, count: 0 };

        const bgUrl = '/public/images/sector_local_img/' + newImageFilename;
        const jpeg = await renderCombinedComposite(bgUrl, drawable, translate);
        if (!jpeg) return { rebaked: false, count: 0, failed: true };

        const posts = [];
        layouts.forEach(l => posts.push(axios.post('/set_sector/set_sector_local_images/save_canvas_data/' + sectorLocalImageId, {
            canvasData: l.json, sectorId: l.sector_id, edited_image: jpeg,
            canvas_width: l.canvas_width, canvas_height: l.canvas_height,
            bg_left: l.bg_left, bg_top: l.bg_top, bg_width: l.bg_width, bg_height: l.bg_height,
        })));
        if (extra && extra.json) posts.push(axios.post('/set_sector_local_image_extra_drawing/save/' + sectorLocalImageId, {
            json: extra.json, edited_image: jpeg,
            canvas_width: extra.canvas_width, canvas_height: extra.canvas_height,
            bg_left: extra.bg_left, bg_top: extra.bg_top, bg_width: extra.bg_width, bg_height: extra.bg_height,
        }));

        const results = await Promise.allSettled(posts);
        const failed = results.filter(r => r.status === 'rejected').length;
        return { rebaked: true, count: drawable.length, failed: failed || undefined };
    } catch (e) {
        console.error('rebakeSectorLocalImageAfterReplace failed:', e);
        return { rebaked: false, count: 0, failed: true };
    }
}

export { rebakeSectorImageAfterReplace, rebakeSectorLocalImageAfterReplace };
