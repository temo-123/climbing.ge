<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Summits</h4>
                            <button v-if="$can('add', 'summit')" class="btn btn-primary btn-sm" @click="open_add">
                                <i class="fa fa-plus"></i> Add Summit
                            </button>
                        </div>
                        <div class="card-body">
                            <div v-if="loading" class="text-center py-4">
                                <div class="spinner-border" role="status"></div>
                            </div>

                            <div v-else-if="summits.length === 0" class="text-center py-4 text-muted">
                                No summits found.
                            </div>

                            <div v-else class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Title / KA</th>
                                            <th>Height</th>
                                            <th>Mount Route</th>
                                            <th>Coordinates</th>
                                            <th>QR</th>
                                            <th>Published</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="s in summits" :key="s.id">
                                            <td>{{ s.id }}</td>
                                            <td>
                                                <div>{{ s.title }}</div>
                                                <div v-if="s.ka_title" class="text-muted small">{{ s.ka_title }}</div>
                                            </td>
                                            <td>{{ s.height ? s.height + 'm' : '-' }}</td>
                                            <td>{{ s.mount_route_name || '-' }}</td>
                                            <td class="small">
                                                <span v-if="s.latitude && s.longitude">
                                                    {{ parseFloat(s.latitude).toFixed(5) }},<br>{{ parseFloat(s.longitude).toFixed(5) }}
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                            <td class="text-center">
                                                <span v-if="s.qr_code" class="badge badge-success" title="QR saved">
                                                    <i class="fa fa-qrcode"></i> Saved
                                                </span>
                                                <span v-else class="badge badge-secondary" title="QR not saved">
                                                    <i class="fa fa-qrcode"></i> None
                                                </span>
                                            </td>
                                            <td>
                                                <span :class="s.published ? 'badge badge-success' : 'badge badge-secondary'">
                                                    {{ s.published ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info mr-1" @click="open_qr(s)" title="QR Code">
                                                    <i class="fa fa-qrcode"></i>
                                                </button>
                                                <button v-if="$can('edit', 'summit')" class="btn btn-sm btn-warning mr-1" @click="open_edit(s)" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <button v-if="$can('del', 'summit')" class="btn btn-sm btn-danger" @click="confirm_delete(s)" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add / Edit modal -->
        <SummitFormModal ref="form_modal" @saved="on_saved" />

        <!-- QR Code modal -->
        <stack-modal :show="qr_modal" title="Summit QR Code" @close="qr_modal = false" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <div v-if="selected" class="text-center">
                <p class="mb-2">QR code for <strong>{{ selected.title }}</strong></p>
                <div class="d-flex justify-content-center mb-3">
                    <qrcode-vue :value="qr_value" :size="220" level="H" render-as="svg" />
                </div>
                <p class="text-muted small">{{ qr_value }}</p>
                <div v-if="selected.qr_code" class="alert alert-success py-1 small">
                    <i class="fa fa-check"></i> QR code already saved in database
                </div>
                <div v-else class="alert alert-warning py-1 small">
                    <i class="fa fa-exclamation-triangle"></i> QR code not yet saved
                </div>
            </div>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="qr_modal = false">Close</button>
                <button type="button" class="btn btn-primary" :disabled="saving_qr" @click="save_qr">
                    <span v-if="saving_qr"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-save"></i> {{ selected && selected.qr_code ? 'Resave QR' : 'Save QR' }}</span>
                </button>
            </template>
        </stack-modal>

        <!-- Delete confirm modal -->
        <stack-modal :show="del_modal" title="Confirm Delete" @close="del_modal = false" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <p>Delete summit <strong>{{ to_delete?.title }}</strong>?</p>
            <p class="text-danger small">This cannot be undone.</p>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="del_modal = false">Cancel</button>
                <button type="button" class="btn btn-danger" :disabled="deleting" @click="delete_summit">
                    <span v-if="deleting"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else>Delete</span>
                </button>
            </template>
        </stack-modal>
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import QrcodeVue from 'qrcode.vue'
import SummitFormModal from './SummitFormModal.vue'

export default {
    name: 'SummitListPage',
    components: { breadcrumb, QrcodeVue, SummitFormModal },
    data() {
        return {
            summits: [],
            loading: false,

            qr_modal: false,
            selected: null,
            saving_qr: false,

            del_modal: false,
            to_delete: null,
            deleting: false,
        }
    },
    computed: {
        qr_value() {
            if (!this.selected) return ''
            const base = process.env.MIX_APP_SSH
                ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '')
                : window.location.origin
            return `${base}/make_ascent/${this.selected.id}`
        }
    },
    mounted() {
        this.load()
    },
    methods: {
        load() {
            this.loading = true
            axios.get('get_summit_admin/index')
                .then(r => { this.summits = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },

        open_add() {
            this.$refs.form_modal.show_modal()
        },
        open_edit(summit) {
            this.$refs.form_modal.show_modal(summit)
        },
        on_saved(data) {
            this.load()
        },

        open_qr(summit) {
            this.selected = summit
            this.qr_modal = true
        },
        save_qr() {
            if (!this.selected) return
            this.saving_qr = true
            axios.post(`set_summit/save_qr/${this.selected.id}`, { qr_code: this.qr_value })
                .then(() => {
                    const idx = this.summits.findIndex(s => s.id === this.selected.id)
                    if (idx !== -1) this.summits[idx].qr_code = this.qr_value
                    this.selected = { ...this.selected, qr_code: this.qr_value }
                })
                .catch(() => { alert('Failed to save QR code.') })
                .finally(() => { this.saving_qr = false })
        },

        confirm_delete(summit) {
            this.to_delete = summit
            this.del_modal = true
        },
        delete_summit() {
            if (!this.to_delete) return
            this.deleting = true
            axios.delete(`set_summit/destroy/${this.to_delete.id}`)
                .then(() => {
                    this.summits = this.summits.filter(s => s.id !== this.to_delete.id)
                    this.del_modal = false
                    this.to_delete = null
                })
                .catch(() => { alert('Failed to delete.') })
                .finally(() => { this.deleting = false })
        },
    }
}
</script>
