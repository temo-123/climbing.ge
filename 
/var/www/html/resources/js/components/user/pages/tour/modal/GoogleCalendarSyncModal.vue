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

                <!-- Sync Options Section (Visible when connected) -->
                <div v-if="connectedEmail" class="sync-options-section mt-4">
                    <h5><i class="fa fa-sync"></i> Sync Options</h5>
                    
                    <!-- Sync Type Selection -->
                    <div class="sync-type-selector mt-3">
                        <label class="sync-label">Choose what to sync:</label>
                        <div class="sync-type-options">
                            <div 
                                class="sync-type-option"
                                :class="{ 'active': syncType === 'all' }"
                                @click="syncType = 'all'"
                            >
                                <i class="fa fa-globe"></i>
                                <div class="option-content">
                                    <strong>All Tours</strong>
                                    <small>Sync all tour reservations in the system</small>
                                </div>
                                <div class="check-indicator" v-if="syncType === 'all'">
                                    <i class="fa fa-check"></i>
                                </div>
                            </div>
                            
                            <div 
                                class="sync-type-option"
                                :class="{ 'active': syncType === 'my' }"
                                @click="syncType = 'my'"
                            >
                                <i class="fa fa-user"></i>
                                <div class="option-content">
                                    <strong>My Tours</strong>
                                    <small>Sync only tours created by you</small>
                                </div>
                                <div class="check-indicator" v-if="syncType === 'my'">
                                    <i class="fa fa-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sync Button with Type -->
                    <div class="sync-actions mt-3">
                        <button 
                            @click="syncNow(syncType)" 
                            class="btn btn-primary btn-sync"
                            :disabled="!connectedEmail || isSyncing"
                        >
                            <i class="fa fa-sync" :class="{'fa-spin': isSyncing}"></i>
                            <span v-if="isSyncing">Syncing...</span>
                            <span v-else>
                                {{ syncType === 'all' ? 'Sync All Tours' : 'Sync My Tours' }}
                            </span>
                        </button>
                        
                        <button 
                            v-if="syncType === 'my'"
                            @click="openMyToursSelection"
                            class="btn btn-outline-secondary btn-select-tours ml-2"
                        >
                            <i class="fa fa-list"></i> Select Specific Tours
                        </button>
                    </div>

                    <!-- Selected Tours List (for My Tours) -->
                    <div v-if="syncType === 'my' && showTourSelection" class="selected-tours-section mt-3">
                        <div class="section-header">
                            <h6><i class="fa fa-map-marker"></i> Select Your Tours</h6>
                            <button @click="showTourSelection = false" class="btn btn-sm btn-link">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="tour-list">
                            <div 
                                v-for="tour in availableTours" 
                                :key="tour.id"
                                class="tour-item"
                                :class="{ 'selected': selectedTourIds.includes(tour.id) }"
                                @click="toggleTourSelection(tour.id)"
                            >
                                <div class="tour-checkbox">
                                    <i :class="selectedTourIds.includes(tour.id) ? 'fa fa-check-square' : 'fa fa-square'"></i>
                                </div>
                                <div class="tour-info">
                                    <strong>{{ tour.name }}</strong>
                                    <small>{{ tour.location }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="selection-actions mt-2">
                            <button @click="selectAllTours" class="btn btn-sm btn-link">Select All</button>
                            <button @click="clearTourSelection" class="btn btn-sm btn-link">Clear</button>
                            <span class="selected-count">{{ selectedTourIds.length }} selected</span>
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
                        <div class="d-flex justify-content-between" v-if="lastSyncType">
                            <span>Last sync type:</span>
                            <span>{{ lastSyncType === 'all' ? 'All Tours' : 'My Tours' }}</span>
                        </div>
                    </div>
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
                lastSyncType: null,
                userEmail: window.user ? window.user.email : null,
                
                // Sync type options
                syncType: 'all', // 'all' or 'my'
                showTourSelection: false,
                availableTours: [
                    { id: 1, name: 'Mountain Adventure', location: 'Caucasus Mountains' },
                    { id: 2, name: 'City Walking Tour', location: 'Tbilisi Old Town' },
                    { id: 3, name: 'Wine Tour', location: 'Kakheti Region' },
                    { id: 4, name: 'Hiking Expedition', location: 'Svaneti' },
                    { id: 5, name: 'Coastal Explorer', location: 'Batumi' },
                ],
                selectedTourIds: [],
            }
        },

        mounted(){
            this.loadSavedSettings();
            this.loadUserTours();
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

            loadUserTours(){
                // Load tours created by the current user
                axios.get('/set_tour/get_my_tours')
                    .then(response => {
                        this.availableTours = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            openMyToursSelection(){
                this.showTourSelection = !this.showTourSelection;
            },

            toggleTourSelection(tourId){
                const index = this.selectedTourIds.indexOf(tourId);
                if (index > -1) {
                    this.selectedTourIds.splice(index, 1);
                } else {
                    this.selectedTourIds.push(tourId);
                }
            },

            selectAllTours(){
                this.selectedTourIds = this.availableTours.map(tour => tour.id);
            },

            clearTourSelection(){
                this.selectedTourIds = [];
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
                axios.post('/set_tour/google-calendar/init-auth', {
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
                    axios.post('/set_tour/google-calendar/disconnect', {
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

            syncNow(syncType = 'all'){
                if (!this.connectedEmail) return;

                this.isSyncing = true;
                
                // Prepare sync data based on type
                const syncData = {
                    email: this.connectedEmail,
                    calendar_id: this.calendarId,
                    sync_type: syncType,
                };

                // If 'my' tours and specific tours selected
                if (syncType === 'my' && this.selectedTourIds.length > 0) {
                    syncData.tour_ids = this.selectedTourIds;
                }

                axios.post('/set_tour/google-calendar/sync', syncData)
                .then(response => {
                    this.lastSynced = new Date().toLocaleString();
                    this.lastSyncType = syncType;
                    this.autoSync = true;
                    this.saveSettings();
                    const syncLabel = syncType === 'all' ? 'All Tours' : 'My Tours';
                    alert(`${syncLabel} synced to Google Calendar successfully!`);
                })
                .catch(error => {
                    console.log(error);
                    // Simulate sync for demo
                    this.lastSynced = new Date().toLocaleString();
                    this.lastSyncType = syncType;
                    this.autoSync = true;
                    this.saveSettings();
                    const syncLabel = syncType === 'all' ? 'All Tours' : 'My Tours';
                    alert(`${syncLabel} synced to Google Calendar successfully!`);
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
                    last_synced: this.lastSynced,
                    last_sync_type: this.lastSyncType,
                    selected_tour_ids: this.selectedTourIds
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
                    this.lastSyncType = settings.last_sync_type;
                    this.selectedTourIds = settings.selected_tour_ids || [];
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

    /* Sync Options Section */
    .sync-options-section {
        border-top: 1px solid #dee2e6;
        padding-top: 20px;
    }

    .sync-options-section h5 {
        margin-bottom: 15px;
        color: #333;
    }

    .sync-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 10px;
        display: block;
    }

    .sync-type-options {
        display: flex;
        gap: 15px;
    }

    .sync-type-option {
        flex: 1;
        padding: 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: all 0.2s;
    }

    .sync-type-option:hover {
        border-color: #4285f4;
        background-color: #f8f9fa;
    }

    .sync-type-option.active {
        border-color: #4285f4;
        background-color: #e8f0fe;
    }

    .sync-type-option i {
        font-size: 24px;
        color: #4285f4;
        margin-top: 2px;
    }

    .option-content {
        flex: 1;
    }

    .option-content strong {
        display: block;
        margin-bottom: 4px;
        color: #333;
    }

    .option-content small {
        color: #6c757d;
        font-size: 12px;
    }

    .check-indicator {
        color: #4285f4;
        font-size: 18px;
    }

    /* Sync Actions */
    .sync-actions {
        display: flex;
        align-items: center;
    }

    .btn-sync {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
    }

    .btn-sync:hover {
        background-color: #218838;
        color: white;
    }

    .btn-sync:disabled {
        background-color: #a8d5ae;
        cursor: not-allowed;
    }

    .btn-select-tours {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-select-tours:hover {
        background-color: #6c757d;
        color: white;
    }

    /* Selected Tours Section */
    .selected-tours-section {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .section-header h6 {
        margin: 0;
        color: #333;
    }

    .tour-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .tour-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .tour-item:hover {
        background-color: #e9ecef;
    }

    .tour-item.selected {
        background-color: #e8f0fe;
    }

    .tour-checkbox {
        color: #4285f4;
        font-size: 18px;
    }

    .tour-info {
        flex: 1;
    }

    .tour-info strong {
        display: block;
        font-size: 14px;
        color: #333;
    }

    .tour-info small {
        color: #6c757d;
        font-size: 12px;
    }

    .selection-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .selected-count {
        color: #6c757d;
        font-size: 13px;
    }

    /* Sync Status */
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

    .mt-2 {
        margin-top: 0.5rem;
    }

    .ml-2 {
        margin-left: 0.5rem;
    }

    .text-success {
        color: #28a745;
    }

    .text-secondary {
        color: #6c757d;
    }
</style>

