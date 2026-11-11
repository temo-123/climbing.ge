<template>
    <StackModal v-model="is_open" :title="warehouse_id ? $t('admin.gear_rental.edit_warehouse_title') : $t('admin.gear_rental.add_warehouse')" size="md" @close="is_open = false">
        <form @submit.prevent="save" novalidate>
            <label class="form-label">{{ $t('common.name') }} *</label>
            <input type="text" class="form-control" :class="{ 'is-invalid': err('name') }" v-model.trim="form.name" maxlength="255" required>
            <div class="invalid-feedback">{{ err('name') }}</div>

            <div class="form-check form-switch mt-3">
                <input id="rw_general" class="form-check-input" type="checkbox" role="switch" v-model="form.general">
                <label for="rw_general" class="form-check-label">{{ $t('admin.gear_rental.general_warehouse') }}</label>
            </div>
            <small class="form-text text-muted">{{ $t('admin.gear_rental.general_hint') }}</small>

            <label class="form-label mt-3">{{ $t('admin.gear_rental.address_col') }}</label>
            <input type="text" class="form-control" v-model.trim="form.address" maxlength="255">

            <label class="form-label mt-3">{{ $t('admin.gear_rental.notes') }}</label>
            <textarea class="form-control" rows="2" v-model="form.note" maxlength="2000"></textarea>
        </form>

        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-light me-auto" @click="is_open = false">{{ $t('common.cancel') }}</button>
                <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                    <i class="fa fa-check" aria-hidden="true"></i> {{ $t('common.save') }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import StackModal from '../../../global_components/modals/StackModal.vue'

export default {
    name: 'RentalWarehouseModal',
    components: { StackModal },
    emits: ['saved'],
    data() {
        return { is_open: false, saving: false, warehouse_id: null, form: {}, errors: {} }
    },
    methods: {
        open(w = null) {
            this.errors = {}
            this.warehouse_id = w ? w.id : null
            this.form = { name: w ? w.name : '', general: w ? !!w.general : false, address: w ? (w.address || '') : '', note: w ? (w.note || '') : '' }
            this.is_open = true
        },
        err(key) {
            const e = this.errors[key]
            return e ? (Array.isArray(e) ? e[0] : e) : ''
        },
        save() {
            this.saving = true
            const url = this.warehouse_id ? '/set_gear_rental/edit_warehouse/' + this.warehouse_id : '/set_gear_rental/add_warehouse'
            axios.post(url, this.form)
                .then(r => {
                    this.is_open = false
                    this.$emit('saved', r.data)
                })
                .catch(e => { if (e.response && e.response.status === 422) this.errors = e.response.data.errors || {} })
                .finally(() => { this.saving = false })
        },
    },
}
</script>
