// "I sent it" share image (1080×1350, Instagram portrait) drawn on a canvas
// after a climber logs an ascent — flat colour band, a hold-yellow grade tape
// and a pencil skyline, like the rest of the climbing.ge scene set. No
// network: everything is drawn here, so it works offline at the crag.

const W = 1080;
const H = 1350;

function css_var(name, fallback) {
    const v = getComputedStyle(document.body).getPropertyValue(name).trim();
    return v || fallback;
}

function wrap(ctx, text, max_width) {
    const words = String(text || '').split(/\s+/).filter(Boolean);
    const lines = [];
    let line = '';
    words.forEach(w => {
        const test = line ? line + ' ' + w : w;
        if (ctx.measureText(test).width > max_width && line) { lines.push(line); line = w; } else line = test;
    });
    if (line) lines.push(line);
    return lines;
}

// wobbly pencil line through points, drawn twice slightly offset
function pencil(ctx, pts, color, width) {
    for (let pass = 0; pass < 2; pass++) {
        ctx.beginPath();
        pts.forEach(([x, y], i) => {
            const jx = x + (pass ? 3 : 0) + Math.sin(i * 1.7 + pass) * 2;
            const jy = y + (pass ? 2 : 0) + Math.cos(i * 1.3 + pass) * 2;
            i ? ctx.lineTo(jx, jy) : ctx.moveTo(jx, jy);
        });
        ctx.strokeStyle = color;
        ctx.globalAlpha = pass ? 0.45 : 1;
        ctx.lineWidth = width;
        ctx.lineCap = ctx.lineJoin = 'round';
        ctx.stroke();
    }
    ctx.globalAlpha = 1;
}

/**
 * @param {{route:string, grade:string, style:string, date:string, area:string, labels:{sent:string}}} d
 * @returns {Promise<Blob>}
 */
export async function draw_send_card(d) {
    if (document.fonts && document.fonts.ready) { try { await document.fonts.ready; } catch (e) {} }
    const display = css_var('--cg-font-display', '"Barlow Condensed", sans-serif');
    const body = css_var('--cg-font-body', 'Manrope, sans-serif');
    const accent = css_var('--cg-accent', '#1f93b0');
    const ink = '#15212b';
    const hold = '#ffc233';

    const c = document.createElement('canvas');
    c.width = W; c.height = H;
    const ctx = c.getContext('2d');

    // chalk page + accent band
    ctx.fillStyle = '#f4f1ea'; ctx.fillRect(0, 0, W, H);
    ctx.fillStyle = accent; ctx.fillRect(0, 0, W, 520);

    // band skyline (pencil, white)
    pencil(ctx, [[0, 470], [140, 360], [230, 420], [360, 250], [470, 380], [560, 330], [700, 180], [820, 340], [930, 290], [1080, 400]], 'rgba(255,255,255,.9)', 6);
    // tiny flag on the main summit
    pencil(ctx, [[700, 180], [700, 120]], '#fff', 5);
    ctx.fillStyle = hold; ctx.beginPath(); ctx.moveTo(702, 120); ctx.lineTo(750, 134); ctx.lineTo(702, 148); ctx.closePath(); ctx.fill();

    // brand
    ctx.fillStyle = '#fff';
    ctx.font = `700 54px ${display}`;
    ctx.textBaseline = 'top';
    ctx.fillText('climbing.ge', 70, 64);

    // "SENT ✓" pill
    ctx.font = `800 40px ${body}`;
    const sent = (d.labels && d.labels.sent || 'Sent') + ' ✓';
    const sw = ctx.measureText(sent).width + 64;
    ctx.fillStyle = ink; roundRect(ctx, W - 70 - sw, 56, sw, 76, 38); ctx.fill();
    ctx.fillStyle = '#fff'; ctx.fillText(sent, W - 70 - sw + 32, 74);

    // grade on route-setting tape
    ctx.font = `700 200px ${display}`;
    const gw = ctx.measureText(d.grade || '?').width + 110;
    ctx.save(); ctx.translate(70, 560); ctx.rotate(-0.035);
    ctx.fillStyle = hold; ctx.beginPath();
    ctx.moveTo(0, 0); ctx.lineTo(gw, 6); ctx.lineTo(gw - 26, 120); ctx.lineTo(gw, 236); ctx.lineTo(4, 230); ctx.closePath(); ctx.fill();
    ctx.fillStyle = ink; ctx.fillText(d.grade || '?', 44, 18);
    ctx.restore();

    // route name
    ctx.fillStyle = ink;
    ctx.font = `700 104px ${display}`;
    let y = 860;
    wrap(ctx, d.route, W - 140).slice(0, 2).forEach(line => { ctx.fillText(line, 70, y); y += 108; });

    // style · date · area
    ctx.font = `700 44px ${body}`;
    ctx.fillStyle = '#4b5560';
    ctx.fillText([d.style, d.date].filter(Boolean).join('  ·  '), 70, y + 24);
    if (d.area) {
        ctx.font = `600 40px ${body}`;
        ctx.fillStyle = accent;
        wrap(ctx, d.area, W - 140).slice(0, 1).forEach(line => ctx.fillText(line, 70, y + 90));
    }

    // carabiner doodle bottom-right
    ctx.save(); ctx.translate(930, 1210); ctx.rotate(0.5);
    ctx.lineWidth = 12; ctx.strokeStyle = ink; ctx.globalAlpha = 0.85;
    roundRect(ctx, -46, -80, 92, 160, 46); ctx.stroke();
    ctx.strokeStyle = hold; ctx.beginPath(); ctx.moveTo(46, -30); ctx.lineTo(46, 30); ctx.stroke();
    ctx.restore();

    return new Promise(resolve => c.toBlob(resolve, 'image/png'));
}

function roundRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
}

// native share sheet with the image where supported, otherwise download it
export async function share_send_card(d) {
    const blob = await draw_send_card(d);
    const name = ('climbing-ge-' + (d.route || 'send')).replace(/[^\wა-ჿ-]+/g, '-').slice(0, 60) + '.png';
    const file = typeof File !== 'undefined' ? new File([blob], name, { type: 'image/png' }) : null;
    if (file && navigator.canShare && navigator.canShare({ files: [file] })) {
        try { await navigator.share({ files: [file], title: d.route, text: d.route + ' ' + (d.grade || '') + ' — climbing.ge' }); return 'shared'; }
        catch (e) { if (e && e.name === 'AbortError') return 'cancelled'; }
    }
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = name;
    document.body.appendChild(a);
    a.click();
    setTimeout(() => { URL.revokeObjectURL(a.href); a.remove(); }, 500);
    return 'downloaded';
}
