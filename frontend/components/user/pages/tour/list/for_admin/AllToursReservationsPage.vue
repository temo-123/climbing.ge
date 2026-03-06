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

                        @show_reservation_calendar_modal="show_reservation_calendar_modal"

                        @del_tour_reservation="del_tour_reservation"

                        @show_reservation_details="show_reservation_details"
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
            />
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'

    import toureReservationCalendarModal from '../../modal/ToureReservationCalendarModal.vue'
    import googleCalendarSyncModal from '../../modal/GoogleCalendarSyncModal.vue'
    import tourReservationDetailsModal from '../../modal/TourReservationDetailsModal.vue'

    export default {
        components: {
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
                axios
                .get("/set_tour/get_reservation/get_all_reservations/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'All Tours Reservations',
                                            'add_action': {
                                                'action': 'fun',
                                                'link': 'show_reservation_calendar_modal', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Persons',
                                                        'Check In',
                                                        'Reserver name',
                                                        'Reserver Email',
                                                        'Verificate',
                                                        'Details',
                                                        'Delete'
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['persons']],
                                                        ['data', ['check_in']],
                                                        ['data', ['name'], ['surname']],
                                                        ['data', ['email']],
                                                        ['data', ['verificate'], 'bool'],
                                                        ['action_fun_id', 'show_reservation_details', 'btn btn-info', '<i aria-hidden="true" class="fa fa-eye"></i>'],
                                                        ['action_fun_id', 'del_tour_reservation', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['tour', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
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
            show_reservation_details(reservation){
                this.$refs.tour_reservation_details_modal.show_modal_func(reservation);
            },
            openGoogleCalendarSync(){
                this.$refs.google_calendar_sync_modal.open_google_calendar_sync_modal();
            },
            del_tour_reservation(id){
                if(confirm('Are you sure, you want delite it?')){
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
            onReservationDeleted(deletedId){
                // Refresh the reservations list after deletion
                this.get_reservations();
            },
        }
    }
</script>
