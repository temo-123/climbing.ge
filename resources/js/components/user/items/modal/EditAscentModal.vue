<template>
    <StackModal
        v-model="is_open"
        :title="$t('user.ascents.edit_modal_title')"
        @close="is_open = false"
        @save="save"
        :saveButton="{ visible: true, title: saving ? $t('user.ascents.saving') : $t('user.ascents.save_btn') }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="ascent">
            <div class="form-group">
                <label>{{ $t('common.route') }}</label>
                <select v-model="selectedRoute" class="form-control" @change="onRouteChange" :disabled="loadingRoutes">
                    <option :value="null">{{ $t('user.ascents.route_select_placeholder') }}</option>
                    <option v-for="route in routes" :key="route.id" :value="route.id">
                        {{ route.name }}<span v-if="route.grade"> ({{ route.grade }})</span>
                    </option>
                    <option value="other">{{ $t('user.ascents.other_route_option') }}</option>
                </select>
            </div>

            <div v-if="showOtherRoute" class="form-group">
                <label>{{ $t('user.ascents.other_route_label') }}</label>
                <input type="text" v-model="form.other_route" class="form-control" />
            </div>

            <div class="form-group">
                <label>{{ $t('user.ascents.comment_label') }}</label>
                <textarea v-model="form.comment" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    name: 'EditAscentModal',
    emits: ['saved'],
    data() {
        return {
            is_open: false,
            ascent: null,
            routes: [],
            loadingRoutes: false,
            selectedRoute: null,
            showOtherRoute: false,
            form: { article_id: null, other_route: '', comment: '' },
            saving: false,
        }
    },
    methods: {
        show_modal(ascent) {
            this.ascent = ascent
            this.form = {
                article_id: ascent.article_id || null,
                other_route: ascent.other_route || '',
                comment: ascent.comment || '',
            }
            this.selectedRoute = ascent.article_id || (ascent.other_route ? 'other' : null)
            this.showOtherRoute = !ascent.article_id && !!ascent.other_route
            this.is_open = true
            this.loadRoutes()
        },
        loadRoutes() {
            if (!this.ascent?.summit?.id) {
                this.routes = []
                return
            }
            this.loadingRoutes = true
            axios.get(`summit/routes/${this.ascent.summit.id}`)
                .then(r => { this.routes = r.data })
                .catch(() => { this.routes = [] })
                .finally(() => { this.loadingRoutes = false })
        },
        onRouteChange() {
            this.showOtherRoute = this.selectedRoute === 'other'
            this.form.article_id = this.showOtherRoute ? null : this.selectedRoute
            if (!this.showOtherRoute) this.form.other_route = ''
        },
        save() {
            if (!this.ascent) return
            this.saving = true
            const payload = {
                comment: this.form.comment,
                article_id: this.form.article_id,
                other_route: this.showOtherRoute ? this.form.other_route : null,
            }
            axios.post(`/get_user_ascents/update_ascent/${this.ascent.id}`, payload)
                .then(() => {
                    this.is_open = false
                    this.$emit('saved')
                })
                .catch(() => { alert(this.$t('user.ascents.update_failed')) })
                .finally(() => { this.saving = false })
        },
    },
}
</script>
