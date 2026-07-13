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
                    <p>{{ $t('admin.summits.loading_summits') }}</p>
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
                    @export_laser_plate="export_laser_plate_by_id"
                    @filtr_summits_by_mount="filtr_summits_by_mount"
                    @open_edit_ascent="open_edit_ascent"
                    @confirm_delete_ascent="confirm_delete_ascent"
                />
            </div>
            <div v-else class="col-sm-12 text-center py-4">
                {{ $t('admin.summits.no_data_available') }}
            </div>
        </div>

        <!-- QR Code modal -->
        <stack-modal :show="qr_modal" :title="$t('admin.summits.summit_qr_code_title')" @close="qr_modal = false" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <div v-if="selected" class="text-center">
                <p class="mb-2">{{ $t('admin.summits.qr_code_for') }} <strong>{{ selected.title }}</strong></p>
                <div class="d-flex justify-content-center mb-3">
                    <qrcode-vue :value="qr_value" :size="220" level="H" render-as="svg" />
                </div>
                <p class="text-muted small">{{ qr_value }}</p>
                <div v-if="selected.qr_code" class="alert alert-success py-1 small">
                    <i class="fa fa-check"></i> {{ $t('admin.summits.qr_already_saved') }}
                </div>
                <div v-else class="alert alert-warning py-1 small">
                    <i class="fa fa-exclamation-triangle"></i> {{ $t('admin.summits.qr_not_saved_yet') }}
                </div>
            </div>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="qr_modal = false">{{ $t('common.close') }}</button>
                <button type="button" class="btn btn-primary" :disabled="saving_qr" @click="save_qr">
                    <span v-if="saving_qr"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-save"></i> {{ selected && selected.qr_code ? $t('admin.summits.resave_qr') : $t('admin.summits.save_qr') }}</span>
                </button>
            </template>
        </stack-modal>

        <!-- Delete confirm modal -->
        <stack-modal :show="del_modal" :title="$t('admin.summits.confirm_delete_title')" @close="del_modal = false" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <p>{{ $t('admin.summits.delete_summit_confirm') }} <strong>{{ to_delete?.title }}</strong>?</p>
            <p class="text-danger small">{{ $t('admin.summits.cannot_be_undone') }}</p>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="del_modal = false">{{ $t('admin.comments.cancel_btn') }}</button>
                <button type="button" class="btn btn-danger" :disabled="deleting" @click="delete_summit">
                    <span v-if="deleting"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else>{{ $t('common.delete') }}</span>
                </button>
            </template>
        </stack-modal>

        <!-- GPS Coordinates modal -->
        <stack-modal :show="gps_modal" :title="$t('admin.summits.update_gps_title')" @close="close_gps_modal" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <div v-if="gps_selected">
                <p class="mb-3">{{ $t('admin.summits.set_coordinates_for') }} <strong>{{ gps_selected.title }}</strong></p>

                <div v-if="gps_selected.latitude && gps_selected.longitude" class="alert alert-info py-2 small mb-3">
                    <i class="fa fa-map-marker"></i>
                    {{ $t('admin.summits.current_coords_prefix') }} {{ gps_selected.latitude }}, {{ gps_selected.longitude }}
                </div>
                <div v-else class="alert alert-warning py-2 small mb-3">
                    <i class="fa fa-exclamation-triangle"></i> {{ $t('admin.summits.no_coords_set_hint') }}
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.summits.height_meters_label') }}</label>
                    <input type="number" v-model="gps_form.height" class="form-control" :placeholder="$t('admin.summits.height_placeholder')" min="0" />
                </div>

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: gps_tab === 'manual' }" href="#" @click.prevent="gps_tab = 'manual'">
                            <i class="fa fa-keyboard-o"></i> {{ $t('admin.summits.manual_input_tab') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: gps_tab === 'device' }" href="#" @click.prevent="gps_tab = 'device'; get_device_location()">
                            <i class="fa fa-map-marker"></i> {{ $t('admin.summits.use_my_location_tab') }}
                        </a>
                    </li>
                </ul>

                <!-- Manual tab -->
                <div v-show="gps_tab === 'manual'">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ $t('admin.summits.latitude_label') }} <span class="text-danger">*</span></label>
                            <input type="number" v-model="gps_form.latitude" class="form-control" step="any" :placeholder="$t('admin.summits.lat_placeholder')" />
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ $t('admin.summits.longitude_label') }} <span class="text-danger">*</span></label>
                            <input type="number" v-model="gps_form.longitude" class="form-control" step="any" :placeholder="$t('admin.summits.lng_placeholder')" />
                        </div>
                    </div>
                </div>

                <!-- Device location tab -->
                <div v-show="gps_tab === 'device'">
                    <div v-if="gps_locating" class="text-center py-3">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2 text-muted">{{ $t('admin.summits.getting_location') }}</p>
                    </div>
                    <div v-else-if="gps_location_error" class="alert alert-danger py-2 small">
                        <i class="fa fa-times-circle"></i> {{ gps_location_error }}
                    </div>
                    <div v-else-if="gps_form.latitude && gps_form.longitude" class="alert alert-success py-2">
                        <i class="fa fa-check"></i>
                        {{ $t('admin.summits.location_detected_prefix') }} <strong>{{ gps_form.latitude }}, {{ gps_form.longitude }}</strong>
                        <div v-if="!gps_form.height" class="small text-muted mt-1">{{ $t('admin.summits.altitude_not_available_hint') }}</div>
                    </div>
                    <div v-else class="text-muted small text-center py-2">
                        {{ $t('admin.summits.click_use_my_location_hint') }}
                    </div>
                </div>

                <div v-if="gps_error" class="alert alert-danger py-2 small mt-2">
                    {{ gps_error }}
                </div>
            </div>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="close_gps_modal">{{ $t('admin.comments.cancel_btn') }}</button>
                <button type="button" class="btn btn-success" :disabled="gps_saving || !gps_form.latitude || !gps_form.longitude" @click="save_coordinates">
                    <span v-if="gps_saving"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-save"></i> {{ $t('admin.summits.save_coordinates_btn') }}</span>
                </button>
            </template>
        </stack-modal>

        <!-- Edit ascent GPS verification modal -->
        <stack-modal :show="edit_ascent_modal" :title="$t('admin.summits.edit_ascent_verification_title')" @close="edit_ascent_modal = false" :saveButton="{ visible: false }" :cancelButton="{ visible: false }">
            <div v-if="ascent_to_edit" class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="ascent_verified_checkbox" v-model="ascent_verified_checkbox" />
                <label class="form-check-label" for="ascent_verified_checkbox">{{ $t('admin.summits.verified_checkbox_label') }}</label>
            </div>
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="edit_ascent_modal = false">{{ $t('admin.comments.cancel_btn') }}</button>
                <button type="button" class="btn btn-primary" :disabled="saving_ascent" @click="save_ascent_verification">
                    <span v-if="saving_ascent"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-save"></i> {{ $t('common.save') }}</span>
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

            mounts: [],
            filter_mount_id: 0,

            qr_modal: false,
            selected: null,
            saving_qr: false,

            del_modal: false,
            to_delete: null,
            deleting: false,

            gps_modal: false,
            gps_selected: null,
            gps_tab: 'manual',
            gps_form: { latitude: null, longitude: null, height: null },
            gps_saving: false,
            gps_error: null,
            gps_locating: false,
            gps_location_error: null,

            edit_ascent_modal: false,
            ascent_to_edit: null,
            ascent_verified_checkbox: false,
            saving_ascent: false,

            deleting_ascent: false,
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
            const filteredSummits = this.filter_mount_id
                ? this.summits.filter(s => s.mount_id === this.filter_mount_id)
                : this.summits
            return [
                {
                    id: 1,
                    table_name: this.$t('admin.summits.summits_table'),
                    list_page: process.env.MIX_APP_SSH
                        ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '') + '/summits/list'
                        : window.location.origin + '/summits/list',
                    add_action: {
                        action: 'fun',
                        link: 'open_add',
                        btn_title: this.$t('admin.summits.add_summit_btn'),
                        class: 'btn btn-primary',
                    },
                    filter_data: {
                        title: this.$t('admin.articles.filter_by_mount_masive'),
                        data: this.mounts,
                        action_fun_id: 'filtr_summits_by_mount',
                        array_key: 'name',
                        id_key: 'id',
                    },
                    tab_data: {
                        data: filteredSummits,
                        tab: {
                            head: [this.$t('common.id'), this.$t('common.title'), this.$t('common.height'), this.$t('admin.summits.mount_massive_label'), this.$t('admin.summits.col_lat_lng'), this.$t('admin.summits.col_qr_saved'), this.$t('admin.common.published'), 'QR', 'GPS', this.$t('common.edit'), this.$t('common.delete'), this.$t('admin.summits.col_laser_plate')],
                            body: [
                                ['data', ['id']],
                                ['data', ['title']],
                                ['data', ['height']],
                                ['data', ['mount_name']],
                                ['data', ['coords_display']],
                                ['data', ['qr_code'], 'bool'],
                                ['data', ['published'], 'bool'],
                                ['action_fun_id', 'open_qr',        'btn btn-sm btn-info',    '<i class="fa fa-qrcode"></i>'],
                                ['action_fun_id', 'open_gps',       'btn btn-sm btn-success', '<i class="fa fa-map-marker"></i>'],
                                ['action_fun_id', 'open_edit',      'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'confirm_delete', 'btn btn-sm btn-danger',  '<i class="fa fa-trash"></i>'],
                                ['action_fun_id', 'export_laser_plate', 'btn btn-sm btn-dark', '<i class="fa fa-file-pdf-o"></i>'],
                            ],
                            perm: [
                                ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                ['no'],
                                ['summit', 'edit'],
                                ['summit', 'edit'],
                                ['summit', 'del'],
                                ['summit', 'show'],
                            ]
                        }
                    }
                },
                {
                    id: 2,
                    table_name: this.$t('admin.summits.ascents_table'),
                    tab_data: {
                        data: this.ascents,
                        tab: {
                            head: [this.$t('common.id'), this.$t('common.name'), this.$t('admin.summits.col_surname'), this.$t('common.email'), this.$t('admin.summits.col_summit'), this.$t('common.date'), this.$t('common.route'), this.$t('common.grade'), 'GPS', this.$t('admin.summits.col_comment'), this.$t('admin.summits.col_edit'), this.$t('admin.summits.col_delete_ascent')],
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
                                ['action_fun_id', 'open_edit_ascent',    'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'confirm_delete_ascent', 'btn btn-sm btn-danger', '<i class="fa fa-trash"></i>'],
                            ],
                            perm: [
                                ['no'], ['no'], ['no'], ['no'], ['no'],
                                ['no'], ['no'], ['no'], ['no'], ['no'],
                                ['summit', 'edit'],
                                ['summit', 'del'],
                            ]
                        }
                    }
                }
            ]
        }
    },
    mounted() {
        this.load_all()
        this.load_mounts()
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

        load_mounts() {
            axios.get('get_summit_admin/get_mounts_list')
                .then(response => { this.mounts = response.data })
                .catch(() => {})
        },

        filtr_summits_by_mount(mount_id) {
            this.filter_mount_id = mount_id
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

        export_laser_plate_by_id(id) {
            axios.get(`get_summit_admin/export_laser_plate/${id}`, { responseType: 'blob' })
                .then(response => {
                    const contentDisposition = response.headers['content-disposition']
                    const filename = contentDisposition
                        ? contentDisposition.split('filename=')[1].replace(/"/g, '')
                        : 'summit-laser-plate.pdf'
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', filename)
                    document.body.appendChild(link)
                    link.click()
                    document.body.removeChild(link)
                    window.URL.revokeObjectURL(url)
                })
                .catch(() => { alert(this.$t('admin.summits.failed_export_laser_plate')) })
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
                .catch(() => { alert(this.$t('admin.summits.failed_save_qr')) })
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
                .catch(() => { alert(this.$t('admin.summits.failed_delete_summit')) })
                .finally(() => { this.deleting = false })
        },

        open_edit_ascent(id) {
            const a = this.ascents.find(x => x.id === id)
            if (!a) return
            this.ascent_to_edit = a
            this.ascent_verified_checkbox = !!a.is_gps_validated
            this.edit_ascent_modal = true
        },

        save_ascent_verification() {
            if (!this.ascent_to_edit) return
            this.saving_ascent = true
            axios.post(`set_summit/update_ascent/${this.ascent_to_edit.id}`, { is_gps_validated: this.ascent_verified_checkbox ? 1 : 0 })
                .then(() => {
                    const idx = this.ascents.findIndex(a => a.id === this.ascent_to_edit.id)
                    if (idx !== -1) this.ascents[idx].is_gps_validated = this.ascent_verified_checkbox
                    this.edit_ascent_modal = false
                    this.ascent_to_edit = null
                })
                .catch(() => { alert(this.$t('admin.summits.failed_update_ascent')) })
                .finally(() => { this.saving_ascent = false })
        },

        confirm_delete_ascent(id) {
            if (!confirm(this.$t('admin.summits.delete_ascent_confirm'))) return
            this.delete_ascent(id)
        },

        delete_ascent(id) {
            this.deleting_ascent = true
            axios.delete(`set_summit/delete_ascent/${id}`)
                .then(() => {
                    this.ascents = this.ascents.filter(a => a.id !== id)
                })
                .catch(() => { alert(this.$t('admin.summits.failed_delete_ascent')) })
                .finally(() => { this.deleting_ascent = false })
        },

        open_gps_by_id(id) {
            const s = this.summits.find(x => x.id === id)
            if (!s) return
            this.gps_selected = s
            this.gps_form = { latitude: s.latitude || null, longitude: s.longitude || null, height: s.height || null }
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
                this.gps_location_error = this.$t('admin.summits.geolocation_not_supported')
                return
            }
            this.gps_locating = true
            this.gps_location_error = null

            // A single getCurrentPosition() shot frequently returns before the
            // device's vertical (altitude) fix has stabilized, even outdoors with
            // a clear sky. Watch for a few seconds instead and keep the best
            // reading, preferring one that actually includes altitude.
            let best_pos = null
            const watch_id = navigator.geolocation.watchPosition(
                (pos) => {
                    const has_altitude = pos.coords.altitude !== null && pos.coords.altitude !== undefined
                    if (!best_pos || (has_altitude && (best_pos.coords.altitude === null || best_pos.coords.altitude === undefined))) {
                        best_pos = pos
                    }
                    if (has_altitude) {
                        finish()
                    }
                },
                (err) => {
                    if (!best_pos) {
                        this.gps_location_error = this.$t('admin.summits.could_not_get_location_prefix') + ' ' + err.message
                        this.gps_locating = false
                        navigator.geolocation.clearWatch(watch_id)
                    }
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            )

            const timeout_id = setTimeout(finish, 7000)

            const finish = () => {
                clearTimeout(timeout_id)
                navigator.geolocation.clearWatch(watch_id)
                if (best_pos) {
                    this.gps_form.latitude  = parseFloat(best_pos.coords.latitude.toFixed(6))
                    this.gps_form.longitude = parseFloat(best_pos.coords.longitude.toFixed(6))
                    if (best_pos.coords.altitude !== null && best_pos.coords.altitude !== undefined) {
                        this.gps_form.height = Math.round(best_pos.coords.altitude)
                    }
                }
                this.gps_locating = false
            }
        },

        save_coordinates() {
            if (!this.gps_selected || !this.gps_form.latitude || !this.gps_form.longitude) return
            this.gps_saving = true
            this.gps_error = null
            const payload = {
                latitude:  this.gps_form.latitude,
                longitude: this.gps_form.longitude,
            }
            if (this.gps_form.height !== null && this.gps_form.height !== '') {
                payload.height = this.gps_form.height
            }
            axios.post(`set_summit/update_coordinates/${this.gps_selected.id}`, payload)
                .then(response => {
                    const idx = this.summits.findIndex(s => s.id === this.gps_selected.id)
                    if (idx !== -1) {
                        this.summits[idx].latitude        = response.data.latitude
                        this.summits[idx].longitude       = response.data.longitude
                        this.summits[idx].height           = response.data.height
                        this.summits[idx].coords_display  = `${response.data.latitude}, ${response.data.longitude}`
                    }
                    this.gps_modal = false
                    this.gps_selected = null
                })
                .catch(() => { this.gps_error = this.$t('admin.summits.failed_save_coordinates') })
                .finally(() => { this.gps_saving = false })
        },
    }
}
</script>
