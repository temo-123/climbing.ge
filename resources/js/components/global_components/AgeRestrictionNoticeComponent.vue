<template>
    <!-- Bar for a logged-in user younger than 13 (every subdomain) -->
    <div v-if="show_bar" class="cg-age-bar" role="status">
        <i class="fa fa-user-times cg-age-bar__icon" aria-hidden="true"></i>
        <div class="cg-age-bar__text">
            <strong>{{ $t('global.age_restriction.bar_title', { age: min_age }) }}</strong>
            <span class="d-none d-md-inline"> {{ $t('global.age_restriction.bar_text') }}</span>
        </div>
        <button type="button" class="cg-age-bar__close" :aria-label="$t('common.close')" @click="hide_bar">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Opened right after login and whenever an action is refused with 403 { code: 'age_restricted' } -->
    <StackModal v-model="is_modal" :title="$t('global.age_restriction.modal_title')" size="md" @close="is_modal = false">
        <div class="text-center py-2">
            <i class="fa fa-user-times cg-age-modal__icon" aria-hidden="true"></i>
            <p class="mt-3 mb-2">{{ $t('global.age_restriction.text', { age: min_age }) }}</p>
            <p class="small text-muted mb-0">{{ $t('global.age_restriction.wrong_birthday') }}</p>
        </div>
        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="is_modal = false">{{ $t('common.close') }}</button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import StackModal from './modals/StackModal.vue'
import { get_auth_user } from '../../services/authUser.js'

const HIDE_KEY = 'cg_age_bar_hidden'

// the user panel shows its own full page instead
const NO_BAR_ROUTES = ['age_restricted']

// docs/FUNCTIONS_DOCS/global/age_restriction.md
export default {
    name: 'AgeRestrictionNotice',
    components: { StackModal },
    data() {
        return {
            underage: false,
            min_age: 13,
            checked_token: null,
            bar_hidden: false,
            is_modal: false,
            unsubs: [],
        }
    },
    computed: {
        show_bar() {
            return this.underage && !this.bar_hidden && !NO_BAR_ROUTES.includes(this.$route?.name)
        },
    },
    watch: {
        '$route.fullPath'() { this.check() },
    },
    mounted() {
        try { this.bar_hidden = sessionStorage.getItem(HIDE_KEY) === '1' } catch (e) {}

        this.unsubs = [
            // fresh login on a public site → show the alert at once
            this.$bus.$on('logged-in', () => this.check(true, true)),
            this.$bus.$on('permissions-loaded', () => this.check(true)),
            this.$bus.$on('logged-out', () => { this.underage = false; this.is_modal = false }),
            this.$bus.$on('age-restricted', (payload) => this.open_modal(payload)),
        ]
        this.check()
    },
    beforeUnmount() {
        this.unsubs.forEach(off => off && off())
    },
    methods: {
        check(force = false, open_after = false) {
            const token = localStorage.getItem('auth_token') || ''
            if (!token) { this.underage = false; this.checked_token = ''; return }
            if (!force && token === this.checked_token) return
            this.checked_token = token

            get_auth_user({ force })
                .then(response => {
                    const user = response.data || {}
                    if (user.min_age) this.min_age = user.min_age
                    this.underage = !!user.id && !!user.is_underage
                    if (this.underage && open_after && !NO_BAR_ROUTES.includes(this.$route?.name)) this.is_modal = true
                })
                .catch(() => { this.underage = false })
        },
        open_modal(payload = {}) {
            if (payload.min_age) this.min_age = payload.min_age
            this.underage = true
            // the user panel already shows the full age page
            if (NO_BAR_ROUTES.includes(this.$route?.name)) return
            this.is_modal = true
        },
        hide_bar() {
            this.bar_hidden = true
            try { sessionStorage.setItem(HIDE_KEY, '1') } catch (e) {}
        },
    },
}
</script>

<style scoped>
.cg-age-bar {
    position: fixed;
    left: 50%;
    bottom: 16px;
    transform: translateX(-50%);
    z-index: 1040;
    display: flex;
    align-items: center;
    gap: 10px;
    width: min(760px, calc(100% - 32px));
    padding: 10px 12px 10px 16px;
    border-radius: var(--cg-r-lg, 12px);
    background: var(--cg-ink, #1f2a30);
    color: #fff;
    box-shadow: var(--cg-shadow-lg, 0 8px 24px rgba(0, 0, 0, .25));
    font-size: .92rem;
}
.cg-age-bar__icon {
    font-size: 1.25rem;
    color: var(--cg-accent);
    flex: none;
}
.cg-age-bar__text {
    flex: 1 1 auto;
    min-width: 0;
    line-height: 1.3;
}
.cg-age-bar__close {
    flex: none;
    border: 0;
    background: transparent;
    color: rgba(255, 255, 255, .7);
    padding: 4px 6px;
    line-height: 1;
}
.cg-age-bar__close:hover { color: #fff; }
.cg-age-modal__icon {
    font-size: 2.6rem;
    color: var(--cg-accent);
}
@media (max-width: 575.98px) {
    .cg-age-bar { bottom: 10px; font-size: .85rem; }
}
</style>
