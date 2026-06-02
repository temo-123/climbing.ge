<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

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
                    @open_gps="open_gps_by_id"
                    @open_edit="open_edit_by_id"
                    @confirm_delete="confirm_delete_by_id"
                />
            </div>
            <div v-else class="col-sm-12 text-center py-4">
                No data available.
            </div>
        </div>

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

        <!-- GPS Coordinates modal -->
        <stack-modal :show="gps_modal" title="Update GPS Coordinates" @close="close_gps_modal" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <div v-if="gps_selected">
                <p class="mb-3">Set coordinates for <strong>{{ gps_selected.title }}</strong></p>

                <div v-if="gps_selected.latitude && gps_selected.longitude" class="alert alert-info py-2 small mb-3">
                    <i class="fa fa-map-marker"></i>
                    Current: {{ gps_selected.latitude }}, {{ gps_selected.longitude }}
                </div>
                <div v-else class="alert alert-warning py-2 small mb-3">
                    <i class="fa fa-exclamation-triangle"></i> No coordinates set yet — summit is hidden from public list.
                </div>

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: gps_tab === 'manual' }" href="#" @click.prevent="gps_tab = 'manual'">
                            <i class="fa fa-keyboard-o"></i> Manual Input
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: gps_tab === 'device' }" href="#" @click.prevent="gps_tab = 'device'; get_device_location()">
                            <i class="fa fa-map-marker"></i> Use My Location
                        </a>
                    </li>
                </ul>

                <!-- Manual tab -->
                <div v-show="gps_tab === 'manual'">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Latitude <span class="text-danger">*</span></label>
                            <input type="number" v-model="gps_form.latitude" class="form-control" step="any" placeholder="e.g. 42.6629" />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Longitude <span class="text-danger">*</span></label>
                            <input type="number" v-model="gps_form.longitude" class="form-control" step="any" placeholder="e.g. 44.0942" />
                        </div>
                    </div>
                </div>

                <!-- Device location tab -->
                <div v-show="gps_tab === 'device'">
                    <div v-if="gps_locating" class="text-center py-3">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2 text-muted">Getting your location...</p>
                    </div>
                    <div v-else-if="gps_location_error" class="alert alert-danger py-2 small">
                        <i class="fa fa-times-circle"></i> {{ gps_location_error }}
                    </div>
                    <div v-else-if="gps_form.latitude && gps_form.longitude" class="alert alert-success py-2">
                        <i class="fa fa-check"></i>
                        Location detected: <strong>{{ gps_form.latitude }}, {{ gps_form.longitude }}</strong>
                    </div>
                    <div v-else class="text-muted small text-center py-2">
                        Click "Use My Location" tab to detect your current position.
                    </div>
                </div>

                <div v-if="gps_error" class="alert alert-danger py-2 small mt-2">
                    {{ gps_error }}
                </div>
            </div>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="close_gps_modal">Cancel</button>
                <button type="button" class="btn btn-success" :disabled="gps_saving || !gps_form.latitude || !gps_form.longitude" @click="save_coordinates">
                    <span v-if="gps_saving"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-save"></i> Save Coordinates</span>
                </button>
            </template>
        </stack-modal>
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import QrcodeVue from 'qrcode.vue'

export default {
    name: 'SummitListPage',
    components: { breadcrumb, tabsComponent, QrcodeVue },
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

            gps_modal: false,
            gps_selected: null,
            gps_tab: 'manual',
            gps_form: { latitude: null, longitude: null },
            gps_saving: false,
            gps_error: null,
            gps_locating: false,
            gps_location_error: null,
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
                    list_page: process.env.MIX_APP_SSH
                        ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '') + '/summits/list'
                        : window.location.origin + '/summits/list',
                    add_action: {
                        action: 'fun',
                        link: 'open_add',
                        btn_title: 'Add Summit',
                        class: 'btn btn-primary',
                    },
                    tab_data: {
                        data: this.summits,
                        tab: {
                            head: ['ID', 'Title', 'URL Title', 'KA Title', 'Height', 'Lat/Lng', 'QR Saved', 'Published', 'QR', 'GPS', 'Edit', 'Delete'],
                            body: [
                                ['data', ['id']],
                                ['data', ['title']],
                                ['data', ['url_title']],
                                ['data', ['ka_title']],
                                ['data', ['height']],
                                ['data', ['coords_display']],
                                ['data', ['qr_code'], 'bool'],
                                ['data', ['published'], 'bool'],
                                ['action_fun_id', 'open_qr',        'btn btn-sm btn-info',    '<i class="fa fa-qrcode"></i>'],
                                ['action_fun_id', 'open_gps',       'btn btn-sm btn-success', '<i class="fa fa-map-marker"></i>'],
                                ['action_fun_id', 'open_edit',      'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'confirm_delete', 'btn btn-sm btn-danger',  '<i class="fa fa-trash"></i>'],
                            ],
                            perm: [
                                ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                ['no'],
                                ['summit', 'edit'],
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
                this.summits = s.data.map(summit => ({
                    ...summit,
                    coords_display: (summit.latitude && summit.longitude)
                        ? `${summit.latitude}, ${summit.longitude}`
                        : '—',
                }))
                this.ascents = a.data
            }).catch(() => {}).finally(() => { this.loading = false })
        },

        open_add() {
            this.$router.push('/summits/add')
        },

        open_edit_by_id(id) {
            this.$router.push('/summits/edit/' + id)
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

        open_gps_by_id(id) {
            const s = this.summits.find(x => x.id === id)
            if (!s) return
            this.gps_selected = s
            this.gps_form = { latitude: s.latitude || null, longitude: s.longitude || null }
            this.gps_tab = 'manual'
            this.gps_error = null
            this.gps_location_error = null
            this.gps_modal = true
        },

        close_gps_modal() {
            this.gps_modal = false
            this.gps_selected = null
            this.gps_locating = false
            this.gps_location_error = null
        },

        get_device_location() {
            if (!navigator.geolocation) {
                this.gps_location_error = 'Geolocation is not supported by your browser.'
                return
            }
            this.gps_locating = true
            this.gps_location_error = null
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.gps_form.latitude  = parseFloat(pos.coords.latitude.toFixed(6))
                    this.gps_form.longitude = parseFloat(pos.coords.longitude.toFixed(6))
                    this.gps_locating = false
                },
                (err) => {
                    this.gps_location_error = 'Could not get location: ' + err.message
                    this.gps_locating = false
                },
                { enableHighAccuracy: true, timeout: 10000 }
            )
        },

        save_coordinates() {
            if (!this.gps_selected || !this.gps_form.latitude || !this.gps_form.longitude) return
            this.gps_saving = true
            this.gps_error = null
            axios.post(`set_summit/update_coordinates/${this.gps_selected.id}`, {
                latitude:  this.gps_form.latitude,
                longitude: this.gps_form.longitude,
            })
                .then(response => {
                    const idx = this.summits.findIndex(s => s.id === this.gps_selected.id)
                    if (idx !== -1) {
                        this.summits[idx].latitude        = response.data.latitude
                        this.summits[idx].longitude       = response.data.longitude
                        this.summits[idx].coords_display  = `${response.data.latitude}, ${response.data.longitude}`
                    }
                    this.gps_modal = false
                    this.gps_selected = null
                })
                .catch(() => { this.gps_error = 'Failed to save coordinates. Please try again.' })
                .finally(() => { this.gps_saving = false })
        },
    }
}
</script>
