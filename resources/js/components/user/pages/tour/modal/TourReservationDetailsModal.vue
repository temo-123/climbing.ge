<template>
    <div class="row">
        <stack-modal
            :show="show_modal"
            title="Reservation Details"
            @close="close_modal"
            :modal-class="{ ['my-custom-class']: true }"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"
        >
            <div class="reservation-details-modal-content" v-if="reservation">
                <!-- Header Section -->
                <div class="details-header" :class="getStatusClass()">
                    <div class="header-icon">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <div class="header-info">
                        <h4>{{ reservation.name }} {{ reservation.surname }}</h4>
                        <span class="badge" :class="getStatusBadgeClass()">
                            {{ getStatusLabel() }}
                        </span>
                    </div>
                    <div class="header-id">
                        <small>Reservation #{{ reservation.id }}</small>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="details-body">
                    <!-- Tour Info Section -->
                    <div class="info-section" v-if="reservation.tour">
                        <h5><i class="fa fa-map-signs"></i> Tour Information</h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Tour:</span>
                                <span class="value">{{ getTourTitle() }}</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Location:</span>
                                <span class="value">{{ getTourLocation() }}</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Duration:</span>
                                <span class="value">{{ getTourDuration() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reservation Info Section -->
                    <div class="info-section">
                        <h5><i class="fa fa-calendar"></i> Reservation Details</h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Check In:</span>
                                <span class="value">{{ formatDate(reservation.check_in) }}</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Persons:</span>
                                <span class="value">
                                    <span class="badge badge-info">{{ reservation.persons }}</span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">Created:</span>
                                <span class="value">{{ formatDateTime(reservation.created_at) }}</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Last Updated:</span>
                                <span class="value">{{ formatDateTime(reservation.updated_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Section -->
                    <div class="info-section">
                        <h5><i class="fa fa-user"></i> Contact Information</h5>
                        <div class="info-grid">
                            <div class="info-item full-width">
                                <span class="label">Name:</span>
                                <span class="value">{{ reservation.name }} {{ reservation.surname }}</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Email:</span>
                                <span class="value">
                                    <a :href="'mailto:' + reservation.email">{{ reservation.email }}</a>
                                </span>
                            </div>
                            <div class="info-item" v-if="reservation.phone">
                                <span class="label">Phone:</span>
                                <span class="value">
                                    <a :href="'tel:' + reservation.phone">{{ reservation.phone }}</a>
                                </span>
                            </div>
                            <div class="info-item" v-if="reservation.city || reservation.country">
                                <span class="label">Location:</span>
                                <span class="value">
                                    {{ reservation.city }}{{ reservation.city ? ', ' : '' }}{{ reservation.country }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="info-section" v-if="reservation.text">
                        <h5><i class="fa fa-sticky-note"></i> Notes</h5>
                        <div class="notes-content">
                            {{ reservation.text }}
                        </div>
                    </div>

                    <!-- Users Section -->
                    <div class="info-section" v-if="reservation.users && reservation.users.length > 0">
                        <h5><i class="fa fa-users"></i> Associated Users</h5>
                        <div class="users-list">
                            <div 
                                v-for="user in reservation.users" 
                                :key="user.id"
                                class="user-item"
                            >
                                <div class="user-avatar">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <div class="user-info">
                                    <strong>{{ user.name }}</strong>
                                    <a :href="'mailto:' + user.email">{{ user.email }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Footer -->
                <div class="details-actions">
                    <button 
                        v-if="!isVerified()"
                        @click="verifyReservation" 
                        class="btn btn-success"
                        :disabled="isLoading"
                    >
                        <i class="fa fa-check"></i> Verify
                    </button>
                    <button 
                        @click="sendEmail" 
                        class="btn btn-primary"
                    >
                        <i class="fa fa-envelope"></i> Send Email
                    </button>
                    <button 
                        @click="addToCalendar" 
                        class="btn btn-outline-primary"
                    >
                        <i class="fa fa-calendar-plus"></i> Add to Calendar
                    </button>
                    <button 
                        @click="confirmDelete" 
                        class="btn btn-danger ml-auto"
                    >
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <i class="fa fa-exclamation-circle"></i>
                <h4>No Reservation Data</h4>
                <p>Unable to load reservation details.</p>
            </div>

            <div slot="modal-footer">
                <button 
                    @click="close_modal" 
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    {{ $t('Close') }}
                </button>
            </div>
        </stack-modal>

        <!-- Delete Confirmation Modal -->
        <div class="delete-confirmation" v-if="showDeleteConfirm">
            <div class="confirmation-overlay"></div>
            <div class="confirmation-dialog">
                <div class="dialog-header">
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    <h4>Confirm Delete</h4>
                </div>
                <div class="dialog-body">
                    <p>Are you sure you want to delete this reservation?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="dialog-actions">
                    <button 
                        @click="showDeleteConfirm = false" 
                        class="btn btn-secondary"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="deleteReservation" 
                        class="btn btn-danger"
                        :disabled="isLoading"
                    >
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
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
                show_modal: false,
                showDeleteConfirm: false,
                isLoading: false,
                reservation: null,
            }
        },

        methods: {
            show_modal_func(reservation){
                this.reservation = reservation;
                this.show_modal = true;
            },

            close_modal(){
                this.show_modal = false;
                this.reservation = null;
                this.showDeleteConfirm = false;
            },

            isVerified(){
                return this.reservation && 
                       (this.reservation.verificate === true || 
                        this.reservation.verificate === 1 || 
                        this.reservation.verificate === '1');
            },

            getStatusClass(){
                if (!this.reservation) return '';
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(this.reservation.check_in);
                
                if (checkIn < today) {
                    return 'status-old';
                } else if (this.isVerified()) {
                    return 'status-verified';
                } else {
                    return 'status-pending';
                }
            },

            getStatusBadgeClass(){
                if (!this.reservation) return 'badge-secondary';
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(this.reservation.check_in);
                
                if (checkIn < today) {
                    return 'badge-danger';
                } else if (this.isVerified()) {
                    return 'badge-success';
                } else {
                    return 'badge-warning';
                }
            },

            getStatusLabel(){
                if (!this.reservation) return 'Unknown';
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const checkIn = new Date(this.reservation.check_in);
                
                if (checkIn < today) {
                    return 'Old';
                } else if (this.isVerified()) {
                    return 'Verified';
                } else {
                    return 'Pending Verification';
                }
            },

            getTourTitle(){
                if (!this.reservation || !this.reservation.tour) return 'N/A';
                
                const locale = this.$i18n.locale || 'en';
                const localeField = locale + '_tour';
                
                if (this.reservation.tour[localeField]) {
                    return this.reservation.tour[localeField].title || 'Tour #' + this.reservation.tour_id;
                }
                return 'Tour #' + this.reservation.tour_id;
            },

            getTourLocation(){
                if (!this.reservation || !this.reservation.tour) return 'N/A';
                
                const locale = this.$i18n.locale || 'en';
                const localeField = locale + '_tour';
                
                if (this.reservation.tour[localeField]) {
                    return this.reservation.tour[localeField].location || '';
                }
                return '';
            },

            getTourDuration(){
                if (!this.reservation || !this.reservation.tour) return 'N/A';
                
                const locale = this.$i18n.locale || 'en';
                const localeField = locale + '_tour';
                
                if (this.reservation.tour[localeField]) {
                    return this.reservation.tour[localeField].duration || '';
                }
                return '';
            },

            formatDate(dateString){
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('default', { 
                    weekday: 'short',
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            },

            formatDateTime(dateString){
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('default', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            verifyReservation(){
                if (!this.reservation || this.isLoading) return;
                
                this.isLoading = true;
                
                axios.post('/set_tour/set_reservation/verifiation_reservation/' + this.reservation.id)
                .then(response => {
                    this.reservation.verificate = 1;
                    this.$notify({
                        type: 'success',
                        text: 'Reservation verified successfully!'
                    });
                })
                .catch(error => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        text: 'Failed to verify reservation'
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            sendEmail(){
                if (!this.reservation || !this.reservation.email) return;
                
                window.location.href = 'mailto:' + this.reservation.email;
            },

            addToCalendar(){
                if (!this.reservation) return;
                
                const title = encodeURIComponent('Tour Reservation: ' + this.reservation.name);
                const description = encodeURIComponent(
                    `Reservation Details:\n` +
                    `Name: ${this.reservation.name}\n` +
                    `Email: ${this.reservation.email}\n` +
                    `Phone: ${this.reservation.phone || 'N/A'}\n` +
                    `Persons: ${this.reservation.persons}\n` +
                    `Tour: ${this.getTourTitle()}`
                );
                const dates = encodeURIComponent(
                    this.reservation.check_in + 'T09:00:00/' + 
                    this.reservation.check_in + 'T18:00:00'
                );
                
                const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&details=${description}&dates=${dates}`;
                window.open(url, '_blank');
            },

            confirmDelete(){
                this.showDeleteConfirm = true;
            },

            deleteReservation(){
                if (!this.reservation || this.isLoading) return;
                
                this.isLoading = true;
                
                axios.post('/set_tour/set_reservation/del_reservation/' + this.reservation.id, {
                    _method: 'DELETE'
                })
                .then(response => {
                    this.$notify({
                        type: 'success',
                        text: 'Reservation deleted successfully!'
                    });
                    this.showDeleteConfirm = false;
                    this.close_modal();
                    this.$emit('deleted', this.reservation.id);
                })
                .catch(error => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        text: 'Failed to delete reservation'
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },
        },
    }
</script>

<style scoped>
    .reservation-details-modal-content {
        padding: 0;
    }

    /* Header Styles */
    .details-header {
        display: flex;
        align-items: center;
        padding: 20px;
        border-radius: 8px 8px 0 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .details-header.status-verified {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .details-header.status-pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .details-header.status-old {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
    }

    .header-icon {
        font-size: 48px;
        margin-right: 20px;
    }

    .header-info {
        flex: 1;
    }

    .header-info h4 {
        margin: 0 0 5px 0;
        font-size: 24px;
    }

    .header-id {
        opacity: 0.8;
    }

    /* Body Styles */
    .details-body {
        padding: 20px;
    }

    .info-section {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .info-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .info-section h5 {
        margin-bottom: 15px;
        color: #343a40;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-section h5 i {
        color: #667eea;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-item.full-width {
        grid-column: span 2;
    }

    .info-item .label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item .value {
        font-size: 15px;
        color: #212529;
        word-break: break-word;
    }

    .info-item a {
        color: #667eea;
        text-decoration: none;
    }

    .info-item a:hover {
        text-decoration: underline;
    }

    /* Notes Section */
    .notes-content {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
        font-style: italic;
        color: #495057;
    }

    /* Users List */
    .users-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .user-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .user-avatar {
        font-size: 32px;
        color: #6c757d;
        margin-right: 15px;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-info strong {
        color: #212529;
    }

    .user-info a {
        color: #667eea;
        font-size: 13px;
    }

    /* Actions Footer */
    .details-actions {
        display: flex;
        gap: 10px;
        padding: 20px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        flex-wrap: wrap;
    }

    .details-actions .btn {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .details-actions .btn.ml-auto {
        margin-left: auto;
    }

    /* Badge Styles */
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success { background-color: #28a745; color: white; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-danger { background-color: #dc3545; color: white; }
    .badge-info { background-color: #17a2b8; color: white; }
    .badge-secondary { background-color: #6c757d; color: white; }

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

    /* Delete Confirmation Dialog */
    .delete-confirmation {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .confirmation-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .confirmation-dialog {
        position: relative;
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .dialog-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .dialog-header i {
        font-size: 36px;
    }

    .dialog-header h4 {
        margin: 0;
    }

    .dialog-body {
        margin-bottom: 25px;
    }

    .dialog-body p {
        margin: 0;
    }

    .dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Modal Footer */
    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
    }

    /* Button Hover Effects */
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item.full-width {
            grid-column: span 1;
        }

        .details-header {
            flex-direction: column;
            text-align: center;
        }

        .header-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .details-actions {
            flex-direction: column;
        }

        .details-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .details-actions .btn.ml-auto {
            margin-left: 0;
        }
    }
</style>

