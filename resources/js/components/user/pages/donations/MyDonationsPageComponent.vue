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
                    </skeleton-loader>
                </div>

                <div class="col-sm-12" v-else-if="donations.length > 0">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="load"
                        @open_donation_modal="openDonationModal"
                    />
                </div>

                <!-- No donations yet -->
                <div class="col-sm-12" v-else>
                    <div class="text-center py-5">
                        <i class="fa fa-heart fa-3x text-muted mb-3 d-block"></i>
                        <h5 class="text-muted mb-3">{{ $t('admin.donations.no_donations_yet') }}</h5>
                        <button class="btn btn-success" @click="openDonationModal">
                            <i class="fa fa-heart mr-1"></i> {{ $t('admin.donations.make_a_donation') }}
                        </button>
                    </div>
                </div>

                <!-- Always show donate button when there are donations too -->
                <div class="row mt-3" v-if="!loading && donations.length > 0">
                    <div class="col-md-12">
                        <button class="btn btn-success" @click="openDonationModal">
                            <i class="fa fa-heart mr-1"></i> {{ $t('admin.donations.make_another_donation') }}
                        </button>
                    </div>
                </div>
                
            </div>
        </div>

        <donationModal ref="donationModalRef" />
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb    from '../../items/BreadcrumbComponent.vue'
    import donationModal from '../../../guide/items/modals/Donation/DonationWarningModalComponent.vue'

    export default {
        components: { tabsComponent, breadcrumb, donationModal },

        data() {
            return {
                donations: [],
                data_for_tab: [],
                loading: false,
            }
        },

        mounted() {
            this.load()
            this.$bus.$on('donation-completed', this.load)
        },

        beforeUnmount() {
            this.$bus.$off('donation-completed', this.load)
        },

        methods: {
            load() {
                this.loading = true
                axios.get('/get_user_donations/my_donations')
                    .then(response => {
                        this.donations = response.data
                        this.data_for_tab = [{
                            id: 1,
                            table_name: this.$t('admin.donations.my_donations_table'),
                            add_action: {
                                action: 'fun',
                                link: 'open_donation_modal',
                                class: 'btn btn-success',
                                btn_title: this.$t('admin.donations.make_donation_btn'),
                            },
                            tab_data: {
                                data: response.data,
                                tab: {
                                    head: ['#', this.$t('admin.donations.amount_col'), this.$t('admin.donations.currency_col'), this.$t('admin.donations.status_col'), this.$t('admin.donations.comment_col'), this.$t('common.date')],
                                    body: [
                                        ['data', ['id']],
                                        ['data', ['amount']],
                                        ['data', ['currency']],
                                        ['data', ['status']],
                                        ['data', ['comment']],
                                        ['data', ['created_at']],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                    ],
                                },
                            },
                        }]
                    })
                    .catch(e => console.error(e))
                    .finally(() => { this.loading = false })
            },

            openDonationModal() {
                this.$refs.donationModalRef.show()
            },
        },
    }
</script>
