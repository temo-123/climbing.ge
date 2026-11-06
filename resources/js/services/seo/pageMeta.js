import { computed, watch } from 'vue'
import { useHead, useSeoMeta } from '@unhead/vue'
import { useRoute } from 'vue-router'

/*
 * Client-side page meta for the public sites (guide, shop, blog, summit, films) — used by
 * every <metaData> component.
 *
 * The server already wrote the right tags for the URL it rendered (SeoService +
 * partials/seo.blade.php: canonical, hreflang, cleaned description, OG). Googlebot always
 * loads a URL directly, so on that first page those tags stay the only ones — the client
 * used to add a second canonical (window.location.href, with ?query and #hash), a second
 * hreflang set and the raw description ("." placeholders), and conflicting canonicals make
 * Google ignore both.
 *
 * After in-app navigation the server tags describe the previous page: they are removed once,
 * and the client writes the tags itself with the same URL rules as the server.
 */

const SERVER_PATH = window.location.pathname
let server_tags_removed = false

function remove_server_tags() {
    if (server_tags_removed) return
    document.querySelectorAll('[data-seo="server"]').forEach((el) => el.remove())
    server_tags_removed = true
}

// PHP rawurlencode() — encodeURIComponent leaves ! ' ( ) * as they are
const rawurlencode = (s) => encodeURIComponent(s).replace(/[!'()*]/g, (c) => '%' + c.charCodeAt(0).toString(16).toUpperCase())

/** Path as SeoService::canonicalUrl() writes it: each segment decoded, then rawurlencoded. */
export function canonical_path(pathname) {
    const parts = pathname.split('/').filter(Boolean).map((seg) => {
        try { return rawurlencode(decodeURIComponent(seg)) } catch (e) { return seg }
    })
    return '/' + parts.join('/')
}

function alternates(path) {
    const en = path.replace(/^\/ka(?=\/|$)/, '') || '/'
    return { en, ka: '/ka' + (en === '/' ? '' : en) }
}

// Same rules as SeoService::truncate()/meaningful(): no tags / entities, no "." placeholders,
// ≤160 chars cut at a word boundary
function clean_text(html) {
    if (!html) return ''
    const doc = new DOMParser().parseFromString(String(html), 'text/html')
    return (doc.body.textContent || '').replace(/[\s ]+/g, ' ').trim()
}
const meaningful = (text) => (text.match(/\p{L}{2,}/gu) || []).length >= 2
function truncate(text, length = 160) {
    if (text.length <= length) return text
    let cut = text.slice(0, length - 1)
    const space = cut.lastIndexOf(' ')
    if (space > length * 0.6) cut = cut.slice(0, space)
    return cut.replace(/[\s,.;:\-–—]+$/, '') + '…'
}

export function use_page_meta(props) {
    const route = useRoute()
    // still on the page the server rendered → keep its tags
    const on_server_page = computed(() => route.path === SERVER_PATH || window.location.pathname === SERVER_PATH)

    watch(on_server_page, (same) => { if (!same) remove_server_tags() }, { immediate: true })

    const path = computed(() => canonical_path(route.path))
    const urls = computed(() => {
        const origin = window.location.origin
        const alt = alternates(path.value)
        return { canonical: origin + path.value, en: origin + alt.en, ka: origin + alt.ka }
    })
    const lang = computed(() => (/^\/ka(\/|$)/.test(route.path) ? 'ka' : 'en'))

    const description = computed(() => {
        const text = clean_text(props.description)
        return meaningful(text) ? truncate(text) : undefined
    })
    const image = computed(() => {
        const img = props.image
        if (!img) return undefined
        if (img.startsWith('http') || img.startsWith('//')) return img
        return window.location.origin + (img.startsWith('/') ? img : '/' + img)
    })
    // undefined → unhead writes no tag (the server's stays)
    const client = (value) => computed(() => (on_server_page.value ? undefined : value()))

    useHead({
        title: client(() => props.title),
        htmlAttrs: { lang },
        link: computed(() => on_server_page.value ? [] : [
            { rel: 'canonical', href: urls.value.canonical },
            { rel: 'alternate', hreflang: 'en', href: urls.value.en },
            { rel: 'alternate', hreflang: 'ka', href: urls.value.ka },
            { rel: 'alternate', hreflang: 'x-default', href: urls.value.en },
        ]),
    })

    useSeoMeta({
        description:        client(() => description.value),
        ogTitle:            client(() => props.title),
        ogDescription:      client(() => description.value),
        ogImage:            client(() => image.value),
        ogType:             'website',
        ogUrl:              client(() => urls.value.canonical),
        twitterCard:        'summary_large_image',
        twitterTitle:       client(() => props.title),
        twitterDescription: client(() => description.value),
        twitterImage:       client(() => image.value),
    })
}
