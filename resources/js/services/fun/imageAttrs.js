// Every <img> on every page ends up with width / height and loading attributes
// (SEO audits flag images without them, and the size gives the image an
// intrinsic aspect ratio).
//
// - width / height: most images can't know their size up front (uploads store
//   no dimensions), so once an image loads its natural size is written onto it.
//   Those attributes are marked  data-cg-size  and neutralised in CSS
//   (_fun.scss, a zero-specificity  width/height: auto ) so they never change
//   the layout — any component rule still wins. Attributes a template sets
//   itself are left alone.
// - loading: templates set it on their own <img> tags; images that arrive as
//   HTML (v-html article texts, Quill content) get  loading="lazy"  here as
//   soon as they're inserted.
// Opt out per image with  data-no-size .

function setNaturalSize(img) {
    if (img.dataset.noSize !== undefined) return
    const w = img.naturalWidth, h = img.naturalHeight
    if (!w || !h) return
    // ours (data-cg-size) are refreshed when the source changes; the template's are kept
    const ours = img.dataset.cgSize !== undefined
    if (!ours && (img.hasAttribute('width') || img.hasAttribute('height'))) return
    img.setAttribute('width', w)
    img.setAttribute('height', h)
    img.dataset.cgSize = ''
}

function setLazy(img) {
    if (!img.hasAttribute('loading')) img.setAttribute('loading', 'lazy')
    if (!img.hasAttribute('decoding')) img.setAttribute('decoding', 'async')
}

export function installImageAttrs() {
    if (typeof document === 'undefined' || window.__cgImgAttrs) return
    window.__cgImgAttrs = true

    document.addEventListener('load', (event) => {
        const img = event.target
        if (img && img.tagName === 'IMG') setNaturalSize(img)
    }, true)

    const observer = new MutationObserver((mutations) => {
        for (const m of mutations) {
            for (const node of m.addedNodes) {
                if (node.nodeType !== 1) continue
                if (node.tagName === 'IMG') setLazy(node)
                else if (node.getElementsByTagName) {
                    for (const img of node.getElementsByTagName('img')) setLazy(img)
                }
            }
        }
    })
    observer.observe(document.documentElement, { childList: true, subtree: true })

    // images already in the page (server-rendered markup) before this ran
    for (const img of document.images) {
        setLazy(img)
        if (img.complete) setNaturalSize(img)
    }
}
