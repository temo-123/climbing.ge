<template>
    <stack-modal
            :show="is_show_modal"
            title="Edit locale data"
            @close="close_model"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <validator_alerts_component
            :errors_prop="error"
        />

        <form method="POST" id="edit_site_locale_data_form" v-on:submit.prevent="edit_site_local_data">
            <p><strong>Slug:</strong> {{ current_item && current_item.slug ? current_item.slug : '' }}</p>
            <div v-if="current_language === 'ka'">
                <label>Georgian Data:</label>
                <ckeditor v-model="data.ka_data" :config="editor_config.ka_data"></ckeditor>
            </div>
            <div v-else-if="current_language === 'us'">
                <label>English Data:</label>
                <ckeditor v-model="data.us_data" :config="editor_config.us_data"></ckeditor>
            </div>
        </form>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="edit_site_locale_data_form"
                >
                Save
                </button>
            </div>
        </div>
    </stack-modal>
</template>


<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import validator_alerts_component from '../../../../../items/validator_alerts_component.vue'

    export default {
        components: {
            validator_alerts_component,
            StackModal,
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
                    ka_data: this.$editor_config.get_big_editor_config(),
                    us_data: this.$editor_config.get_big_editor_config(),
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
                .post('/set_site_data/edit_site_local_data/' + this.current_item.id + '/' + this.current_language, {
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