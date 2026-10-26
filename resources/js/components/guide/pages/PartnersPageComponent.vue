<template>
    <!-- "Find a partner" board: upcoming climbing days posted by climbers.
         API: get_partner_post/* (public), set_partner_post/* (logged in). -->
    <div class="h-recent-work">
        <div class="container">
            <section class="cg-page-head cg-page-head--list cg-art-partners">
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
                <select v-if="languages_present.length > 1" class="form-select form-select-sm cg-partners__lang" v-model="lang_filter" :aria-label="$t('guide.partners.speaks')">
                    <option value="">{{ $t('guide.partners.any_language') }}</option>
                    <option v-for="l in languages_present" :key="l" :value="l">{{ $t('guide.partners.languages.' + l) }}</option>
                </select>
                <button type="button" class="btn btn-hold ms-auto" @click="open_form">
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
                        <label class="form-label" for="pp-start">{{ $t('guide.partners.trip_start') }} *</label>
                        <input id="pp-start" type="date" class="form-control" v-model="form.climb_date" :min="today" required />
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label" for="pp-end">{{ $t('guide.partners.trip_end') }}</label>
                        <input id="pp-end" type="date" class="form-control" v-model="form.end_date" :min="form.climb_date || today" :max="max_end" :disabled="!form.climb_date" />
                        <div class="form-text">{{ form_days > 1 ? $t('guide.partners.n_days', { n: form_days }) : $t('guide.partners.one_day_hint') }}</div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <span class="form-label d-block">{{ $t('guide.partners.speaks') }}</span>
                        <div class="cg-partners__langs" role="group" :aria-label="$t('guide.partners.speaks')">
                            <button v-for="l in languages" :key="l" type="button" class="cg-lang-chip" :class="{ 'is-on': form.languages.includes(l) }"
                                    :aria-pressed="form.languages.includes(l)" @click="toggle_lang(l)">{{ $t('guide.partners.languages.' + l) }}</button>
                        </div>
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
                    <h2 class="cg-partners__date" :class="{ 'is-now': day.date === 'now' }">
                        <i v-if="day.date === 'now'" class="fa fa-circle" aria-hidden="true"></i> {{ day.label }}
                    </h2>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4" v-for="p in day.posts" :key="p.id">
                            <article class="cg-partner-card is-clickable" role="button" tabindex="0" :aria-label="$t('guide.partners.open_post')"
                                     @click="open_post(p)" @keydown.enter.self="open_post(p)">
                                <header>
                                    <button v-if="p.user" type="button" class="cg-partner-card__who" @click.stop="open_climber(p.user)" :title="$t('guide.partners.view_profile')">
                                        <img v-if="p.user.image" class="cg-partner-card__avatar" :src="avatar(p.user)" alt="" loading="lazy">
                                        <span v-else class="cg-partner-card__avatar">{{ initials(p.user) }}</span>
                                        <strong>{{ p.user.name }} {{ p.user.surname }}</strong>
                                    </button>
                                    <span class="cg-chip cg-chip--accent">{{ $t('guide.partners.disciplines.' + p.discipline) }}</span>
                                </header>
                                <p class="cg-partner-card__place">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <span>{{ place_of(p) }}</span>
                                    <span v-if="p.grade_from || p.grade_to" class="cg-partner-card__grade">{{ [p.grade_from, p.grade_to].filter(Boolean).join(' – ') }}</span>
                                </p>
                                <p class="cg-partner-card__when">
                                    <i class="fa fa-calendar-o" aria-hidden="true"></i> {{ range_label(p) }}
                                    <span v-if="trip_days(p) > 1" class="cg-partner-card__days">{{ $t('guide.partners.n_days', { n: trip_days(p) }) }}</span>
                                    <span v-if="is_now(p)" class="cg-partner-card__now">{{ $t('guide.partners.happening_now') }}</span>
                                </p>
                                <p v-if="p.languages && p.languages.length" class="cg-partner-card__langs">
                                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                                    <span v-for="l in p.languages" :key="l">{{ $t('guide.partners.languages.' + l) }}</span>
                                </p>
                                <p v-if="p.note" class="cg-partner-card__note">{{ p.note }}</p>
                                <button v-else-if="p.has_note || p.has_contact" type="button" class="cg-partner-card__locked" @click.stop="ask_login('register')">
                                    <i class="fa fa-lock" aria-hidden="true"></i> {{ $t('guide.partners.locked') }}
                                </button>
                                <footer>
                                    <span v-if="p.contact" class="cg-partner-card__contact"><i class="fa fa-comment-o" aria-hidden="true"></i> {{ p.contact }}</span>
                                    <span v-else class="cg-partner-card__more">{{ $t('guide.partners.details') }} <i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                    <button v-if="mine.includes(p.id)" type="button" class="btn btn-link btn-sm text-danger p-0" @click.stop="remove(p)">{{ $t('guide.conditions.delete') }}</button>
                                    <small v-else class="text-muted">{{ $timeAgo(p.created_at) }}</small>
                                </footer>
                            </article>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- one post in full; from here on to the area page or the climber -->
        <StackModal
            v-model="post_modal"
            :title="$t('guide.partners.post_title')"
            @close="close_post"
            @save="go_to_area"
            :saveButton="{ visible: !!(active && active_area), title: $t('guide.partners.open_area'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        >
            <div v-if="active" class="cg-pp">
                <button v-if="active.user" type="button" class="cg-pp__who" @click="open_climber(active.user)">
                    <img v-if="active.user.image" class="cg-pp__avatar" :src="avatar(active.user)" alt="">
                    <span v-else class="cg-pp__avatar">{{ initials(active.user) }}</span>
                    <span>
                        <strong>{{ active.user.name }} {{ active.user.surname }}</strong>
                        <small>{{ $t('guide.partners.view_profile') }} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
                    </span>
                </button>

                <div class="cg-pp__chips">
                    <span class="cg-chip cg-chip--accent">{{ $t('guide.partners.disciplines.' + active.discipline) }}</span>
                    <span v-if="is_now(active)" class="cg-partner-card__now">{{ $t('guide.partners.happening_now') }}</span>
                </div>

                <dl class="cg-pp__facts">
                    <dt><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $t('guide.partners.when') }}</dt>
                    <dd>{{ range_label(active) }}<template v-if="trip_days(active) > 1"> · {{ $t('guide.partners.n_days', { n: trip_days(active) }) }}</template></dd>
                    <dt><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $t('guide.partners.where') }}</dt>
                    <dd>{{ place_of(active) }}</dd>
                    <template v-if="active.grade_from || active.grade_to">
                        <dt><i class="fa fa-signal" aria-hidden="true"></i> {{ $t('guide.partners.grades') }}</dt>
                        <dd>{{ [active.grade_from, active.grade_to].filter(Boolean).join(' – ') }}</dd>
                    </template>
                    <template v-if="active.languages && active.languages.length">
                        <dt><i class="fa fa-comments-o" aria-hidden="true"></i> {{ $t('guide.partners.speaks') }}</dt>
                        <dd>{{ active.languages.map(l => $t('guide.partners.languages.' + l)).join(', ') }}</dd>
                    </template>
                </dl>

                <p v-if="active.note" class="cg-pp__note">{{ active.note }}</p>
                <div v-if="active.contact" class="cg-pp__contact"><i class="fa fa-comment-o" aria-hidden="true"></i> {{ active.contact }}</div>
                <button v-else-if="!logged_in && (active.has_note || active.has_contact)" type="button" class="cg-partner-card__locked" @click="close_post(); ask_login('register')">
                    <i class="fa fa-lock" aria-hidden="true"></i> {{ $t('guide.partners.locked') }}
                </button>
                <p v-else-if="active.user" class="cg-pp__hint">{{ $t('guide.partners.contact_via_profile') }}</p>

                <div class="cg-pp__foot">
                    <small class="text-muted">{{ $t('guide.partners.posted') }} {{ $timeAgo(active.created_at) }}</small>
                    <button v-if="mine.includes(active.id)" type="button" class="btn btn-link btn-sm text-danger p-0" @click="remove(active)">{{ $t('guide.conditions.delete') }}</button>
                </div>
            </div>
        </StackModal>

        <climber-profile-modal ref="climberModal" />

        <metaData
            :title="$t('guide.partners.title')"
            :description="$t('guide.partners.lead')"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent'
    import ClimberProfileModal from '../items/climber/ClimberProfileModalComponent.vue'
    import { CATEGORY_PATHS } from '../../../services/guide/recentlyViewed.js'

    const DISCIPLINES = ['sport', 'boulder', 'multipitch', 'trad', 'ice', 'alpine', 'indoor'];
    // PartnerPost::LANGUAGES / MAX_TRIP_DAYS
    const LANGUAGES = ['ka', 'en', 'ru', 'uk', 'de', 'fr', 'es', 'it', 'pl', 'tr', 'he', 'zh'];
    const MAX_TRIP_DAYS = 30;
    const iso = d => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    const day_of = s => new Date(String(s).slice(0, 10) + 'T12:00:00');

    export default {
        components: { metaData, 'climber-profile-modal': ClimberProfileModal },
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
                languages: LANGUAGES,
                lang_filter: '',
                post_modal: false,
                active: null,
                form: this.empty_form(),
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                MIX_SITE_URL: process.env.MIX_SITE_URL,
            };
        },
        computed: {
            today() { return iso(new Date()); },
            intl() { return this.$i18n && this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            max_end() {
                if (!this.form.climb_date) return null;
                const d = day_of(this.form.climb_date); d.setDate(d.getDate() + MAX_TRIP_DAYS - 1);
                return iso(d);
            },
            form_days() { return this.form.climb_date && this.form.end_date ? this.trip_days({ climb_date: this.form.climb_date, end_date: this.form.end_date }) : 1; },
            languages_present() { return LANGUAGES.filter(l => this.posts.some(p => (p.languages || []).includes(l))); },
            disciplines_present() { return DISCIPLINES.filter(d => this.posts.some(p => p.discipline === d)); },
            visible() {
                return this.posts
                    .filter(p => this.filter === 'all' || p.discipline === this.filter)
                    .filter(p => !this.lang_filter || (p.languages || []).includes(this.lang_filter));
            },
            // trips already under way first ("On a trip now"), then by start day
            days() {
                const groups = {};
                this.visible.forEach(p => {
                    const key = this.is_now(p) && p.climb_date < this.today ? 'now' : p.climb_date;
                    (groups[key] = groups[key] || []).push(p);
                });
                return Object.keys(groups).sort((a, b) => (a === 'now' ? -1 : b === 'now' ? 1 : a.localeCompare(b))).map(date => ({
                    date,
                    posts: groups[date],
                    label: date === 'now'
                        ? this.$t('guide.partners.on_trip_now')
                        : new Intl.DateTimeFormat(this.intl, { weekday: 'long', day: 'numeric', month: 'long' }).format(day_of(date)),
                }));
            },
            active_area() {
                const a = this.active && this.active.article;
                return a && this.area_title(a) ? a : null;
            },
            area_groups() {
                return ['outdoor', 'ice', 'indoor']
                    .map(key => ({ key, items: this.areas.filter(a => a.category === key) }))
                    .filter(g => g.items.length);
            },
        },
        watch: {
            'form.climb_date'(v) { if (this.form.end_date && (this.form.end_date < v || this.form.end_date > this.max_end)) this.form.end_date = ''; },
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
                const ui = this.$i18n && this.$i18n.locale === 'ka' ? 'ka' : 'en';
                return { climb_date: '', end_date: '', discipline: 'sport', article_id: null, place: '', grade_from: '', grade_to: '', languages: [ui], note: '', contact: '' };
            },
            toggle_lang(l) {
                const on = this.form.languages.includes(l);
                if (!on && this.form.languages.length >= 6) return;
                this.form.languages = on ? this.form.languages.filter(x => x !== l) : this.form.languages.concat([l]);
            },
            end_of(p) { return p.end_date || p.climb_date; },
            trip_days(p) { return Math.round((day_of(this.end_of(p)) - day_of(p.climb_date)) / 86400000) + 1; },
            is_now(p) { return p.climb_date <= this.today && this.end_of(p) >= this.today; },
            range_label(p) {
                const f = new Intl.DateTimeFormat(this.intl, { day: 'numeric', month: 'short' });
                const a = day_of(p.climb_date), b = day_of(this.end_of(p));
                if (this.trip_days(p) <= 1) return new Intl.DateTimeFormat(this.intl, { weekday: 'short', day: 'numeric', month: 'short' }).format(a);
                return f.formatRange ? f.formatRange(a, b) : f.format(a) + ' – ' + f.format(b);
            },
            load() {
                this.loading = true;
                axios.get('/get_partner_post/get_partner_posts')
                    .then(r => {
                        this.posts = r.data || [];
                        // shared link: open that post (if it's still on the board)
                        const id = Number(this.$route.query.post);
                        const p = id && this.posts.find(x => x.id === id);
                        if (p) this.open_post(p);
                        else if (this.active) this.active = this.posts.find(x => x.id === this.active.id) || null;
                    })
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
            place_of(p) { return (p.article && this.area_title(p.article)) || p.place || '—'; },
            avatar(user) { return '/public/images/user_profil_img/' + user.image; },
            // the post in a modal; its id in the URL so it can be shared (?post=12)
            open_post(p) {
                this.active = p;
                this.post_modal = true;
                if (String(this.$route.query.post) !== String(p.id)) this.$router.replace({ query: { ...this.$route.query, post: p.id } });
            },
            close_post() {
                this.post_modal = false;
                if (this.$route.query.post) {
                    const query = { ...this.$route.query };
                    delete query.post;
                    this.$router.replace({ query });
                }
            },
            go_to_area() {
                const to = this.area_link(this.active_area);
                // navigate first (the new page drops ?post anyway), then hide the modal
                this.$router.push(to).finally(() => { this.post_modal = false; });
            },
            // a registered climber's profile, in the climber modal (one modal at a time)
            open_climber(user) {
                if (!user || !user.id) return;
                if (this.post_modal) this.close_post();
                this.$nextTick(() => this.$refs.climberModal.show_modal(user.id));
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
                    .then(() => {
                        this.posts = this.posts.filter(x => x.id !== p.id);
                        if (this.active && this.active.id === p.id) this.close_post();
                    })
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
    .cg-partners__lang { width: auto; min-width: 10rem; }
    .cg-partners__langs { display: flex; flex-wrap: wrap; gap: .35rem; }
    .cg-lang-chip { padding: .3rem .7rem; border: 1px solid var(--cg-line); border-radius: 2rem; background: #fff; color: var(--cg-ink); font-size: .85rem; font-weight: 600; transition: background-color .15s, border-color .15s, color .15s; }
    .cg-lang-chip:hover { border-color: var(--cg-accent); }
    .cg-lang-chip.is-on { background: var(--cg-accent); border-color: var(--cg-accent); color: var(--cg-accent-contrast, #fff); }
    .cg-partners__date.is-now { color: #2fa36b !important; text-transform: none; }
    .cg-partners__date.is-now .fa { font-size: .7em; vertical-align: middle; animation: cg-now-pulse 1.6s ease-in-out infinite; }
    @keyframes cg-now-pulse { 50% { opacity: .35; } }
    .cg-partner-card__when, .cg-partner-card__langs { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; margin: 0; color: var(--cg-ink-2); font-size: .88rem; font-weight: 600; }
    .cg-partner-card__when .fa, .cg-partner-card__langs .fa { color: var(--cg-accent); }
    .cg-partner-card__days { padding: .05rem .45rem; border-radius: .35rem; background: var(--cg-chalk-2); font-size: .78rem; }
    .cg-partner-card__now { padding: .05rem .5rem; border-radius: 1rem; background: #2fa36b; color: #fff; font-size: .75rem; font-weight: 800; }
    .cg-partner-card__langs span { padding: .05rem .5rem; border-radius: 1rem; border: 1px solid var(--cg-line); font-size: .78rem; }
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
    .cg-partner-card.is-clickable { cursor: pointer; transition: border-color .15s, box-shadow .15s; }
    .cg-partner-card.is-clickable:hover { box-shadow: var(--cg-shadow); }
    .cg-partner-card.is-clickable:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .45); outline-offset: 2px; }
    .cg-partner-card__who { display: flex; align-items: center; gap: .5rem; min-width: 0; padding: 0; border: 0; background: none; color: var(--cg-ink); text-align: left; }
    .cg-partner-card__who:hover strong { color: var(--cg-accent-dark); text-decoration: underline; }
    .cg-partner-card__more { color: var(--cg-accent-dark); font-weight: 700; }
    .cg-partner-card__who strong { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .cg-partner-card__avatar { flex: 0 0 2.2rem; width: 2.2rem; height: 2.2rem; object-fit: cover; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-hold); font-weight: 800; font-size: .85rem; }
    .cg-partner-card__place { margin: 0; font-weight: 700; }
    .cg-partner-card__place .fa { color: var(--cg-rope, #e0632a); margin-right: .25rem; }
    .cg-partner-card__grade { display: inline-block; white-space: nowrap; margin-left: .5rem; padding: .05rem .5rem; border-radius: .35rem; background: var(--cg-chalk-2); font-family: var(--cg-font-display); }
    .cg-partner-card__note { margin: 0; color: var(--cg-ink-2); font-size: .93rem; }
    .cg-partner-card footer { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-top: auto; font-size: .88rem; }
    .cg-partner-card__contact { font-weight: 700; color: var(--cg-accent-dark); overflow-wrap: anywhere; }
    /* post modal */
    .cg-pp { display: flex; flex-direction: column; gap: .9rem; }
    .cg-pp__who { display: flex; align-items: center; gap: .75rem; padding: .5rem; margin: -.5rem; border: 0; border-radius: var(--cg-r); background: none; color: var(--cg-ink); text-align: left; }
    .cg-pp__who:hover { background: var(--cg-chalk); }
    .cg-pp__who strong { display: block; font-size: 1.15rem; }
    .cg-pp__who small { color: var(--cg-accent-dark); font-weight: 700; }
    .cg-pp__avatar { flex: 0 0 3.2rem; width: 3.2rem; height: 3.2rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; object-fit: cover; background: var(--cg-hold); font-weight: 800; }
    .cg-pp__chips { display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; }
    .cg-pp__facts { display: grid; grid-template-columns: max-content 1fr; gap: .45rem 1rem; margin: 0; padding: .8rem 1rem; border-radius: var(--cg-r); background: var(--cg-chalk); }
    .cg-pp__facts dt { color: var(--cg-muted); font-size: .8rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .cg-pp__facts dt .fa { width: 1rem; color: var(--cg-accent); }
    .cg-pp__facts dd { margin: 0; color: var(--cg-ink); font-weight: 700; }
    .cg-pp__note { margin: 0; white-space: pre-line; color: var(--cg-ink-2); }
    .cg-pp__contact { padding: .6rem .8rem; border-left: 4px solid var(--cg-accent); border-radius: var(--cg-r-sm, 6px); background: var(--cg-accent-soft); color: var(--cg-accent-dark); font-weight: 700; overflow-wrap: anywhere; }
    .cg-pp__hint { margin: 0; color: var(--cg-stone); font-size: .9rem; }
    .cg-pp__foot { display: flex; justify-content: space-between; align-items: center; gap: .5rem; }
    @media (max-width: 575.98px) { .cg-pp__facts { grid-template-columns: 1fr; gap: .1rem; } .cg-pp__facts dd { margin-bottom: .4rem; } }
    .cg-skeleton { background: linear-gradient(90deg, var(--cg-chalk-2) 25%, var(--cg-chalk) 50%, var(--cg-chalk-2) 75%); background-size: 200% 100%; animation: cg-shimmer 1.2s linear infinite; border-top-color: var(--cg-line); }
    @keyframes cg-shimmer { to { background-position: -200% 0; } }
    @media (prefers-reduced-motion: reduce) { .cg-skeleton, .cg-partners__date.is-now .fa { animation: none; } }
</style>
