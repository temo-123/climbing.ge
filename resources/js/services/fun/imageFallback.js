// Missing images: when any <img> fails to load (file lost from the server,
// bad path…), swap in a climbing placeholder instead of an empty box — the
// chalk background with faint gear doodles, like every other image area.
//
// One capturing listener on the document catches every image error. It waits a
// tick first so a component's own @error fallback (e.g. a default image) wins;
// only if the image is still broken afterwards is the placeholder used.
// Opt out per image with  data-no-fallback .

// A transparent 400×225 box keeps the 16:9 shape of every photo frame; the climbing look
// (chalk + gear doodles) comes from CSS — img.cg-img-fallback in _fun.scss —
// so it matches every other image area on the site.
export const IMAGE_PLACEHOLDER = "data:image/svg+xml;charset=utf-8," +
    encodeURIComponent("<svg xmlns='http://www.w3.org/2000/svg' width='400' height='225'/>")

// The site's stock "no image" pictures (black NO IMAGE graphic, demo product
// photo) are treated like missing images too, so every empty image area looks
// the same. CSS keeps them hidden until they're swapped (no black flash).
const STOCK_NO_IMAGE = /\/site_img\/image\.png$|\/demo_imgs\/shop_demo\.jpg$/

function usePlaceholder(img) {
    img.dataset.cgFallback = '1'
    img.removeAttribute('srcset')
    img.src = IMAGE_PLACEHOLDER
    img.classList.add('cg-img-fallback')
}

export function installImageFallback() {
    if (typeof document === 'undefined' || window.__cgImgFallback) return
    window.__cgImgFallback = true

    document.addEventListener('error', (event) => {
        const img = event.target
        if (!img || img.tagName !== 'IMG') return
        if (img.dataset.noFallback !== undefined || img.dataset.cgFallback) return
        const failed = img.getAttribute('src')
        setTimeout(() => {
            // a component replaced the source itself — let it try first
            if (img.getAttribute('src') !== failed) return
            usePlaceholder(img)
        }, 0)
    }, true)

    // the same <img> may later get a new, working source (sliders, re-used
    // components) — clear the fallback state so a future error is handled too
    document.addEventListener('load', (event) => {
        const img = event.target
        if (!img || img.tagName !== 'IMG') return
        if (STOCK_NO_IMAGE.test(img.getAttribute('src') || '') && img.dataset.noFallback === undefined) {
            usePlaceholder(img)
            return
        }
        if (!img.dataset.cgFallback) return
        if (img.getAttribute('src') === IMAGE_PLACEHOLDER) return
        delete img.dataset.cgFallback
        img.classList.remove('cg-img-fallback')
    }, true)
}
