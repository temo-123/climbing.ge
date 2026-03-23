<template>
    <stack-modal
        :show="show_statistic_modal"
        title="General Info Statistics"
        @close="close_modal()"
        :modal-class="{ [modalClass]: true }">
        
        <div class="root">
            <div class="col-md-12">
                <div class="row">
                    <h4>Statistics for General Info ID: {{ general_info_id }}</h4>
                    
                    <div v-if="loading" class="col-md-12">
                        <p>Loading statistics...</p>
                    </div>
                    
                    <div v-else class="col-md-12">
                        <p>Here you can display statistics for this general info.</p>
                        <!-- Add your statistics display logic here -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Metric</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Views</td>
                                    <td>{{ statistics.total_views || 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Last Updated</td>
                                    <td>{{ statistics.last_updated || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    // import StackModal from '@innologica/vue-stackable-modal';  // Global now
     
    export default {
        components: {
            StackModal,
        },
        data(){
            return {
                show_statistic_modal: false,
                modalClass: 'modal-lg',
                
                general_info_id: null,
                loading: false,
                statistics: {
                    total_views: 0,
                    last_updated: null
                },
            }
        },

        mounted() {
            // 
        },

        methods: {
            show_modal(general_info_id){
                this.show_statistic_modal = true;
                this.general_info_id = general_info_id;

                if (this.show_statistic_modal == true) {
                    this.get_statistics();
                }
            },

            get_statistics(){
                this.loading = true;
                // Add your API call here to fetch statistics
                // Example:
                // axios
                // .get('/get_general_info_statistics/' + this.general_info_id)
                // .then(response => {
                //     this.statistics = response.data;
                // })
                // .catch(error => console.log(error))
                // .finally(() => this.loading = false);
                
                // For now, just set loading to false
                this.loading = false;
            },

            close_modal(){
                this.show_statistic_modal = false;
                this.general_info_id = null;
                this.statistics = {
                    total_views: 0,
                    last_updated: null
                };
                
                // Emit event to parent to refresh if needed
                this.$emit('update');
            },
        }
    }
</script>

<style>
    .modal-lg {
        width: 80% !important;
        max-width: 1000px;
    }
</style>

