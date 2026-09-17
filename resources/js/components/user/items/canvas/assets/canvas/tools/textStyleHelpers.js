// Shared logic for the text tool's Bold/Italic/Strikethrough/Highlight
// toggles (requested September 2026 as "main functions, not a lot" for text
// editing) — used both at CREATION time (DrawingTools.vue's add_text/
// toggleText* methods, while actively drawing) and afterward, per saved item,
// from the layers panel (EditorComponent.vue's changeLayer* handlers). Pulled
// out as a plain module (no Vue) so both call sites share exactly one copy of
// this logic instead of two copies that could quietly drift apart.
//
// Bold is a real Paper.js TextStyle property. Italic has no native
// equivalent in Paper.js, so it's faked with a shear transform — the shared
// paperJsonRenderer.js was updated to apply a PointText's FULL transform
// matrix (not just its x/y translation) specifically so this bakes into
// composites/viewers identically to the live editor. Strikethrough/Highlight
// need actual extra geometry a bare PointText can't hold, so those two
// promote the text into a small Group the first time either is turned on —
// the same "atomic composite symbol" pattern already used for arrows/rappel
// markers/etc (see DrawingTools.vue's _isAtomicMarkerGroup).
import paper from 'paper';

// Finds the actual PointText for either a bare legacy text item or an
// already-promoted text Group — every function below reads/writes real text
// properties through this, so a bare vs. promoted item never needs two
// separate code paths anywhere else.
function textNode(item) {
    if (!item) return null;
    if (item instanceof paper.PointText) return item;
    if (item instanceof paper.Group) {
        return item.children.find(c => c.data && c.data.isTextBody)
            || item.children.find(c => c instanceof paper.PointText)
            || null;
    }
    return null;
}

// Promotes a bare legacy PointText into a Group at the SAME position in its
// parent's stacking order, so decorations (highlight rect drawn first /
// strikethrough line drawn after) have somewhere to live alongside it. A
// no-op if already promoted. Returns the group either way.
function ensureTextGroup(item) {
    if (item instanceof paper.Group) return item;
    if (!(item instanceof paper.PointText)) return item;
    const parent = item.parent;
    const index = item.index;
    const group = new paper.Group({ name: item.name, data: { isTextGroup: true } });
    item.data = { ...(item.data || {}), isTextBody: true };
    if (parent) parent.insertChild(index, group);
    group.addChild(item);
    return group;
}

function toggleBold(item) {
    const text = textNode(item);
    if (!text) return;
    text.fontWeight = (text.fontWeight === 'bold') ? 'normal' : 'bold';
}

// Always shears the INNER PointText itself, never the wrapping group — bare
// vs. promoted-for-strikethrough/highlight must not change WHICH object
// holds the italic flag/shear, or toggling again after a later promotion
// would shear a second, already-sheared object instead of reversing the
// first one. Exactly reversible no matter how many times it's toggled,
// since it always shears from a flag-tracked clean state rather than
// nudging the live matrix repeatedly.
function toggleItalic(item) {
    const text = textNode(item);
    if (!text) return;
    const ITALIC_SHEAR = 0.22; // ~12.5°, a standard italic slant angle
    const wasItalic = !!(text.data && text.data.italic);
    text.shear(new paper.Point(wasItalic ? -ITALIC_SHEAR : ITALIC_SHEAR, 0), text.position);
    text.data = { ...(text.data || {}), italic: !wasItalic };
}

function toggleStrikethrough(item) {
    const group = ensureTextGroup(item);
    const text = textNode(group);
    if (!text) return group;
    const existing = (group.children || []).find(c => c.data && c.data.isTextStrike);
    if (existing) { existing.remove(); return group; }
    const b = text.bounds; // in the text's own parent (the group) space
    const line = new paper.Path.Line({
        from: [b.left, b.center.y],
        to:   [b.right, b.center.y],
        strokeColor: text.fillColor,
        strokeWidth: Math.max(1, (text.fontSize || 16) * 0.08),
        data: { isTextStrike: true },
    });
    group.addChild(line);
    return group;
}

// color === null/undefined turns highlight OFF (removes the rect). A
// non-null color turns it on (creating the rect if needed) or just updates
// the existing rect's color.
function toggleHighlight(item, color) {
    const group = ensureTextGroup(item);
    const text = textNode(group);
    if (!text) return group;
    const existing = (group.children || []).find(c => c.data && c.data.isTextHighlight);
    if (!color) {
        if (existing) existing.remove();
        return group;
    }
    if (existing) { existing.fillColor = color; return group; }
    const pad = (text.fontSize || 16) * 0.18;
    const rect = new paper.Path.Rectangle({
        point: [text.bounds.left - pad, text.bounds.top - pad],
        size: [text.bounds.width + pad * 2, text.bounds.height + pad * 2],
        fillColor: color,
        data: { isTextHighlight: true },
    });
    group.insertChild(0, rect); // behind the text, not on top of it
    return group;
}

// Changes the text's font size, then re-fits any highlight rect/
// strikethrough line to the new bounds — both were sized/positioned off
// the text's bounds AT THE TIME they were created, so a later font-size
// change would otherwise leave them the wrong size or misaligned.
function resizeTextGroup(item, fontSize) {
    const text = textNode(item);
    if (!text) return;
    text.fontSize = fontSize;
    if (!(item instanceof paper.Group)) return;
    const hadHighlight = item.children.find(c => c.data && c.data.isTextHighlight);
    const highlightColor = hadHighlight ? hadHighlight.fillColor : null;
    if (hadHighlight) toggleHighlight(item, null);
    const hadStrike = !!item.children.find(c => c.data && c.data.isTextStrike);
    if (hadStrike) toggleStrikethrough(item); // remove
    if (highlightColor) toggleHighlight(item, highlightColor);
    if (hadStrike) toggleStrikethrough(item); // re-add at the new size
}

export { textNode, ensureTextGroup, toggleBold, toggleItalic, toggleStrikethrough, toggleHighlight, resizeTextGroup };
