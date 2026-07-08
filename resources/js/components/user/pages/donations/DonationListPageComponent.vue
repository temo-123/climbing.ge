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
                    <skeleton-loader
                        viewBox="0 0 500 150"
                        primaryColor="#f3f3f3"
                        secondaryColor="#7427bb75"
                    >
                        <rect x="0" y="0" rx="2" ry="2" width="100%" height="25" />
                        <rect x="0" y="45" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="60" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="75" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="90" rx="3" ry="3" width="100%" height="10" />
                    </skeleton-loader>
                </div>
                <div class="col-sm-12" v-else>
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="load"
                        @del_donation="del_donation"
                        @show_status_modal="show_status_modal"
                    />
                </div>
            </div>
        </div>

        <StackModal
            v-model="is_status_modal"
            :title="$t('admin.donations.update_status_title')"
            @close="is_status_modal = false"
            @save="save_status"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        >
            <div v-if="editing_donation">
                <p><strong>#{{ editing_donation.id }}</strong> — {{ editing_donation.name }} {{ editing_donation.surname }}</p>
                <p>{{ $t('admin.donations.amount_prefix') }} <strong>{{ editing_donation.amount }} {{ editing_donation.currency }}</strong></p>
                <select class="form-control" v-model="new_status">
                    <option value="pending">{{ $t('admin.donations.status_pending') }}</option>
                    <option value="processing">{{ $t('admin.donations.status_processing') }}</option>
                    <option value="paid">{{ $t('admin.donations.status_paid') }}</option>
                    <option value="failed">{{ $t('admin.donations.status_failed') }}</option>
                    <option value="declined">{{ $t('admin.donations.status_declined') }}</option>
                    <option value="expired">{{ $t('admin.donations.status_expired') }}</option>
                </select>
            </div>
        </StackModal>
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue';
    import breadcrumb from '../../items/BreadcrumbComponent.vue';

    export default {
        components: { tabsComponent, breadcrumb },

        data() {
            return {
                data_for_tab: [],
                loading: false,
                is_status_modal: false,
                editing_donation: null,
                new_status: 'pending',
            };
        },

        mounted() {
            this.load();
        },

        methods: {
            load() {
                this.loading = true;
                axios.get('set_donation_admin/get_all_donations')
                    .then(response => {
                        this.data_for_tab = [{
                            id: 1,
                            table_name: this.$t('admin.donations.donations_table'),
                            add_action: null,
                            tab_data: {
                                data: response.data,
                                tab: {
                                    head: [this.$t('common.id'), this.$t('common.name'), this.$t('common.email'), this.$t('admin.donations.amount_col'), this.$t('admin.donations.status_col'), this.$t('admin.donations.user_col'), this.$t('common.date'), this.$t('admin.donations.status_col'), this.$t('common.delete')],
                                    body: [
                                        ['data', ['id']],
                                        ['data', ['name']],
                                        ['data', ['email']],
                                        ['data', ['amount']],
                                        ['data', ['status']],
                                        ['data', ['user', 'name']],
                                        ['data', ['created_at']],
                                        ['action_fun_id', 'show_status_modal', 'btn btn-primary', '<i class="fa fa-edit"></i>'],
                                        ['action_fun_id', 'del_donation', 'btn btn-danger', '<i class="fa fa-trash"></i>'],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                        ['donation', 'edit'],
                                        ['donation', 'del'],
                                    ],
                                },
                            },
                        }];
                    })
                    .catch(e => console.error(e))
                    .finally(() => { this.loading = false; });
            },

            del_donation(id) {
                if (confirm(this.$t('admin.donations.confirm_delete_donation'))) {
                    axios.delete(`set_donation_admin/del_donation/${id}`)
                        .then(() => this.load())
                        .catch(e => console.error(e));
                }
            },

            show_status_modal(id) {
                const d = this.data_for_tab[0]?.tab_data?.data?.find(x => x.id == id);
                if (!d) return;
                this.editing_donation = d;
                this.new_status = d.status;
                this.is_status_modal = true;
            },

            save_status() {
                if (!this.editing_donation) return;
                axios.post(`set_donation_admin/update_status/${this.editing_donation.id}`, { status: this.new_status })
                    .then(() => {
                        this.is_status_modal = false;
                        this.load();
                    })
                    .catch(e => console.error(e));
            },
        },
    };
</script>
