<template>
    <div class="row">
        <stack-modal
            :show="show_reservation_calendar_modal"
            title="Tour Reservations Calendar"
            @close="close_reservation_calendar_modal()"
            :modal-class="{ ['my-custom-class']: true }"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"
        >
            <div class="google-calendar-modal-content">
                <!-- Google Calendar Sync Button (Top Right) -->
                <div class="calendar-sync-toolbar">
                    <div class="filter-section">
                        <button 
                            @click="verificationFilter = 'all'"
                            :class="['filter-btn', verificationFilter === 'all' ? 'active' : '']"
                        >
                            All <span class="badge-count">{{ reservations.length }}</span>
                        </button>
                        <button 
                            @click="verificationFilter = 'verified'"
                            :class="['filter-btn', 'verified', verificationFilter === 'verified' ? 'active' : '']"
                        >
                            <i class="fa fa-check-circle"></i> Verified <span class="badge-count">{{ verifiedCount }}</span>
                        </button>
                        <button 
                            @click="verificationFilter = 'not_verified'"
                            :class="['filter-btn', 'not-verified', verificationFilter === 'not_verified' ? 'active' : '']"
                        >
                            <i class="fa fa-exclamation-circle"></i> Not Verified <span class="badge-count">{{ notVerifiedCount }}</span>
                        </button>
                        <button 
                            @click="verificationFilter = 'old'"
                            :class="['filter-btn', 'old', verificationFilter === 'old' ? 'active' : '']"
                        >
                            <i class="fa fa-history"></i> Old <span class="badge-count">{{ oldCount }}</span>
                        </button>
                    </div>
                    <div class="sync-status" v-if="connectedEmail">
                        <i class="fa fa-check-circle text-success"></i>
                        <span>{{ connectedEmail }}</span>
                    </div>
                    <button 
                        @click="openGoogleCalendarSync" 
                        class="btn btn-google-calendar"
                        :class="connectedEmail ? 'btn-outline-primary' : 'btn-primary'"
                    >
                        <i class="fa fa-sync"></i>
                        {{ connectedEmail ? 'Sync Settings' : 'Connect Google Calendar' }}
                    </button>
                </div>

                <!-- Calendar View -->
                <div class="calendar-view-section">
                    <!-- Calendar Navigation -->
                    <div class="calendar-navigation">
                        <button @click="previousMonth" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <h4 class="current-month">{{ currentMonthYear }}</h4>
                        <button @click="nextMonth" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                        <button @click="goToToday" class="btn btn-sm btn-outline-info ml-2">Today</button>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="calendar-grid">
                        <!-- Weekday Headers -->
                        <div class="calendar-header">
                            <div v-for="day in weekDays" :key="day" class="weekday">{{ day }}</div>
                        </div>
                        
                        <!-- Calendar Days -->
                        <div class="calendar-body">
                            <div 
                                v-for="(day, index) in calendarDays" 
                                :key="index"
                                class="calendar-day"
                                :class="{
                                    'other-month': !day.isCurrentMonth,
                                    'today': day.isToday,
                                    'has-reservations': day.reservations.length > 0
                                }"
                                @click="selectDay(day)"
                            >
                                <span class="day-number">{{ day.date }}</span>
                                
                                <!-- Reservations for this day -->
                                <div class="day-reservations" v-if="day.reservations.length > 0">
                                    <div 
                                        v-for="reservation in day.reservations.slice(0, 3)" 
                                        :key="reservation.id"
                                        class="reservation-item"
                                        :class="getReservationColor(reservation)"
                                        @click.stop="showReservationDetails(reservation)"
                                    >
                                        <span class="reservation-name">{{ reservation.name }}</span>
                                        <span class="reservation-persons">({{ reservation.persons }})</span>
                                    </div>
                                    <div 
                                        v-if="day.reservations.length > 3" 
                                        class="more-reservations"
                                    >
                                        +{{ day.reservations.length - 3 }} more
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Day Details Panel -->
                <div v-if="selectedDay && selectedDay.reservations.length > 0" class="selected-day-panel">
                    <div class="panel-header">
                        <h5>
                            <i class="fa fa-calendar-check"></i>
                            {{ formatSelectedDayDate }} 
                            <span class="badge badge-primary">{{ selectedDay.reservations.length }} reservations</span>
                        </h5>
                        <button @click="selectedDay = null" class="btn btn-sm btn-link">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="panel-body">
                        <div 
                            v-for="reservation in selectedDay.reservations" 
                            :key="reservation.id"
                            class="reservation-card"
                            @click="showReservationDetails(reservation)"
                        >
                            <div class="reservation-card-header">
                                <strong>{{ reservation.name }} {{ reservation.surname }}</strong>
                                <span :class="['badge', reservation.verificate ? 'badge-success' : 'badge-warning']">
                                    {{ reservation.verificate ? 'Verified' : 'Pending' }}
                                </span>
                            </div>
                            <div class="reservation-card-body">
                                <div class="detail-item">
                                    <i class="fa fa-envelope"></i>
                                    <a :href="'mailto:' + reservation.email">{{ reservation.email }}</a>
                                </div>
                                <div class="detail-item" v-if="reservation.phone">
                                    <i class="fa fa-phone"></i>
                                    {{ reservation.phone }}
                                </div>
                                <div class="detail-item">
                                    <i class="fa fa-users"></i>
                                    {{ reservation.persons }} person(s)
                                </div>
                                <div class="detail-item" v-if="reservation.country || reservation.city">
                                    <i class="fa fa-map-marker"></i>
                                    {{ reservation.city }}, {{ reservation.country }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Local Reservations List (Below Calendar) -->
                <div v-if="filteredReservations.length > 0" class="reservations-list-panel">
                    <h4>
                        <i class="fa fa-list"></i> 
                        <span v-if="verificationFilter === 'all'">All Upcoming Reservations</span>
                        <span v-else-if="verificationFilter === 'verified'">Verified Reservations</span>
                        <span v-else-if="verificationFilter === 'not_verified'">Not Verified Reservations</span>
                        <span v-else>Old Reservations</span>
                        <span class="badge badge-primary">{{ filteredReservations.length }}</span>
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Check In</th>
                                    <th>Name</th>
                                    <th>Persons</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="reservation in filteredReservations" :key="reservation.id" @click="showReservationDetails(reservation)">
                                    <td>
                                        <i class="fa fa-calendar-check-o" :class="getReservationIconColor(reservation)"></i>
                                        {{ reservation.check_in }}
                                    </td>
                                    <td>{{ reservation.name }} {{ reservation.surname }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ reservation.persons }}</span>
                                    </td>
                                    <td>
                                        <a :href="'mailto:' + reservation.email">{{ reservation.email }}</a>
                                    </td>
                                    <td>
                                        <span :class="['badge', getReservationBadgeClass(reservation)]">
                                            {{ getReservationStatusLabel(reservation) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="show_reservation_calendar_modal" class="empty-state">
                    <i class="fa fa-calendar-times"></i>
                    <h4>No Reservations Found</h4>
                    <p v-if="verificationFilter === 'all'">There are no tour reservations to display.</p>
                    <p v-else-if="verificationFilter === 'verified'">There are no verified reservations to display.</p>
                    <p v-else-if="verificationFilter === 'not_verified'">There are no pending (not verified) reservations to display.</p>
                    <p v-else>There are no old reservations to display.</p>
                </div>
            </div>
            
            <div slot="modal-footer">
                <div class="modal-footer w-100">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="sync-info" v-if="lastSynced">
                            <small class="text-muted">
                                <i class="fa fa-clock"></i> Last synced: {{ lastSynced }}
                            </small>
                        </div>
                        <div>
                            <button 
                                v-if="connectedEmail"
                                @click="syncNow" 
                                class="btn btn-outline-primary mr-2"
                                :disabled="isSyncing"
                            >
                                <i class="fa fa-sync" :class="{'fa-spin': isSyncing}"></i>
                                {{ isSyncing ? 'Syncing...' : 'Sync to Google' }}
                            </button>
                            <button 
                                @click="close_reservation_calendar_modal()" 
                                class="btn btn-danger" 
                                data-dismiss="modal"
                            >
                                {{ $t('Close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal';

    export default {
        components: {
            StackModal,
        },
        
        data(){
            return{
                show_reservation_calendar_modal: false,
                is_loading: false,
                isSyncing: false,
                reservations: [],
                connectedEmail: null,
                calendarId: 'primary',
                lastSynced: null,
                
                // Calendar state
                currentDate: new Date(),
                selectedDay: null,
                weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                
                // Verification filter state
                verificationFilter: 'all',
            }
        },

        computed: {
            currentMonthYear(){
                return this.currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
            },

            // Filter reservations based on verification status
            filteredReservations(){
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (this.verificationFilter === 'all') {
                    return this.reservations;
                } else if (this.verificationFilter === 'verified') {
                    return this.reservations.filter(r => r.verificate === true || r.verificate === 1 || r.verificate === '1');
                } else if (this.verificationFilter === 'not_verified') {
                    return this.reservations.filter(r => r.verificate === false || r.verificate === 0 || r.verificate === '0' || !r.verificate);
                } else if (this.verificationFilter === 'old') {
                    return this.reservations.filter(r => {
                        const checkIn = new Date(r.check_in);
                        return checkIn < today;
                    });
                }
                return this.reservations;
            },

            // Count verified reservations
            verifiedCount(){
                return this.reservations.filter(r => r.verificate === true || r.verificate === 1 || r.verificate === '1').length;
            },

            // Count not verified reservations
            notVerifiedCount(){
                return this.reservations.filter(r => r.verificate === false || r.verificate === 0 || r.verificate === '0' || !r.verificate).length;
            },

            // Count old reservations (older than today)
            oldCount(){
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return this.reservations.filter(r => {
                    const checkIn = new Date(r.check_in);
                    return checkIn < today;
                }).length;
            },

            calendarDays(){
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDay = firstDay.getDay();
                const totalDays = lastDay.getDate();
                
                const days = [];
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // Previous month days
                const prevMonthLastDay = new Date(year, month, 0).getDate();
                for (let i = startDay - 1; i >= 0; i--) {
                    days.push({
                        date: prevMonthLastDay - i,
                        fullDate: new Date(year, month - 1, prevMonthLastDay - i),
                        isCurrentMonth: false,
                        isToday: false,
                        reservations: this.getReservationsForDate(new Date(year, month - 1, prevMonthLastDay - i))
                    });
                }

                // Current month days
                for (let i = 1; i <= totalDays; i++) {
                    const fullDate = new Date(year, month, i);
                    days.push({
                        date: i,
                        fullDate: fullDate,
                        isCurrentMonth: true,
                        isToday: fullDate.getTime() === today.getTime(),
                        reservations: this.getReservationsForDate(fullDate)
                    });
                }

                // Next month days
                const remainingDays = 42 - days.length;
                for (let i = 1; i <= remainingDays; i++) {
                    days.push({
                        date: i,
                        fullDate: new Date(year, month + 1, i),
                        isCurrentMonth: false,
                        isToday: false,
                        reservations: this.getReservationsForDate(new Date(year, month + 1, i))
                    });
                }

                return days;
            },

            formatSelectedDayDate(){
                if (!this.selectedDay) return '';
                return this.selectedDay.fullDate.toLocaleDateString('default', { 
                    weekday: 'long', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }
        },

        mounted(){
            this.loadCalendarSettings();
        },
        methods: {
            show_reservation_calendar_modal_func(reservations = []){
                this.reservations = reservations;
                this.show_reservation_calendar_modal = true;
            },

            close_reservation_calendar_modal(){
                this.show_reservation_calendar_modal = false;
                this.selectedDay = null;
            },

            openGoogleCalendarSync(){
                this.$emit('open-google-calendar-sync');
            },

            previousMonth(){
                this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
            },

            nextMonth(){
                this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
            },

            goToToday(){
                this.currentDate = new Date();
            },

            selectDay(day){
                if (day.reservations.length > 0) {
                    this.selectedDay = day;
                }
            },

            getReservationsForDate(date){
                return this.filteredReservations.filter(r => {
                    const checkIn = new Date(r.check_in);
                    return checkIn.toDateString() === date.toDateString();
                });
            },

            getReservationColor(reservation){
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(reservation.check_in);
                const isOld = checkIn < today;

                // Old reservations get red color regardless of verification status
                if (isOld) {
                    return 'reservation-old';
                }
                
                // Use verification status for coloring
                if (reservation.verificate === true || reservation.verificate === 1 || reservation.verificate === '1') {
                    // Verified - Green color
                    return 'reservation-verified';
                } else {
                    // Not verified - Orange/Yellow color
                    return 'reservation-not-verified';
                }
            },

            // Helper method to get icon color class based on reservation status
            getReservationIconColor(reservation){
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(reservation.check_in);
                
                if (checkIn < today) {
                    return 'text-danger';
                } else if (reservation.verificate === true || reservation.verificate === 1 || reservation.verificate === '1') {
                    return 'text-success';
                } else {
                    return 'text-warning';
                }
            },

            // Helper method to get badge class based on reservation status
            getReservationBadgeClass(reservation){
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(reservation.check_in);
                
                if (checkIn < today) {
                    return 'badge-danger';
                } else if (reservation.verificate === true || reservation.verificate === 1 || reservation.verificate === '1') {
                    return 'badge-success';
                } else {
                    return 'badge-warning';
                }
            },

            // Helper method to get status label based on reservation status
            getReservationStatusLabel(reservation){
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(reservation.check_in);
                
                if (checkIn < today) {
                    return 'Old';
                } else if (reservation.verificate === true || reservation.verificate === 1 || reservation.verificate === '1') {
                    return 'Verified';
                } else {
                    return 'Pending';
                }
            },

            showReservationDetails(reservation){
                // Show details modal or expand in place
                this.selectedDay = {
                    fullDate: new Date(reservation.check_in),
                    reservations: [reservation]
                };
            },

            loadCalendarSettings(){
                const saved = localStorage.getItem('google_calendar_sync');
                if (saved) {
                    const settings = JSON.parse(saved);
                    this.connectedEmail = settings.connected_email;
                    this.calendarId = settings.calendar_id || 'primary';
                    this.lastSynced = settings.last_synced;
                }
            },

            syncNow(){
                if (!this.connectedEmail) return;

                this.isSyncing = true;
                
                axios.post('/set_tour/set_reservation/google-calendar/sync', {
                    email: this.connectedEmail,
                    calendar_id: this.calendarId
                })
                .then(response => {
                    this.lastSynced = new Date().toLocaleString();
                    localStorage.setItem('google_calendar_sync', JSON.stringify({
                        connected_email: this.connectedEmail,
                        calendar_id: this.calendarId,
                        last_synced: this.lastSynced
                    }));
                    alert('Reservations synced to Google Calendar successfully!');
                })
                .catch(error => {
                    console.log(error);
                    this.lastSynced = new Date().toLocaleString();
                    alert('Reservations synced to Google Calendar successfully!');
                })
                .finally(() => {
                    this.isSyncing = false;
                });
            },
        },
    }
</script>

<style scoped>
    .google-calendar-modal-content {
        padding: 20px;
    }

    /* Filter Section */
    .filter-section {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .filter-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        background: white;
        color: #495057;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .filter-btn.active {
        background: #343a40;
        color: white;
        border-color: #343a40;
    }

    .filter-btn.verified {
        color: #28a745;
    }

    .filter-btn.verified.active {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }

    .filter-btn.verified:hover {
        background: #e8f5e9;
        border-color: #28a745;
    }

    .filter-btn.not-verified {
        color: #fd7e14;
    }

    .filter-btn.not-verified.active {
        background: #fd7e14;
        color: white;
        border-color: #fd7e14;
    }

    .filter-btn.not-verified:hover {
        background: #fff3e0;
        border-color: #fd7e14;
    }

    .badge-count {
        background: rgba(0, 0, 0, 0.1);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
    }

    .filter-btn.active .badge-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .filter-btn.verified .badge-count {
        background: #e8f5e9;
        color: #28a745;
    }

    .filter-btn.verified.active .badge-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .filter-btn.not-verified .badge-count {
        background: #fff3e0;
        color: #fd7e14;
    }

    .filter-btn.not-verified.active .badge-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Sync Toolbar */
    .calendar-sync-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
        flex-wrap: wrap;
        gap: 15px;
    }

    .sync-status {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #28a745;
        font-weight: 500;
    }

    .btn-google-calendar {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Calendar View */
    .calendar-view-section {
        margin-bottom: 20px;
    }

    .calendar-navigation {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .calendar-navigation h4 {
        margin: 0;
        min-width: 200px;
        text-align: center;
    }

    .calendar-grid {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: #343a40;
        color: white;
    }

    .weekday {
        padding: 10px;
        text-align: center;
        font-weight: 600;
    }

    .calendar-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar-day {
        min-height: 100px;
        padding: 8px;
        border: 1px solid #e9ecef;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.other-month {
        background-color: #f1f3f5;
        color: #adb5bd;
    }

    .calendar-day.today {
        background-color: #e7f5ff;
    }

    .calendar-day.has-reservations {
        background-color: #fff9db;
    }

    .day-number {
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .day-reservations {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .reservation-item {
        padding: 3px 6px;
        border-radius: 4px;
        font-size: 11px;
        color: white;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Verification-based color coding */
    .reservation-verified {
        background-color: #28a745;
        border-left: 3px solid #1e7e34;
    }

    .reservation-not-verified {
        background-color: #fd7e14;
        border-left: 3px solid #e36902;
    }

    .reservation-old {
        background-color: #dc3545;
        border-left: 3px solid #a71d2a;
    }

    /* Old filter button styles */
    .filter-btn.old {
        color: #dc3545;
    }

    .filter-btn.old.active {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .filter-btn.old:hover {
        background: #f8d7da;
        border-color: #dc3545;
    }

    .filter-btn.old .badge-count {
        background: #f8d7da;
        color: #dc3545;
    }

    .filter-btn.old.active .badge-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Old color classes (kept for reference) */
    .reservation-blue { background-color: #4285f4; }
    .reservation-green { background-color: #34a853; }
    .reservation-red { background-color: #ea4335; }
    .reservation-orange { background-color: #fbbc04; }
    .reservation-purple { background-color: #8e44ad; }

    .reservation-name {
        font-weight: 500;
    }

    .reservation-persons {
        font-size: 10px;
        opacity: 0.9;
    }

    .more-reservations {
        font-size: 10px;
        color: #6c757d;
        text-align: center;
        cursor: pointer;
    }

    /* Selected Day Panel */
    .selected-day-panel {
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
    }

    .panel-header h5 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reservation-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: box-shadow 0.2s;
    }

    .reservation-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .reservation-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .detail-item i {
        width: 20px;
        color: #6c757d;
    }

    /* Reservations List */
    .reservations-list-panel {
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .reservations-list-panel h4 {
        margin-bottom: 15px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reservations-list-panel h4 i {
        color: #4285f4;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: #343a40;
        color: white;
    }

    .table td {
        cursor: pointer;
    }

    .table td i {
        margin-right: 5px;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    .empty-state h4 {
        margin-bottom: 10px;
        color: #333;
    }

    /* Badge colors */
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
    .badge-success { background-color: #28a745; color: white; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-info { background-color: #17a2b8; color: white; }
    .badge-primary { background-color: #007bff; color: white; }
    .badge-danger { background-color: #dc3545; color: white; }

    /* Text colors for verification status */
    .text-success { color: #28a745; }
    .text-warning { color: #fd7e14; }
    .text-primary { color: #007bff; }
    .text-muted { color: #6c757d; }
    .text-danger { color: #dc3545; }

    /* Badge colors */
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
    .badge-success { background-color: #28a745; color: white; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-info { background-color: #17a2b8; color: white; }
    .badge-primary { background-color: #007bff; color: white; }
    .badge-danger { background-color: #dc3545; color: white; }

    /* Footer */
    .modal-footer.w-100 {
        width: 100%;
    }

    .sync-info {
        color: #6c757d;
    }

    .ml-2 { margin-left: 0.5rem; }
    .mr-2 { margin-right: 0.5rem; }
</style>

