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
                <div class="col-md-12" v-if="loading">
                    <div class="text-center py-4">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Loading summits...</p>
                    </div>
                </div>
                <div class="col-sm-12" v-else-if="data_for_tab.length">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="load_all"
                        @open_add="open_add"
                        @open_qr="open_qr_by_id"
                        @open_edit="open_edit_by_id"
                        @confirm_delete="confirm_delete_by_id"
                    />
                </div>
                <div v-else class="col-sm-12 text-center py-4">
                    No data available.
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
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import QrcodeVue from 'qrcode.vue'
import SummitFormModal from './SummitFormModal.vue'

export default {
    name: 'SummitListPage',
    components: { breadcrumb, tabsComponent, QrcodeVue, SummitFormModal },
    data() {
        return {
            summits: [],
            ascents: [],
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
        },
        data_for_tab() {
            if (this.loading) return []
            return [
                {
                    id: 1,
                    table_name: 'Summits',
                    add_action: {
                        action: 'fun',
                        link: 'open_add',
                        btn_title: '+ Add Summit',
                        class: 'btn btn-primary',
                    },
                    tab_data: {
                        data: this.summits,
                        tab: {
                            head: ['ID', 'Title', 'KA Title', 'Height', 'Mount Route', 'QR', 'Published', 'QR Code', 'Edit', 'Delete'],
                            body: [
                                ['data', ['id']],
                                ['data', ['title']],
                                ['data', ['ka_title']],
                                ['data', ['height']],
                                ['data', ['mount_route_name']],
                                ['data', ['qr_code'], 'bool'],
                                ['data', ['published'], 'bool'],
                                ['action_fun_id', 'open_qr',        'btn btn-sm btn-info',    '<i class="fa fa-qrcode"></i>'],
                                ['action_fun_id', 'open_edit',      'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'confirm_delete', 'btn btn-sm btn-danger',  '<i class="fa fa-trash"></i>'],
                            ],
                            perm: [
                                ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                ['no'],
                                ['summit', 'edit'],
                                ['summit', 'del'],
                            ]
                        }
                    }
                },
                {
                    id: 2,
                    table_name: 'Ascents',
                    tab_data: {
                        data: this.ascents,
                        tab: {
                            head: ['ID', 'Name', 'Surname', 'Email', 'Summit', 'Date', 'Route', 'Grade', 'GPS', 'Comment'],
                            body: [
                                ['data', ['id']],
                                ['data', ['name']],
                                ['data', ['surname']],
                                ['data', ['email']],
                                ['data', ['summit_title']],
                                ['data', ['ascent_date']],
                                ['data', ['route_name']],
                                ['data', ['route_grade']],
                                ['data', ['is_gps_validated'], 'bool'],
                                ['data', ['comment']],
                            ],
                            perm: [
                                ['no'], ['no'], ['no'], ['no'], ['no'],
                                ['no'], ['no'], ['no'], ['no'], ['no'],
                            ]
                        }
                    }
                }
            ]
        }
    },
    mounted() {
        this.load_all()
    },
    methods: {
        load_all() {
            this.loading = true
            Promise.all([
                axios.get('get_summit_admin/index'),
                axios.get('get_summit_admin/get_all_ascents'),
            ]).then(([s, a]) => {
                this.summits = s.data
                this.ascents = a.data
            }).catch(() => {}).finally(() => { this.loading = false })
        },
        on_saved() { this.load_all() },

        open_add() { this.$refs.form_modal.show_modal() },

        open_edit_by_id(id) {
            const s = this.summits.find(x => x.id === id)
            if (s) this.$refs.form_modal.show_modal(s)
        },
        open_qr_by_id(id) {
            const s = this.summits.find(x => x.id === id)
            if (s) { this.selected = s; this.qr_modal = true }
        },
        confirm_delete_by_id(id) {
            const s = this.summits.find(x => x.id === id)
            if (s) { this.to_delete = s; this.del_modal = true }
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
