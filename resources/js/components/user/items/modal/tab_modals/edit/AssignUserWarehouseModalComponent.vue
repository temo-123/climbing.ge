<template>
    <stack-modal
            :show="is_modal_open"
            :title="$t('admin.users.assign_warehouse_title')"
            @close="close_modal()"
            :saveButton="{ visible: has_permission, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('admin.comments.cancel_btn'), btnClass: { 'btn btn-secondary': true } }"
        >
        <div>
            <div class="row justify-content-center" v-if="is_loading">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div v-else>
                <div v-if="!has_permission" class="alert alert-warning">
                    {{ $t('admin.users.assign_warehouse_needs_permission_hint') }}
                </div>

                <div v-else class="form-group">
                    <label>{{ $t('admin.users.assign_warehouse_select_label') }}</label>
                    <select class="form-control" v-model="selected_warehouse_id">
                        <option :value="null">{{ $t('admin.users.assign_warehouse_none_option') }}</option>
                        <option v-for="warehouse in sale_point_warehouses" :key="warehouse.id" :value="warehouse.id">
                            {{ warehouse.name }}
                        </option>
                    </select>
                    <small class="text-muted">{{ $t('admin.users.assign_warehouse_hint') }}</small>
                </div>
            </div>
        </div>
        <template #footer>
            <button v-if="has_permission && !is_loading" type="button" class="btn btn-primary" @click="save()">
                {{ $t('common.save') }}
            </button>
        </template>
    </stack-modal>
</template>

<script>
    export default {
        data(){
            return {
                is_modal_open: false,
                is_loading: false,

                user_id: '',
                has_permission: false,

                sale_point_warehouses: [],
                selected_warehouse_id: null,
            }
        },
        methods: {
            show_modal(id){
                this.user_id = id
                this.is_modal_open = true
                this.load()
            },

            close_modal(){
                this.is_modal_open = false
                this.is_loading = false
                this.user_id = ''
                this.has_permission = false
                this.sale_point_warehouses = []
                this.selected_warehouse_id = null
            },

            load(){
                this.is_loading = true
                axios
                    .get('/set_role/get_user_permissions/' + this.user_id)
                    .then(response => {
                        const permissions = response.data.permissions || []
                        this.has_permission = permissions.some(
                            permission => permission.subject === 'warehouse' && permission.action === 'sell_own'
                        )
                        if (this.has_permission) {
                            return Promise.all([
                                axios.get('/get_warehouse/get_sale_point_warehouses'),
                                axios.get('/get_warehouse/get_user_warehouse/' + this.user_id),
                            ]).then(([warehousesResponse, assignmentResponse]) => {
                                this.sale_point_warehouses = warehousesResponse.data
                                this.selected_warehouse_id = assignmentResponse.data?.warehouse_id || null
                            })
                        }
                    })
                    .catch(error => console.log('Error loading warehouse assignment:', error))
                    .finally(() => this.is_loading = false)
            },

            save(){
                this.is_loading = true
                axios
                    .post('/set_warehouse/assign_user_warehouse/' + this.user_id, {
                        warehouse_id: this.selected_warehouse_id,
                    })
                    .then(() => {
                        this.$bus.$emit('toast', {
                            type: 'success',
                            title: this.$t('admin.users.toast_title'),
                            message: this.$t('admin.users.assign_warehouse_saved_message'),
                        })
                        this.close_modal()
                    })
                    .catch(error => {
                        console.log('Error saving warehouse assignment:', error)
                        this.$bus.$emit('toast', {
                            type: 'danger',
                            title: this.$t('admin.users.toast_title'),
                            message: error.response?.data?.error || this.$t('admin.users.role_saved_error_message'),
                        })
                    })
                    .finally(() => this.is_loading = false)
            },
        }
    }
</script>
