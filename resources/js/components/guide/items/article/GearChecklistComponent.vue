<template>
    <!-- "What You Need" as a packing checklist. The list is read from the
         article's own text (editor <ol>/<ul> items, or lines split with <br>);
         sentences around the lists stay as small notes between the groups
         ("In winter you also need:"). Ticks are remembered per article in this
         browser. Text without a list falls back to the normal text block
         (unless text_fallback is off because the page shows the text itself);
         a mountaineering route without one gets the list for its grade. -->
    <div v-if="groups.length" class="cg-gear">
        <div class="cg-gear__top">
            <div class="cg-gear__progress">
                <div class="cg-gear__label">
                    <span v-if="all_done" class="cg-gear__done"><i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('guide.gear_check.all_packed') }}</span>
                    <span v-else><i class="fa fa-check-square-o" aria-hidden="true"></i> {{ $t('guide.gear_check.title') }}</span>
                    <small>{{ $t('guide.gear_check.packed', { n: checked_count, total: items.length }) }}</small>
                </div>
                <div class="cg-gear__bar" aria-hidden="true"><span :style="{ width: (checked_count * 100 / items.length) + '%' }"></span></div>
            </div>
            <div class="cg-gear__tools">
                <button type="button" class="btn btn-sm btn-outline-secondary" @click="share_list" :title="$t('guide.gear_check.share')">
                    <i class="fa" :class="shared ? 'fa-check' : 'fa-share-alt'" aria-hidden="true"></i>
                    <span class="d-none d-sm-inline">{{ shared ? $t('guide.gear_check.copied') : $t('guide.gear_check.share') }}</span>
                </button>
                <button v-if="checked_count" type="button" class="btn btn-sm btn-link" @click="reset">{{ $t('guide.gear_check.reset') }}</button>
            </div>
        </div>

        <template v-for="(g, gi) in groups" :key="gi">
            <p v-if="g.note" class="cg-gear__note" v-html="g.note"></p>
            <ul v-if="g.items.length" class="cg-gear__list">
                <li v-for="item in g.items" :key="item.key" :class="{ 'is-on': checked[item.key] }">
                    <label>
                        <input type="checkbox" class="form-check-input" :checked="!!checked[item.key]" @change="toggle(item.key)" />
                        <span v-html="item.html"></span>
                    </label>
                </li>
            </ul>
        </template>
    </div>

    <generalInfo v-else-if="text_fallback && has_text" :global_info_prop="global_info_prop" :locale_data_prop="locale_data_prop" />
</template>

<script>
    import generalInfo from './GeneralInfoComponent'

    // short stable key per item text, so ticks survive the list being edited
    function key_of(text) {
        let h = 5381;
        for (let i = 0; i < text.length; i++) h = ((h << 5) + h + text.charCodeAt(i)) | 0;
        return (h >>> 0).toString(36);
    }
    const clean_text = s => String(s || '').replace(/ /g, ' ').replace(/\s+/g, ' ').trim();
    const meaningful = s => clean_text(s).replace(/[.\-–—:;,]/g, '').trim().length > 0;

    export default {
        name: 'GearChecklist',
        components: { generalInfo },
        props: {
            // same inputs as GeneralInfoComponent (general block + the article's own text)
            global_info_prop: { default: () => [] },
            locale_data_prop: { type: String, default: '' },
            article_id: { type: [Number, String], required: true },
            // labels used when the text has no list (mountaineering: the grade's gear)
            default_items: { type: Array, default: () => [] },
            title: { type: String, default: '' },
            // no list found: show the text instead (false when the page already shows it)
            text_fallback: { type: Boolean, default: true },
        },
        data() {
            return { checked: {}, shared: false };
        },
        computed: {
            // the text exactly as GeneralInfoComponent would show it
            html() {
                const g = this.global_info_prop;
                const own = this.locale_data_prop || '';
                if (!g || g.length === 0 || !g.block_action) return own;
                if (g.block_action === 'befor') return (g.text || '') + own;
                if (g.block_action === 'after') return own + (g.text || '');
                if (g.block_action === 'instead') return g.text || '';
                return own;
            },
            has_text() { return meaningful(String(this.html).replace(/<[^>]*>/g, '')); },
            parsed() { return this.parse(this.html); },
            groups() {
                const count = this.parsed.reduce((n, g) => n + g.items.length, 0);
                if (count >= 2) return this.parsed;
                if (!this.default_items.length) return [];
                // no list in the text: the text (if any) stays as a note above the grade's list
                const note = this.has_text && this.text_fallback ? this.html.replace(/<\/?(p|div)[^>]*>/gi, ' ').trim() : '';
                return [{ note: note || this.$t('guide.gear_check.suggested'), items: this.default_items.map(label => ({ key: key_of(label), html: this.escape(label), text: label })) }];
            },
            items() { return this.groups.reduce((all, g) => all.concat(g.items), []); },
            checked_count() { return this.items.filter(i => this.checked[i.key]).length; },
            all_done() { return this.items.length > 0 && this.checked_count === this.items.length; },
            storage_key() { return 'cg_gear_check_' + this.article_id; },
        },
        watch: {
            article_id: { immediate: true, handler() { this.load(); } },
        },
        methods: {
            escape(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; },

            // top-level blocks in order: lists → items, <br>-split paragraphs →
            // items, other text → a note that starts a new group
            parse(html) {
                if (!html || typeof DOMParser === 'undefined') return [];
                const body = new DOMParser().parseFromString('<div>' + html + '</div>', 'text/html').body.firstChild;
                const groups = [];
                let current = null;
                const group = () => (current = current || (groups.push({ note: '', items: [] }), groups[groups.length - 1]));
                const add = (node_html, text) => {
                    if (!meaningful(text)) return;
                    group().items.push({ key: key_of(clean_text(text)), html: node_html.trim().replace(/(&nbsp;|\s)+$/g, ''), text: clean_text(text) });
                };
                const add_list = list => {
                    Array.from(list.children).forEach(li => {
                        if (li.tagName !== 'LI') return;
                        const nested = Array.from(li.querySelectorAll(':scope > ul, :scope > ol'));
                        nested.forEach(n => n.remove());
                        add(li.innerHTML, li.textContent);
                        nested.forEach(add_list);
                    });
                };
                const walk = parent => {
                    Array.from(parent.childNodes).forEach(node => {
                        if (node.nodeType === 3) {
                            if (meaningful(node.textContent)) { current = null; group().note = this.escape(clean_text(node.textContent)); }
                            return;
                        }
                        if (node.nodeType !== 1) return;
                        const tag = node.tagName;
                        if (tag === 'UL' || tag === 'OL') { add_list(node); return; }
                        if (tag === 'DIV' || tag === 'SPAN' && node.querySelector('ul, ol, p')) { walk(node); return; }
                        const lines = node.innerHTML.split(/<br\s*\/?>/i).filter(l => meaningful(l.replace(/<[^>]*>/g, '')));
                        if (lines.length >= 2) {
                            lines.forEach(l => { const d = document.createElement('div'); d.innerHTML = l; add(l, d.textContent); });
                        } else if (meaningful(node.textContent)) {
                            // a sentence: a note heading the items that follow it
                            current = null;
                            group().note = node.innerHTML.trim();
                        }
                    });
                };
                walk(body);
                return groups.filter(g => g.items.length || g.note);
            },

            load() {
                try { this.checked = JSON.parse(localStorage.getItem(this.storage_key) || '{}'); } catch (e) { this.checked = {}; }
            },
            save() {
                try { localStorage.setItem(this.storage_key, JSON.stringify(this.checked)); } catch (e) {}
            },
            toggle(key) {
                this.checked = Object.assign({}, this.checked, { [key]: !this.checked[key] });
                this.save();
            },
            reset() { this.checked = {}; this.save(); },
            // the list as text with ☑ / ☐, for the team chat
            share_list() {
                const lines = [this.$t('guide.gear_check.share_intro', { title: this.title || document.title })]
                    .concat(this.items.map(i => (this.checked[i.key] ? '☑ ' : '☐ ') + i.text))
                    .concat([window.location.href]).join('\n');
                const done = () => { this.shared = true; setTimeout(() => { this.shared = false; }, 2000); };
                if (navigator.share) navigator.share({ title: this.title, text: lines }).then(done).catch(() => {});
                else if (navigator.clipboard) navigator.clipboard.writeText(lines).then(done).catch(() => window.prompt('', lines));
                else window.prompt('', lines);
            },
        },
    };
</script>

<style scoped>
    .cg-gear { margin: .5rem 0 1.5rem; padding: 1rem 1.1rem; background: var(--cg-surface, #fff); border: 1px solid var(--cg-line); border-left: 5px solid var(--cg-accent); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-gear__top { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1rem; margin-bottom: .75rem; }
    .cg-gear__progress { flex: 1 1 14rem; }
    .cg-gear__label { display: flex; align-items: baseline; justify-content: space-between; gap: .5rem; margin-bottom: .35rem; font-weight: 800; color: var(--cg-ink); }
    .cg-gear__label .fa { color: var(--cg-accent); }
    .cg-gear__label small { color: var(--cg-stone); font-weight: 700; white-space: nowrap; }
    .cg-gear__done, .cg-gear__done .fa { color: #2fa36b !important; }
    .cg-gear__bar { height: .45rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
    .cg-gear__bar span { display: block; height: 100%; background: #2fa36b; transition: width .3s var(--cg-ease, ease); }
    .cg-gear__tools { display: flex; align-items: center; gap: .25rem; }
    .cg-gear__note { margin: .75rem 0 .35rem; color: var(--cg-stone); font-weight: 700; font-size: .92rem; }
    .cg-gear__note:first-of-type { margin-top: 0; }
    .cg-gear__list { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr)); gap: .15rem 1rem; }
    .cg-gear__list label { font-weight: 500; display: flex; gap: .6rem; align-items: flex-start; padding: .4rem .5rem; margin: 0 -.5rem; border-radius: var(--cg-r-sm, 6px); cursor: pointer; line-height: 1.35; }
    .cg-gear__list label:hover { background: var(--cg-chalk); }
    .cg-gear__list input { flex: 0 0 auto; width: 1.15rem; height: 1.15rem; margin-top: .05rem; }
    .cg-gear__list input:checked { background-color: #2fa36b; border-color: #2fa36b; }
    .cg-gear__list li.is-on span { color: var(--cg-muted); text-decoration: line-through; }
    .cg-gear__list :deep(a) { position: relative; z-index: 1; }
    @media (prefers-reduced-motion: reduce) { .cg-gear__bar span { transition: none; } }
</style>
