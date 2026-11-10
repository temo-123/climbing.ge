<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @update="get_reservations"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { money } from '../../../../../../services/shop/tourPrice.js'
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb
        },
    
        data() {
            return {
                data_for_tab:[],
            }
        },

        mounted() {
            this.get_reservations();
        },
         
        methods: {
            get_reservations: function(){
                this.data_for_tab = []
                axios
                .get("/set_tour/get_reservation/get_user_reservations/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': this.$t('admin.tour.all_tours_reservations_table'),
                                            'tab_data': {
                                                // language + climbing.ge fee (helper guides pay 15 %)
                                                'data': (response.data || []).map(r => ({
                                                    ...r,
                                                    language_label: r.tour_language ? this.$t('shop.tour.lang.' + r.tour_language) : '',
                                                    fee_label: r.commission_percent
                                                        ? (r.commission_amount != null ? money(r.commission_amount, r.price_currency) : r.commission_percent + '%') + ' · ' + (r.commission_paid_at ? this.$t('admin.tour_fee.paid') : this.$t('admin.tour_fee.unpaid'))
                                                        : '',
                                                })),
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('admin.tour.persons_col'),
                                                        this.$t('admin.tour.check_in_col'),
                                                        this.$t('admin.tour.reserver_name_col'),
                                                        this.$t('admin.tour.reserver_email_col'),
                                                        this.$t('shop.tour.lang.label'),
                                                        this.$t('admin.tour_guides.guide_in_charge'),
                                                        this.$t('admin.tour_fee.fee_col'),
                                                        this.$t('admin.tour.verified_col'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['persons']],
                                                        ['data', ['check_in']],
                                                        ['data', ['name']],
                                                        ['data', ['email']],
                                                        ['data', ['language_label']],
                                                        ['data', ['guide_name']],
                                                        ['data', ['fee_label']],
                                                        ['data', ['verificate'], 'bool'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                    ],
                                                    // accent = I'm the guide in charge
                                                    'row_marks': [
                                                        { field: ['is_mine'], when: 'true', class: 'cg-row-accent', label: this.$t('admin.tour_guides.assigned_to_me') },
                                                        { field: ['verificate'], when: 'false', class: 'table-warning', label: this.$t('admin.tour.row_unverified') },
                                                    ],
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>