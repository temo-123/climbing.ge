<template>
    <stack-modal
        :show="visible"
        title="Add Permission"
        @close="close"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div>
            <div v-if="error" class="alert alert-danger">{{ error }}</div>

            <div class="form-group">
                <label>Subject</label>
                <input
                    list="subjects-list"
                    class="form-control"
                    v-model="form.subject"
                    placeholder="e.g. product, article, user"
                    autocomplete="off"
                />
                <datalist id="subjects-list">
                    <option v-for="s in subjects" :key="s" :value="s" />
                </datalist>
            </div>

            <div class="form-group mt-3">
                <label>Action</label>
                <div class="d-flex gap-2 flex-wrap mb-2">
                    <button
                        v-for="a in common_actions"
                        :key="a"
                        type="button"
                        class="btn btn-sm"
                        :class="form.action === a ? 'btn-primary' : 'btn-outline-secondary'"
                        @click="form.action = a"
                    >{{ a }}</button>
                </div>
                <input
                    class="form-control"
                    v-model="form.action"
                    placeholder="or type custom action"
                />
            </div>
        </div>

        <template #footer>
            <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
            <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                <span v-if="saving"><i class="fa fa-spinner fa-spin"></i></span>
                <span v-else>Add Permission</span>
            </button>
        </template>
    </stack-modal>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            saving: false,
            error: null,
            form: { subject: '', action: '' },
            subjects: [],
            common_actions: ['add', 'edit', 'del', 'show', 'view'],
        }
    },
    methods: {
        show_modal() {
            this.visible = true
            this.error = null
            this.form = { subject: '', action: '' }
            this.load_subjects()
        },
        close() {
            this.visible = false
        },
        load_subjects() {
            axios.get('/parmisions_list')
                .then(r => {
                    const seen = new Set()
                    r.data.forEach(p => seen.add(p.subject))
                    this.subjects = Array.from(seen).sort()
                })
                .catch(() => {})
        },
        save() {
            this.error = null
            if (!this.form.subject.trim() || !this.form.action.trim()) {
                this.error = 'Subject and Action are required.'
                return
            }
            this.saving = true
            axios.post('/set_permission/store', this.form)
                .then(() => {
                    this.close()
                    this.$emit('saved')
                })
                .catch(err => {
                    this.error = err.response?.data?.message || 'Failed to save permission.'
                })
                .finally(() => { this.saving = false })
        },
    }
}
</script>
