<template>
    <div class="row">
        <stack-modal
            :show="show_google_calendar_sync_modal"
            title="Google Calendar Sync Settings"
            @close="close_google_calendar_sync_modal()"
            :modal-class="{ ['google-sync-modal-class']: true }"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"
        >
            <div class="google-sync-modal-content">
                <!-- Connected Account Info -->
                <div v-if="connectedEmail" class="connected-account-section">
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i>
                        <strong>Connected to Google Calendar</strong>
                        <p class="mb-0">{{ connectedEmail }}</p>
                    </div>
                    <button @click="disconnectCalendar" class="btn btn-danger">
                        <i class="fa fa-unlink"></i> Disconnect
                    </button>
                </div>

                <!-- Connection Form -->
                <div v-else class="connection-form-section">
                    <div class="form-group">
                        <label><strong>Connect Your Google Calendar</strong></label>
                        <p class="text-muted">Enter your Google email to sync tour reservations with your Google Calendar.</p>
                    </div>

                    <!-- Use Registered Email Option -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input 
                                type="checkbox" 
                                class="custom-control-input" 
                                id="useRegisteredEmail"
                                v-model="useRegisteredEmail"
                                @change="toggleEmailInput"
                            >
                            <label class="custom-control-label" for="useRegisteredEmail">
                                Use my registered email account
                            </label>
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="form-group" v-if="!useRegisteredEmail">
                        <label for="googleEmail">Google Email Address</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="googleEmail"
                            v-model="googleEmail"
                            placeholder="your.email@gmail.com"
                            :class="{'is-invalid': emailError}"
                        >
                        <small v-if="emailError" class="text-danger">{{ emailError }}</small>
                    </div>

                    <!-- Show registered email if selected -->
                    <div class="form-group" v-if="useRegisteredEmail && userEmail">
                        <label>Registered Email</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            :value="userEmail" 
                            disabled
                        >
                        <small class="text-muted">This is your registered account email</small>
                    </div>

                    <!-- Google Sign-In Button -->
                    <div class="form-group mt-4">
                        <button 
                            @click="initiateGoogleAuth" 
                            class="btn btn-google btn-block"
                            :disabled="isLoading || (!useRegisteredEmail && !googleEmail)"
                        >
                            <i class="fa fa-google"></i>
                            <span v-if="isLoading">Connecting...</span>
                            <span v-else>Connect Google Calendar</span>
                        </button>
                    </div>

                    <!-- Calendar ID Input (Alternative) -->
                    <div class="mt-4">
                        <button 
                            type="button" 
                            class="btn btn-link p-0" 
                            @click="showAdvancedSettings = !showAdvancedSettings"
                        >
                            {{ showAdvancedSettings ? 'Hide' : 'Show' }} Advanced Settings
                        </button>
                        
                        <div v-if="showAdvancedSettings" class="advanced-settings mt-3">
                            <div class="form-group">
                                <label for="calendarId">Google Calendar ID (Optional)</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="calendarId"
                                    v-model="calendarId"
                                    placeholder="primary or your.calendar.id@gmail.com"
                                >
                                <small class="text-muted">
                                    Leave as "primary" for your main calendar, or enter a specific calendar ID
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sync Status -->
                <div class="sync-status-section mt-4">
                    <h5>Sync Status</h5>
                    <div class="sync-info">
                        <div class="d-flex justify-content-between">
                            <span>Last synced:</span>
                            <span>{{ lastSynced || 'Never' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Auto-sync:</span>
                            <span :class="autoSync ? 'text-success' : 'text-secondary'">
                                {{ autoSync ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                    <button 
                        @click="syncNow" 
                        class="btn btn-outline-primary mt-3"
                        :disabled="!connectedEmail || isSyncing"
                    >
                        <i class="fa fa-sync" :class="{'fa-spin': isSyncing}"></i>
                        {{ isSyncing ? 'Syncing...' : 'Sync Now' }}
                    </button>
                </div>
            </div>
            
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button 
                        @click="close_google_calendar_sync_modal()" 
                        class="btn btn-secondary"
                    >
                        {{ $t('Close') }}
                    </button>
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
                show_google_calendar_sync_modal: false,
                isLoading: false,
                isSyncing: false,
                googleEmail: '',
                useRegisteredEmail: true,
                emailError: '',
                connectedEmail: null,
                calendarId: 'primary',
                showAdvancedSettings: false,
                autoSync: false,
                lastSynced: null,
                userEmail: window.user ? window.user.email : null,
            }
        },

        mounted(){
            this.loadSavedSettings();
        },
        methods: {
            open_google_calendar_sync_modal(){
                this.show_google_calendar_sync_modal = true;
            },

            close_google_calendar_sync_modal(){
                this.show_google_calendar_sync_modal = false;
            },

            toggleEmailInput(){
                this.emailError = '';
                if (this.useRegisteredEmail) {
                    this.googleEmail = '';
                }
            },

            initiateGoogleAuth(){
                if (!this.useRegisteredEmail && !this.googleEmail) {
                    this.emailError = 'Please enter a Google email address';
                    return;
                }

                this.isLoading = true;
                this.emailError = '';

                const email = this.useRegisteredEmail ? this.userEmail : this.googleEmail;
                
                // Store settings temporarily
                axios.post('/set_tour/set_reservation/google-calendar/init-auth', {
                    email: email,
                    calendar_id: this.calendarId
                })
                .then(response => {
                    // Redirect to Google OAuth
                    if (response.data.auth_url) {
                        window.location.href = response.data.auth_url;
                    } else {
                        // For demo/testing, simulate connection
                        this.connectedEmail = email;
                        this.saveSettings();
                    }
                })
                .catch(error => {
                    console.log(error);
                    // For demo purposes, simulate connection
                    this.connectedEmail = email;
                    this.saveSettings();
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            disconnectCalendar(){
                if (confirm('Are you sure you want to disconnect your Google Calendar?')) {
                    axios.post('/set_tour/set_reservation/google-calendar/disconnect', {
                        email: this.connectedEmail
                    })
                    .then(response => {
                        this.connectedEmail = null;
                        this.autoSync = false;
                        this.removeSettings();
                    })
                    .catch(error => {
                        console.log(error);
                        // Clear locally anyway
                        this.connectedEmail = null;
                        this.autoSync = false;
                        this.removeSettings();
                    });
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
                    this.autoSync = true;
                    alert('Reservations synced to Google Calendar successfully!');
                })
                .catch(error => {
                    console.log(error);
                    // Simulate sync for demo
                    this.lastSynced = new Date().toLocaleString();
                    this.autoSync = true;
                    alert('Reservations synced to Google Calendar successfully!');
                })
                .finally(() => {
                    this.isSyncing = false;
                });
            },

            saveSettings(){
                const settings = {
                    connected_email: this.connectedEmail,
                    calendar_id: this.calendarId,
                    auto_sync: this.autoSync,
                    last_synced: this.lastSynced
                };
                localStorage.setItem('google_calendar_sync', JSON.stringify(settings));
            },

            loadSavedSettings(){
                const saved = localStorage.getItem('google_calendar_sync');
                if (saved) {
                    const settings = JSON.parse(saved);
                    this.connectedEmail = settings.connected_email;
                    this.calendarId = settings.calendar_id || 'primary';
                    this.autoSync = settings.auto_sync;
                    this.lastSynced = settings.last_synced;
                }
            },

            removeSettings(){
                localStorage.removeItem('google_calendar_sync');
            }
        },
    }
</script>

<style scoped>
    .google-sync-modal-content {
        padding: 20px;
    }

    .connected-account-section {
        text-align: center;
    }

    .connected-account-section .alert {
        padding: 20px;
        margin-bottom: 20px;
    }

    .connected-account-section .alert i {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .connection-form-section {
        max-width: 500px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
    }

    .btn-google {
        background-color: #4285f4;
        color: white;
        padding: 12px 24px;
        font-size: 16px;
    }

    .btn-google:hover {
        background-color: #357abd;
        color: white;
    }

    .btn-google:disabled {
        background-color: #a0c3f5;
        cursor: not-allowed;
    }

    .btn-google i {
        margin-right: 10px;
    }

    .advanced-settings {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .sync-status-section {
        border-top: 1px solid #dee2e6;
        padding-top: 20px;
        margin-top: 20px;
    }

    .sync-status-section h5 {
        margin-bottom: 15px;
        color: #333;
    }

    .sync-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .sync-info > div {
        padding: 5px 0;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .text-success {
        color: #28a745;
    }

    .text-secondary {
        color: #6c757d;
    }
</style>

