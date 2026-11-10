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
            <!-- dashboard: stat cards, calendar of reserved trips, month chart, top tours -->
            <div class="row">
                <div class="col-sm-12">
                    <reservationsDashboard ref="dashboard" @open="show_reservation_details" />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent 
                        :table_data="this.data_for_tab"

                        @update="get_reservations"

                        @show_reservation_calendar_modal="show_reservation_calendar_modal"

                        @del_tour_reservation="del_tour_reservation"

                        @show_reservation_details="show_reservation_details"
                        @manage_tour="manage_tour"

                        @delete_selected="delete_selected"
                    />
                </div>
            </div>

            <!-- Calendar Modal -->
            <toureReservationCalendarModal 
                ref="toure_reservation_calendar_modal"
                @open-google-calendar-sync="openGoogleCalendarSync"
            />

            <!-- Google Calendar Sync Modal (separate level) -->
            <googleCalendarSyncModal ref="google_calendar_sync_modal" />

            <!-- Reservation Details Modal -->
            <tourReservationDetailsModal 
                ref="tour_reservation_details_modal"
                @deleted="onReservationDeleted"
                @verified="refresh_all"
            />

            <!-- "Manage tour & guides" for the reservation's tour -->
            <tour_guide_modal ref="tour_guide_modal" @updated="refresh_all" />
        </div>
    </div>
</template>

<script>
    import { money } from '../../../../../../services/shop/tourPrice.js'
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import reservationsDashboard from './ReservationsDashboardComponent.vue'
    import tour_guide_modal from '../../../../items/modal/tab_modals/TourGuideRelationModalComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'

    import toureReservationCalendarModal from '../../modal/ToureReservationCalendarModal.vue'
    import googleCalendarSyncModal from '../../modal/GoogleCalendarSyncModal.vue'
    import tourReservationDetailsModal from '../../modal/TourReservationDetailsModal.vue'

    export default {
        components: {
            reservationsDashboard,
            tour_guide_modal,
            tabsComponent,
            breadcrumb,

            toureReservationCalendarModal,
            googleCalendarSyncModal,
            tourReservationDetailsModal
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

                const tabHead = [this.$t('common.id'), this.$t('admin.tour.persons_col'), this.$t('admin.tour_prices.days_col'), this.$t('admin.tour_prices.price_col'), this.$t('admin.tour.check_in_col'), this.$t('common.name'), this.$t('common.email'), this.$t('shop.tour.lang.label'), this.$t('admin.tour_guides.guide_in_charge'), this.$t('admin.tour_fee.fee_col'), this.$t('admin.tour_fee.completed_col'), this.$t('admin.tour.verified_col'), this.$t('admin.tour.verified_at_col'), this.$t('admin.tour.details_col'), this.$t('admin.tour_guides.manage_tour_col'), this.$t('common.delete')];
                const tabBody = [
                    ['data', ['id']],
                    ['data', ['persons']],
                    ['data', ['days']],
                    ['data', ['price_label']],
                    ['data', ['check_in']],
                    ['data', ['name']],
                    ['data', ['email']],
                    ['data', ['language_label']],
                    ['data', ['guide_name']],
                    ['data', ['fee_label']],
                    ['data', ['is_completed'], 'bool'],
                    ['data', ['verificate'], 'bool'],
                    ['data', ['verified_at_label']],
                    ['action_fun_id', 'show_reservation_details', 'btn btn-info', '<i aria-hidden="true" class="fa fa-eye"></i>'],
                    ['action_fun_id', 'manage_tour', 'btn btn-secondary', '<i aria-hidden="true" class="fa fa-users"></i>'],
                    ['action_fun_id', 'del_tour_reservation', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                ];
                const tabPerm = [
                    ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                    ['tour', 'show'],
                    ['tour', 'del'],
                ];

                Promise.all([
                    axios.get("/set_tour/get_reservation/get_all_reservations/"),
                    axios.get("/set_tour/get_reservation/get_declarations/"),
                ]).then(([allResponse, declResponse]) => {
                    const prepare = rows => (rows || []).map(row => ({
                        ...row,
                        verified_at_label: this.verified_label(row),
                        // saved price (server calculation); days chosen but no rule → on request
                        price_label: row.price_total !== null && row.price_total !== undefined
                            ? money(row.price_total, row.price_currency)
                            : (row.days ? this.$t('admin.tour_prices.on_request') : ''),
                        language_label: row.tour_language ? this.$t('shop.tour.lang.' + row.tour_language) : '',
                        is_completed: !!row.completed_at,
                        // "81 € · unpaid" / "81 € · paid ✓" — only reservations with a helper guide
                        fee_label: this.fee_label(row),
                        fee_unpaid: !!row.commission_percent && !row.commission_paid_at,
                    }));
                    // yellow = waiting for the reserver's email confirmation, grey = check-in date passed
                    const row_marks = [
                        { field: ['verificate'], when: 'false', class: 'table-warning', label: this.$t('admin.tour.row_unverified') },
                        { field: ['fee_unpaid'], when: 'true', class: 'table-danger', label: this.$t('admin.tour_fee.row_unpaid') },
                        { field: ['is_completed'], when: 'true', class: 'table-success', label: this.$t('admin.tour_fee.row_completed') },
                        { when: row => !row.completed_at && !!row.check_in && new Date(String(row.check_in).slice(0, 10) + 'T00:00') < new Date(new Date().toDateString()), class: 'cg-row-muted', label: this.$t('admin.tour.row_past') },
                    ];
                    this.data_for_tab.push({
                        'id': 1,
                        'table_name': this.$t('admin.tour.all_reservations_table'),
                        'add_action': {
                            'action': 'fun',
                            'link': 'show_reservation_calendar_modal',
                            'class': 'btn btn-primary'
                        },
                        'tab_data': {
                            'data': prepare(allResponse.data),
                            'tab': { 'row_marks': row_marks, 'head': tabHead, 'body': tabBody, 'perm': tabPerm }
                        },
                    });
                    this.data_for_tab.push({
                        'id': 2,
                        'table_name': this.$t('admin.tour.declarations_table'),
                        'tab_data': {
                            'data': prepare(declResponse.data),
                            'tab': { 'row_marks': row_marks, 'head': tabHead, 'body': tabBody, 'perm': tabPerm }
                        },
                    });
                })
                .catch(error => console.log(error));
            },
            fee_label(row){
                if (!row.commission_percent) return '';
                const amount = row.commission_amount !== null && row.commission_amount !== undefined ? money(row.commission_amount, row.price_currency) : (row.commission_percent + '%');
                return amount + ' · ' + (row.commission_paid_at ? this.$t('admin.tour_fee.paid') + ' ✓' : this.$t('admin.tour_fee.unpaid'));
            },
            // "12 Nov 2026, 14:05 · email" — when and how it was confirmed
            verified_label(row){
                if (!row.verified_at) return '';
                const d = new Date(String(row.verified_at).replace(' ', 'T'));
                const when = isNaN(d.getTime()) ? row.verified_at : d.toLocaleString('en-GB', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                const how = row.verify_method ? this.$t('admin.tour.verify_method_' + row.verify_method) : '';
                return how ? when + ' · ' + how : when;
            },
            show_reservation_calendar_modal(){
                if (this.data_for_tab.length > 0 && 
                    this.data_for_tab[0].tab_data && 
                    this.data_for_tab[0].tab_data.data) {
                    this.$refs.toure_reservation_calendar_modal.show_reservation_calendar_modal_func(
                        this.data_for_tab[0].tab_data.data
                    );
                }
            },
            // the table button sends the row id; the modal needs the whole row
            show_reservation_details(reservation){
                let row = reservation;
                if (typeof reservation !== 'object') {
                    const rows = this.data_for_tab.flatMap(tab => (tab.tab_data && tab.tab_data.data) || []);
                    row = rows.find(r => String(r.id) === String(reservation));
                }
                if (row) this.$refs.tour_reservation_details_modal.show_modal_func({ ...row });
            },
            openGoogleCalendarSync(){
                this.$refs.google_calendar_sync_modal.open_google_calendar_sync_modal();
            },
            del_tour_reservation(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_tour/set_reservation/del_reservation/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_reservations()
                    })
                    .catch(error => console.log(error))
                }
            },
            // the table button sends the reservation id → open its tour in the guides modal
            manage_tour(reservation_id){
                const rows = this.data_for_tab.flatMap(tab => (tab.tab_data && tab.tab_data.data) || []);
                const row = rows.find(r => String(r.id) === String(reservation_id));
                if (row) this.$refs.tour_guide_modal.show_modal(row.tour_id, row.tour_title || '');
            },
            // table + dashboard numbers
            refresh_all(){
                this.get_reservations();
                if (this.$refs.dashboard) this.$refs.dashboard.load();
            },
            onReservationDeleted(deletedId){
                // Refresh the reservations list + dashboard after deletion
                this.refresh_all();
            },
            delete_selected(ids){
                axios
                .post('/set_tour/set_reservation/bulk_delete', { ids })
                .then(Response => {
                    this.get_reservations()
                })
                .catch(error => console.log(error))
            },
        }
    }
</script>
