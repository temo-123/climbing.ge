// Real-world plywood panel look: a 21cm bolt-hole (T-nut) grid — the actual
// industry-standard spacing used on real climbing wall panels — plus contour
// lines marking where individual 1.5m x 1.5m plywood sheets seam together,
// since a real wall surface is built from multiple standard sheets, not one
// continuous slab.
import { WALL_CONSTRUCTION } from '../wallConstructionConfig.js';

export const HOLE_SPACING = WALL_CONSTRUCTION.HOLE_SPACING;
export const SHEET_SIZE = WALL_CONSTRUCTION.SHEET_SIZE;
const HOLE_MARGIN = 0.1;

// Local-space (meters) grid of bolt-hole positions across a panel of the given
// width/length. Shared by the texture painter and the holds instancer so the
// physical holds always land exactly on a drawn hole, never floating between them.
export function computeBoltHoleGrid(panelWidth, panelLength) {
    const cols = [];
    for (let x = -panelWidth / 2 + HOLE_MARGIN; x <= panelWidth / 2 - HOLE_MARGIN + 1e-6; x += HOLE_SPACING) {
        cols.push(x);
    }
    const rows = [];
    for (let y = HOLE_MARGIN; y <= panelLength - HOLE_MARGIN + 1e-6; y += HOLE_SPACING) {
        rows.push(y);
    }
    const points = [];
    rows.forEach((y) => cols.forEach((x) => points.push({ x, y })));
    return points;
}

const MAX_TEXTURE_PX = 2048;

export function buildPanelTexture(THREE, panelWidth, panelLength) {
    const pxPerMeter = 110;
    const w = Math.min(Math.max(Math.round(panelWidth * pxPerMeter), 64), MAX_TEXTURE_PX);
    const h = Math.min(Math.max(Math.round(panelLength * pxPerMeter), 64), MAX_TEXTURE_PX);
    const scaleX = w / panelWidth;
    const scaleY = h / panelLength;

    const canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    const ctx = canvas.getContext('2d');

    // Base plywood tone with faint horizontal grain streaks.
    ctx.fillStyle = '#c8a876';
    ctx.fillRect(0, 0, w, h);
    ctx.strokeStyle = 'rgba(120,90,55,0.10)';
    ctx.lineWidth = 1;
    for (let gy = 0; gy < h; gy += 5) {
        ctx.beginPath();
        ctx.moveTo(0, gy + (Math.sin(gy * 0.7) * 1.5));
        ctx.lineTo(w, gy + (Math.cos(gy * 0.4) * 1.5));
        ctx.stroke();
    }

    // Plywood sheet seams every 1.5m — panel x runs -width/2..width/2, so seams
    // are drawn from the panel's edge, not from an arbitrary origin.
    ctx.strokeStyle = 'rgba(70,50,30,0.55)';
    ctx.lineWidth = Math.max(scaleX, scaleY) * 0.02;
    for (let sx = 0; sx <= panelWidth + 1e-6; sx += SHEET_SIZE) {
        const px = sx * scaleX;
        ctx.beginPath();
        ctx.moveTo(px, 0);
        ctx.lineTo(px, h);
        ctx.stroke();
    }
    for (let sy = 0; sy <= panelLength + 1e-6; sy += SHEET_SIZE) {
        const py = h - sy * scaleY; // texture v=0 is the panel's top edge
        ctx.beginPath();
        ctx.moveTo(0, py);
        ctx.lineTo(w, py);
        ctx.stroke();
    }

    // Bolt holes (T-nuts) on the real 21cm grid.
    const holeRadiusPx = 0.018 * Math.max(scaleX, scaleY);
    ctx.fillStyle = 'rgba(35,25,15,0.85)';
    computeBoltHoleGrid(panelWidth, panelLength).forEach(({ x, y }) => {
        const px = (x + panelWidth / 2) * scaleX;
        const py = h - y * scaleY;
        ctx.beginPath();
        ctx.arc(px, py, holeRadiusPx, 0, Math.PI * 2);
        ctx.fill();
    });

    const texture = new THREE.CanvasTexture(canvas);
    texture.wrapS = THREE.ClampToEdgeWrapping;
    texture.wrapT = THREE.ClampToEdgeWrapping;
    return texture;
}
