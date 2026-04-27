<template>
    <StackModal
            :show="is_show_modal"
            title="Edit locale data"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            @save="$refs.edit_site_locale_data_form.requestSubmit()"
            @close="close_model"
        >
        <validator_alerts_component
            :errors_prop="error"
        />

        <form ref="edit_site_locale_data_form" id="edit_site_locale_data_form" v-on:submit.prevent="edit_site_local_data">
            <p><strong>Slug:</strong> {{ current_item && current_item.slug ? current_item.slug : '' }}</p>
            <div v-if="current_language === 'ka'">
                <label>Georgian Data:</label>
                <big_editor v-model="data.ka_data" />
            </div>
            <div v-else-if="current_language === 'us'">
                <label>English Data:</label>
                <big_editor v-model="data.us_data" />
            </div>
        </form>
    </StackModal>
</template>


<script>
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now

    // import validator_alerts_component from '../../../../../items/validator_alerts_component.vue'

    export default {
        components: {
            // validator_alerts_component,
            // StackModal,
        },

        props: [
            // 'show'
        ],

        data() {
            return {
                data: {
                    ka_data: '',
                    us_data: ''
                },
                current_item: null,
                current_language: '',

                error: [],

                editor_config: {
                    ka_data: {},
                    us_data: {},
                },

                is_show_modal: false,
            }
        },

        mounted() {
            //
        },

        methods: {
            edit_site_local_data(){
                axios
                .post('set_site_data/edit_site_local_data/' + this.current_item.id + '/' + this.current_language, {
                    data: this.data,
                    _method: 'post'
                })
                .then(response => {
                    // Success - emit event to parent and close modal
                    this.$emit('update')
                    this.close_model()
                })
                .catch(err => {
                    if (err.response && err.response.status == 422) {
                        this.error = err.response.data.validation
                    } else {
                        console.log(err);
                    }
                })
            },
            close_model(){
                this.is_show_modal = false
                this.data = {
                    ka_data: '',
                    us_data: ''
                }
                this.current_item = null
                this.current_language = ''
                this.error = []
            },
            // show_modal(){
            //     this.is_show_modal = true
            // },
            // Method to receive data from parent component
            show_modal_with_data(item, language){
                this.current_item = item
                this.current_language = language
                
                // Set the appropriate data based on language
                if (language === 'ka') {
                    this.data.ka_data = item.ka_data || ''
                } else if (language === 'us') {
                    this.data.us_data = item.us_data || ''
                }
                
                this.is_show_modal = true
                // this.show_modal()
            }
        }
    }
</script>