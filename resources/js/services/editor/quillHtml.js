import { ref, watch } from 'vue'

/*
 * Editor → stored HTML: quill.getSemanticHTML(), not root.innerHTML.
 * Quill 2 keeps every list internally as <ol><li data-list="bullet|ordered"> with
 * <span class="ql-ui"> markers and nesting as ql-indent-N classes; saved as-is, bullet
 * lists show numbered on the site and nested lists come out flat. getSemanticHTML()
 * writes real <ul>/<ol>/<li> (nested lists included) and drops the editor-only markup.
 * Its one loss: the video embed writes itself as a plain <a> link — keep the iframe.
 */
//
// Table width: Quill keeps no attributes on <table>, so the width is a block format on
// every cell (class ql-tw-75 / -50 / -25 / -fit; none = full width). It survives the
// clipboard load like alignment does; CSS sizes the table with table:has(td.ql-tw-…)
// (components/_rich_text.scss).
export const TABLE_WIDTHS = ['75', '50', '25', 'fit']

// Registered from the editor's ready event: vue-quill loads Quill lazily, and its
// exported `Quill` is a proxy that throws until then, so this must not run on import.
let formats_registered = false
function register_formats(Quill) {
    if (formats_registered) return
    const Video = Quill.import('formats/video')
    class StoredVideo extends Video {
        html() {
            return this.domNode.outerHTML
        }
    }
    Quill.register('formats/video', StoredVideo, true)

    const { ClassAttributor, Scope } = Quill.import('parchment')
    Quill.register(new ClassAttributor('table-width', 'ql-tw', { scope: Scope.BLOCK, whitelist: TABLE_WIDTHS }), true)

    formats_registered = true
}

/*
 * Stored HTML → Quill editor.
 *
 * vue-quill's own v-model:content (contentType="html") loads text with
 * `quill.root.innerHTML = html`; Quill 2 then drops markup it has no blot for.
 * Old texts (CKEditor era: <div> wrappers, oEmbed blocks, iframes) lost whole
 * paragraphs, videos and maps that way. Quill's clipboard converter maps any
 * HTML onto Quill formats instead, so nothing visible is lost.
 *
 * Loaded "silent": opening a form doesn't count as an edit — the stored HTML is
 * only replaced once the admin actually changes the text.
 */
export function load_html(quill, html) {
    quill.setContents(quill.clipboard.convert({ html: html || '' }), 'silent')
}

/*
 * v-model for <big_editor> / <small_editor> / <mini_editor>.
 * Use on <QuillEditor contentType="html" @ready="on_ready" @update:content="on_update"> —
 * do NOT also bind v-model:content (that brings the innerHTML path back).
 * show_source (optional ref): while the raw-HTML textarea (bound to `content`) is open,
 * its edits are emitted from here; Quill is re-created and reloaded when it closes.
 */
export function use_quill_html(props, emit, show_source = null) {
    const content = ref(props.modelValue || '')
    let quill = null
    let last_emitted = null

    const source_open = () => !!(show_source && show_source.value)

    const emit_value = (html) => {
        last_emitted = html
        emit('update:modelValue', html)
    }

    const on_ready = (instance) => {
        quill = instance
        register_formats(instance.constructor)   // before loading, so videos / table widths use it
        load_html(quill, content.value)
    }

    // vue-quill's payload is root.innerHTML — serialize semantically instead (see top)
    const on_update = (html) => {
        if (quill) html = quill.getSemanticHTML()
        content.value = html
        emit_value(html)
    }

    // value set from outside (form data loaded later, reset…)
    watch(() => props.modelValue, (value) => {
        value = value || ''
        if (value === last_emitted || value === content.value) return
        content.value = value
        if (quill && !source_open()) load_html(quill, value)
    })

    if (show_source) {
        watch(content, (value) => {
            if (source_open() && value !== last_emitted) emit_value(value)
        })
        // the QuillEditor is unmounted while the source is open
        watch(show_source, (open) => { if (open) quill = null })
    }

    return { content, on_ready, on_update }
}

/*
 * Hover tooltips (title + aria-label) for the Quill toolbar — Quill renders bare icons.
 * t = vue-i18n translate; strings live in admin.editor.tools.*.
 */
const TOOL_TITLES = [
    ['.ql-header[value="1"]', 'heading_1'],
    ['.ql-header[value="2"]', 'heading_2'],
    ['.ql-picker.ql-header', 'heading'],
    ['.ql-picker.ql-font', 'font'],
    ['.ql-picker.ql-size', 'size'],
    ['.ql-picker.ql-color', 'color'],
    ['.ql-picker.ql-background', 'background'],
    ['.ql-picker.ql-align', 'align'],
    ['.ql-bold', 'bold'],
    ['.ql-italic', 'italic'],
    ['.ql-underline', 'underline'],
    ['.ql-strike', 'strike'],
    ['.ql-blockquote', 'blockquote'],
    ['.ql-direction', 'direction'],
    ['.ql-list[value="ordered"]', 'list_ordered'],
    ['.ql-list[value="bullet"]', 'list_bullet'],
    ['.ql-indent[value="-1"]', 'indent_less'],
    ['.ql-indent[value="+1"]', 'indent_more'],
    ['.ql-link', 'link'],
    ['.ql-image', 'image'],
    ['.ql-video', 'video'],
    ['.ql-table', 'table'],
    ['.ql-clean', 'clean'],
]

export function add_toolbar_titles(quill, t) {
    const toolbar = quill.getModule('toolbar')
    if (!toolbar || !toolbar.container) return
    for (const [selector, key] of TOOL_TITLES) {
        toolbar.container.querySelectorAll(selector).forEach((el) => {
            const label = t('admin.editor.tools.' + key)
            el.setAttribute('title', label)
            if (el.tagName === 'BUTTON') el.setAttribute('aria-label', label)
        })
    }
}
