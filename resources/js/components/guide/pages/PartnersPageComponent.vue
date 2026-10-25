<template>
    <!-- "Find a partner" board: upcoming climbing days posted by climbers.
         API: get_partner_post/* (public), set_partner_post/* (logged in). -->
    <div class="h-recent-work">
        <div class="container">
            <section class="cg-page-head cg-page-head--list cg-art-carabiners">
                <span class="cg-page-head__icon"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>
                <div class="cg-page-head__text">
                    <h1>{{ $t('guide.partners.title') }}</h1>
                    <div class="cg-page-head__lead">{{ $t('guide.partners.lead') }}</div>
                </div>
            </section>

            <div class="cg-partners__bar">
                <div class="cg-segmented" role="group" :aria-label="$t('guide.partners.discipline')">
                    <button type="button" :class="{ active: filter === 'all' }" @click="filter = 'all'">{{ $t('guide.partners.all') }}</button>
                    <button v-for="d in disciplines_present" :key="d" type="button" :class="{ active: filter === d }" @click="filter = d">{{ $t('guide.partners.disciplines.' + d) }}</button>
                </div>
                <button type="button" class="btn btn-hold" @click="open_form">
                    <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('guide.partners.new_post') }}
                </button>
            </div>

            <!-- guests: sign up / log in to post and to see notes + contacts -->
            <div v-if="!logged_in" class="cg-partners__gate">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <div>
                    <strong>{{ $t('guide.partners.gate_title') }}</strong>
                    <p class="mb-0">{{ $t('guide.partners.gate_text') }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-hold btn-sm" @click="ask_login('register')">{{ $t('auth.quick_register.tab') }}</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" @click="ask_login('login')">{{ $t('common.login') }}</button>
                </div>
            </div>

            <form v-if="show_form" class="cg-partners__form" @submit.prevent="send">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <label class="form-label">{{ $t('guide.partners.date') }} *</label>
                        <input type="date" class="form-control" v-model="form.climb_date" :min="today" required />
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label">{{ $t('guide.partners.discipline') }} *</label>
                        <select class="form-select" v-model="form.discipline" required>
                            <option v-for="d in disciplines" :key="d" :value="d">{{ $t('guide.partners.disciplines.' + d) }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('guide.partners.area') }}</label>
                        <select class="form-select" v-model="form.article_id">
                            <option :value="null">{{ $t('guide.partners.area_other') }}</option>
                            <optgroup v-for="group in area_groups" :key="group.key" :label="$t('guide.recently_viewed.categories.' + group.key)">
                                <option v-for="a in group.items" :key="a.id" :value="a.id">{{ a.title }}</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6" v-if="!form.article_id">
                        <label class="form-label">{{ $t('guide.partners.place') }} *</label>
                        <input type="text" class="form-control" v-model="form.place" maxlength="120" :placeholder="$t('guide.partners.place_placeholder')" />
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label">{{ $t('guide.partners.grade_from') }}</label>
                        <input type="text" class="form-control" v-model="form.grade_from" maxlength="8" placeholder="6a" />
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label">{{ $t('guide.partners.grade_to') }}</label>
                        <input type="text" class="form-control" v-model="form.grade_to" maxlength="8" placeholder="6c" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ $t('guide.partners.note') }}</label>
                        <textarea class="form-control" rows="2" maxlength="500" v-model="form.note" :placeholder="$t('guide.partners.note_placeholder')"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('guide.partners.contact') }}</label>
                        <input type="text" class="form-control" v-model="form.contact" maxlength="120" :placeholder="$t('guide.partners.contact_placeholder')" />
                        <div class="form-text"><i class="fa fa-eye" aria-hidden="true"></i> {{ $t('guide.partners.contact_public') }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" :disabled="sending">
                        <span v-if="sending" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>{{ $t('guide.partners.publish') }}
                    </button>
                    <button type="button" class="btn btn-link" @click="show_form = false">{{ $t('guide.conditions.cancel') }}</button>
                    <span v-if="error" class="text-danger fw-bold small">{{ error }}</span>
                </div>
            </form>

            <div v-if="loading" class="row g-3">
                <div class="col-md-6 col-lg-4" v-for="n in 3" :key="n"><div class="cg-partner-card cg-skeleton"></div></div>
            </div>

            <div v-else-if="!visible.length" class="cg-empty cg-partners__empty">
                <cg-scene name="empty" wide />
                <p>{{ $t('guide.partners.empty') }}</p>
            </div>

            <template v-else>
                <div v-for="day in days" :key="day.date" class="cg-partners__day">
                    <h2 class="cg-partners__date">{{ day.label }}</h2>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4" v-for="p in day.posts" :key="p.id">
                            <article class="cg-partner-card">
                                <header>
                                    <router-link v-if="p.user" :to="{ name: 'climberProfile', params: { id: p.user.id } }" class="cg-partner-card__who">
                                        <span class="cg-partner-card__avatar">{{ initials(p.user) }}</span>
                                        <strong>{{ p.user.name }} {{ p.user.surname }}</strong>
                                    </router-link>
                                    <span class="cg-chip cg-chip--accent">{{ $t('guide.partners.disciplines.' + p.discipline) }}</span>
                                </header>
                                <p class="cg-partner-card__place">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <router-link v-if="p.article && area_title(p.article)" :to="area_link(p.article)">{{ area_title(p.article) }}</router-link>
                                    <span v-else>{{ p.place || '—' }}</span>
                                    <span v-if="p.grade_from || p.grade_to" class="cg-partner-card__grade">{{ [p.grade_from, p.grade_to].filter(Boolean).join(' – ') }}</span>
                                </p>
                                <p v-if="p.note" class="cg-partner-card__note">{{ p.note }}</p>
                                <button v-else-if="p.has_note || p.has_contact" type="button" class="cg-partner-card__locked" @click="ask_login('register')">
                                    <i class="fa fa-lock" aria-hidden="true"></i> {{ $t('guide.partners.locked') }}
                                </button>
                                <footer>
                                    <span v-if="p.contact" class="cg-partner-card__contact"><i class="fa fa-comment-o" aria-hidden="true"></i> {{ p.contact }}</span>
                                    <router-link v-else-if="p.user && logged_in" :to="{ name: 'climberProfile', params: { id: p.user.id } }">{{ $t('guide.partners.view_profile') }}</router-link>
                                    <span v-else></span>
                                    <button v-if="mine.includes(p.id)" type="button" class="btn btn-link btn-sm text-danger p-0" @click="remove(p)">{{ $t('guide.conditions.delete') }}</button>
                                    <small v-else class="text-muted">{{ $timeAgo(p.created_at) }}</small>
                                </footer>
                            </article>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <metaData
            :title="$t('guide.partners.title')"
            :description="$t('guide.partners.lead')"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent'
    import { CATEGORY_PATHS } from '../../../services/guide/recentlyViewed.js'

    const DISCIPLINES = ['sport', 'boulder', 'multipitch', 'trad', 'ice', 'alpine', 'indoor'];

    export default {
        components: { metaData },
        data() {
            return {
                posts: [],
                loading: true,
                filter: 'all',
                show_form: false,
                sending: false,
                error: '',
                areas: [],
                mine: [],
                logged_in: !!localStorage.getItem('auth_token'),
                disciplines: DISCIPLINES,
                form: this.empty_form(),
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                MIX_SITE_URL: process.env.MIX_SITE_URL,
            };
        },
        computed: {
            today() { return new Date().toISOString().slice(0, 10); },
            disciplines_present() { return DISCIPLINES.filter(d => this.posts.some(p => p.discipline === d)); },
            visible() { return this.filter === 'all' ? this.posts : this.posts.filter(p => p.discipline === this.filter); },
            days() {
                const locale = this.$i18n && this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB';
                const groups = {};
                this.visible.forEach(p => { (groups[p.climb_date] = groups[p.climb_date] || []).push(p); });
                return Object.keys(groups).sort().map(date => ({
                    date,
                    posts: groups[date],
                    label: new Intl.DateTimeFormat(locale, { weekday: 'long', day: 'numeric', month: 'long' }).format(new Date(date + 'T12:00:00')),
                }));
            },
            area_groups() {
                return ['outdoor', 'ice', 'indoor']
                    .map(key => ({ key, items: this.areas.filter(a => a.category === key) }))
                    .filter(g => g.items.length);
            },
        },
        mounted() {
            this.load();
            this.load_areas();
            if (localStorage.getItem('auth_token')) {
                axios.get('/set_partner_post/get_my_partner_posts').then(r => { this.mine = r.data || []; }).catch(() => {});
            }
        },
        methods: {
            empty_form() {
                return { climb_date: '', discipline: 'sport', article_id: null, place: '', grade_from: '', grade_to: '', note: '', contact: '' };
            },
            load() {
                this.loading = true;
                axios.get('/get_partner_post/get_partner_posts')
                    .then(r => { this.posts = r.data || []; })
                    .catch(() => { this.posts = []; })
                    .finally(() => { this.loading = false; });
            },
            // area names for the select and for linking posts to their area page
            load_areas() {
                const lang = localStorage.getItem('lang') || 'us';
                ['outdoor', 'ice', 'indoor'].forEach(category => {
                    axios.get('/get_article/get_locale_articles/' + category + '/' + lang)
                        .then(r => {
                            const items = (r.data || []).filter(a => a && a.global_data && a.locale_data).map(a => ({
                                id: a.global_data.id, category, url_title: a.global_data.url_title, title: a.locale_data.title,
                            }));
                            this.areas = this.areas.concat(items).sort((a, b) => a.title.localeCompare(b.title));
                        })
                        .catch(() => {});
                });
            },
            area_title(article) {
                const a = this.areas.find(x => x.id === article.id);
                return a ? a.title : null;
            },
            area_link(article) {
                const loc = this.$route.params.locale;
                return (loc ? '/' + loc : '') + (CATEGORY_PATHS[article.category] || ['/outdoor/'])[0] + article.url_title;
            },
            initials(user) { return ((user.name || '?')[0] + ((user.surname || '')[0] || '')).toUpperCase(); },
            open_form() {
                if (!localStorage.getItem('auth_token')) { this.ask_login('register', true); return; }
                this.show_form = !this.show_form;
            },
            // login / quick sign-up modal; afterwards reload with notes + contacts
            ask_login(mode, then_open_form = false) {
                const callback = () => {
                    this.logged_in = true;
                    this.load();
                    axios.get('/set_partner_post/get_my_partner_posts').then(r => { this.mine = r.data || []; }).catch(() => {});
                    if (then_open_form) this.show_form = true;
                };
                if (mode === 'login') this.$bus.$emit('open-login-modal', callback);
                else this.$bus.$emit('open-register-modal', { callback, reason: this.$t('guide.partners.gate_reason') });
            },
            send() {
                this.sending = true;
                this.error = '';
                axios.post('/set_partner_post/create_partner_post', this.form)
                    .then(r => {
                        this.mine = this.mine.concat([r.data.post_id]);
                        this.form = this.empty_form();
                        this.show_form = false;
                        this.load();
                    })
                    .catch(e => {
                        const d = e.response && e.response.data;
                        this.error = (d && d.errors && Object.values(d.errors)[0][0]) || (d && d.message) || this.$t('guide.conditions.error');
                    })
                    .finally(() => { this.sending = false; });
            },
            remove(p) {
                if (!confirm(this.$t('guide.partners.delete_confirm'))) return;
                axios.delete('/set_partner_post/del_my_partner_post/' + p.id)
                    .then(() => { this.posts = this.posts.filter(x => x.id !== p.id); })
                    .catch(() => {});
            },
        },
    };
</script>

<style scoped>
    .cg-partners__bar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: 1.25rem; }
    .cg-partners__form { margin-bottom: 1.5rem; padding: 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-partners__form .form-label { font-weight: 700; font-size: .88rem; }
    .cg-partners__day { margin-bottom: 1.75rem; }
    .cg-partners__gate { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1rem; margin-bottom: 1.25rem; padding: .9rem 1.1rem; border-radius: var(--cg-r-lg); background: var(--cg-accent-soft); color: var(--cg-accent-dark); }
    .cg-partners__gate > .fa { font-size: 1.5rem; }
    .cg-partners__gate > div:first-of-type { flex: 1 1 16rem; }
    .cg-partner-card__locked { align-self: flex-start; padding: .3rem .7rem; border: 1px dashed var(--cg-line); border-radius: var(--cg-r); background: var(--cg-chalk); color: var(--cg-stone); font-size: .85rem; font-weight: 700; }
    .cg-partners__date { margin: 0 0 .75rem; font-size: 1.35rem !important; text-transform: capitalize; }
    .cg-partners__empty { padding: 2.5rem 1rem; text-align: center; }
    .cg-partners__empty :deep(.cg-scene-img) { margin-bottom: 1rem; }
    .cg-partners__empty p { margin: 0; font-weight: 700; color: var(--cg-stone); }
    .cg-partner-card { display: flex; flex-direction: column; gap: .55rem; height: 100%; min-height: 9rem; padding: 1rem; background: #fff; border: 1px solid var(--cg-line); border-top: 4px solid var(--cg-accent); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-partner-card header { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
    .cg-partner-card__who { display: flex; align-items: center; gap: .5rem; min-width: 0; color: var(--cg-ink); text-decoration: none; }
    .cg-partner-card__who strong { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .cg-partner-card__avatar { flex: 0 0 2.2rem; height: 2.2rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-hold); font-weight: 800; font-size: .85rem; }
    .cg-partner-card__place { margin: 0; font-weight: 700; }
    .cg-partner-card__place .fa { color: var(--cg-rope, #e0632a); margin-right: .25rem; }
    .cg-partner-card__grade { display: inline-block; white-space: nowrap; margin-left: .5rem; padding: .05rem .5rem; border-radius: .35rem; background: var(--cg-chalk-2); font-family: var(--cg-font-display); }
    .cg-partner-card__note { margin: 0; color: var(--cg-ink-2); font-size: .93rem; }
    .cg-partner-card footer { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-top: auto; font-size: .88rem; }
    .cg-partner-card__contact { font-weight: 700; color: var(--cg-accent-dark); overflow-wrap: anywhere; }
    .cg-skeleton { background: linear-gradient(90deg, var(--cg-chalk-2) 25%, var(--cg-chalk) 50%, var(--cg-chalk-2) 75%); background-size: 200% 100%; animation: cg-shimmer 1.2s linear infinite; border-top-color: var(--cg-line); }
    @keyframes cg-shimmer { to { background-position: -200% 0; } }
    @media (prefers-reduced-motion: reduce) { .cg-skeleton { animation: none; } }
</style>
